<?php
Route::get('/kvkk/login/{username}/{password}/{id}','Kvkk\DocumentController@kvkkLogin');
Route::middleware(['auth'])->group(function () {

    Route::resource('/notifications','Payrolls\NotificationController');
    Route::resource('/personelFiles','Payrolls\PersonelFileController');
    Route::resource('/personelReport','Payrolls\PayrollReportController');
    Route::get('payroll/report/{id}','Payrolls\PayrollReportController@PayrollExport')->name('payroll_export');



    Route::post('bordrolama/edit', 'Payrolls\PayrollController@update')->name('bordrolama.edit');
    Route::group(['middleware'=>['hasCompany']],function () {
        Route::get('bordrolama/pdf/upload', 'Payrolls\PayrollController@pdfUpload')->name('payroll_pdf_upload');
        Route::get('bordrolama/index', 'Payrolls\PayrollController@index')->name('bordrolama.index');
    });
    Route::get('bordrolama/employee/not/in/{id}', 'Payrolls\PayrollController@payrollNotIn')->name('payroll_not');

    Route::get('bordrolama/pdf/show/{id}', 'Payrolls\PayrollController@payroll_show')->name('employee_payroll_show');

    Route::get('employee/payrolls/sms/{id}', 'Payrolls\PayrollController@employee_sms')->name('employee_sms');

    Route::get('One/employee/payrolls/sms/{id}/{employee_id}', 'Payrolls\PayrollController@Oneemployee_sms')->name('One_employee_sms');

    Route::get('employee/notification/sms/{id}', 'Payrolls\PersonelFileController@FileEmployeeSms');

    Route::get('employee/payrolls/protest/form/{id}', 'Payrolls\PayrollController@payroll_protest_form')->name('payroll_protest_form');


    Route::post('payrolls/protest', 'Payrolls\PayrollController@employee_payroll_protest')->name('payroll_protest');


    Route::get('/employee/payrolls/protest/accept/{id}', 'Payrolls\PayrollController@payroll_protest_accept');

    Route::post('employee/payrolls/accept', 'ZamaneController@employee_accept')->name('employee_payroll_accept');

    Route::post('employee/evrak/accept', 'Payrolls\PersonelFileController@employee_accept')->name('employee_file_accept');

    Route::post('employee/payrolls/protest', 'Payrolls\PersonelFileController@employee_file_protest')->name('employee_file_protest');

    Route::post('employee/notification/protest', 'Payrolls\NotificationController@employee_notification_protest')->name('employee_notification_protest');

    Route::post('company/payroll/delete/{id}', 'Payrolls\PayrollController@delete')->name('payroll.delete');

    Route::post('/send_sms', 'Payrolls\PayrollController@sendSms');

    Route::get('/employeeSmsId/{id}', 'Payrolls\PayrollController@employeeSmsStart');

    Route::post('/ajax/employee/FileType/create', 'Performances\AjaxController@file_type_create');
    Route::get('/ajax/employee/filter/{id}/{department_id}', 'Performances\AjaxController@employee_filter');

    Route::get('payroll_service/employee/payroll/arshiv/{id}','Payrolls\PayrollController@fullFile')->name('payroll_zip');



    Route::group(
        [
            'prefix' => 'payrolls',
            'as' => 'payrolls.',
            'namespace' => 'Payrolls',
            'middleware' =>
                [
                    'auth'
                ]
        ], function () {
        Route::post('/Payrollsave', ['as'=>'payrollSave','uses'=>'PayrollController@pdfUploadSave']);
        Route::get('/Payrollstore', ['as'=>'payrollStore','uses'=>'PayrollController@pdfUploadStore']);
        Route::get('/Payrollstore2', ['as'=>'payrollStore2','uses'=>'PayrollController@pdfUploadStore2']);
        Route::get('employee/payrolls/sms', ['as'=>'PayrollSms','uses'=>'PayrollController@employee_sms']);
    });


});
Route::get('payroll/login/{username}/{password}/{id}','Payrolls\PayrollController@payrollLogin')->name('payrollLogin');

Route::get('payroll_report','Payrolls\PayrollController@payrollreport')->name('payroll_report.payrollreport');
Route::post('invoice_update','Payrolls\PayrollController@invoiceupdate')->name('invoice_update.invoiceupdate');

