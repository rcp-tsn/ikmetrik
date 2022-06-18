<?php

namespace App\Http\Controllers;

use App\Models\IncentiveService;
use App\Models\ApprovedIncentive;
use App\Models\MetricReport;
use App\Models\MetricReportGroup;
use App\Models\SgkCompany;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\HashingSlug;

class MetricController extends Controller
{
   public function metricGroups($id)
   {

      $metric_report_group = MetricReportGroup::find(HashingSlug::decodeHash($id));
      $metric_reports = MetricReport::where('metric_report_group_id', $metric_report_group->id)->orderBy('id', 'ASC')->get();
      return view('newmetric.'.$metric_report_group->slug.'.index', compact('metric_reports', 'metric_report_group'));
   }




}
