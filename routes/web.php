<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);
Route::middleware(['auth', 'hasRole.user'])->group(function () {
    Route::namespace('Dashboard')->group(function () {
        Route::redirect('/', url('/dashboard'));
        Route::redirect('/home', url('/dashboard'));

        Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
        Route::get('/dashboard-administrator', 'DashboardController@dashboardAdministrator')->name(
            'dashboard.administrator'
        );
        Route::get('/dashboard-user', 'DashboardController@dashboardUser')->name('dashboard.user');
    });
    Route::namespace('User')->group(function () {
        Route::middleware('permission:view-user')->group(function () {
            Route::post('/user/data-user', 'UserController@dataUsers')->name('user.data');
            Route::post('/user/data-tourism', 'UserController@dataTourisms')->name('user.tourism');

            Route::resource('user', 'UserController')->except('destroy');
        });

        Route::middleware('permission:view-role')->group(function () {
            Route::post('/role/data-role', 'RoleController@dataRole')->name('role.data');
            Route::resource('role', 'RoleController')->except(['create', 'store', 'destroy']);
        });

        Route::middleware('permission:view-user-log')->group(function () {
            Route::post('/user-log-activity/data-log', 'UserActivityLogController@dataLog')->name('user-log.data');
            Route::resource('user-log-activity', 'UserActivityLogController')->except([
                'create',
                'store',
                'destroy',
                'update',
                'edit',
            ]);
        });
    });

    Route::namespace('Tourism')->group(function () {
        Route::middleware('permission:view-tourism-info')->group(function () {
            Route::post('/tourism-info/data-tourism', 'TourismInfoController@tourismInfoData')->name('tourism-info.data');
            Route::post('/tourism-info/upload-file', 'TourismInfoController@uploadFile')->name('tourism-info.upload-file');
            Route::post('tourism-info/delete-file', 'TourismInfoController@destroyFile')->name('tourism-info.destroy-file');

            Route::resource('tourism-info', 'TourismInfoController')->except('destroy');

        });

        Route::middleware('permission:view-withdrawal')->group(function () {

            Route::post('/tourism-info-withdrawal/data-withdrawal', 'TourismInfoWithdrawalController@data')->name('tourism-info-withdrawal.data');
            Route::post('/tourism-info-withdrawal/submission', 'TourismInfoWithdrawalController@submission')->name('tourism-info-withdrawal.processed');
            Route::post('/tourism-info-withdrawal/processed', 'TourismInfoWithdrawalController@processed')->name('tourism-info-withdrawal.processed');
            Route::post('/tourism-info-withdrawal/rejected', 'TourismInfoWithdrawalController@rejected')->name('tourism-info-withdrawal.rejected');
            Route::post('/tourism-info-withdrawal/completed', 'TourismInfoWithdrawalController@completed')->name('tourism-info-withdrawal.completed');


            Route::resource('tourism-info-withdrawal', 'TourismInfoWithdrawalController')->except('destroy');

        });
    });

    Route::namespace('Setting')->prefix('setting')->as('setting.')->group(function () {
        Route::post('amenities-data', 'AmenityController@data')->name('amenities.data');
        Route::resource('amenities', 'AmenityController')->except('destroy','show');
    });

    Route::namespace('Promotion')->group(function () {
        Route::middleware('permission:view-ticket-promotion')->group(function () {
            Route::post('/ticket-promotion/data-ticket-promotion', 'TicketPromotionController@data')->name(
                'ticket-promotion.data'
            );
            Route::post('/ticket-promotion/data-tourism', 'TicketPromotionController@dataTourisms')->name(
                'ticket-promotion.tourism'
            );

            Route::resource('ticket-promotion', 'TicketPromotionController')->except('destroy');
        });
    });

    Route::namespace('Ticket')->group(function () {
        Route::middleware('permission:view-ticket')->group(function () {
            Route::post('/ticket/data-ticket', 'TicketController@ticketData')->name('ticket.data');
            Route::resource('ticket', 'TicketController')->except('show', 'create', 'destroy');
        });

        Route::middleware('permission:view-report-ticket-user')->group(function () {
            Route::get('/report-ticket-user', 'ReportController@reportUser')->name('report-ticket.user');
        });

        Route::middleware('permission:view-report-ticket-administrator')->group(function () {
            Route::get('/report-ticket-administrator', 'ReportController@reportAdministratorMonthly')->name(
                'report-ticket.administrator'
            );
            Route::get('/report-ticket-administrator-void', 'ReportController@reportAdministratorMonthlyVoid')->name(
                'report-ticket.administrator-void'
            );
            Route::get('/report-ticket-administrator-daily', 'ReportController@reportAdministratorDaily')->name(
                'report-ticket.administrator-daily'
            );
            Route::get('/report-ticket-administrator-daily-void', 'ReportController@reportAdministratorDailyVoid')->name(
                'report-ticket.administrator-daily-void'
            );
        });
    });

    Route::namespace('Emergency')->group(function () {
        Route::post('/report-emergency/report-emergency', 'EmergencyReportController@data')->name(
            'report-emergency.data'
        );
        Route::resource('report-emergency', 'EmergencyReportController')->except('destroy');
    });
});

Route::middleware('guest')->namespace('Tourism')->group(function () {
    Route::get('/register/tourism-info', 'TourismInfoController@createGuest')->name('tourism-register.create');
    Route::post('/register/tourism-info', 'TourismInfoController@storeGuest')->name('tourism-register.store');
});
