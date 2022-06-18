<?php

namespace App\Http\Controllers;

use App\Models\IncentiveService;
use App\Models\ApprovedIncentive;
use App\Models\SgkCompany;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HrOperationalKPIController extends Controller
{
   public function index()
   {
      return view('newmetric.hroperationalkpi.index');
   }

   public function turnoverrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }

      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      $ay_sayisi = (int)(Carbon::parse($mindate)->diffInDays(Carbon::parse($maxdate))/30);

      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      $firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/$ay_sayisi);
      
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_finish'); 
      $firma_isten_ayrilan_sayisi=$firmadata->whereNotNull('job_finish')->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=number_format($firma_isten_ayrilan_sayisi*100/$firma_calisan_sayisi,1);
      }     

      $son_iki__ay_sektor=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('approved_incentives.total_staff');  
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_finish');
      $sektor_calisan_sayisi=round($son_iki__ay_sektor->sum('total_staff')/$ay_sayisi);
      $sektor_isten_ayrilan_sayisi=$sectordata->whereNotNull('job_finish')->count()/12;
      if ($sektor_calisan_sayisi==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=number_format($sektor_isten_ayrilan_sayisi*100/$sektor_calisan_sayisi,1);
      }
      
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_isten_ayrilan_sayisi',$firma_isten_ayrilan_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_isten_ayrilan_sayisi',$sektor_isten_ayrilan_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hroperationalkpi.turnoverrate');
   }
   public function reasonofquitjob()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('icns',function($join){$join->on('incentive_services.icn','=','icns.code');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('icns.name as istencikmanedeni',DB::raw('COUNT(incentive_services.icn) as toplamneden'))->groupBy('incentive_services.icn')->orderBy('toplamneden','desc')->take(10)->get();
 
      
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('icns',function($join){$join->on('incentive_services.icn','=','icns.code');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('icns.name as istencikmanedeni',DB::raw('COUNT(incentive_services.icn) as toplamneden'))->groupBy('incentive_services.icn')->orderBy('toplamneden','desc')->take(10)->get();
   
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      return view('newmetric.hroperationalkpi.reasonofquitjob');
   }
   public function indisciplinerate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->where("approved_incentives.service",">", Carbon::now()->subMonths(3))->select('total_staff');
      $firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/2);
      
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where(function($q) {
            $q->where('incentive_services.icn', 29)
            ->orWhere('incentive_services.icn', 26);
      	  })->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id');
      $firma_ahlaksiz_sayisi=$firmadata->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_ahlaksiz_sayisi*100/$firma_calisan_sayisi;
      }
           
      $son_iki__ay_sektor=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where("approved_incentives.service",">", Carbon::now()->subMonths(3))->select('approved_incentives.total_staff');

      $sektor_calisan_sayisi=round($son_iki__ay_sektor->sum('total_staff')/2);
      
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where(function($q) {
            $q->where('incentive_services.icn', 29)
            ->orWhere('incentive_services.icn', 26);
      	  })->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id');
    
      $sektor_ahlaksiz_sayisi=$sectordata->count();
      if ($sektor_calisan_sayisi==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=$sektor_ahlaksiz_sayisi*100/$sektor_calisan_sayisi;
      }
      
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_ahlaksiz_sayisi',$firma_ahlaksiz_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_ahlaksiz_sayisi',$sektor_ahlaksiz_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hroperationalkpi.indisciplinerate');
   }
   public function reportedannulledrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');

      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->where("approved_incentives.service",">", Carbon::now()->subMonths(3))->select('total_staff');
      $firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/2);
      
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 4)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id');
      $firma_bildirimli_fesih_sayisi=$firmadata->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_bildirimli_fesih_sayisi*100/$firma_calisan_sayisi;
      }
          
      $son_iki__ay_sektor=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where("approved_incentives.service",">", Carbon::now()->subMonths(3))->select('approved_incentives.total_staff');

      $sektor_calisan_sayisi=round($son_iki__ay_sektor->sum('total_staff')/2);
      
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 4)->where("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id');
    
      $sektor_bildirimli_fesih_sayisi=$sectordata->count();
      if ($sektor_calisan_sayisi==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=$sektor_bildirimli_fesih_sayisi*100/$sektor_calisan_sayisi;
      }
   
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_bildirimli_fesih_sayisi',$firma_bildirimli_fesih_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_bildirimli_fesih_sayisi',$sektor_bildirimli_fesih_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hroperationalkpi.reportedannulledrate');
   }
   public function jobcompliancerate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_finish','incentive_services.job_start');

      $firma_calisan_sayisi=$firmadata->whereNotNull('job_start')->count();    
      $firma_isten_ayrilan_sayisi=$firmadata->whereNotNull('job_finish')->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=($firma_calisan_sayisi-$firma_isten_ayrilan_sayisi)*100/$firma_calisan_sayisi;
      }
           
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_start','incentive_services.job_finish');
    
      $sektor_calisan_sayisi=$sectordata->whereNotNull('job_start')->count();
      $sektor_isten_ayrilan_sayisi=$sectordata->whereNotNull('job_finish')->count();
      if ($sektor_calisan_sayisi==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=($sektor_calisan_sayisi-$sektor_isten_ayrilan_sayisi)*100/$sektor_calisan_sayisi;
      }
   
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_isten_ayrilan_sayisi',$firma_isten_ayrilan_sayisi);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_isten_ayrilan_sayisi',$sektor_isten_ayrilan_sayisi);
      return view('newmetric.hroperationalkpi.jobcompliancerate');
   }
   public function resignrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(13)->format('m');
      $minyear=Carbon::now()->subMonths(13)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_start');
      // die(var_dump($data->sum('eksik_gun')));
      $firma_calisan_sayisi=$firmadata->whereNotNull('job_start')->count();
      $firma_isten_ayrilan_sayisi=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id')->where(function($q) {
            $q->where('incentive_services.icn', 2)
            ->orWhere('incentive_services.icn', 3)
            ->orWhere('incentive_services.icn', 23)
            ->orWhere('incentive_services.icn', 24)
            ->orWhere('incentive_services.icn', 25);
      	  })->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_isten_ayrilan_sayisi*100/$firma_calisan_sayisi;
      } 
      
      //Veri gelsin diye where'i orWhere yaptım
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->orWhere('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_start');
    
      $sektor_calisan_sayisi=$sectordata->whereNotNull('job_start')->count();
   	$sektor_isten_ayrilan_sayisi=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id')->where(function($q) {
            $q->where('incentive_services.icn', 2)
            ->orWhere('incentive_services.icn', 3)
            ->orWhere('incentive_services.icn', 23)
            ->orWhere('incentive_services.icn', 24)
            ->orWhere('incentive_services.icn', 25);
      	  })->count();
      if ($sektor_calisan_sayisi==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=$sektor_isten_ayrilan_sayisi*100/$sektor_calisan_sayisi; 
      }
      
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_isten_ayrilan_sayisi',$firma_isten_ayrilan_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_isten_ayrilan_sayisi',$sektor_isten_ayrilan_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hroperationalkpi.resignrate');
   }
   public function missingdaycauses()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(13)->format('m');
      $minyear=Carbon::now()->subMonths(13)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('egns',function($join){$join->on('incentive_services.egn','=','egns.id');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('egns.name as eksikgunnedeni',DB::raw('COUNT(incentive_services.egn) as toplamneden'))->groupBy('incentive_services.egn')->get();
 
      
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('egns',function($join){$join->on('incentive_services.egn','=','egns.id');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('egns.name as eksikgunnedeni',DB::raw('COUNT(incentive_services.egn) as toplamneden'))->groupBy('incentive_services.egn')->get();
      // die(var_dump($sectordata));
   
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      return view('newmetric.hroperationalkpi.missingdaycauses');
   }
   public function workaccidentrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }

      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      $ay_sayisi = (int)(Carbon::parse($mindate)->diffInDays(Carbon::parse($maxdate))/30);

      $son_iki_ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff')->get();
      $son_iki_ay_personel_sayisi=$son_iki_ay->sum('total_staff');
      
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=DB::table('work_accidents')->where('sgk_company_id','=',$sgk_company_id)->whereBetween("work_accidents.kaza_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('work_accidents.kisi_sayisi')->get();
      // die(var_dump($data->sum('eksik_gun')));
      $firma_calisan_sayisi=round(($son_iki_ay_personel_sayisi)/$ay_sayisi);
      $firma_kaza_sayisi=$firmadata->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=number_format($firma_kaza_sayisi*100/$firma_calisan_sayisi,1);
      }
      
      $son_iki_ay_sektor=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('approved_incentives.total_staff');
      $son_iki_ay_sektor_personel_sayisi=$son_iki_ay_sektor->sum('total_staff');
      
      $sectordata=SgkCompany::join('work_accidents','sgk_companies.id','=','work_accidents.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('work_accidents.sgk_company_id','!=',$sgk_company_id)->whereBetween("work_accidents.kaza_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('work_accidents.kisi_sayisi')->get();
    
      $sektor_calisan_sayisi=round($son_iki_ay_sektor_personel_sayisi/$ay_sayisi);
      $sektor_kaza_sayisi=$sectordata->sum('kisi_sayisi');
      if ($sektor_calisan_sayisi==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=number_format($sektor_kaza_sayisi*100/$sektor_calisan_sayisi,1);
      }
      
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_kaza_sayisi',$firma_kaza_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_kaza_sayisi',$sektor_kaza_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);

      return view('newmetric.hroperationalkpi.workaccidentrate');
   }
   public function accidentfrequencyrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(13)->format('m');
      $minyear=Carbon::now()->subMonths(13)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      $firma_kaza_sayisi=DB::table('work_accidents')->where('sgk_company_id','=',$sgk_company_id)->whereBetween("kaza_tarihi", [Carbon::createFromFormat('Y-m', $minyear.'-'.$minmonth),Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)])->count();
    
      $firmadata=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_day'); 

      $firma_calisilan_gun_toplami=$firmadata->sum('total_day');
      
      $sektor_kaza_sayisi=SgkCompany::join('work_accidents','sgk_companies.id','=','work_accidents.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('work_accidents.sgk_company_id','!=',$sgk_company_id)->where("kaza_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->count();

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('approved_incentives.total_day');
      $sektor_calisilan_gun_toplami=$sectordata->sum('total_day');
    
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_kaza_sayisi',$firma_kaza_sayisi);
      View::share('firma_calisilan_gun_toplami',$firma_calisilan_gun_toplami);
      View::share('sektor_kaza_sayisi',$sektor_kaza_sayisi);
      View::share('sektor_calisilan_gun_toplami',$sektor_calisilan_gun_toplami);
      return view('newmetric.hroperationalkpi.accidentfrequencyrate');
   }
   public function timeallocatedtoeducation()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $egitim_suresi_1=mt_rand(30000,99999);
      $egitim_suresi_2=mt_rand(90000,99999);
      $egitim_suresi_3=mt_rand(10000,99999);
      $egitim_suresi_4=mt_rand(80000,99999);
      $egitim_suresi_5=mt_rand(10000,99999);
      
      $date_1=Carbon::now()->subMonths(48)->format('Y');
      $date_2=Carbon::now()->subMonths(36)->format('Y');
      $date_3=Carbon::now()->subMonths(24)->format('Y');
      $date_4=Carbon::now()->subMonths(12)->format('Y');
      $date_5=Carbon::now()->format('Y');

      $firma_data=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->where('service','>=',Carbon::createFromFormat('Y-m-d',$date_1.'-'.$month.'-15'))->select('total_staff');
      $firma_calisan_sayisi_1=$firma_data->sum('total_staff')/2;
      $firma_data=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->where('service','>=',Carbon::createFromFormat('Y-m-d',$date_2.'-'.$month.'-15'))->select('total_staff');
      $firma_calisan_sayisi_2=$firma_data->sum('total_staff')/2;

      $firma_data=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->where('service','>=',Carbon::createFromFormat('Y-m-d',$date_3.'-'.$month.'-15'))->select('total_staff');
      $firma_calisan_sayisi_3=$firma_data->sum('total_staff')/2;

      $firma_data=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->where('service','>=',Carbon::createFromFormat('Y-m-d',$date_4.'-'.$month.'-15'))->select('total_staff');
      $firma_calisan_sayisi_4=$firma_data->sum('total_staff')/2;

      $firma_data=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->where('service','>=',Carbon::createFromFormat('Y-m-d',$date_5.'-'.$month.'-15'))->select('total_staff');
      $firma_calisan_sayisi_5=$firma_data->sum('total_staff')/2;

      $egitime_ayrilan_sure_orani_1=$egitim_suresi_1/$firma_calisan_sayisi_1;
      $egitime_ayrilan_sure_orani_2=$egitim_suresi_2/$firma_calisan_sayisi_2;
      $egitime_ayrilan_sure_orani_3=$egitim_suresi_3/$firma_calisan_sayisi_3;
      $egitime_ayrilan_sure_orani_4=$egitim_suresi_4/$firma_calisan_sayisi_4;
      $egitime_ayrilan_sure_orani_5=$egitim_suresi_5/$firma_calisan_sayisi_5;
      if ($firma_calisan_sayisi_5==0) {
         $egitime_ayrilan_sure_orani_5=0;
      }else{
         $egitime_ayrilan_sure_orani_5=$egitim_suresi_5/$firma_calisan_sayisi_5;
      }
      if ($firma_calisan_sayisi_4==0) {
         $egitime_ayrilan_sure_orani_4=0;
      }else{
         $egitime_ayrilan_sure_orani_4=$egitim_suresi_4/$firma_calisan_sayisi_4;
      }
      if ($firma_calisan_sayisi_3==0) {
         $egitime_ayrilan_sure_orani_3=0;
      }else{
         $egitime_ayrilan_sure_orani_3=$egitim_suresi_3/$firma_calisan_sayisi_3;
      }
      if ($firma_calisan_sayisi_2==0) {
         $egitime_ayrilan_sure_orani_2=0;
      }else{
         $egitime_ayrilan_sure_orani_2=$egitim_suresi_2/$firma_calisan_sayisi_2;
      }
      if ($firma_calisan_sayisi_1==0) {
         $egitime_ayrilan_sure_orani_1=0;
      }else{
         $egitime_ayrilan_sure_orani_1=$egitim_suresi_1/$firma_calisan_sayisi_1;
      }
      View::share('date_1',$date_1);
      View::share('date_5',$date_5);
      View::share('date_2',$date_2);
      View::share('date_3',$date_3);
      View::share('date_4',$date_4);
      View::share('egitime_ayrilan_sure_orani_1',$egitime_ayrilan_sure_orani_1);
      View::share('egitime_ayrilan_sure_orani_2',$egitime_ayrilan_sure_orani_2);
      View::share('egitime_ayrilan_sure_orani_3',$egitime_ayrilan_sure_orani_3);
      View::share('egitime_ayrilan_sure_orani_4',$egitime_ayrilan_sure_orani_4);
      View::share('egitime_ayrilan_sure_orani_5',$egitime_ayrilan_sure_orani_5);
      return view('newmetric.hroperationalkpi.timeallocatedtoeducation');
   }
   public function terminationchartforspecialreasons()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->where("approved_incentives.service",">", Carbon::now()->subMonths(3))->select('total_staff');
      $firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/2);
      
      $firma_evlilikten_ayrilanlar=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn', 13)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $firma_emeklilikten_ayrilanlar=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn', 8)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $firma_askerlikten_ayrilanlar=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn', 12)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();

      $son_iki__ay_sektor=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where("approved_incentives.service",">", Carbon::now()->subMonths(3))->select('approved_incentives.total_staff');

      $sektor_calisan_sayisi=round($son_iki__ay_sektor->sum('total_staff')/2);
      
      $sektor_emeklilikten_ayrilanlar=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 8)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $sektor_askerlikten_ayrilanlar=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 12)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $sektor_evlilikten_ayrilanlar=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 13)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
   
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_evlilikten_ayrilanlar',$firma_evlilikten_ayrilanlar);
      View::share('firma_emeklilikten_ayrilanlar',$firma_emeklilikten_ayrilanlar);
      View::share('firma_askerlikten_ayrilanlar',$firma_askerlikten_ayrilanlar);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_evlilikten_ayrilanlar',$sektor_evlilikten_ayrilanlar);
      View::share('sektor_emeklilikten_ayrilanlar',$sektor_emeklilikten_ayrilanlar);
      View::share('sektor_askerlikten_ayrilanlar',$sektor_askerlikten_ayrilanlar);
      return view('newmetric.hroperationalkpi.terminationchartforspecialreasons');
   }
   public function accidentweightrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $firma_kayip_gun_sayisi=0;
      $sektor_kayip_gun_sayisi=0;
      $minmonth=Carbon::now()->subMonths(13)->format('m');
      $minyear=Carbon::now()->subMonths(13)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      
      $raporlar=DB::table('work_vizites')->where('sgk_company_id','=',$sgk_company_id)->where('vaka','=','IS KAZASI')->whereBetween("poliklinik_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('poliklinik_tarihi','isbasi_tarihi')->get();
      foreach ($raporlar as $rapor) {
         $to = \Carbon\Carbon::createFromFormat('Y-m-d', $rapor->poliklinik_tarihi);
         $from = \Carbon\Carbon::createFromFormat('Y-m-d', $rapor->isbasi_tarihi);
         $firma_kayip_gun_sayisi += $to->diffInDays($from);
      }
      $firmadata=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_day'); 

      $firma_calisilan_gun_toplami=$firmadata->sum('total_day');
      
      $sektorraporlar=SgkCompany::join('work_vizites','sgk_companies.id','=','work_vizites.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('work_vizites.sgk_company_id','!=',$sgk_company_id)->where('work_vizites.vaka','=','IS KAZASI')->whereBetween("poliklinik_tarihi",[Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('work_vizites.poliklinik_tarihi','work_vizites.isbasi_tarihi')->get();
      foreach ($sektorraporlar as $rapor) {
         $to = \Carbon\Carbon::createFromFormat('Y-m-d', $rapor->poliklinik_tarihi);
         $from = \Carbon\Carbon::createFromFormat('Y-m-d', $rapor->isbasi_tarihi);
         $sektor_kayip_gun_sayisi += $to->diffInDays($from);
      }
     
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service",[Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('approved_incentives.total_day');
      $sektor_calisilan_gun_toplami=$sectordata->sum('total_day');
    
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_kayip_gun_sayisi',$firma_kayip_gun_sayisi);
      View::share('firma_calisilan_gun_toplami',$firma_calisilan_gun_toplami);
      View::share('sektor_kayip_gun_sayisi',$sektor_kayip_gun_sayisi);
      View::share('sektor_calisilan_gun_toplami',$sektor_calisilan_gun_toplami);
      return view('newmetric.hroperationalkpi.accidentweightrate');
   }
   public function reportingrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth='04';
      $maxyear=date('Y');
      if (isset($_GET['minyear']) AND !empty($_GET['minyear'])) {
         $minyear=$_GET['minyear'];
      }
      if (isset($_GET['minmonth']) AND !empty($_GET['minmonth'])) {
         $minmonth=$_GET['minmonth'];
      }
      if (isset($_GET['maxyear']) AND !empty($_GET['maxyear'])) {
         $maxyear=$_GET['maxyear'];
      }
      if (isset($_GET['maxmonth']) AND !empty($_GET['maxmonth'])) {
         $maxmonth=$_GET['maxmonth'];
      }

      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      $ay_sayisi = (int)(Carbon::parse($mindate)->diffInDays(Carbon::parse($maxdate))/30);
      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      $firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/$ay_sayisi);
      
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firma_rapor_sayisi=DB::table('work_vizites')->where('sgk_company_id','=',$sgk_company_id)->whereBetween("poliklinik_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->count()/$ay_sayisi;
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_rapor_sayisi*100/$firma_calisan_sayisi;
      }
      
      $son_iki__ay_sektor=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('approved_incentives.total_staff');

      $sektor_calisan_sayisi=round($son_iki__ay_sektor->sum('total_staff')/$ay_sayisi);
      
      $sektor_rapor_sayisi=SgkCompany::join('work_vizites','sgk_companies.id','=','work_vizites.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('work_vizites.sgk_company_id','!=',$sgk_company_id)->whereBetween("work_vizites.poliklinik_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->count()/$ay_sayisi;
      if ($sektor_calisan_sayisi==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=$sektor_rapor_sayisi*100/$sektor_calisan_sayisi;
      }
      
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_rapor_sayisi',$firma_rapor_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_rapor_sayisi',$sektor_rapor_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hroperationalkpi.reportingrate');
   }
}
