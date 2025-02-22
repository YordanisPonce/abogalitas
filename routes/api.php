<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(
    [
        'prefix' => '/auth'
    ],
    base_path('routes/api/auth.php')
);

Route::group(
    [
        'prefix' => '/generals'
    ],
    base_path('routes/api/generals.php')
);

Route::group(
    [
        'prefix' => '/documents'
    ],
    base_path('routes/api/documents.php')
);
Route::group(
    [
        'prefix' => '/categories'
    ],
    base_path('routes/api/categories.php')
);
Route::group(
    [
        'prefix' => '/plans'
    ],
    base_path('routes/api/plans.php')
);