<?php

namespace App\Http\Controllers;

use App\Models\IncentiveService;
use App\Models\ApprovedIncentive;
use App\Models\SgkCompany;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HrOperatingKPIController extends Controller
{
   public function index()
   {
      return view('newmetric.hroperatingkpi.index');
   }
   public function laborlossrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
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
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.gun','incentive_services.eksik_gun')->get();
      // die(var_dump($data->sum('eksik_gun')));
      $eksik_gun=$firmadata->sum('eksik_gun');
      $planlanan_gun=$firmadata->sum('eksik_gun')+$firmadata->sum('gun');
      if ($planlanan_gun==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=number_format($eksik_gun*100/$planlanan_gun,1);
      }
      
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.gun','incentive_services.eksik_gun')->get();
      $sektor_eksik_gun=$sectordata->sum('eksik_gun');
      $sektor_planlanan_gun=$sectordata->sum('eksik_gun')+$sectordata->sum('gun');
      if ($sektor_planlanan_gun==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=number_format($sektor_eksik_gun*100/$sektor_planlanan_gun,1);
      }
      
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('eksik_gun',$eksik_gun);
      View::share('planlanan_gun',$planlanan_gun);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      View::share('sektor_eksik_gun',$sektor_eksik_gun);
      View::share('sektor_planlanan_gun',$sektor_planlanan_gun);
      return view('newmetric.hroperatingkpi.laborlossrate');
   }
   public function transferrateincompany()
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

      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id','incentive_services.icn');
      // die(var_dump($data->sum('eksik_gun')));
      $firma_bu_ayki_personel_sayisi=$firmadata->count();
      $firma_nakil_yapilan_personel_sayisi=$firmadata->where('incentive_services.icn','=','16')->count();
      $firma_ortalama_kisi_sayisi=round($firma_bu_ayki_personel_sayisi/$ay_sayisi);
      if ($firma_ortalama_kisi_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_nakil_yapilan_personel_sayisi*100/$firma_ortalama_kisi_sayisi;
      }

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id','incentive_services.icn');
      
      $sektor_bu_ayki_personel_sayisi=$sectordata->count();
      $sektor_nakil_yapilan_personel_sayisi=$sectordata->where('incentive_services.icn','=','16')->count();
      $sektor_ortalama_kisi_sayisi=round($sektor_bu_ayki_personel_sayisi/$ay_sayisi);
      if ($sektor_ortalama_kisi_sayisi==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=$sektor_nakil_yapilan_personel_sayisi*100/$sektor_ortalama_kisi_sayisi;
      }
      
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_nakil_yapilan_personel_sayisi',$firma_nakil_yapilan_personel_sayisi);
      View::share('firma_ortalama_kisi_sayisi',$firma_ortalama_kisi_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_nakil_yapilan_personel_sayisi',$sektor_nakil_yapilan_personel_sayisi);
      View::share('sektor_ortalama_kisi_sayisi',$sektor_ortalama_kisi_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hroperatingkpi.transferrateincompany');
   }
   public function taskdefinitionchart()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
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
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('profession_codes',function($join){$join->on('incentive_services.meslek_kod','=','profession_codes.isco');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('profession_codes.name as meslekadi',DB::raw('COUNT(incentive_services.meslek_kod) as personelmesleksayisi'))->groupBy('incentive_services.meslek_kod')->orderBy('personelmesleksayisi','desc')->take(10)->get();

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('profession_codes',function($join){$join->on('incentive_services.meslek_kod','=','profession_codes.isco');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('profession_codes.name as meslekadi',DB::raw('COUNT(incentive_services.meslek_kod) as personelmesleksayisi'))->groupBy('incentive_services.meslek_kod')->orderBy('personelmesleksayisi','desc')->take(10)->get();
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      return view('newmetric.hroperatingkpi.taskdefinitionchart');
   }
   public function agedistributionchart()
   {
      $tarih='1992-05-19';
      $yas=Carbon::parse($tarih)->age;
      // die(var_dump($yas));
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
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
      $gencyasfirma=array();
      $ortayasfirma=array();
      $yaslifirma=array();
      $gencyassektor=array();
      $ortayassektor=array();
      $yaslisektor=array();

      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('declaration_infos',function($join){$join->on('incentive_services.tck','=','declaration_infos.tck');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereNotNull('declaration_infos.birth_date')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select(DB::raw('declaration_infos.birth_date as groupyear'))->groupBy('incentive_services.tck')->get();
      // die(var_dump($firmadata->count()));

      foreach ($firmadata as $data) {
         if (Carbon::parse($data['groupyear'])->age<35) {
            array_push($gencyasfirma, Carbon::parse($data['groupyear'])->age);
         }elseif (Carbon::parse($data['groupyear'])->age>=35 AND Carbon::parse($data['groupyear'])->age<50) {
            array_push($ortayasfirma, Carbon::parse($data['groupyear'])->age);
         }elseif (Carbon::parse($data['groupyear'])->age>=50) {
            array_push($yaslifirma, Carbon::parse($data['groupyear'])->age);
         }
         
      }
      //die(var_dump(count($yaslarfirma)));
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('declaration_infos',function($join){$join->on('incentive_services.tck','=','declaration_infos.tck');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereNotNull('declaration_infos.birth_date')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select(DB::raw('declaration_infos.birth_date as groupyear'))->groupBy('incentive_services.tck')->get();

      foreach ($sectordata as $sdata) {
         if (Carbon::parse($sdata['groupyear'])->age<35) {
            array_push($gencyassektor, Carbon::parse($sdata['groupyear'])->age);
         }elseif (Carbon::parse($sdata['groupyear'])->age>=35 AND Carbon::parse($sdata['groupyear'])->age<50) {
            array_push($ortayassektor, Carbon::parse($sdata['groupyear'])->age);
         }elseif (Carbon::parse($sdata['groupyear'])->age>=50) {
            array_push($yaslisektor, Carbon::parse($sdata['groupyear'])->age);
         }
      }
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('gencyassektor',$gencyassektor);
      View::share('ortayassektor',$ortayassektor);
      View::share('yaslisektor',$yaslisektor);
      View::share('ortayasfirma',$ortayasfirma);
      View::share('gencyasfirma',$gencyasfirma);
      View::share('yaslifirma',$yaslifirma);
      return view('newmetric.hroperatingkpi.agedistributionchart');
   }
   public function genderdistributionchart()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $previousmonth='03';
      $year=date('Y');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('approved_incentives.total_staff','incentive_services.*',DB::raw('COUNT(incentive_services.gender) as cinsiyetsayisi'))->groupBy('incentive_services.gender')->get();

      // die(var_dump($data->sum('eksik_gun')));
      /*  $firma_erkek_sayisi=$firmadata[1]['cinsiyetsayisi'];
      $firma_kadin_sayisi=$firmadata[0]['cinsiyetsayisi'];*/
      $firma_erkek_sayisi=20;
      $firma_kadin_sayisi=160;

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('approved_incentives.total_staff','incentive_services.*')->select('approved_incentives.total_staff','incentive_services.*',DB::raw('COUNT(incentive_services.gender) as cinsiyetsayisi'))->groupBy('incentive_services.gender')->get();
      $sektor_kadin_sayisi=120;
      $sektor_erkek_sayisi=240;
      View::share('month',$month);
      View::share('year',$year);
      View::share('firma_erkek_sayisi',$firma_erkek_sayisi);
      View::share('firma_kadin_sayisi',$firma_kadin_sayisi);
      View::share('sektor_erkek_sayisi',$sektor_erkek_sayisi);
      View::share('sektor_kadin_sayisi',$sektor_kadin_sayisi);

      return view('newmetric.hroperatingkpi.genderdistributionchart');
   }
   public function educationlevelchart()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
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
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('declaration_infos',function($join){$join->on('incentive_services.tck','=','declaration_infos.tck');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereNotNull('declaration_infos.education')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('declaration_infos.education as educationname',DB::raw('COUNT(declaration_infos.education) as educationlevelcount'))->groupBy('declaration_infos.education')->get();
      
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('declaration_infos',function($join){$join->on('incentive_services.tck','=','declaration_infos.tck');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereNotNull('declaration_infos.education')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('approved_incentives.service','declaration_infos.education as educationname',DB::raw('COUNT(declaration_infos.education) as educationlevelcount'))->groupBy('declaration_infos.education')->get();

      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      return view('newmetric.hroperatingkpi.educationlevelchart');
   }
   public function disabilityassessment()
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
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6 
      $firmadata=ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('law_no',DB::raw('SUM(total_staff) as personelsayisi'))->get();
      $firma_personel_sayisi=$firmadata[0]['personelsayisi']/$ay_sayisi;
      $firmadata=ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('law_no',14857)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('law_no',DB::raw('SUM(total_staff) as personelsayisi'),'total_staff')->get();
      $firma_engelli_sayisi= $firmadata[0]['personelsayisi']/$firmadata->count();
      if ($firma_engelli_sayisi==NULL) {
         $firma_engelli_sayisi=0;
      }
      if ($firma_personel_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=($firma_engelli_sayisi/$ay_sayisi)*100/$firma_personel_sayisi;
      }
      
      $olmasi_gereken_engelli_sayisi=0;
      if ($firma_personel_sayisi>=50) {
         $olmasi_gereken_engelli_sayisi=round($firma_personel_sayisi*3/100);
      }
      /*
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('law_no',DB::raw('SUM(total_staff) as personelsayisi'))->get();
      $sektor_personel_sayisi=$sectordata[0]['personelsayisi'];
      
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('law_no',14857)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('law_no',DB::raw('SUM(total_staff) as personelsayisi'))->get();
      $sektor_engelli_sayisi= $sectordata[0]['personelsayisi'];
      if ($sektor_engelli_sayisi==NULL) {
         $sektor_engelli_sayisi=0;
      }
      $sektor_yuzdesi=$sektor_engelli_sayisi*100/$sektor_personel_sayisi;*/
      $sektor_yuzdesi=3;
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_personel_sayisi',$firma_personel_sayisi);
      View::share('firma_engelli_sayisi',$firma_engelli_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('olmasi_gereken_engelli_sayisi',$olmasi_gereken_engelli_sayisi);
      /* View::share('sektor_personel_sayisi',$sektor_personel_sayisi);
      View::share('sektor_engelli_sayisi',$sektor_engelli_sayisi);*/
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hroperatingkpi.disabilityassessment');
   }
}
