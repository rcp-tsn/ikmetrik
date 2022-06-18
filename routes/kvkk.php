<?php

Route::resource('documents','Kvkk\DocumentController');
Route::resource('/kvkkEducations','Kvkk\KvkkEducationController');
Route::resource('kvkkReports','Kvkk\KvkkReportController');
Route::resource('/requests','Kvkk\RequestController');
Route::post('/company_return','Kvkk\RequestController@company_return')->name('company_return');
Route::get('employee/kvkk/excel/report/{id}','Kvkk\KvkkReportController@employeeExcel')->name('kvkkReports.excel');
