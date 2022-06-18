<?php

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
include('performance.php');
include('payroll.php');
include('kvkk.php');
include('pdk.php');
include('crm.php');
include('discipline.php');
include('Leaves.php');
Route::get('/request-demo', 'DemoController@requestDemo');
Route::post('/request-demo', 'DemoController@requestDemoPost');
Route::post('/send-ikmetrik-form','IkmetrikWebFormController@sendIkMetrikForm');
Route::post('payroll/zamane/service/accept','ZamaneController@serviceAccept');
Route::get('/panel', function () {
    return view('auth.login');
})->name('root');
Route::get('/cron/zamane/accept', 'ZamaneController@zamaneCron');
Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/faq', 'HomeController@faq')->name('faq');
Route::get('/change-password', 'HomeController@changePassword')->name('change-password');
Route::post('/change-password','HomeController@savePassword')->name('save-password');
Route::post('/personelFiles/file/upload','Payrolls\PersonelFileController@fileUpload')->name('personelFiles.fileUpload');


Route::get('/undue_uses', 'UndueUsesController@index')->name('undue.index');
Route::post('/undue_uses', 'UndueUsesController@return')->name('undue.post');



Route::middleware(['auth'])->group(function () {


    Route::get('/surveys-deactive/{survey_id}', 'SurveyController@deactive')->name('surveys.deactive');
    Route::get('/surveys-delete/{survey_id}', 'SurveyController@delete')->name('surveys.delete');
    Route::get('/surveys-active/{survey_id}', 'SurveyController@active')->name('surveys.active');
    Route::get('/surveys-reports/{survey_id}', 'SurveyController@reports')->name('surveys.reports');
    Route::resource('/surveys','SurveyController');

    Route::get('/live-survey/{survey_id}', 'GuestController@live')->name('surveys.live');
    Route::get('/live-survey-completed/{survey_id}/{type}', 'GuestController@surveyCompleted')->name('surveys.completed');
    Route::post('/live-survey/{survey_id}', 'GuestController@livePost')->name('surveys.live.store');

});

         Route::post('/api/mobile/login', 'ApiController@Login');
         Route::get('/api/mobile/payroll/{search}/{page}/{token}', 'ApiController@payrollsIndex');
         Route::get('/api/mobile/sliders/{token}', 'ApiController@crmNotification');
         Route::get('/api/mobile/sgk_company/{token}', 'ApiController@sgkCompany');
         Route::get('/api/mobile/cities/{token}', 'ApiController@cities');
         Route::post('/api/mobile/logout/{token}', 'ApiController@Logout');
         Route::post('/api/mobile/payroll_show/{id}/{token}', 'ApiController@payroll_show');



