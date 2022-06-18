<?php
Route::middleware(['auth','hasCompany'])->group(function ()
{
    Route::resource('/performance', 'Performances\PerformanceController');
    Route::post('/employee_any_store','Performances\EmployeeController@any_employee_store')->name('employee_any_store');
    Route::get('/employee-calendar','Performances\PerformanceController@interest')->name('interest');
    Route::resource('/department-managers', 'Performances\DepartmentManagerController');
});
Route::group(['middleware'=>'auth'],function ()
{
  Route::get('performance/delete/{id}','Performances\PerformanceController@delete')->name('performance.delete');
    // RESOURCE BURDA ÇALIŞAN EKLEME PERFORMANS EKLE SİL GÜNCELLE VE DEĞERLENDİRMELERİN YAPILDIĞI BÖLÜM
    Route::resource('/employee', 'Performances\EmployeeController');
    Route::resource('/managers', 'Performances\EmployeeManagerController');
    Route::resource('/questions', 'Performances\QuestionController');



    Route::resource('/department-question-managers', 'Performances\DepartmentQuestionController');
    Route::resource('/performance_targets', 'Performances\TargetsController');
    //BİTİŞ

    Route::get('performance_target/{type}','Performances\TargetController@index')->name('target.index');
    Route::get('employee/delete/{id}','Performances\EmployeeController@delete')->name('employee.delete');
    Route::get('performance_target_create/{type}','Performances\TargetController@create')->name('target.create');
    Route::post('performance_target_store/{type}','Performances\TargetController@store')->name('target.store');
    Route::get('performance_target_edit/{id}/{type}','Performances\TargetController@edit')->name('target.edit');
    Route::post('performance_target_update/{id}/{type}','Performances\TargetController@update')->name('target.update');


    //Çalışanlar üst ast eş degerlendirme için koydum buradan yönetici atamalarını yapmak için sayfa

    Route::get('/employeee/manager/settings/{type}/{id}/{page}','Performances\EmployeeManagerController@settings')->name('employee_settings');
    Route::get('/employeee/managers/settings/index/{id}','Performances\EmployeeManagerController@settings_index')->name('employee_settings_index');
    Route::get('/employeee/managers/settings/subordinate/{id}','Performances\EmployeeManagerController@employee_subordinate')->name('employee_subordinate');
    Route::put('/employeee/managers/settings/subordinate/update/{id}','Performances\EmployeeManagerController@employee_supodinate_update')->name('employee_supodinate_update');




    Route::put('/equivalent_edit/{id}', 'Performances\EmployeeManagerController@equivalent_edit')->name('equivalent_edit');
    Route::get('/equivalent_show/{id}', 'Performances\EmployeeManagerController@show')->name('equivalent_show');



    Route::get('/questions/evalation/create/{type}/{id}/{page}','Performances\QuestionController@question_evalation')->name('question_evalation');

    Route::get('/metric_index','Performances\PerformanceController@metric_index')->name('metric_index');

    Route::post('/questions/evalation/store/{id}/{top_manager}/{type}','Performances\QuestionController@question_evalation_store')->name('question_evalation_store');






    // PERFORMANS ZAM TÜRLERİ ROOT
    Route::get('performance-interest','Performances\PerformanceController@interest_performance')->name('interest_performance');
    Route::post('/performance-interest-create','Performances\PerformanceController@interest_performance_store')->name('performance.interest.create');
    Route::post('seyyanen-interest-create','Performances\PerformanceController@interest_seyyanen_store')->name('performance.seyyanen.create');
    Route::get('seyyanen-interest','Performances\PerformanceController@seyyanen_interest')->name('seyyanen_interest');
    Route::get('/employee-salary-interest/{id}','Performances\PerformanceController@employee_salary_interest');

    // ZAM TÜRLERİ BİTİŞ


    // PERFORMANS DEĞERLENDİRME SORULARI
    Route::get('company_questions_index','Performances\QuestionController@company_questions_index')->name('company_questions_index');
    Route::get('company_question_edit/{id}','Performances\QuestionController@company_question_edit')->name('company_question_edit');
    Route::get('company_question_create','Performances\QuestionController@company_question_create')->name('company_question_create');
    Route::post('/company_question_store','Performances\QuestionController@company_question_store');
    Route::post('/company_question_update','Performances\QuestionController@company_question_update');
    Route::get('/company_quesion_delete/{id}','Performances\QuestionController@company_quesion_delete')->name('company_quesion_delete');
    // PERFORMANS DEĞERLENDİRME SORULARI BİTİŞ

    // POLİVALANS SORULARI İÇİN
    Route::get('company_polivalans_question_create','Performances\QuestionController@company_polivalans_create')->name('company_polivalans_create');
    Route::post('/company_polivalans_question_store','Performances\QuestionController@company_polivalans_store');
    Route::get('company_polivalans_question_edit/{id}','Performances\QuestionController@company_polivalans_question_edit')->name('company_polivalans_question_edit');
    Route::get('company_polivalans_question_delete/{id}','Performances\QuestionController@company_polivalans_question_delete')->name('company_polivalans_question_delete');
    Route::post('/company_polivalans_question_update','Performances\QuestionController@company_polivalans_question_update');
    //Polivalans Test İçin
    Route::get('/job_criters_test/{id}/{program_id}','Performances\QuestionController@job_criters_test')->name('job_criters_test');
   //Polivalans Bitiş



        // PERFORMANS DEĞERLENDİRME RAPORLAMA ROOT
    Route::get('performances_user_reports/{id}','Performances\PerformanceReportController@applicantReport')->name('performance_user_report');
     Route::get('performances_evalation_reports/{id}','Performances\PerformanceReportController@evalation_report_index')->name('evalation_report_index');
    Route::get('evalation/delete/{id}','Performances\PerformanceReportController@evalation_delete');
    Route::get('performances/employee/reports','Performances\PerformanceReportController@employee_department_report')->name('employee_department_report');
    Route::get('performances/employee/reports/show/{id}','Performances\PerformanceReportController@department_employee_report_show')->name('department_employee_report_show');

    Route::get('performance/education_report/{id}','Performances\PerformanceReportController@education_report')->name('education_report');
    Route::get('performances_user_reports/document/{employee_id}/{program_id}','Performances\PerformanceReportController@applicantReportDocument')->name('user_performance_rapor_document');
    Route::get('performances_user_reports/document/{program_id}','Performances\PerformanceReportController@performance_program_report')->name('performance_rapor_document');
    Route::get('performances_reports','Performances\PerformanceController@reports_index')->name('performance_reports');
    Route::get('performance/program/polivalans_report/{id}/{department_id}','Performances\PerformanceReportController@polivalans_report')->name('polivalans_report');
    Route::post('performance/program/polivalans_report/excel','Performances\PerformanceReportController@polivalans_report_excel')->name('polivalans_report_excel');
    Route::get('/polivalans/excel/all/{department_id}/{id}','Performances\PerformanceReportController@polivalans_all_report');
        // RAPOR ROOT BİTİŞ



       // Performans değerlendirme türlerinin listelendiği bölüm
      Route::get('/performance_type/{id}', 'Performances\PerformanceController@performance')->name('performance_type');
      //bitiş



    //AJAX İLE KATIT EKLE SİL GÜNCELLE YAPTIĞIM KISIMLAR ÇALIŞAN OKUL BİLGİSİ DEPARTMENT DEVAMSIZLIK DİSİPLİN DİL BİLGİSİ VE SOSYAL YAŞAM BİLGİSİ İŞLEMLERİ BURDA
      Route::get('/employee-sgkCompany-department-filterr/{sgk_company_id}/{department_id}','Performances\AjaxController@sgk_company_department_filter');
      Route::post('/employee-discipline-create/', 'Performances\EmployeeController@employee_disciplines')->name('employee.disciplines');
      Route::get('/employee-discipline-delete/{id}', 'Performances\EmployeeController@discipline_delete')->name('discipline_delete');
      Route::post('/ajax-employee-dicontinuity-create', 'Performances\AjaxController@discontinuity_create');
      Route::post('/ajax-employee-social_life_update', 'Performances\AjaxController@social_life_update');
      Route::post('/ajax-employee-language-create', 'Performances\AjaxController@employee_language');
      Route::post('/ajax-company-working-title-create', 'Performances\AjaxController@working_title')->name('working_title');
      Route::post('/ajax-company-deparment-create', 'Performances\AjaxController@department');
      Route::post('/questions/meslek/company/create', 'Performances\AjaxController@meslek_create');
      Route::post('/ajax-employee-scholl-update','Performances\AjaxController@scholl');
      Route::get('/employee-sgk_company-filter/{id}/{performance_program_id}/{work_type}','Performances\AjaxController@sgk_company_filter')->name('sgk_company_filter');
      Route::get('/performance-interest-save/{id}/{min_zam}/{max_zam}','Performances\AjaxController@zam');
      Route::post('/ajax-discipline-store/{id}','Performances\AjaxController@discipline')->name('ajax.discipline_store');
      Route::post('/ajax-management-employee-puans','Performances\AjaxController@management_puan');
      Route::post('/questions/company/create','Performances\AjaxController@questions_create');
      Route::post('/employee-educations-filter','Performances\AjaxController@educations_filter');
      Route::post('/employee-educations-excel-report','Performances\AjaxController@educations_excel_report')->name('educations_excel_report');
      Route::post('/performance-employee-report','Performances\AjaxController@performance_employee_report');
      Route::get('/employee-university/{type}','Performances\AjaxController@university');
      Route::get('/employee-interest/salary/{id}','Performances\AjaxController@salaryInterest');
      // BİTİŞ
});
Route::group(['middleware'=>['auth','working_title','hasCompany']],function () {

    //Personel Kariyer Yönetimi Başlangıç
    Route::get('/employee/career/management', 'Performances\CareerController@index')->name('carerr.index');
    Route::get('/employee/career/setting/{slug}', 'Performances\CareerController@work_titles_setting')->name('work_titles_setting');
    Route::put('/employee/career/setting/store/{slug}', 'Performances\CareerController@work_titles_setting_store')->name('work_titles_setting_store');



});

Route::group(['middleware'=>['auth','hasCompany']],function () {
    Route::get('/company_status/index','Performances\CompanyStatus@index')->name('status.index');
    Route::get('/company_status/create','Performances\CompanyStatus@create')->name('status.create');
    Route::get('/company_status/edit/{id}','Performances\CompanyStatus@edit')->name('status.edit');
    Route::post('/company_status/store','Performances\CompanyStatus@store')->name('status.store');
    Route::put('/company_status/type/store/update/{id}','Performances\CompanyStatus@type_status_update')->name('type.status.update');
    Route::put('/company_status/update/{id}','Performances\CompanyStatus@update')->name('status.update');
    Route::get('/company_status/type/{id}','Performances\CompanyStatus@typeCreate')->name('type.create');
    Route::get('/company_status/sorgula/{id}','Performances\CompanyStatus@statusSorgula');
    Route::get('/company_status/basamak/sorgula/{id}','Performances\CompanyStatus@statusSorgulaBasamak');
    Route::get('/excel/sablon','Performances\EmployeeControll@sablon')->name('excell-sablon');

});

Route::get('/work/titles/selected', 'Performances\CareerController@selectWorkTitle')->name('carerr_select_worktitle');

Route::get('/work/titles/select/{id}', 'Performances\CareerController@selectWorkTitleSelect')->name('worktitleSelect');

Route::get('work/titles/Unset', 'Performances\CareerController@workTitleUnset')->name('WorkTitleUnset');
