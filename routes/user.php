<?php


include('metrik.php');

Route::resource('sgk_companies', 'SgkCompanyController');
Route::put('/sgk-companies-metric-data/{id}', 'SgkCompanyController@showPost')->name('sgk_companies.show_post');
Route::resource('company_assignments', 'CompanyAssignmentController');
Route::get('/companies-select-list', 'CompanyController@selectList')->name('companies-list.select.form');
Route::get('excel-files/{file_name}', function ($file_name = null) {
    $result = \App\Models\Report::find($file_name);
    if ($result) {
        $path = storage_path() . '/' . 'app' . '/excel_reports/' . $result->sgk_company_id . '-' . $result->sgk_company->registry_id . '-' . $result->accrual->format('Y-m-d') . '.xlsx';

        $path2 = storage_path() . '/' . 'app' . '/excel_reports/' . $result->sgk_company_id . '--' . $result->accrual->format('Y-m-d') . '.xlsx';
        if (file_exists($path)) {
            return Response::download($path);
        } elseif (file_exists($path2)) {
            return Response::download($path2);
        }
    }

})->name('download-excel-file');


Route::get('sgk_company_select', 'SgkCompanySelectController@select')->name('sgk_company_select');
Route::get('sgk_company_select/select/{id}', 'SgkCompanySelectController@selectListStore')->name('sgk_company_select.store');
Route::get('sgk_company_select/un-select/', 'SgkCompanySelectController@unSelectListStore')->name('sgk_company_un_select.store');
Route::get('pdf-exports/incentives-detailed/{id}', 'ReportController@incentivesDetailed')->name('pdf.incentives-detailed');
Route::get('pdf-exports/all-incentives-detailed/{id}', 'ReportController@allIncentivesDetailed')->name('pdf.all-incentives-detailed');
Route::post('pdf-image-screenshot', 'ReportController@imageScreenshot')->name('pdf.iamges-screenshot');
Route::get('/sgk_company_select/unselect/', 'SgkCompanySelectController@unSelectListStore')->name('sgk_company_select.unselect.store');
Route::get('control-gain-incentives/{accrual}', 'HomeController@controlIndex')->name('control-gain-incentives');
//modals
Route::get('/modals/create-value/{id}/{type}', 'SgkCompanyController@createModal')->name('modal.open-value');
Route::post('/modals/create-value/{id}/{type}', 'SgkCompanyController@storeModal')->name('modal.store-value');
Route::get('/modals/edit-value/{id}', 'SgkCompanyController@editModal')->name('modal.edit-value');
Route::put('/modals/edit-value/{id}', 'SgkCompanyController@updateModal')->name('modal.update-value');
Route::delete('/modals/delete-modal/{id}', 'SgkCompanyController@destroyModal')->name('modal.destroy-value');


Route::get('/captcha/{uuid?}', function ($uuid) {
    return getCaptcha($uuid);
})->name('captcha');


Route::get('/lostLeakExport','CalculationLeakController@lostLeakExcelExport')->name('lostLeakExcelExport');

Route::group(
    [
        'prefix' => 'declarations',
        'as' => 'declarations.',
        'namespace' => 'Declarations',
        'middleware' =>
            [
                'auth', 'hasCompany'
            ]
    ], function () {
    Route::group([
        'prefix' => 'incentives',
        'as' => 'incentives.',
    ], function () {

        Route::get('test', 'WorkController@test');

        Route::get('incentives-options', 'WorkController@optionsForm')->name('options.form');
        Route::post('incentives-options', 'WorkController@optionsStore')->name('options.store');

        Route::get('past-incentives/{law}', 'PastIncentiveController@index')->name('past');
        Route::get('check-incentives/{date}/{law}', 'PastIncentiveController@checkIncentive')->name('check-incentive');
        Route::get('potential-incentives', 'WorkController@potential')->name('potential');
        Route::post('potential-incentives', ['as' => 'potential-store', 'uses' => 'WorkController@potentialStore']);
        Route::get('metrik-start', 'WorkController@metrik')->name('metrik');
        Route::get('incentives-start', 'WorkController@main')->name('main');
        Route::get('incentives-start7252', 'WorkController@main7252')->name('main7252');
        Route::get('incentives-start-v2', 'WorkController@mainV2')->name('main-v2');
        Route::get('gain-start', 'WorkController@gainStart')->name('gain-start');
        Route::get('gain-start7252', 'WorkController@gainStart7252')->name('gain-start7252');
        Route::post('excel-upload', 'WorkController@excelUpload')->name('excel_upload');
        Route::get('excel-set', 'WorkController@excelSet')->name('excel_set');
        Route::get('hadedis/icentive/document', 'WorkController@incentive_document')->name('incentive_document'); // V2 Çalışan Listesini Onaylanmış Belgelerden Çeksin Diye Koydum

        Route::get('excel-export', ['as' => 'excel-export', 'uses' => 'IncentiveController@excelExport']);

        Route::get('current_incentives', ['as' => 'current_incentives', 'uses' => 'IncentiveController@currentIncentives']);
        Route::get('gain_incentives', ['as' => 'gain_incentives', 'uses' => 'GainIncentiveController@index']);
        Route::get('all_gain_incentives', ['as' => 'all_gain_incentives', 'uses' => 'GainIncentiveController@allIndex']);
    });
    Route::get('/v3captchaPotential', ['as' => 'v3captchaPotential', 'uses' => 'IncentiveController@captchaPotentialGet']);
    Route::post('/v3captchaPotential', ['as' => 'v3loginPotential', 'uses' => 'IncentiveController@captchaPotentialPost']);

    Route::get('/v3captchaCalculate', ['as' => 'v3captchaCalculate', 'uses' => 'IncentiveController@captcha']);
    Route::post('/v3loginCalculate', ['as' => 'v3loginCalculate', 'uses' => 'IncentiveController@loginV3']);


    Route::post('/loginv2', ['as' => 'loginv2.post', 'uses' => 'WorkController@loginv2Post']);

    Route::group(['middleware' => ['v2']], function () {
        Route::get('/v2TahakkukList', ['as' => 'v2.TahakkukList', 'uses' => 'WorkController@v2TahakkukList']);
        //Route::get('/Tahakkuk_Lost_Date', ['as' => 'v2.TahakkukDate' , 'uses'=> 'LostLeakController@v2TahakkukDate']);


        Route::get('/v2TahakkukListMetrik', ['as' => 'v2.TahakkukListMetrik', 'uses' => 'WorkController@v2TahakkukListMetrik']);
        Route::get('/v2PdfDownloadMetrik', ['as' => 'v2.PdfDownloadMetrik', 'uses' => 'WorkController@v2PdfDownloadMetrik']);

        Route::get('/v2PdfParseMetrik', ['as' => 'v2.PdfParseMetrik', 'uses' => 'WorkController@v2PdfParseMetrik']);


        Route::get('/v2PdfDownload', ['as' => 'v2.PdfDownload', 'uses' => 'WorkController@v2PdfDownload']);
        Route::get('/v2PdfParse', ['as' => 'v2.PdfParse', 'uses' => 'WorkController@v2PdfParse']);

        Route::get('/v2BildirgeCek', ['as' => 'v2.BildirgeCek', 'uses' => 'WorkController@v2BildirgeCek']);
        Route::get('/v2BildirgeParse', ['as' => 'v2.BildirgeParse', 'uses' => 'WorkController@v2BildirgeParse']);
    });
    Route::post('/loginv3', ['as' => 'loginv3.post', 'uses' => 'WorkController@loginv3Post']);
    Route::post('/loginv4', ['as' => 'loginv4.post', 'uses' => 'WorkController@loginv4Post']);
    Route::group(['middleware' => ['v3']], function () {
        Route::get('/v3OldEncouragementSave_7103', ['as' => 'v3.OldEncouragementSave_7103', 'uses' => 'WorkController@v3OldEncouragementSave_7103']);
        Route::get('/v3OldEncouragementSave_6111', ['as' => 'v3.OldEncouragementSave_6111', 'uses' => 'WorkController@v3OldEncouragementSave_6111']);
        Route::get('/v3OldEncouragementSave_7316', ['as' => 'v3.OldEncouragementSave_7316', 'uses' => 'WorkController@v3OldEncouragementSave_7316']);
        Route::get('/v3OldEncouragementSave_3294', ['as' => 'v3.OldEncouragementSave_3294', 'uses' => 'WorkController@v3OldEncouragementSave_3294']);
        Route::get('/v3OldEncouragementSave_26', ['as' => 'v3.OldEncouragementSave_26', 'uses' => 'WorkController@v3OldEncouragementSave_26']);
        Route::get('/v3OldEncouragementSave_31', ['as' => 'v3.OldEncouragementSave_31', 'uses' => 'WorkController@v3OldEncouragementSave_31']);

        Route::get('/v3NewRequest', ['as' => 'v3.NewRequest', 'uses' => 'WorkController@v3NewRequest']);
        Route::get('/v3NewRequest2', ['as' => 'v3.NewRequest2', 'uses' => 'WorkController@v3NewRequest2']);
        Route::get('/v3Potential', ['as' => 'v3.Potential', 'uses' => 'WorkController@v3Potential']);
    });

    Route::group(['middleware'=>['v4']],function ()
    {
        Route::get('/v4OldEncouragementSave_14857', ['as' => 'v4.OldEncouragementSave_14857', 'uses' => 'WorkController@v4OldEncouragementSave_14857']);
    });
}
);


//test
Route::group(
    [
        'prefix' => 'declarations',
        'as' => 'declarations.',
        'namespace' => 'Declarations',
        'middleware' =>
            [
                'auth', 'hasCompany'
            ]
    ], function () {
    Route::group([
        'prefix' => 'incentives',
        'as' => 'incentives.',
    ], function () {
        Route::get('pdf-incentive-reports', 'ReportController@pdfIncentiveReport')->name('pdf-incentive-reports');
        Route::get('is-kazasi', 'TestController@isKazasi')->name('is-kazasi');
        Route::get('calisan-viziteleri', 'TestController@calisanViziteleri')->name('calisan-viziteleri');
        Route::get('kimlik-bildirim-sistemi', 'TestController@kimlikBildirimSistemi')->name('kimlik-bildirim-sistemi');
        Route::get('giris-cikis-bildirgesi-start', 'TestController@giriscikisBildirgesiStart')->name('giris-cikis-bildirgesi-start');

    });
    Route::post('/loginGirisCikisBildirgesi', ['as' => 'giris-cikis-bildirgesi-post', 'uses' => 'TestController@GirisCikisBildirgesiPost']);

    Route::group(['middleware' => ['GirisCikisBildirgesi']], function () {
        Route::get('/giris-cikis-bildirgesi-cek', ['as' => 'giris-cikis-bildirgesi-cek', 'uses' => 'TestController@GirisCikisBildirgesiCek']);

    });

    Route::post('/loginIsKazasi', ['as' => 'IsKazasi-post', 'uses' => 'TestController@IsKazasiPost']);

    Route::group(['middleware' => ['IsKazasi']], function () {
        Route::get('/IsKazasiCek', ['as' => 'IsKazasi.IsKazasiCek', 'uses' => 'TestController@IsKazasiCek']);

    });

    //iş viziteesi

    Route::post('/loginIsVizitesi', ['as' => 'IsVizitesi-post', 'uses' => 'TestController@IsVizitesiPost']);

    Route::group(['middleware' => ['IsVizitesi']], function () {
        Route::get('/IsVizitesiCek', ['as' => 'IsVizitesi.IsVizitesiCek', 'uses' => 'TestController@IsVizitesiCek']);

    });


    //kbscalisan

    Route::post('/loginKbsCalisan', ['as' => 'KbsCalisan-post', 'uses' => 'TestController@KbsCalisanPost']);

    Route::group(['middleware' => ['KbsCalisan']], function () {
        Route::get('/KbsCalisaniCek', ['as' => 'KbsCalisan.KbsCalisanCek', 'uses' => 'TestController@KbsCalisanCek']);

    });

});

Route::middleware(['auth','hasCompany'])->group(function ()
{
    Route::get('/lost_leak', 'LostLeakController@index')->name('lost.index');
    Route::get('/colculation_leak', 'CalculationLeakController@calculation')->name('leak.calculation');
    Route::get('/all/colculation_leak', 'CalculationLeakController@all_calculation')->name('all_callaction');
});
Route::group(
    [
        'prefix' => 'declaration',
        'as' => 'declaration.',
        'middleware' =>
            [
                'auth', 'hasCompany'
            ]
    ], function () {

    Route::group(['middleware' => ['v2']], function () {
        Route::get('/Tahakkuk_Lost_Date', ['as' => 'v2.TahakkukDate', 'uses' => 'LostLeakController@v2TahakkukDate']);
        Route::post('/Tahakkuk_Lost', ['as' => 'v2.LostTahakkuk', 'uses' => 'LostLeakController@v2tahakkukList']);
        Route::get('/v2Pdf-Download', ['as' => 'v2.LostPdfDownload', 'uses' => 'LostLeakController@v2pdfdownload']);
        Route::get('/v2Pdf-Parse', ['as' => 'v2.LostPdfParse', 'uses' => 'LostLeakController@v2PdfParse']);
        // Route::get('/v3-login', 'LostLeakController@v3_login')->name('lost.v3_login');
        //  Route::post('/v3-login-post', 'LostLeakController@v3_post')->name('lost.v3_post');
    });
    Route::group(['middleware' => ['v3']], function () {
        Route::get('/v3LeakNewRequest', ['as' => 'v3.LeakNewRequest', 'uses' => 'LostLeakController@v3NewRequest']);
        Route::get('/v3-6111', ['as' => 'lost.6111' , 'uses' => 'LostLeakController@v3OldEncouragementSave_6111']);
        Route::get('/v3-26', ['as' => 'lost.26' , 'uses' => 'LostLeakController@v3OldEncouragementSave_26']);
        Route::get('/v3-7103', ['as' => 'lost.7103' , 'uses' => 'LostLeakController@v3OldEncouragementSave_7103']);
    });
    Route::group(['middleware'=>['v4']],function ()
    {
        Route::get('/v4-14857', ['as' => 'lost.14857', 'uses' => 'LostLeakController@v4OldEncouragementSave_14857']);
    });
});



