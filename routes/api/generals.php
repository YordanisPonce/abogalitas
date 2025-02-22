<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

Route::post('/upload', [GeneralController::class, 'uploadFile']);
