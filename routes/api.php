<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\LoginController;
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
            });
        });
    });

    Route::namespace('Ticket')->group(function () {
        Route::group(['prefix' => 'ticket'], function () {
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/{id}', [App\Http\Controllers\API\Ticket\TicketController::class, 'show']);
                Route::post('/store', [App\Http\Controllers\API\Ticket\TicketController::class, 'store']);
                Route::post('/store-bulk', [App\Http\Controllers\API\Ticket\TicketController::class, 'storeBulk']);
                Route::resource('/', TicketController::class)->only('index', 'destroy', 'update');
            });
        });
    });
});
