<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CategoryController::class, 'index']);
Route::get('/{slug}', [CategoryController::class, 'show']);
Route::post('/', [CategoryController::class, 'store']);
Route::put('/{id}', [CategoryController::class, 'update']);
Route::delete('/{id}', [CategoryController::class, 'destroy']);