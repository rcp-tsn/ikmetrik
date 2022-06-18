<?php

namespace App\Http\Controllers;

use App\Models\IncentiveService;
use App\Models\ApprovedIncentive;
use App\Models\SgkCompany;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HrCostKPIController extends Controller
{
   public function index()
   {

      return view('newmetric.hrcostkpi.index');
   }
   public function overtimedayrate()
   {
   	$sabitler=config('constants');

      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $year=date('Y');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('incentive_services.eksik_gun','incentive_services.gun')->get();
      // die(var_dump($data->sum('eksik_gun')));
      $firma_fazla_mesai_saati = $sabitler['fazla_mesai_saati_firma'];
      $planlanan_gun=$firmadata->sum('eksik_gun')+$firmadata->sum('gun');
      $firma_planlanan_gun_saati = $planlanan_gun*7.5;
      $firma_mesai_yuzdesi=number_format($firma_fazla_mesai_saati*100/$firma_planlanan_gun_saati,1);

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('incentive_services.eksik_gun','incentive_services.gun')->get();
      $sektor_fazla_mesai_saati = $sabitler['fazla_mesai_saati_sektor'];
      $sektor_planlanan_gun=$sectordata->sum('eksik_gun')+$sectordata->sum('gun');
      $sektor_planlanan_gun_saati = $sektor_planlanan_gun*7.5/12;
      $sektor_mesai_yuzdesi=number_format($sektor_fazla_mesai_saati*100/$sektor_planlanan_gun_saati,1);
      $base_logo=$sabitler['base_logo'];
      $firma_adi=$sabitler['firma_adi'];
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Fazla Mesai Günü Oranı";
      $rapor_metni="Bir işyerinde haftalık çalışma süresini aşan çalışmalar fazla mesai olarak tanımlanmıştır. Normal mesaisi için bir ücret alan işçi, fazla mesaisi için de ayrıca bir ücret alır.İş Kanununda yazılı koşullar çerçevesinde haftalık 45 saati aşan çalışmaları ifade etmektedir. İşletmelerde meydana gelen değişiklikler ve planlı/plansız üretim artışları sonucunda oluşan fazla çalışmayı ifade eder.";
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('month',$month);
      View::share('year',$year);
      View::share('firma_fazla_mesai_saati',$firma_fazla_mesai_saati);
      View::share('firma_planlanan_gun_saati',$firma_planlanan_gun_saati);
      View::share('firma_mesai_yuzdesi',$firma_mesai_yuzdesi);
      View::share('sektor_fazla_mesai_saati',$sektor_fazla_mesai_saati);
      View::share('sektor_planlanan_gun_saati',$sektor_planlanan_gun_saati);
      View::share('sektor_mesai_yuzdesi',$sektor_mesai_yuzdesi);
      return view('newmetric.hrcostkpi.overtimedayrate');
   }
   public function staffunitcostrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $year=date('Y');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      // die(var_dump(Carbon::now()->subMonths(12)->format('d F Y')));
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6
      $son_ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereMonth('service','=',$month)->whereYear('service','=',$year)->select('total_staff');
      $firma_calisan_sayisi=$son_ay->sum('total_staff');
      //  die(var_dump($firma_calisan_sayisi));
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');

      $firma_toplam_maliyet=22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl');
      $firma_yuzdesi=$firma_toplam_maliyet/$firma_calisan_sayisi;

      $son_ay_sektor=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('approved_incentives.total_staff');

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth('approved_incentives.service','=',$month)->whereYear('approved_incentives.service','=',$year)->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');
      $sektor_calisan_sayisi=$son_ay_sektor->sum('total_staff');
      $sektor_toplam_maliyet=22.5*($sectordata->sum('ucret_tl')+$sectordata->sum('ikramiye_tl'))/100+$sectordata->sum('ucret_tl')+$sectordata->sum('ikramiye_tl');
      $sektor_yuzdesi=$sektor_toplam_maliyet/$sektor_calisan_sayisi;

      View::share('month',$month);
      View::share('year',$year);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_calisan_sayisi',$sektor_calisan_sayisi);
      View::share('sektor_toplam_maliyet',$sektor_toplam_maliyet);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hrcostkpi.staffunitcostrate');
   }
   public function dutywagechart()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month = date('m', strtotime('last month'));
      $month = '04';
      $year = date('Y');
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

      //die('girdi');
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('profession_codes',function($join){$join->on('incentive_services.meslek_kod','=','profession_codes.isco');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$minmonth),Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)])->select('profession_codes.name as departmanadi',DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'))->groupBy('incentive_services.meslek_kod')->orderBy('toplamucret','desc')->take(10)->get();
      // die(var_dump($firmadata[0]['toplamucret']));

         foreach ($firmadata as $firm) {
            if (is_null($firm['departmanadi'])) {
               $firm['departmanadi']="DİĞER";
            }
         }
      $firma_toplam_maliyet=22.5*($firmadata->sum('toplamucret')+$firmadata->sum('toplamikramiye'))/100+$firmadata->sum('toplamucret')+$firmadata->sum('toplamikramiye');

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('profession_codes',function($join){$join->on('incentive_services.meslek_kod','=','profession_codes.isco');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$minmonth),Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)])->select('profession_codes.name as departmanadi',DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'))->groupBy('incentive_services.meslek_kod')->orderBy('toplamucret','desc')->take(10)->get();

      $sektor_toplam_maliyet=22.5*($sectordata->sum('toplamucret')+$sectordata->sum('toplamikramiye'))/100+$sectordata->sum('toplamucret')+$sectordata->sum('toplamikramiye');

      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('sektor_toplam_maliyet',$sektor_toplam_maliyet);
      return view('newmetric.hrcostkpi.dutywagechart');
   }
   public function extrapaycostrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
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
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$minmonth),Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)])->select('incentive_services.ikramiye_tl','incentive_services.ucret_tl');

      $firma_ek_odeme_maliyet=$firmadata->sum('ikramiye_tl');
      $firma_toplam_maliyet=22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl');
      $firma_yuzdesi=$firma_ek_odeme_maliyet*100/$firma_toplam_maliyet;

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$minmonth),Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)])->select('incentive_services.ikramiye_tl','incentive_services.ucret_tl');

      $sektor_ek_odeme_maliyet=$sectordata->sum('ikramiye_tl');
      $sektor_toplam_maliyet=22.5*($sectordata->sum('ucret_tl')+$sectordata->sum('ikramiye_tl'))/100+$sectordata->sum('ucret_tl')+$sectordata->sum('ikramiye_tl');
      $sektor_yuzdesi=$sektor_ek_odeme_maliyet*100/$sektor_toplam_maliyet;

      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_ek_odeme_maliyet',$firma_ek_odeme_maliyet);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_ek_odeme_maliyet',$sektor_ek_odeme_maliyet);
      View::share('sektor_toplam_maliyet',$sektor_toplam_maliyet);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hrcostkpi.extrapaycostrate');
   }
   public function educationcostperperson()
   {
      $sector_id=35;
      $sgk_company_id=654;
      //$month=date('m', strtotime('last month'));
      $month='04';

      $previous_month='03';
      $year=date('Y');
      $egitim_maliyeti_1=mt_rand(1000000,9999999);
      $egitim_maliyeti_2=mt_rand(1000000,9999999);
      $egitim_maliyeti_3=mt_rand(1000000,9999999);
      $egitim_maliyeti_4=mt_rand(1000000,9999999);
      $egitim_maliyeti_5=mt_rand(1000000,9999999);

      $date_1=Carbon::now()->subMonths(48)->format('Y');
      $date_2=Carbon::now()->subMonths(36)->format('Y');
      $date_3=Carbon::now()->subMonths(24)->format('Y');
      $date_4=Carbon::now()->subMonths(12)->format('Y');
      $date_5=Carbon::now()->format('Y');
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6
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

      $kisi_basi_egitim_maliyet_orani_5=$egitim_maliyeti_5/$firma_calisan_sayisi_5;
      $kisi_basi_egitim_maliyet_orani_4=$egitim_maliyeti_4/$firma_calisan_sayisi_4;
      $kisi_basi_egitim_maliyet_orani_3=$egitim_maliyeti_3/$firma_calisan_sayisi_3;
      $kisi_basi_egitim_maliyet_orani_2=$egitim_maliyeti_2/$firma_calisan_sayisi_2;
      $kisi_basi_egitim_maliyet_orani_1=$egitim_maliyeti_1/$firma_calisan_sayisi_1;

      View::share('date_1',$date_1);
      View::share('date_5',$date_5);
      View::share('date_2',$date_2);
      View::share('date_3',$date_3);
      View::share('date_4',$date_4);
      View::share('kisi_basi_egitim_maliyet_orani_5',$kisi_basi_egitim_maliyet_orani_5);
      View::share('kisi_basi_egitim_maliyet_orani_4',$kisi_basi_egitim_maliyet_orani_4);
      View::share('kisi_basi_egitim_maliyet_orani_3',$kisi_basi_egitim_maliyet_orani_3);
      View::share('kisi_basi_egitim_maliyet_orani_2',$kisi_basi_egitim_maliyet_orani_2);
      View::share('kisi_basi_egitim_maliyet_orani_1',$kisi_basi_egitim_maliyet_orani_1);

      return view('newmetric.hrcostkpi.educationcostperperson');
   }
   public function rateofcosttototalincome()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $year=date('Y');
      $toplam_ciro_1=mt_rand(100000000,999999999);
      $toplam_ciro_2=mt_rand(100000000,999999999);
      $toplam_ciro_3=mt_rand(100000000,999999999);
      $toplam_ciro_4=mt_rand(100000000,999999999);
      $toplam_ciro_5=mt_rand(100000000,999999999);

      $date_1=Carbon::now()->subMonths(48)->format('Y');
      $date_2=Carbon::now()->subMonths(36)->format('Y');
      $date_3=Carbon::now()->subMonths(24)->format('Y');
      $date_4=Carbon::now()->subMonths(12)->format('Y');
      $date_5=Carbon::now()->format('Y');
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereYear("approved_incentives.service", $date_1)->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');

      $firma_toplam_maliyet_1=22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl');

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereYear("approved_incentives.service", $date_2)->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');

      $firma_toplam_maliyet_2=22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl');

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereYear("approved_incentives.service", $date_3)->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');

      $firma_toplam_maliyet_3=22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl');

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereYear("approved_incentives.service", $date_4)->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');

      $firma_toplam_maliyet_4=22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl');

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereYear("approved_incentives.service", $date_5)->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');

      $firma_toplam_maliyet_5=22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl');

      $ucretin_toplam_gelire_orani_5=$firma_toplam_maliyet_5*100/$toplam_ciro_5;
      $ucretin_toplam_gelire_orani_4=$firma_toplam_maliyet_4*100/$toplam_ciro_4;
      $ucretin_toplam_gelire_orani_3=$firma_toplam_maliyet_3*100/$toplam_ciro_3;
      $ucretin_toplam_gelire_orani_2=$firma_toplam_maliyet_2*100/$toplam_ciro_2;
      $ucretin_toplam_gelire_orani_1=$firma_toplam_maliyet_1*100/$toplam_ciro_1;

      View::share('date_1',$date_1);
      View::share('date_5',$date_5);
      View::share('date_2',$date_2);
      View::share('date_3',$date_3);
      View::share('date_4',$date_4);
      View::share('ucretin_toplam_gelire_orani_1',$ucretin_toplam_gelire_orani_1);
      View::share('ucretin_toplam_gelire_orani_2',$ucretin_toplam_gelire_orani_2);
      View::share('ucretin_toplam_gelire_orani_3',$ucretin_toplam_gelire_orani_3);
      View::share('ucretin_toplam_gelire_orani_4',$ucretin_toplam_gelire_orani_4);
      View::share('ucretin_toplam_gelire_orani_5',$ucretin_toplam_gelire_orani_5);
      return view('newmetric.hrcostkpi.rateofcosttototalincome');
   }
   public function costratebydepartment()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $year=date('Y');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('jobs',function($join){$join->on('incentive_services.meslek_kod','=','jobs.kod');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth("approved_incentives.service","=", $month)->whereYear("approved_incentives.service","=", $year)->select('jobs.departman_adi',DB::raw('SUM(incentive_services.ucret_tl) as ucret_toplam'),DB::raw('SUM(incentive_services.ikramiye_tl) as ikramiye_toplam'))->groupBy('jobs.departman_adi')->orderBy('ucret_toplam','desc')->take(10)->get();
      $firma_toplam_maliyet=0;

      foreach ($firmadata as $firm) {
         if (is_null($firm['departman_adi'])) {
            $firm['departman_adi']="DİĞER";
         }
         $firma_toplam_maliyet +=$firm['ucret_toplam'];
         $firma_toplam_maliyet +=$firm['ikramiye_toplam'];
      }
      $firma_toplam_maliyet=$firma_toplam_maliyet*22.5/100+$firma_toplam_maliyet;

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('jobs',function($join){$join->on('incentive_services.meslek_kod','=','jobs.kod');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth("approved_incentives.service","=", $month)->whereYear("approved_incentives.service","=", $year)->select('jobs.departman_adi','jobs.kod',DB::raw('SUM(incentive_services.ucret_tl) as ucret_toplam'),DB::raw('SUM(incentive_services.ikramiye_tl) as ikramiye_toplam'))->groupBy('jobs.departman_adi')->orderBy('ucret_toplam','desc')->take(10)->get();
      $sektor_toplam_maliyet=0;
      foreach ($sectordata as $sec) {
         if (is_null($sec['departman_adi'])) {
            $sec['departman_adi']="DİĞER";
         }
         $sektor_toplam_maliyet +=$sec['ucret_toplam'];
         $sektor_toplam_maliyet +=$sec['ikramiye_toplam'];
      }
      $sektor_toplam_maliyet=$sektor_toplam_maliyet*22.5/100+$sektor_toplam_maliyet;
      View::share('month',$month);
      View::share('year',$year);
      View::share('firmadata',$firmadata);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('sectordata',$sectordata);
      View::share('sektor_toplam_maliyet',$sektor_toplam_maliyet);
      return view('newmetric.hrcostkpi.costratebydepartment');
   }
   public function incentiveutilizationrate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='05';
      $year=date('Y');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      $tesvikdata=DB::table('gain_incentives')->where('sgk_company_id','=',$sgk_company_id)->whereMonth("accrual","=", $month)->whereYear("accrual","=", $year)->get();
      $law_5510=$tesvikdata->sum('law_5510');
      $law_27103=$tesvikdata->sum('law_27103');
      $law_6111=$tesvikdata->sum('law_6111');
      $law_14857=$tesvikdata->sum('law_14857');
      $firma_toplam_tesvik_kazanci=$law_14857+$law_6111+$law_27103+$law_5510;
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth("approved_incentives.service",">=", $month)->whereYear("approved_incentives.service",">=", $year)->select('incentive_services.ucret_tl','incentive_services.ucret_tl')->get();
      $firma_toplam_maliyet=0;
      $firma_ucret_toplam=$firmadata->sum('ucret_tl');
      $firma_ikramiye_toplam=$firmadata->sum('ikramiye_tl');
      $firma_toplam_maliyet=$firma_ikramiye_toplam+$firma_ucret_toplam;
      $firma_toplam_maliyet=$firma_toplam_maliyet*22.5/100+$firma_toplam_maliyet;
      $firma_yuzdesi=$firma_toplam_tesvik_kazanci*100/$firma_toplam_maliyet;

      $sektor_tesvikdata=SgkCompany::join('gain_incentives','sgk_companies.id','=','gain_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->whereMonth("gain_incentives.accrual","=", $month)->whereYear("gain_incentives.accrual","=", $year)->get();
      $sektor_law_5510=$sektor_tesvikdata->sum('law_5510');
      $sektor_law_27103=$sektor_tesvikdata->sum('law_27103');
      $sektor_law_6111=$sektor_tesvikdata->sum('law_6111');
      $sektor_law_14857=$sektor_tesvikdata->sum('law_14857');
      $sektor_toplam_tesvik_kazanci=$sektor_law_14857+$sektor_law_6111+$sektor_law_27103+$sektor_law_5510;

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth("approved_incentives.service","=", $month)->whereYear("approved_incentives.service","=", $year)->select('incentive_services.ucret_tl','incentive_services.ucret_tl')->get();
      $sektor_toplam_maliyet=0;
      $sektor_ucret_toplam=$sectordata->sum('ucret_tl');
      $sektor_ikramiye_toplam=$sectordata->sum('ikramiye_tl');
      $sektor_toplam_maliyet=$sektor_ikramiye_toplam+$sektor_ucret_toplam;
      $sektor_toplam_maliyet=$sektor_toplam_maliyet*22.5/100+$sektor_toplam_maliyet;
      $sektor_yuzdesi=$sektor_toplam_tesvik_kazanci*100/$sektor_toplam_maliyet;

      View::share('month',$month);
      View::share('year',$year);
      View::share('firma_toplam_tesvik_kazanci',$firma_toplam_tesvik_kazanci);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_toplam_tesvik_kazanci',$sektor_toplam_tesvik_kazanci);
      View::share('sektor_toplam_maliyet',$sektor_toplam_maliyet);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hrcostkpi.incentiveutilizationrate');
   }
   public function personbasedincentiverate()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='05';
      $year=date('Y');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      $tesvikdata=DB::table('gain_incentives')->where('sgk_company_id','=',$sgk_company_id)->whereMonth("accrual","=", $month)->whereYear("accrual","=", $year)->select('law_5510','law_27103','law_6111','law_14857')->get();
      $law_5510=$tesvikdata->sum('law_5510');
      $law_27103=$tesvikdata->sum('law_27103');
      $law_6111=$tesvikdata->sum('law_6111');
      $law_14857=$tesvikdata->sum('law_14857');
      $firma_toplam_tesvik_kazanci=$law_14857+$law_6111+$law_27103+$law_5510;
      //Eksik gün nedeni kısmi istihdam olanı getirmedik. kısmi istihdam id=6
      /* $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where("approved_incentives.service",">=", Carbon::now()->subMonths(4))->select('incentive_services.*')->get();*/
      $firma_kisi_sayisi=ApprovedIncentive::whereMonth("service","=", $month)->whereYear("service","=", $year)->sum('total_staff');
      $firma_yuzdesi=$firma_toplam_tesvik_kazanci/$firma_kisi_sayisi;

      $sektor_tesvikdata=SgkCompany::join('gain_incentives','sgk_companies.id','=','gain_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->whereMonth("gain_incentives.accrual","=", $month)->whereYear("gain_incentives.accrual","=", $year)->select('gain_incentives.law_5510','gain_incentives.law_27103','gain_incentives.law_6111','gain_incentives.law_14857')->get();
      $sektor_law_5510=$sektor_tesvikdata->sum('law_5510');
      $sektor_law_27103=$sektor_tesvikdata->sum('law_27103');
      $sektor_law_6111=$sektor_tesvikdata->sum('law_6111');
      $sektor_law_14857=$sektor_tesvikdata->sum('law_14857');
      $sektor_toplam_tesvik_kazanci=$sektor_law_14857+$sektor_law_6111+$sektor_law_27103+$sektor_law_5510;

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereMonth("approved_incentives.service","=", $month)->whereYear("approved_incentives.service","=", $year)->select('incentive_services.id')->get();
      $sektor_kisi_sayisi=$sectordata->count();
      $sektor_yuzdesi=$sektor_toplam_tesvik_kazanci/$sektor_kisi_sayisi;

      View::share('month',$month);
      View::share('year',$year);
      View::share('firma_toplam_tesvik_kazanci',$firma_toplam_tesvik_kazanci);
      View::share('firma_kisi_sayisi',$firma_kisi_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_toplam_tesvik_kazanci',$sektor_toplam_tesvik_kazanci);
      View::share('sektor_kisi_sayisi',$sektor_kisi_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hrcostkpi.personbasedincentiverate');
   }
   public function totalincentiveearnings()
   {
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='05';
      $year=date('Y');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      $tesvikdata=DB::table('gain_incentives')->where('sgk_company_id','=',$sgk_company_id)->whereMonth("accrual","=", $month)->whereYear("accrual","=", $year)->select('law_5510','law_27103','law_6111','law_14857')->get();
      $law_5510=$tesvikdata->sum('law_5510');
      $law_27103=$tesvikdata->sum('law_27103');
      $law_6111=$tesvikdata->sum('law_6111');
      $law_14857=$tesvikdata->sum('law_14857');
      $firma_yuzdesi=$firma_toplam_tesvik_kazanci=$law_14857+$law_6111+$law_27103+$law_5510;

      $sektor_tesvikdata=SgkCompany::join('gain_incentives','sgk_companies.id','=','gain_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->whereMonth("gain_incentives.accrual","=", $month)->whereYear("gain_incentives.accrual","=", $year)->select('gain_incentives.law_5510','gain_incentives.law_27103','gain_incentives.law_6111','gain_incentives.law_14857')->get();
      $sektordeki_firma_sayisi=SgkCompany::where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->select('sgk_companies.id')->count();
      //burda kaldım
      $sektor_law_5510=$sektor_tesvikdata->sum('law_5510');
      $sektor_law_27103=$sektor_tesvikdata->sum('law_27103');
      $sektor_law_6111=$sektor_tesvikdata->sum('law_6111');
      $sektor_law_14857=$sektor_tesvikdata->sum('law_14857');
      $sektor_toplam_tesvik_kazanci=$sektor_law_14857+$sektor_law_6111+$sektor_law_27103+$sektor_law_5510;
      $sektor_yuzdesi=round($sektor_toplam_tesvik_kazanci/$sektordeki_firma_sayisi);
      View::share('month',$month);
      View::share('year',$year);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      View::share('firma_toplam_tesvik_kazanci',$firma_toplam_tesvik_kazanci);
      View::share('law_5510',$law_5510);
      View::share('law_27103',$law_27103);
      View::share('law_6111',$law_6111);
      View::share('law_14857',$law_14857);
      View::share('sektor_toplam_tesvik_kazanci',$sektor_toplam_tesvik_kazanci);
      View::share('sektor_law_5510',$sektor_law_5510);
      View::share('sektor_law_27103',$sektor_law_27103);
      View::share('sektor_law_6111',$sektor_law_6111);
      View::share('sektor_law_14857',$sektor_law_14857);
      return view('newmetric.hrcostkpi.totalincentiveearnings');
   }
   public function severancepayburden()
   {
      $sosyal_hak_edis=0;
      $sektor_sosyal_hak_edis=0;
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $year=date('Y');
      $day=date('d');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      // die('girdi');
      $calisilan_gun_sayisi=0;
      $sektor_calisilan_gun_sayisi=0;

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'))->first();

      $firma_toplam_maliyet=$firmadata->toplamucret+$firmadata->toplamikramiye;

      $brut_ucret=round($firma_toplam_maliyet/12);
      if ($brut_ucret>7117.17) {
         $brut_ucret=7117.17;
      }
      $toplam_brut=$brut_ucret+$sosyal_hak_edis;
      $bir_gunluk_kidem=round($toplam_brut/365);
      $firmadata=ApprovedIncentive::join('incentives','approved_incentives.sgk_company_id','=','incentives.sgk_company_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereNotNull('incentives.job_start')->whereNull('incentives.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentives.job_start')->groupBy('incentives.tck')->get();
      foreach ($firmadata as $firm) {
         $to = Carbon::createFromFormat('Y-m-d', $firm['job_start']);
         $from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
         $calisilan_gun_sayisi += $to->diffInDays($from);
      }
      //die(var_dump($calisilan_gun_sayisi));
      $kidem_esas_kazanc=$calisilan_gun_sayisi*$bir_gunluk_kidem;
      $damga_vergisi=$kidem_esas_kazanc*0.00759;
      $firma_yuzdesi=$net_kidem_tazminati=$kidem_esas_kazanc-$damga_vergisi;

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'))->first();
      $sektor_toplam_maliyet=$sectordata->toplamucret+$sectordata->toplamikramiye;

      $sektor_brut_ucret=round($sektor_toplam_maliyet/12);
      if ($sektor_brut_ucret>7117.17) {
         $sektor_brut_ucret=7117.17;
      }
      $sektor_toplam_brut=$sektor_brut_ucret+$sektor_sosyal_hak_edis;
      $sektor_bir_gunluk_kidem=round($sektor_toplam_brut/365);

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentives','approved_incentives.sgk_company_id','=','incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereNotNull('incentives.job_start')->whereNull('incentives.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentives.job_start')->groupBy('incentives.tck')->get();
      foreach ($sectordata as $sec) {
         $to = Carbon::createFromFormat('Y-m-d', $sec['job_start']);
         $from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
         $sektor_calisilan_gun_sayisi += $to->diffInDays($from);
      }
      //die(var_dump($calisilan_gun_sayisi));
      $sektor_kidem_esas_kazanc=$sektor_calisilan_gun_sayisi*$sektor_bir_gunluk_kidem;
      $damga_vergisi=$sektor_kidem_esas_kazanc*0.00759;

      $sektordeki_firma_sayisi=SgkCompany::where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->select('sgk_companies.id')->count();

      $sektor_yuzdesi=$sektor_net_kidem_tazminati=($sektor_kidem_esas_kazanc-$damga_vergisi)/$sektordeki_firma_sayisi;

      View::share('month',$month);
      View::share('year',$year);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      View::share('net_kidem_tazminati',$net_kidem_tazminati);
      View::share('calisilan_gun_sayisi',$calisilan_gun_sayisi);
      View::share('sektor_net_kidem_tazminati',$sektor_net_kidem_tazminati);
      return view('newmetric.hrcostkpi.severancepayburden');
   }
   public function noticecompensation()
   {
      $sektor_sosyal_hak_edis=0;
      $sosyal_hak_edis=0;
      $sector_id=35;
      $sgk_company_id=654;
      $month=date('m', strtotime('last month'));
      $month='04';
      $year=date('Y');
      $day=date('d');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      $calisilan_gun_sayisi=0;
      $ihbar_gunu=0;
      $ihbar_esas_kazanc=0;
      $sektor_calisilan_gun_sayisi=0;
      $sektor_ihbar_gunu=0;
      $sektor_ihbar_esas_kazanc=0;

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'))->first();

      $firma_toplam_maliyet=$firmadata->toplamucret+$firmadata->toplamikramiye;

      $brut_ucret=round($firma_toplam_maliyet/12);
      $toplam_brut=$brut_ucret+$sosyal_hak_edis;
      $bir_gunluk_kidem=round($toplam_brut/30);

      $firmadata=ApprovedIncentive::join('incentives','approved_incentives.sgk_company_id','=','incentives.sgk_company_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereNotNull('incentives.job_start')->whereNull('incentives.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentives.job_start')->groupBy('incentives.tck')->get();
      foreach ($firmadata as $firm) {
         $to = Carbon::createFromFormat('Y-m-d', $firm['job_start']);
         $from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
         $calisilan_gun_sayisi += $to->diffInDays($from);
         $calisilan_ay=ceil($calisilan_gun_sayisi/30);
         if ($calisilan_ay<=6) {
            $ihbar_gunu=14;
         }elseif ($calisilan_ay>6 AND $calisilan_ay<=18) {
            $ihbar_gunu=28;
         }elseif ($calisilan_ay>18 AND $calisilan_ay<=36) {
            $ihbar_gunu=42;
         }elseif ($calisilan_ay>36) {
            $ihbar_gunu=56;
         }
         $ihbar_esas_kazanc +=$bir_gunluk_kidem*$ihbar_gunu;
      }
      $damga_vergisi=$ihbar_esas_kazanc*0.00759;
      $gelir_vergisi=$toplam_brut*0.15;
      $firma_yuzdesi=$net_ihbar_tazminati=$ihbar_esas_kazanc-$damga_vergisi-$gelir_vergisi;

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'))->first();
      $sektor_toplam_maliyet=$sectordata->toplamucret+$sectordata->toplamikramiye;

      $sektor_brut_ucret=round($sektor_toplam_maliyet/12);
      $sektor_toplam_brut=$sektor_brut_ucret+$sektor_sosyal_hak_edis;
      $sektor_bir_gunluk_kidem=round($sektor_toplam_brut/30);

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentives','approved_incentives.sgk_company_id','=','incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereNotNull('incentives.job_start')->whereNull('incentives.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentives.job_start')->groupBy('incentives.tck')->get();
      foreach ($sectordata as $sec) {
         $to = Carbon::createFromFormat('Y-m-d', $sec['job_start']);
         $from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
         $sektor_calisilan_gun_sayisi += $to->diffInDays($from);
         $sektor_calisilan_ay=ceil($sektor_calisilan_gun_sayisi/30);
         if ($sektor_calisilan_ay<=6) {
            $ihbar_gunu=14;
         }elseif ($sektor_calisilan_ay>6 AND $sektor_calisilan_ay<=18) {
            $ihbar_gunu=28;
         }elseif ($sektor_calisilan_ay>18 AND $sektor_calisilan_ay<=36) {
            $ihbar_gunu=42;
         }elseif ($sektor_calisilan_ay>36) {
            $sektor_ihbar_gunu=56;
         }
         $sektor_ihbar_esas_kazanc +=$sektor_bir_gunluk_kidem*$sektor_ihbar_gunu;
      }
      $sektor_damga_vergisi=$sektor_ihbar_esas_kazanc*0.00759;
      $sektor_gelir_vergisi=$sektor_toplam_brut*0.15;
      $sektordeki_firma_sayisi=SgkCompany::where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->select('sgk_companies.id')->count();
      $sektor_yuzdesi=$sektor_net_ihbar_tazminati=($sektor_ihbar_esas_kazanc-$sektor_damga_vergisi-$sektor_gelir_vergisi)/$sektordeki_firma_sayisi;

      View::share('month',$month);
      View::share('year',$year);
      View::share('net_ihbar_tazminati',$net_ihbar_tazminati);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      return view('newmetric.hrcostkpi.noticecompensation');
   }
}
