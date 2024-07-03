<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Services\RebrickableService;
use App\Services\CacheService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;

class RebrickableServiceTest extends TestCase
{
    protected $rebrickableService;
    protected $mockClient;
    protected $mockCacheService;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(Client::class);
        $this->mockCacheService = Mockery::mock(CacheService::class);

        $this->rebrickableService = new RebrickableService($this->mockClient, $this->mockCacheService);
    }

    public function testGetSets()
    {
        $mockResponse = new Response(200, [], json_encode([
            'count' => 1,
            'results' => [
                [
                    'set_num' => '0014-1',
                    'name' => 'Test Set',
                    'year' => 2024,
                    'theme_id' => 1,
                    'num_parts' => 100,
                ]
            ]
        ]));

        $this->mockCacheService->shouldReceive('remember')
            ->once()
            ->andReturn(json_decode($mockResponse->getBody()->getContents(), true));

        $sets = $this->rebrickableService->getSets(null, 1, 20, null, 2024);

        $this->assertEquals(1, $sets['count']);
        $this->assertEquals('0014-1', $sets['results'][0]['set_num']);
    }

    public function testGetParts()
    {
        $mockResponse = new Response(200, [], json_encode([
            'count' => 1,
            'results' => [
                [
                    'part_num' => '3001',
                    'name' => 'Brick 2x4',
                    'category_id' => 1,
                ]
            ]
        ]));

        $this->mockCacheService->shouldReceive('remember')
            ->once()
            ->andReturn(json_decode($mockResponse->getBody()->getContents(), true));

        $parts = $this->rebrickableService->getParts(null, 1, 20, null);

        $this->assertEquals(1, $parts['count']);
        $this->assertEquals('3001', $parts['results'][0]['part_num']);
    }

    public function testCustomizePartsInSet()
    {
        $customParts = [
            [
                'part_num' => '3001',
                'quantity' => 10
            ]
        ];

        $mockCustomSet = Mockery::mock(CustomSet::class);
        $mockCustomSet->shouldReceive('where')
            ->with('set_num', '0014-1')
            ->andReturnSelf();
        $mockCustomSet->shouldReceive('get')
            ->andReturn(collect([$mockCustomSet]));
        $mockCustomSet->shouldReceive('keyBy')
            ->with('part_num')
            ->andReturn(collect(['3001' => $mockCustomSet]));
        $mockCustomSet->shouldReceive('update')
            ->with(['quantity' => 10])
            ->andReturn(true);
        $mockCustomSet->shouldReceive('forget')
            ->with('3001')
            ->andReturn(true);
        $mockCustomSet->shouldReceive('delete')
            ->andReturn(true);

        $response = $this->rebrickableService->customizePartsInSet('0014-1', $customParts);

        $this->assertEquals(['message' => 'Parts customized successfully'], $response->getData(true));
    }

    public function testGetSetDetails()
    {
        $mockSetDetailsResponse = new Response(200, [], json_encode([
            'set_num' => '0014-1',
            'name' => 'Test Set',
            'year' => 2024,
            'theme_id' => 1,
            'num_parts' => 100,
        ]));

        $this->mockCacheService->shouldReceive('remember')
            ->once()
            ->andReturn(json_decode($mockSetDetailsResponse->getBody()->getContents(), true));

        $mockPartsInSetResponse = new Response(200, [], json_encode([
            'results' => [
                [
                    'part' => [
                        'part_num' => '3001',
                        'name' => 'Brick 2x4',
                        'part_cat_id' => 1,
                        'part_url' => 'http://example.com',
                        'part_img_url' => 'http://example.com/image.jpg'
                    ],
                    'set_num' => '0014-1',
                    'quantity' => 10
                ]
            ]
        ]));

        $this->mockCacheService->shouldReceive('remember')
            ->once()
            ->andReturn(json_decode($mockPartsInSetResponse->getBody()->getContents(), true));

        $setDetails = $this->rebrickableService->getSetDetails('0014-1');

        $this->assertEquals('0014-1', $setDetails['set_num']);
        $this->assertEquals('Test Set', $setDetails['name']);

        $this->assertEquals('3001', $setDetails['partsInSet'][0]['part_num']);
        $this->assertEquals('Brick 2x4', $setDetails['partsInSet'][0]['name']);
        $this->assertEquals(10, $setDetails['partsInSet'][0]['quantity']);
    }
}
