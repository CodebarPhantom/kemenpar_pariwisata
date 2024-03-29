<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Report\Ticket\ReportTicketController;
use App\Http\Controllers\API\Tourism\TourismInfoController;

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

            Route::middleware('auth:sanctum', 'api.user')->group(function () {
                Route::get('/data', [LoginController::class, 'show']);
                Route::post('/logout', [LogoutController::class, 'index']);
            });
        });
    });

    Route::namespace('Ticket')->group(function () {
        Route::middleware('auth:sanctum', 'api.user')->group(function () {
            Route::get('/ticket/ticket-data', [App\Http\Controllers\API\Ticket\TicketController::class, 'ticketData']);
            Route::get('/ticket/ticket-void-data', [App\Http\Controllers\API\Ticket\TicketController::class, 'ticketVoidData']);
            Route::post('/ticket/store-bulk', [App\Http\Controllers\API\Ticket\TicketController::class, 'storeBulk']);
            Route::post('/ticket/void', [App\Http\Controllers\API\Ticket\TicketController::class, 'voidTicket']);

            Route::delete('/ticket/truncate', [App\Http\Controllers\API\Ticket\TicketController::class, 'truncate']);

            Route::post('/ticket/seed', function () {
                if (getenv('APP_DEBUG')) {
                    return Artisan::call('db:seed --class TicketsSeeder');
                } else {
                    return abort(404);
                }
            });

            Route::apiResources(['/ticket' => 'TicketController', 'middleware' => 'throttle:10000,1']);
        });
    });

    Route::namespace('Test')->group(function () {
        Route::middleware('auth:sanctum', 'api.user')->group(function () {
            Route::post('/test/store-bulk', [App\Http\Controllers\API\Test\TestController::class, 'storeBulk']);
            Route::delete('/test/truncate', [App\Http\Controllers\API\Test\TestController::class, 'truncate']);
            Route::post('/test/seed', function () {
                if (getenv('APP_DEBUG')) {
                    return Artisan::call('db:seed --class PrimaryTestsSeeder');
                } else {
                    return abort(404);
                }
            });

            Route::apiResources(['/test' => 'TestController', 'middleware' => 'throttle:10000,1']);
        });
    });

    Route::namespace('Report')->group(function () {
        Route::namespace('Ticket')->group(function () {
            Route::middleware('auth:sanctum', 'api.user')->group(function () {
                Route::post('/report/ticket/daily', [ReportTicketController::class, 'daily'])->name(
                    'report.ticket.daily'
                );
                Route::post('/report/ticket/monthly', [ReportTicketController::class, 'monthly'])->name(
                    'report.ticket.monthly'
                );

                Route::post('/report/ticket/custom-date', [ReportTicketController::class, 'customDate'])->name(
                    'report.ticket.custom-date'
                );

                Route::post('/report/ticket/void/daily', [ReportTicketController::class, 'dailyVoid'])->name(
                    'report.ticket.void.daily'
                );
                Route::post('/report/ticket/void/monthly', [ReportTicketController::class, 'monthlyVoid'])->name(
                    'report.ticket.void.monthly'
                );

                Route::post('/report/ticket/void/custom-date', [ReportTicketController::class, 'customDateVoid'])->name(
                    'report.ticket.void.custom-date'
                );
            });
        });
    });

    Route::namespace('Emergency')->group(function () {
        Route::middleware('auth:sanctum', 'api.user')->group(function () {
            Route::apiResources(['/emergency' => 'EmergencyController']);
        });
    });

    // to ulinyu.id nanti aja pengamannya gan
    Route::namespace('Tourism')->group(function () {
        Route::group([],function () {
            Route::post('/tourism-info/category-info',[TourismInfoController::class,'categoryInfo']);
            Route::apiResources(['/tourism-info'=>'TourismInfoController']);
        });
    });

});
