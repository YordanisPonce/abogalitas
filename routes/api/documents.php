<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DocumentController::class, 'index']);
Route::get('/{id}', [DocumentController::class, 'show']);
Route::post('/', [DocumentController::class, 'store']);
Route::put('/{id}', [DocumentController::class, 'update']);
Route::delete('/{id}', [DocumentController::class, 'destroy']);