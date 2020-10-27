<?php

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
Route::middleware(['auth'])->group(function(){ 
    Route::namespace('Dashboard')->group(function() {  
           
            Route::redirect('/', url('/dashboard'));
            Route::redirect('/home', url('/dashboard'));

            Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
            Route::get('/dashboard-administrator', 'DashboardController@dashboardAdministrator')->name('dashboard.administrator');
            Route::get('/dashboard-user', 'DashboardController@dashboardUser')->name('dashboard.user');
            
    });
    Route::namespace('User')->group(function() {
        Route::middleware('permission:view-user')->group(function() {
            Route::post('/user/data-user', 'UserController@dataUsers')->name('user.data');
            Route::post('/user/data-tourism', 'UserController@dataTourisms')->name('user.tourism');

            Route::resource('user', 'UserController')->except('destroy'); 
        });


        Route::middleware('permission:view-role')->group(function() {
            Route::post('/role/data-role', 'RoleController@dataRole')->name('role.data');
            Route::resource('role', 'RoleController')->except(['create','store','destroy']); 
        });

        Route::middleware('permission:view-user-log')->group(function() {
            Route::post('/user-log-activity/data-log', 'UserActivityLogController@dataLog')->name('user-log.data');
            Route::resource('user-log-activity', 'UserActivityLogController')->except(['create','store','destroy','update','edit']); 
        });

    });

    Route::namespace('Tourism')->group(function() {
        Route::middleware('permission:view-tourism-info')->group(function() {
            Route::post('/tourism-info/data-tourism', 'TourismInfoController@tourismInfoData')->name('tourism-info.data');
            Route::resource('tourism-info', 'TourismInfoController'); 
        });
    });

    Route::namespace('Ticket')->group(function() {
        Route::middleware('permission:view-ticket')->group(function() {
            Route::post('/ticket/data-ticket', 'TicketController@ticketData')->name('ticket.data');
            Route::resource('ticket', 'TicketController')->except('show','create','destroy'); 
        });

        Route::middleware('permission:view-report-ticket-user')->group(function() {
            Route::get('/report-ticket-user', 'ReportController@reportUser')->name('report-ticket.user');
        });

        Route::middleware('permission:view-report-ticket-administrator')->group(function() {
            Route::get('/report-ticket-administrator', 'ReportController@reportAdministratorMonthly')->name('report-ticket.administrator');
            Route::get('/report-ticket-administrator-daily', 'ReportController@reportAdministratorDaily')->name('report-ticket.administrator-daily');

        });
    });
    
 });
