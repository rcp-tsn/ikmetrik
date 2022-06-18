<?php

Route::group(['middleware'=>'auth'],function () {
    Route::resource('/disciplines', 'DisciplineController');
    Route::resource('/disciplineQuestions', 'DisciplineQuestionController');

    Route::post('/company_discipline_question_store', 'DisciplineQuestionController@company_question_store');
    Route::post('/company_discipline_upload', 'DisciplineController@fileUpload')->name('fileUpload');
    Route::post('/company_discipline_excel_upload', 'DisciplineController@excelUpload')->name('excelUpload');
    Route::get('/company_discipline_report/{id}', 'DisciplineController@reportIndex')->name('reportIndex');
    Route::get('/company_discipline_report/pdf/{id}', 'DisciplineController@reportIndexPdf')->name('reportIndexPdf');
    Route::get('/company_discipline_delete/{id}', 'DisciplineQuestionController@questionDelete')->name('disciplineDelete');

});
