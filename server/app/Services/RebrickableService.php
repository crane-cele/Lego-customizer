<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Services\CacheService;
use GuzzleHttp\Exception\RequestException;
use App\Models\CustomSet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RebrickableService
{
    /**
     * Acts as a service layer to interact with the Rebrickable API, providing methods to fetch data related to LEGO sets, parts, themes, and categories.
     * Handles caching of API responses to reduce the number of API calls and improve performance.
     * 
     * Key Points:
     * 
     * API Client: Uses Guzzle HTTP client to make requests to the Rebrickable API.
     * Caching: Implements caching for API responses to enhance performance and reduce API rate limits.
     * Helper Methods: Provides various methods to fetch data from the API, such as getSets, getParts, getSetDetails, getPartsInSet, getPartCategories, and getThemes.
     * Custom Parts Management: Manages custom parts associated with specific LEGO sets by interacting with the CustomSet model.
     */
    
    private $client;
    private $apiKey;
    private $cacheService;

    /**
     * Inject HTTP client and cache service through the constructor
     */
    public function __construct(Client $client, CacheService $cacheService)
    {
        $this->apiKey = env('REBRICKABLE_API_KEY');
        $this->client = new Client([
            'base_uri' => 'https://rebrickable.com/api/v3/lego/',
            'headers' => [
                'Authorization' => 'key ' . $this->apiKey
            ],
            'verify' => false
        ]); 
        $this->cacheService = $cacheService;
    }

    /**
     * Method to get a list of sets
     */
    public function getSets($query, $page = 1, $page_size = 20, $theme = null, $year = 2024)
    {
        $theme = ($theme == 0) ? null : $theme;
        $year = ($year == 0) ? null : $year;
    
        $params = [
            'search' => $query,
            'page' => $page,
            'page_size' => $page_size,
            'theme_id' => $theme,
            'min_year' => $year,
            'max_year' => $year
        ];
        return $this->fetchData('sets/', $params);
    }
    
    /**
     * Method to get a list of parts
     */
    public function getParts($query, $page = 1, $page_size = 20, $category = null)
    {
        $category = ($category == 0) ? null : $category;

        $params = ['search' => $query, 'page' => $page, 'page_size' => $page_size, 'part_cat_id' => $category];
        return $this->fetchData('parts/', $params);
    }

    /**
     * Method to get details of a specific set
     */
    public function getSetDetails($set_num)
    {
        /**
         * Fetch set details from API
         */
        $setDetails = $this->fetchData("sets/{$set_num}/");
    
        /**
         * Fetch parts in set from API
         */
        $partsInSetResponse = $this->fetchData("sets/{$set_num}/parts/");
        $partsInSet = [];
        foreach ($partsInSetResponse['results'] as $part) {
            $partsInSet[] = [
                'part_num' => $part['part']['part_num'],
                'name' => $part['part']['name'] ?? '',
                'part_cat_id' => $part['part']['part_cat_id'] ?? '',
                'part_url' => $part['part']['part_url'] ?? '',
                'part_img_url' => $part['part']['part_img_url'] ?? null,
                'set_num' => $part['set_num'],
                'quantity' => $part['quantity']
            ];
        }
    
        /**
         * Fetch custom parts in set from database
         */
        $customPartsResponse = $this->getCustomPartsInSet($set_num);
        if (array_key_exists('results', $customPartsResponse)) {
            $setDetails['customParts'] = $customPartsResponse['results'];
        }
    
        /**
         * Add parts to set details
         */
        $setDetails['partsInSet'] = $partsInSet;
    
        return $setDetails;
    }    

    /**
     * Method to get parts in a specific set
     */
    public function getPartsInSet($set_num)
    {
        return $this->fetchData("sets/{$set_num}/parts/");
    }

    /**
     * Method to get part categories
     */
    public function getPartCategories()
    {
        return $this->fetchData('part_categories/');
    }


    /**
     * Method to get themes
     */
    public function getThemes()
    {
        return $this->fetchData('themes');
    }

    /**
     * Helper method to fetch data from the Rebrickable API
     */
    private function fetchData($endpoint, array $params = [])
    {
        /**
         * Check cache first
         */
        $cacheKey = $endpoint . '?' . http_build_query($params);
        return $this->cacheService->remember($cacheKey, 3600, function () use ($endpoint, $params) {
            try {
                $response = $this->client->request('GET', $endpoint, ['query' => $params]);
                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                Log::error('Error fetching data from Rebrickable: ' . $e->getMessage());
                return ['error' => 'Failed to fetch data', 'details' => $e->getMessage()];
            }
        });
    }

    /**
     * Method to customize parts in a specific set
     */
    public function customizePartsInSet($set_num, $custom_parts)
    {
        $existing_parts = CustomSet::where('set_num', $set_num)->get()->keyBy('part_num');

        foreach ($custom_parts as $part) {
            $part_num = $part['part_num'];
            $quantity = $part['quantity'];

            if ($existing_parts->has($part_num)) {
                $existing_parts[$part_num]->update(['quantity' => $quantity]);
                $existing_parts->forget($part_num);
            } else {
                CustomSet::create([
                    'set_num' => $set_num,
                    'part_num' => $part_num,
                    'quantity' => $quantity
                ]);
            }
        }

        foreach ($existing_parts as $part) {
            $part->delete();
        }
        return response()->json(['message' => 'Parts customized successfully'], 200);
    }

    /**
     * Method to get custom parts in a specific set
     */
    public function getCustomPartsInSet($set_num)
    {
        $customParts = CustomSet::where('set_num', $set_num)->get();
        if ($customParts->isEmpty()) {
            return ['message' => 'No add parts found for this set number'];
        }

        $result = ['count' => 0, 'next' => null, 'previous' => null, 'results' => []];
        foreach ($customParts as $customPart) {
            $part = $this->fetchData("parts/{$customPart->part_num}/");
            if (!$part) {
                Log::warning("Missing part for set num: {$set_num}");
                continue;
            }

            $result['count'] += $customPart->quantity;
            $result['results'][] = [
                'part_num' => $part['part_num'],
                'name' => $part['name'] ?? '',
                'part_cat_id' => $part['part_cat_id'] ?? '',
                'part_url' => $part['part_url'] ?? '',
                'part_img_url' => $part['part_img_url'] ?? null,
                "set_num" => $customPart->set_num,
                "quantity" => $customPart->quantity
            ];
        }
        return $result;
    }
}
