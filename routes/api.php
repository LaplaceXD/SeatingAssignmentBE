<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IssueTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IssueController;

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

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::middleware('auth:sanctum')->post('logout', 'logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('issues')->group(function () {
        Route::controller(IssueController::class)->group(function () {
            $notFound = fn () => abort(Response::HTTP_NOT_FOUND, 'Issue not found.');

            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{issue}', 'show')->missing($notFound);
            Route::put('{issue}', 'update')->can('ownerOrAdmin', 'issue')->missing($notFound);
            Route::delete('{issue}', 'destroy')->can('admin', 'App\Models\Issue')->missing($notFound);
            Route::post('{issue}/status', 'validated')->can('admin', 'App\Models\Issue')->missing($notFound);
        });

        Route::resource('types', IssueTypeController::class)->missing(fn () => abort(Response::HTTP_NOT_FOUND, 'Issue Type not found.'));
    });

    Route::prefix('users')->controller(UserController::class)->group(function () {
        $notFound = fn () => abort(Response::HTTP_NOT_FOUND, 'User not found.');

        Route::get('/', 'index')->can('admin', 'App\Models\User');
        Route::get('{user}', 'show')->can('ownerOrHigherRole', 'user')->missing($notFound);
        Route::delete('{user}', 'destroy')->can('higherRole', 'user')->missing($notFound);
        Route::put('{user}', 'updateDetails')->can('ownerOrHigherRole', 'user')->missing($notFound);
        Route::put('{user}/password', 'changePassword')->can('owner', 'user')->missing($notFound);
    });
});

Route::fallback(fn () => abort(Response::HTTP_NOT_FOUND, 'Resource not found.'));
