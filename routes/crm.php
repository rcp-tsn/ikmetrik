<?php

Route::get('crm_notifications/create','CrmNotificationController@create')->name('crm_notifications.create');
Route::post('crm_notifications/post','CrmNotificationController@store')->name('crm_notifications.store');
Route::get('/crm_notifications/ajax/read','CrmNotificationController@ajax_read')->name('crm_notifications.read');
Route::get('/crm_notifications/{id}','CrmNotificationController@detail')->name('newspaper.detail');

Route::get('/crm_notifications/type/index','CrmNotificationController@index')->name('newspaper.index');
Route::get('/crm_notifications/delete/{id}','CrmNotificationController@delete')->name('newspaper.delete');
Route::get('/crm_notifications/edit/{id}','CrmNotificationController@edit')->name('newspaper.edit');
Route::put('/crm_notifications/update/{id}','CrmNotificationController@update')->name('newspaper.update');
