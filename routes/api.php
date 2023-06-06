<?php

use App\Http\Controllers\Api\V1\AuthController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('send-verification-email', [EmailVerificationController::class, 'send']);
Route::post('verify-email', [EmailVerificationController::class, 'verify']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'index']);
    //USER ROLES FOR ADMIN
    Route::post('user/roles/{user_id}/{role_id}', [UserRolesController::class, 'store']);
});
