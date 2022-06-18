<?php
Route::group(['middleware'=>'auth'],function () {
    Route::resource('/leaves', 'LeaveController');
    Route::get('/leave/statu', 'LeaveController@leaves_status')->name('leaves_status');
    Route::get('/leaves/ajax/accept/{id}/{employeeLeave}/{type}/{status}/{notification}', 'AjaxLeaveController@leave_status');
    Route::get('/leaves/ajax/decline/{id}/{employeeLeave}/{type}/{status}', 'AjaxLeaveController@leave_status_decline');

    Route::get('leave/login/{token}/{id}', 'LeaveController@LeaveLogin');
    Route::get('leave/Form/To/Pdf/{id}', 'LeaveController@LeaveFormPdf')->name('LeaveFormToPdf');
    Route::get('leave/delete/{id}', 'LeaveController@delete')->name('leave_delete');
    Route::post('leave/delete/{id}', 'LeaveController@acceptForm')->name('acceptForm');
});
