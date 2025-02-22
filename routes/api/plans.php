<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlanController::class, 'index']);
Route::get('/{id}', [PlanController::class, 'show']);
Route::post('/', [PlanController::class, 'store'])->middleware(AdminMiddleware::class);
Route::put('/{id}', [PlanController::class, 'update'])->middleware(AdminMiddleware::class);
Route::delete('/{id}', [PlanController::class, 'destroy'])->middleware(AdminMiddleware::class);