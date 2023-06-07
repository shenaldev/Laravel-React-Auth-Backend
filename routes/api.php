<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;
use App\Http\Controllers\Api\V1\Email\EmailVerificationController;
use App\Http\Controllers\Api\V1\Roles\UserRolesController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    //EMAIL VERIFY
    Route::post('/send-verification-email', [EmailVerificationController::class, 'send']);
    Route::post('/verify-email', [EmailVerificationController::class, 'verify']);
    //PASSWORD RESET
    Route::post('/forgot-password', [PasswordController::class, 'sendResetMail']);
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
    Route::post('/verify-token/{token}', [PasswordController::class, 'verifyToken']);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    //USER ROLES FOR ADMIN
    Route::post('/user/roles/{user_id}/{role_id}', [UserRolesController::class, 'store']);
});
