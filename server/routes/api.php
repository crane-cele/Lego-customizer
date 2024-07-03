<?php

use Illuminate\Http\Request;
use App\Http\Controllers\LegoController;
use Illuminate\Support\Facades\Route;


Route::prefix('lego')->group(function () {
    Route::get('/sets', [LegoController::class, 'getSets']);
    Route::get('/parts', [LegoController::class, 'getParts']);
    Route::get('/sets/{set_num}', [LegoController::class, 'getSetDetails']);
    Route::get('/sets/{set_num}/parts', [LegoController::class, 'getPartsInSet']);
    Route::get('/part-categories', [LegoController::class, 'getPartCategories']);
    Route::get('/themes', [LegoController::class, 'getThemes']);
    Route::get('/sets/{set_num}/custom-parts', [LegoController::class, 'getCustomPartsInSet']);
    Route::post('/sets/{set_num}/parts', [LegoController::class, 'customizePartsInSet']);
});