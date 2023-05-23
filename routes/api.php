<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IssueTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LaboratoryController;

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
    Route::prefix('laboratories')->controller(LaboratoryController::class)->group(function () {
        $notFound = fn () => abort(Response::HTTP_NOT_FOUND, 'Laboratory not found.');

        Route::get('/', 'index');
        Route::get('{laboratory}', 'show')->missing($notFound);
        Route::get('{laboratory}/issues', 'getIssues')->missing($notFound);
        Route::get('{laboratory}/seats/{seat}/issues', 'getSeatIssues')
            ->where('seat', '[A-Za-z0-9]+')
            ->missing($notFound);
    });

    Route::prefix('issues')->group(function () {
        Route::controller(IssueController::class)->group(function () {
            $notFound = fn () => abort(Response::HTTP_NOT_FOUND, 'Issue not found.');

            Route::get('/', 'index');
            Route::get('{issue}', 'show')->missing($notFound);
            Route::get('{issue}/trails', 'trails')->missing($notFound);

            Route::post('/', 'store');
            Route::post('{issue}/status', 'validated')->can('validated', 'App\Models\Issue')->missing($notFound);

            Route::put('{issue}', 'update')->can('update', 'issue')->missing($notFound);
            Route::put('{issue}/status', 'updateStatus')->can('updateStatus', 'App\Models\Issue')->missing($notFound);
            Route::put('{issue}/assignee', 'assign')->can('assign', 'App\Models\Issue')->missing($notFound);

            Route::delete('{issue}', 'destroy')->can('destroy', 'issue')->missing($notFound);
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
