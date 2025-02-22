<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/login", [AuthController::class, "login"]);
Route::post("/send-new-pin", [AuthController::class, "sendNewPin"]);
Route::post("/register", [AuthController::class, "register"]);
Route::get("/profile", [AuthController::class, "profile"]);
Route::put("/profile", [AuthController::class, "updateProfile"]);
Route::post("/logout", [AuthController::class, "logout"]);
Route::put("/update-password", [AuthController::class, "updatePassword"]);
Route::post("/forgot-password", [AuthController::class, "forgotPassword"]);
Route::post("/reset-password", [AuthController::class, "resetPassword"]);
Route::post("/verify-account", [AuthController::class, "verifyAccount"]);
Route::post("/delete-my-account", [AuthController::class, "deleteMyAccount"]);