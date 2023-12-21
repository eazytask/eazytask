<?php

use App\Http\Controllers\admin\ActivityLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CompanyTypeController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\MyavailabilityController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\RoasterStatusController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UpcomingeventController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\InductedsiteController;
use App\Http\Controllers\super_admin\ComplianceController;

use App\Http\Controllers\CalenderDemoController;
use App\Http\Controllers\user\UserCalendarController;

use App\Http\Controllers\NewTimeKeeperController;
use App\Http\Controllers\user\PastShiftController;
use App\Http\Controllers\user\SignInController;
use App\Http\Controllers\user\UnconfirmedShiftController;
use App\Http\Controllers\user\UpcomingShiftController;
use App\Http\Controllers\admin\ViewScheduleController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\AdminEventRequestController;
use App\Http\Controllers\admin\ChartController;
use App\Http\Controllers\admin\KioskController;
use App\Http\Controllers\admin\PDFGeneratorController;
use App\Http\Controllers\admin\PaymentListcontroller;
use App\Http\Controllers\admin\ScheduleStatusController;
use App\Http\Controllers\admin\SignInStatusController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\supervisor\PDFReportController;
use App\Http\Controllers\supervisor\RoasterCalendarController;
use App\Http\Controllers\supervisor\SupervisorChartController;
use App\Http\Controllers\supervisor\SupervisorController;
use App\Http\Controllers\supervisor\SupervisorHomeController;
use App\Http\Controllers\supervisor\SupervisorPaymentController;
use App\Http\Controllers\user\SwitchCompanyController;
use App\Http\Controllers\user\TimesheetController;
use App\Http\Controllers\user\UserComplianceController;
use App\Http\Controllers\user\UserReportController;
use App\Http\Controllers\user\UserRosterCalendar;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\MessagesController;

//dcw add controller
use App\Http\Controllers\admin\PDFEmailController;
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

Route::get('cam1', function () {
    return view('cam1');
});
// Route::get('cam2', function(){
//     return view('camera');
// });

Route::get('delete-month/{from}', [NewTimeKeeperController::class, 'delete_month']);
Route::get('copy-week/{from}/{to}', [NewTimeKeeperController::class, 'copy_week']);
Route::get('copy-month/{from}/{to}', [NewTimeKeeperController::class, 'copy_month']);
Route::get('change', [NewTimeKeeperController::class, 'change']);

Route::group(['middleware' => ['web']], function () {
    Route::get('autologout', function () {
        Auth::logout();
        return redirect()->intended('/');
    });

    Route::get('autologin', function () {
        $user = $_GET['id'];
        if (!Auth::user()) {
            Auth::loginUsingId($user, true);
        }
        // return redirect()->intended('/');
        // return redirect('http://localhost:8888/autologin?id='.auth()->user()->id.'&api_token=token');
        // return redirect('https://'.auth()->user()->user_roles->first()->company->company_code .'.easytask.com.au/autologin?id='.auth()->user()->id.'&api_token=token');
        return redirect('/');
    });
});

Route::get('clear-cache', function () {
    Artisan::call('cache:clear');

    Artisan::call('config:cache');
    Artisan::call('config:clear');

    // Artisan::call('route:cache');
    Artisan::call('route:clear');

    Artisan::call('view:cache');
    Artisan::call('view:clear');

    // return Artisan::call('queue:restart');
    
    echo 'all cache cleared';
});

// roster calendar pdf
Route::get('/roster/calendar/pdf', function () {
    return view('pages.Admin.pdf.roster_calendar');
});


Route::get('/', function () {
    // return auth()->user()->company;
    // return auth()->user()->user_roles;
    return view('auth.login');
})->middleware('redirect');
Auth::routes();

Route::get('/super-admin/home', [HomeController::class, 'SuperadminHome'])->name('super-admin.home')->middleware('super_admin');

//statuses
Route::get('super-admin/status', [StatusController::class, 'index'])->middleware('super_admin');
Route::post('/status', [StatusController::class, 'store'])->name('status.store')->middleware('super_admin');
Route::post('/status/edit', [StatusController::class, 'update'])->name('status.update')->middleware('super_admin');
Route::get('status/delete/{id}', [StatusController::class, 'destroy'])->middleware('super_admin');

#company type
Route::get('super-admin/company/type', [CompanyTypeController::class, 'index'])->name('company.type')->middleware('super_admin');
Route::post('company/type', [CompanyTypeController::class, 'store'])->name('companyType.store')->middleware('super_admin');
Route::post('company/type/edit', [CompanyTypeController::class, 'update'])->name('companyType.update')->middleware('super_admin');
Route::get('company/type/delete/{id}', [CompanyTypeController::class, 'destroy'])->middleware('super_admin');

//Company Routes
Route::get('/super-admin/companies', [CompanyController::class, 'index'])->name('companies')->middleware('super_admin');
Route::post('/super-admin/company/store', [CompanyController::class, 'storeCompanies'])->name('company-store')->middleware('super_admin');
Route::post('/super-admin/company/update', [CompanyController::class, 'updateCompany'])->name('company-update')->middleware('super_admin');
Route::get('/super-admin/company/delete/{id}', [CompanyController::class, 'delete'])->middleware('super_admin');

//compliance routes
Route::get('/super-admin/compliance', [ComplianceController::class, 'index'])->name('compliance')->middleware('super_admin');
Route::post('/super-admin/compliance/store', [ComplianceController::class, 'store'])->name('compliance.store')->middleware('super_admin');
Route::post('/super-admin/compliance/update', [ComplianceController::class, 'update'])->name('compliance.update')->middleware('super_admin');
Route::get('/super-admin/compliance/delete/{id}', [ComplianceController::class, 'destroy'])->middleware('super_admin');

//super admin profile
Route::get('/super-admin/profile-settings/{id}', [CompanyController::class, 'SuperAdminProfile'])->middleware('super_admin');
Route::post('/super-admin/profile-settings/update', [CompanyController::class, 'profileUpdate'])->name('super-admin-profile-update')->middleware('super_admin');
Route::post('/super-admin/profile-settings/image/update', [CompanyController::class, 'UpdateSuperAdminPhoto'])->name('super-admin-profile-photo-update')->middleware('super_admin');
Route::post('/super-admin/user-password/change-password-store', [CompanyController::class, 'changePassStore'])->name('change-password-store')->middleware('super_admin');
Route::group(['middleware' => ['super_admin']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
Route::get('admin/home/admins/{id}', [HomeController::class, 'adminHomeall'])->middleware('super_admin');

Route::group(['middleware' => ['company_status']], function () {

    #notification mark as read
    Route::get('notification/mark/as/read', [HomeController::class, 'markNotification']);
    Route::get('notification/delete', [HomeController::class, 'delete_notifications']);

    #activity log
    Route::get('admin/home/activity/log', [ActivityLogController::class, 'index'])->middleware('is_admin');
    Route::post('admin/home/activity/log/search', [ActivityLogController::class, 'search'])->name('log.search')->middleware('is_admin');

    #supervisor routes
    Route::group(['middleware' => ['is_supervisor']], function () {
        Route::get('supervisor/home', [SupervisorHomeController::class, 'index'])->name('supervisor.dashboard');
        Route::get('supervisor/home/switch/company', [SupervisorHomeController::class, 'switch_company']);
        //supervisor chart routes
        Route::get('supervisor/order/chart/data', [SupervisorChartController::class, 'order_chart']);
        Route::get('supervisor/revenue/report/data', [SupervisorChartController::class, 'revenue_report_chart']);
        Route::get('supervisor/client/portion/data', [SupervisorChartController::class, 'client_portion_chart']);

        //supervisor task desctiption
        Route::get('supervisor/home/task/descriptions', [SupervisorHomeController::class, 'taskDescriptions']);
        Route::post('supervisor/home/task/descriptions', [SupervisorHomeController::class, 'storeTaskDescriptions']);
        Route::post('supervisor/home/task/descriptions/update', [SupervisorHomeController::class, 'updateTaskDescriptions']);
        Route::get('supervisor/home/task/descriptions/manage', [SupervisorHomeController::class, 'manageTaskDescriptions']);

        // supervisor Profile
        Route::get('/supervisor/profile-settings', [SupervisorController::class, 'userProfile']);
        Route::post('/supervisor/profile-settings/update', [EmployeeController::class, 'userProfileUpdate'])->name('supervisor-profile-update');
        Route::post('supervisor/profile-settings/image/update', [EmployeeController::class, 'updateUserPhoto'])->name('supervisor-profile-photo-update');
        Route::post('/supervisor/user-password/change-password-store', [EmployeeController::class, 'userchangePassStore'])->name('supervisor-change-password-store');

        //roster calendar

        Route::get('supervisor/home/filter/employee', [RoasterCalendarController::class, 'filter_emoployee']);
        Route::get('supervisor/home/roster/calender/', [RoasterCalendarController::class, 'index']);
        Route::get('supervisor/roster/calender/search', [RoasterCalendarController::class, 'search']);
        Route::get('supervisor/get_project/{admin_id}/{id}', [RoasterCalendarController::class, 'get_project']);

        Route::post('supervisor/home/roster/calender/store', [RoasterCalendarController::class, 'store']);
        Route::post('supervisor/home/roster/calender/update', [RoasterCalendarController::class, 'update']);
        Route::get('supervisor/home/roster/calender/delete/{id}', [RoasterCalendarController::class, 'delete']);

        //payment
        Route::get('supervisor/home/payment/{id}', [SupervisorPaymentController::class, 'payment_index']);
        Route::post('supervisor/home/payment/search', [SupervisorPaymentController::class, 'payment_search'])->name('payment_search');
        Route::post('supervisor/home/timekeeper/updatedate', [SupervisorPaymentController::class, 'DateUpdate'])->name('supervisorDateUpdate');
        Route::post('supervisor/payment/add', [SupervisorPaymentController::class, 'addpayment'])->name('supervisoraddpayment');
        Route::post('supervisor/home/payment/storepaymentdetails', [SupervisorPaymentController::class, 'storepaymentdetails'])->name('supervisorstorepaymentdetails');

        //payment list
        Route::post('supervisor/home/payment/invoice/send', [SupervisorPaymentController::class, 'invoice_send']);
        Route::get('supervisor/home/payslip/list', [SupervisorPaymentController::class, 'index']);
        Route::get('supervisor/home/payment/list/{id}', [SupervisorPaymentController::class, 'view']);
        Route::post('supervisor/home/payment/list/search', [SupervisorPaymentController::class, 'search']);

        #pdf generator routes
        Route::get('supervisor/home/date/wise/report', [PDFReportController::class, 'date_wise']);
        Route::post('report/download', [PDFReportController::class, 'index'])->name('generatePDF');
        Route::get('supervisor/home/all/report', [PDFReportController::class, 'all_report']);
        Route::post('supervisor/home/all/report/search', [PDFReportController::class, 'search_report']);
    });

    Route::get('/redirecting/subdomain', [HomeController::class, 'redirectSubdomain'])->name('home');

    Route::group(['middleware' => ['is_user']], function () {
        // User Profile
        Route::get('user/roster/calendar/project', [UserRosterCalendar::class, 'get_projects']);
        Route::get('user/roster/calendar/shifts', [UserRosterCalendar::class, 'get_shifts']);

        Route::get('user/home', [HomeController::class, 'index'])->name('user.dashboard');

        Route::get('home/switch/company', [SwitchCompanyController::class, 'index'])->middleware('auth');

        #my availability
        Route::get('home/time/off', [MyavailabilityController::class, 'userIndex']);

        // Upcoming event routes user
        Route::get('user/home/upcomingevent', [UpcomingeventController::class, 'userIndex']);
        //user singin page 
        Route::get('home/sign/in', [SignInController::class, 'index']);

        //user calender
        Route::get('home/calender', [UserCalendarController::class, 'calender']);

        //user all unconfirmed shift page 
        Route::get('home/unconfirmed/shift', [UnconfirmedShiftController::class, 'index']);
        Route::get('home/unconfirmed/multiple/shift/{action}/{id}', [UnconfirmedShiftController::class, 'multiple']);

        //user all upcoming shift page 
        Route::get('home/upcoming/shift', [UpcomingShiftController::class, 'index']);

        //user all past shift page 
        Route::get('home/past/shift', [PastShiftController::class, 'index']);

        #payment report routes
        Route::get('home/payment/report', [UserReportController::class, 'payment_report']);
        Route::get('home/payment/report/{id}', [UserReportController::class, 'view_payment_report']);

        #pdf generator routes
        Route::get('home/all/report', [UserReportController::class, 'all_report']);

        #user timesheets
        Route::get('home/timesheet', [TimesheetController::class, 'index']);

        #user compliance
        Route::get('home/compliance', [UserComplianceController::class, 'index']);
        Route::get('home/user/compliance/fetch', [UserComplianceController::class, 'fetch']);
        Route::post('home/user/compliance/store', [UserComplianceController::class, 'store']);
        Route::get('home/user/compliance/delete/{id}', [UserComplianceController::class, 'distroy']);
    });

    #user post method routes
    Route::group(['middleware' => ['is_user']], function () {
        // User Profile
        Route::post('/user/employee/user-pin/change-pin-store', [EmployeeController::class, 'userchangePinStore'])->name('user-change-pin-store');

        #my availability
        Route::post('myavailability', [MyavailabilityController::class, 'store'])->name('myAvailability.store');
        Route::post('myavailability/edit', [MyavailabilityController::class, 'update'])->name('myAvailability.update');
        Route::get('myavailability/delete/{id}', [MyavailabilityController::class, 'destroy']);
        
        #my leave
        Route::post('leave', [LeaveController::class, 'store'])->name('leave.store');
        Route::post('leave/edit', [LeaveController::class, 'update'])->name('leave.update');
        Route::get('leave/delete/{id}', [LeaveController::class, 'destroy']);

        // Upcoming event routes user
        Route::post('user/home/event/store', [UpcomingeventController::class, 'eventStore'])->name('store-event');
        //user singin page 
        Route::post('home/sign/in/timekeeper', [SignInController::class, 'signIn'])->name('sign-in-timekeeper');
        Route::post('home/sign/out/timekeeper', [SignInController::class, 'signOut'])->name('sign-out-timekeeper');
        Route::post('home/user/store/timekeeper', [SignInController::class, 'storeTimekeeper'])->name('user-store-timekeeper');

        //user calender
        Route::get('user/dataget', [UserCalendarController::class, 'dataget']);
        // Route::get('user/get_project/{user_id}/{id}',[CalenderDemoController::class,'get_project'])->middleware('is_admin');

        //user all upcoming shift page 
        Route::post('home/upcoming/shift/search', [UpcomingShiftController::class, 'search'])->name('upcoming-shift-search');

        //user all past shift page 
        Route::post('home/past/shift/search', [PastShiftController::class, 'search'])->name('past-shift-search');

        //report
        Route::post('home/payment/report/search', [UserReportController::class, 'search_payment_report']);

        #pdf generator routes
        Route::post('home/all/report/search', [UserReportController::class, 'search_report']);

        //timesheet
        Route::post('home/timesheet/store', [TimesheetController::class, 'store'])->name('store-timesheet');
        Route::post('home/timesheet/update', [TimesheetController::class, 'update'])->name('update-timesheet');
        Route::get('home/timesheet/delete/{id}', [TimesheetController::class, 'delete'])->name('delete-timesheet');
        Route::post('home/timesheet/search', [TimesheetController::class, 'search'])->name('search-timesheet');

        //switch company
    });
    Route::get('home/switch/company/{company}', [SwitchCompanyController::class, 'switch'])->middleware('auth');

    //Message for admin and supervisor
    Route::get('home/messages', [MessagesController::class, 'index'])->middleware('auth');
    Route::post('home/messages', [MessagesController::class, 'store'])->middleware('is_admin');
    Route::post('home/messages/reply', [MessagesController::class, 'storeReply'])->middleware('auth');
    Route::post('home/messages/confirm', [MessagesController::class, 'confirm'])->middleware('auth');
    Route::post('home/messages/unconfirm', [MessagesController::class, 'unconfirm'])->middleware('auth');
    Route::post('home/messages/update', [MessagesController::class, 'update'])->middleware('auth');
    Route::post('home/messages/destroy', [MessagesController::class, 'destroy'])->middleware('auth');
    Route::post('home/messages/update-reply', [MessagesController::class, 'updateReply'])->middleware('auth');
    Route::post('home/messages/destroy-reply', [MessagesController::class, 'destroyReply'])->middleware('auth');
    
    Route::get('admin/home/payment/list/{id}/{company}', [PaymentListcontroller::class, 'download']);

    #switch company
    Route::get('admin/home/switch/company', [HomeController::class, 'switch_company'])->middleware('is_admin');
    #kiosk 
    Route::get('admin/kisok', [KioskController::class, 'index'])->middleware('is_admin');
    Route::get('admin/kisok/employees', [KioskController::class, 'search_employees']);
    Route::post('admin/kisok/check/pin', [KioskController::class, 'check_pin'])->middleware('is_admin');
    Route::get('admin/kisok/all/shifts/{employee_id}/{project_id}', [KioskController::class, 'all_shifts'])->middleware('is_admin');
    Route::post('admin/kisok/store/timekeeper', [KioskController::class, 'storeTimekeeper'])->middleware('is_admin');

    Route::post('admin/sign/in/timekeeper', [KioskController::class, 'signIn'])->middleware('is_admin');
    Route::post('admin/sign/out/timekeeper', [KioskController::class, 'signOut'])->middleware('is_admin');


    #admin supervisor
    Route::get('admin/home/supervisors', [SupervisorController::class, 'index'])->middleware('is_admin');
    Route::post('admin/home/supervisor/store', [SupervisorController::class, 'store'])->name('store-supervisor')->middleware('is_admin');
    Route::post('admin/home/supervisor/update', [SupervisorController::class, 'update'])->name('update-supervisor')->middleware('is_admin');
    Route::get('admin/home/supervisor/delete/{id}', [SupervisorController::class, 'delete'])->middleware('is_admin');

    #pdf generator routes
    Route::post('report/download', [PDFGeneratorController::class, 'index'])->name('generatePDF');
    Route::get('admin/home/date/wise/report', [PDFGeneratorController::class, 'date_wise'])->middleware('is_admin');
    Route::get('admin/home/all/report', [PDFGeneratorController::class, 'all_report'])->middleware('is_admin');
    Route::post('admin/home/all/report/search', [PDFGeneratorController::class, 'search_report'])->middleware('is_admin');
    
    Route::post('admin/home/all/report/search/email', [PDFEmailController::class, 'emailpdf']);//->middleware('is_admin'); //dcw add route to generate pdf and send email
    
    
    //payment list
    Route::post('admin/home/payment/invoice/send', [PaymentListcontroller::class, 'invoice_send'])->middleware('is_admin');
    Route::get('admin/home/payment/list', [PaymentListcontroller::class, 'index'])->middleware('is_admin');
    Route::get('admin/home/payment/list/{id}', [PaymentListcontroller::class, 'view'])->middleware('is_admin');
    Route::post('admin/home/payment/list/search', [PaymentListcontroller::class, 'search'])->name('search-payment-list')->middleware('is_admin');

    //chart routes
    Route::get('/order/chart/data', [ChartController::class, 'order_chart'])->middleware('is_admin');
    Route::get('/revenue/report/data', [ChartController::class, 'revenue_report_chart'])->middleware('is_admin');
    Route::get('/client/portion/data', [ChartController::class, 'client_portion_chart'])->middleware('is_admin');

    Route::get('admin/home/task/descriptions', [HomeController::class, 'taskDescriptions'])->middleware('is_admin');
    Route::post('admin/home/task/descriptions', [HomeController::class, 'storeTaskDescriptions'])->middleware('is_admin');
    Route::post('admin/home/task/descriptions/update', [HomeController::class, 'updateTaskDescriptions'])->middleware('is_admin');
    Route::get('admin/home/task/descriptions/manage', [HomeController::class, 'manageTaskDescriptions'])->middleware('is_admin');

    //admin view-schedule
    Route::get('admin/home/view/schedule/excel/print', [ViewScheduleController::class, 'printExcel'])->name('view-print-excel')->middleware('is_admin');
    Route::get('admin/home/view/schedule/{id}', [ViewScheduleController::class, 'index'])->middleware('is_admin');
    Route::post('admin/home/view/schedule/search', [ViewScheduleController::class, 'search'])->name('view-search')->middleware('is_admin');
    Route::post('admin/home/view/schedule/update', [ViewScheduleController::class, 'update'])->name('view-update-timekeeper')->middleware('is_admin');
    Route::get('admin/home/view/schedule/delete/{id}', [ViewScheduleController::class, 'delete'])->middleware('is_admin');
    Route::get('admin/home/timekeeper/approve/{ids}', [ViewScheduleController::class, 'approve'])->middleware('is_admin');

    //admin new roster-timekeeper
    Route::get('admin/home/new/timekeeper/{id}', [NewTimeKeeperController::class, 'index'])->middleware('is_admin');
    Route::post('admin/home/new/timekeeper/search', [NewTimeKeeperController::class, 'search'])->name('search-timekeeper')->middleware('is_admin');
    Route::post('admin/home/new/timekeeper/store', [NewTimeKeeperController::class, 'storeTimeKeeper'])->name('store-new-timekeeper')->middleware('is_admin');
    Route::post('admin/home/new/timekeeper/update', [NewTimeKeeperController::class, 'update'])->name('update-new-timekeeper')->middleware('is_admin');
    Route::get('admin/home/new/timekeeper/delete/{id}', [NewTimeKeeperController::class, 'delete'])->middleware('is_admin');
    
    //admin sign-in status
    Route::get('admin/home/sign/in/status', [SignInStatusController::class, 'index'])->middleware('is_admin');
    Route::get('admin/home/sign/in/status/search', [SignInStatusController::class, 'search'])->middleware('is_admin');

    #filter employee
    Route::get('admin/home/filter/employee', [ReportController::class, 'filter_emoployee'])->middleware('is_admin');

    #admin roster report
    Route::get('admin/home/report', [ReportController::class, 'index'])->middleware('is_admin');
    Route::get('admin/home/report/search', [ReportController::class, 'search'])->middleware('is_admin');
    Route::get('admin/home/report/drag/keeper', [ReportController::class, 'drag_keeper']);
    Route::get('admin/home/report/delete/{id}', [ReportController::class, 'delete'])->middleware('is_admin');
    Route::get('admin/home/report/publish/{id}', [ReportController::class, 'publish_shift'])->middleware('is_admin');
    Route::get('/get-projects/{client_id}', [ReportController::class, 'getProjects'])->middleware('is_admin');

    #admin roster sign in status
    Route::get('admin/home/schedule/status', [ScheduleStatusController::class, 'index'])->middleware('is_admin');
    Route::get('admin/home/schedule/status/search', [ScheduleStatusController::class, 'search'])->middleware('is_admin');
    Route::post('admin/home/shift/approve', [ScheduleStatusController::class, 'approve'])->middleware('is_admin');
    Route::get('admin/home/shift/approve/week', [ScheduleStatusController::class, 'approve_week'])->middleware('is_admin');
    // Route::post('admin/home/sign/in/status/change', [SignInStatusController::class, 'change'])->middleware('is_admin');

    // admin Revenue routes
    Route::get('admin/home/revenue/{id}', [RevenueController::class, 'index'])->middleware('is_admin');
    Route::post('admin/home/revenue/search', [RevenueController::class, 'search'])->name('search-revenue')->middleware('is_admin');
    Route::post('admin/home/revenue/store', [RevenueController::class, 'store'])->name('store-revenue')->middleware('is_admin');
    Route::post('admin/home/revenue/update', [RevenueController::class, 'update'])->name('update-revenue')->middleware('is_admin');
    Route::get('admin/home/revenue/delete/{id}', [RevenueController::class, 'delete'])->middleware('is_admin');

    // admin Upcoming event routes
    // Route::get('admin/home/upcomingevent', [UpcomingeventController::class, 'index'])->middleware('is_admin');
    // Route::post('admin/home/upcomingevent/search', [UpcomingeventController::class, 'search'])->name('search-event')->middleware('is_admin');
    Route::post('admin/home/upcomingevent/store', [UpcomingeventController::class, 'store'])->name('store-upcomingevent')->middleware('is_admin');
    Route::post('admin/home/upcomingevent/update', [UpcomingeventController::class, 'update'])->name('update-upcomingevent')->middleware('is_admin');
    Route::get('admin/home/upcomingevent/delete/{id}', [UpcomingeventController::class, 'delete'])->middleware('is_admin');

    // admin Upcoming event request

    Route::get('admin/home/event/request', [AdminEventRequestController::class, 'index'])->middleware('is_admin');
    Route::get('admin/home/event/search', [AdminEventRequestController::class, 'dataget'])->middleware('is_admin');
    Route::get('admin/home/event/publish', [AdminEventRequestController::class, 'publish'])->middleware('is_admin');


    //Route::get('admin/home', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');
    // admin/company routes
    Route::get('admin/home/{id}', [HomeController::class, 'adminHome'])->middleware('is_admin');
    Route::get('admin/home/employee/{id}', [EmployeeController::class, 'index'])->middleware('is_admin');
    Route::get('admin/home/fetch/employee', [EmployeeController::class, 'fetch']);
    Route::post('admin/home/employee/store', [EmployeeController::class, 'store'])->name('store-employee')->middleware('is_admin');
    Route::post('admin/home/employee/update', [EmployeeController::class, 'update'])->name('update-employee')->middleware('is_admin');
    Route::get('admin/home/employee/delete/{id}', [EmployeeController::class, 'delete'])->middleware('is_admin');
    Route::get('admin/home/filter/employee/compliance', [EmployeeController::class, 'filter_compliance']);

    //admin/company profile routes
    Route::get('/admin/company/profile-settings/{id}', [CompanyController::class, 'AdminProfile']);
    Route::post('/admin/company/profile-settings/update', [CompanyController::class, 'AdminprofileUpdate'])->name('admin-profile-update');
    Route::post('/admin/company/profile-settings/image/update', [CompanyController::class, 'UpdateAdminPhoto'])->name('admin-profile-photo-update');
    Route::post('/admin/company/user-password/change-password-store', [CompanyController::class, 'AdminchangePassStore'])->name('admin-change-password-store');
    //admin add clients
    Route::get('admin/home/client/{id}', [ClientController::class, 'index'])->middleware('is_admin');
    Route::get('admin/home/fetch/client', [ClientController::class, 'fetch'])->middleware('is_admin');
    Route::post('admin/home/client/store', [ClientController::class, 'store'])->name('store-client')->middleware('is_admin');
    Route::post('admin/home/client/update', [ClientController::class, 'update'])->name('update-client')->middleware('is_admin');
    Route::get('admin/home/client/delete/{id}', [ClientController::class, 'delete'])->middleware('is_admin');

    //admin add contractors
    Route::get('admin/home/contractor/{id}', [ContractorController::class, 'index'])->middleware('is_admin');
    Route::get('admin/home/fetch/contractor', [ContractorController::class, 'fetch'])->middleware('is_admin');
    Route::post('admin/home/contractor/store', [ContractorController::class, 'store'])->name('store-contractor')->middleware('is_admin');
    Route::post('admin/home/contractor/update', [ContractorController::class, 'update'])->name('update-contractor')->middleware('is_admin');
    Route::get('admin/home/contractor/delete/{id}', [ContractorController::class, 'delete'])->middleware('is_admin');
    
    //admin add project
    Route::get('admin/home/fetch/project', [ProjectController::class, 'fetch'])->middleware('is_admin');
    Route::get('admin/home/project/{id}', [ProjectController::class, 'index'])->middleware('is_admin');
    Route::post('admin/home/project/store', [ProjectController::class, 'store'])->name('store-project')->middleware('is_admin');
    Route::post('admin/home/project/update', [ProjectController::class, 'update'])->name('update-project')->middleware('is_admin');
    Route::get('admin/home/project/delete/{id}', [ProjectController::class, 'delete'])->middleware('is_admin');

    //admin payment
    Route::get('admin/home/payment/{id}', [PaymentController::class, 'index'])->middleware('is_admin');
    // Route::post('admin/home/timekeeper/updatedate', [PaymentController::class, 'DateUpdate'])->name('DateUpdate')->middleware('is_admin');
    Route::post('admin/home/payment/search', [PaymentController::class, 'search'])->name('searchData')->middleware('is_admin');
    Route::post('admin/home/payment/storepaymentdetails', [PaymentController::class, 'storepaymentdetails'])->name('storepaymentdetails')->middleware('is_admin');

    Route::get('admin/home/calendar/{id}', [CalenderDemoController::class, 'calender_demo'])->middleware('is_admin');
    Route::get('/dataget', [CalenderDemoController::class, 'dataget'])->middleware('is_admin');
    Route::get('/get_project/{admin_id}/{id}', [CalenderDemoController::class, 'get_project'])->middleware('is_admin');

    Route::post('admin/home/calendar/store', [CalenderDemoController::class, 'storeCalenderTimeKeeper'])->middleware('is_admin');
    Route::post('admin/home/calendar/update', [CalenderDemoController::class, 'updateCalenderTimeKeeper'])->middleware('is_admin');
    Route::delete('admin/home/calendar/delete', [CalenderDemoController::class, 'deleteCalenderTimeKeeper'])->middleware('is_admin');

    #roster status
    Route::get('admin/home/roster/status', [RoasterStatusController::class, 'index'])->middleware('is_admin');
    Route::post('roster/status', [RoasterStatusController::class, 'store'])->name('roasterStatus.store')->middleware('is_admin');
    Route::post('roster/status/edit', [RoasterStatusController::class, 'update'])->name('roasterStatus.update')->middleware('is_admin');
    Route::get('roster/status/delete/{id}', [RoasterStatusController::class, 'destroy'])->middleware('is_admin');

    #payment status
    Route::get('admin/home/payment/status/{company_code}', [PaymentStatusController::class, 'index'])->name('payment.status');
    Route::post('payment/status', [PaymentStatusController::class, 'store'])->name('paymentStatus.store');
    Route::post('payment/status/edit', [PaymentStatusController::class, 'update'])->name('paymentStatus.update');
    Route::post('payment/add', [PaymentController::class, 'addpayment'])->name('addpayment');
    Route::get('payment/status/delete/{id}', [PaymentStatusController::class, 'destroy']);

    #job type
    Route::get('admin/home/job/type', [JobTypeController::class, 'index'])->name('job.type')->middleware('is_admin');
    Route::post('job/type', [JobTypeController::class, 'store'])->name('jobType.store')->middleware('is_admin');
    Route::post('job/type/edit', [JobTypeController::class, 'update'])->name('jobType.update')->middleware('is_admin');
    Route::get('job/type/delete/{id}', [JobTypeController::class, 'destroy'])->middleware('is_admin');

    #my unavailability
    Route::get('admin/home/myavailability/{company_code}', [MyavailabilityController::class, 'index']);
    Route::post('admin/home/myavailability/search', [MyavailabilityController::class, 'admin_search'])->name('availability.search');
    Route::post('admin/home/myavailability/store', [MyavailabilityController::class, 'admin_store']);
    Route::post('admin/home/myavailability/update', [MyavailabilityController::class, 'admin_update']);
    Route::get('admin/home/myavailability/delete/{id}', [MyavailabilityController::class, 'destroy']);
    Route::get('admin/home/myavailability/approve/{id}', [MyavailabilityController::class, 'approve']);

    #add leave
    Route::get('admin/home/leave/{company_code}', [LeaveController::class, 'index']);
    Route::post('admin/home/leave/search', [LeaveController::class, 'admin_search'])->name('leave.search');
    Route::post('admin/home/leave/store', [LeaveController::class, 'admin_store']);
    Route::post('admin/home/leave/update', [LeaveController::class, 'admin_update']);
    Route::get('admin/home/leave/delete/{id}', [LeaveController::class, 'destroy']);
    Route::get('admin/home/leave/approve/{id}', [LeaveController::class, 'approve']);

    // Upcoming event routes user
    Route::get('user/home/upcomingevent/{id}', [UpcomingeventController::class, 'userIndex']);
    Route::post('user/home/event/store', [UpcomingeventController::class, 'eventStore'])->name('store-event');


    // Induction routes
    Route::get('admin/home/inducted/site/{id}', [InductedsiteController::class, 'index'])->middleware('is_admin');
    Route::post('admin/home/inducted/site/store', [InductedsiteController::class, 'store'])->name('store-induction')->middleware('is_admin');
    Route::post('admin/home/inducted/site/update', [InductedsiteController::class, 'update'])->name('update-induction')->middleware('is_admin');
    Route::get('admin/home/inducted/site/delete/{id}', [InductedsiteController::class, 'delete'])->middleware('is_admin');
});
