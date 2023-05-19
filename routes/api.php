<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IssueTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

Route::resource('issues/types', IssueTypeController::class);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
});

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('{user}', 'show');
    Route::delete('{user}', 'destroy');
    Route::match(['put', 'patch'], '{user}', 'update_details');
    Route::match(['put', 'patch'], '{user}/password', 'change_password');
});

Route::fallback(fn () => response(['message' => 'Resource not found.'], Response::HTTP_NOT_FOUND));
