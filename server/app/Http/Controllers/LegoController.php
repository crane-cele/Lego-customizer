<?php

namespace App\Http\Controllers;

use App\Services\RebrickableService;
use Illuminate\Http\Request;

class LegoController extends Controller
{
    protected $rebrickableService;

    public function __construct(RebrickableService $rebrickableService)
    {
        $this->rebrickableService = $rebrickableService;
    }

    public function getSets(Request $request)
    {   
        $query = $request->input('query');
        $page = $request->input('page', 1);
        $page_size = $request->input('page_size', 20);
        $theme = $request->input('theme');
        $year = $request->input('year', 2024);

        $sets = $this->rebrickableService->getSets($query, $page, $page_size, $theme, $year);

        return response()->json($sets);
    }

    public function getParts(Request $request)
    {
        $query = $request->input('query');
        $page = $request->input('page', 1);
        $page_size = $request->input('page_size', 20);
        $category = $request->input('category');

        $parts = $this->rebrickableService->getParts($query, $page, $page_size, $category);
       
        return response()->json($parts);
    }

    public function getSetDetails($set_num)
    {
        $setDetails = $this->rebrickableService->getSetDetails($set_num);
        
        return response()->json($setDetails);
    }

    public function getPartsInSet($set_num)
    {
        $parts = $this->rebrickableService->getPartsInSet($set_num);
        
        return response()->json($parts);
    }

    public function getPartCategories()
    {
        $categories = $this->rebrickableService->getPartCategories();
       
        return response()->json($categories);
    }

    public function getThemes()
    {
        $themes = $this->rebrickableService->getThemes();
       
        return response()->json($themes);
    }

    public function customizePartsInSet(Request $request, $set_num)
    {
        $custom_parts = $request->json()->all();
        $response = $this->rebrickableService->customizePartsInSet($set_num, $custom_parts);
        
        return response()->json($response);
    }

    public function getCustomPartsInSet($set_num)
    {
        $parts = $this->rebrickableService->getCustomPartsInSet($set_num);

        return response()->json($parts);
    }
}