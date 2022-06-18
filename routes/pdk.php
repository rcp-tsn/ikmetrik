<?php


Route::get('pdks','Payrolls\PdkController@index')->name('pdks.index');
Route::get('pdks/pdf/show/{id}', 'Payrolls\PdkController@pdk_show')->name('employee_pdk_show');
Route::get('pdks/pdf/create', 'Payrolls\PdkController@create')->name('pdks.create');
Route::get('pdks/pdf/not/in/{id}', 'Payrolls\PdkController@pdk_not_in')->name('pdks_not');
Route::get('employee/pdks/sms/{id}', 'Payrolls\PdkController@employee_sms');
Route::post('employee/pdks/accept', 'ZamaneController@pdkAccept')->name('employee_pdks_accept');
Route::post('pdks/protest', 'Payrolls\PdkController@employee_pdk_protest')->name('pdks_protest');
Route::post('pdk/edit', 'Payrolls\PdkController@update')->name('pdks.edit');
Route::post('company/pdk/delete/{id}', 'Payrolls\PdkController@delete')->name('pdk.delete');


Route::group(
    [
        'prefix' => 'pdks',
        'as' => 'pdks.',
        'namespace' => 'Payrolls',
        'middleware' =>
            [
                'auth'
            ]
    ], function () {
    Route::post('/PdksSave', ['as'=>'pdksSave','uses'=>'PdkController@pdfUploadSave']);
    Route::get('/PdksStore', ['as'=>'pdksStore','uses'=>'PdkController@pdfUploadStore']);
    Route::get('/PdksStore2', ['as'=>'pdksStore2','uses'=>'PdkController@pdfUploadStore2']);
});
