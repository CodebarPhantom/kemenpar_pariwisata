<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
// use App\Http\Controllers\API\Ticket\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('API')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/login', [LoginController::class, 'index']);

            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/show', [LoginController::class, 'show']);
                Route::post('/logout', [LogoutController::class, 'index']);
            });
        });
    });

    Route::namespace('Ticket')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/ticket/store-bulk', [App\Http\Controllers\API\Ticket\TicketController::class, 'storeBulk']);
            Route::delete('/ticket/truncate', [App\Http\Controllers\API\Ticket\TicketController::class, 'truncate']);
            Route::post('/ticket/seed', function () {
                return Artisan::call('db:seed --class PrimaryTestsSeeder');
            });

            Route::apiResources(['/ticket' => 'TicketController']);
        });
    });
});
