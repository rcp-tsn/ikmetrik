<?php
Route::get('/get-ajax-metrics/{id}', 'HomeController@getAjaxMetric')->name('get-ajax-metrics');
Route::get('/get-ajax-incentives/{id}', 'HomeController@getAjaxIncentive')->name('get-ajax-incentives');
Route::group(
    [
        'middleware' =>
            [
                'auth', 'hasCompany'
            ]
    ], function () {
    Route::get('/metrics/{id}', 'MetricController@metricGroups')->name('metrics');
    Route::get('/sub-metrics/{slug}', 'HrMetricController@subMetricGroups')->name('sub-metrics');
    Route::get('pdf-exports/hr-detailed/{id}', 'HrMetricController@hrDetailed')->name('pdf.hr-detailed');
    Route::post('save-hr-image', 'HrMetricController@saveHrImage')->name('save-hr-image');

    Route::get('/hrcostkpi', 'HrCostKPIController@index');
    Route::get('/overtimedayrate', 'HrCostKPIController@overtimedayrate');
    Route::get('/staffunitcostrate', 'HrCostKPIController@staffunitcostrate');
    Route::get('/dutywagechart', 'HrCostKPIController@dutywagechart');
    Route::get('/extrapaycostrate', 'HrCostKPIController@extrapaycostrate');
    Route::get('/educationcostperperson', 'HrCostKPIController@educationcostperperson');
    Route::get('/rateofcosttototalincome', 'HrCostKPIController@rateofcosttototalincome');
    Route::get('/costratebydepartment', 'HrCostKPIController@costratebydepartment');
    Route::get('/incentiveutilizationrate', 'HrCostKPIController@incentiveutilizationrate');
    Route::get('/personbasedincentiverate', 'HrCostKPIController@personbasedincentiverate');
    Route::get('/totalincentiveearnings', 'HrCostKPIController@totalincentiveearnings');
    Route::get('/severancepayburden', 'HrCostKPIController@severancepayburden');
    Route::get('/severancepayperperson', 'HrMetricController@severancePayPerPerson');
    Route::post('/severancepayperperson', 'HrMetricController@calculateSeverancePayPerPerson');
    Route::post('/calculatenotice', 'HrMetricController@calculateNoticeCompensationPerPerson');
    Route::get('/noticecompensation', 'HrCostKPIController@noticecompensation');

    Route::get('/hroperationalkpi', 'HrOperationalKPIController@index');
    Route::get('/turnoverrate', 'HrOperationalKPIController@turnoverrate');
    Route::get('/reasonofquitjob', 'HrOperationalKPIController@reasonofquitjob');
    Route::get('/indisciplinerate', 'HrOperationalKPIController@indisciplinerate');
    Route::get('/reportedannulledrate', 'HrOperationalKPIController@reportedannulledrate');
    Route::get('/jobcompliancerate', 'HrOperationalKPIController@jobcompliancerate');
    Route::get('/resignrate', 'HrOperationalKPIController@resignrate');
    Route::get('/missingdaycauses', 'HrOperationalKPIController@missingdaycauses');
    Route::get('/workaccidentrate', 'HrOperationalKPIController@workaccidentrate');
    Route::get('/accidentfrequencyrate', 'HrOperationalKPIController@accidentfrequencyrate');
    Route::get('/timeallocatedtoeducation', 'HrOperationalKPIController@timeallocatedtoeducation');
    Route::get('/terminationchartforspecialreasons', 'HrOperationalKPIController@terminationchartforspecialreasons');
    Route::get('/accidentweightrate', 'HrOperationalKPIController@accidentweightrate');
    Route::get('/reportingrate', 'HrOperationalKPIController@reportingrate');


    Route::get('/hroperatingkpi', 'HrOperatingKPIController@index');
    Route::get('/laborlossrate', 'HrOperatingKPIController@laborlossrate');
    Route::get('/transferrateincompany', 'HrOperatingKPIController@transferrateincompany');
    Route::get('/taskdefinitionchart', 'HrOperatingKPIController@taskdefinitionchart');
    Route::get('/agedistributionchart', 'HrOperatingKPIController@agedistributionchart');
    Route::get('/genderdistributionchart', 'HrOperatingKPIController@genderdistributionchart');
    Route::get('/educationlevelchart', 'HrOperatingKPIController@educationlevelchart');
    Route::get('/disabilityassessment', 'HrOperatingKPIController@disabilityassessment');
});
