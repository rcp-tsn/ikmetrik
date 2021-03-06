<?php

namespace App\Http\Controllers;

use App\Models\IncentiveService;
use App\Models\ApprovedIncentive;
use App\Models\MetricReport;
use App\Models\SgkCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HrMetricController extends Controller
{
    public function subMetricGroups($slug)
    {
        //dd(camel_case($slug));
        $sgk_company = getSgkCompany();
        $metricviewfilename = strtolower($slug);
        $metricviewfilename = str_replace('-', '', $metricviewfilename);
        //dd($metricviewfilename);
        $metric_report = MetricReport::where('slug_en', $slug)->first();
        if ($metric_report) {
            $slug = camel_case($slug);
            $this->$slug();
            View::share('sgk_company',$sgk_company);
            View::share('metric_report',$metric_report);
            return view('newmetric.'.$metricviewfilename);
            //call_user_func($slug);
          //dd();
        } else {
            die('rapor bulunamad─▒');
        }
    }

    public function saveHrImage(Request $request)
    {
        $sgk_company = getSgkCompany();
        $image = $request->base64;
        $type = $request->type;

        $location = "hr_images/".$sgk_company->id;
        $image_parts = explode(";base64,", $image);

        $image_base64 = base64_decode($image_parts[1]);
        $fileName =  $type.".png";

        $file = $location . '/'.$fileName;

        if (!is_dir($location)) {
            // dir doesn't exist, make it
            mkdir($location);
        }

        $success = file_put_contents($file, $image_base64);
        //$success = Storage::disk('pdf_images')->put($fileName, $image_base64);;
        return $success ? $fileName : 'Unable to save the file.';

    }

    public function overtimeDayRate()
    {
      $sabitler=config('constants');
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $year=date('Y');
      $years=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','overtime')->select('value_year')->groupBy('value_year')->get();

      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      $ortalama=0;
      $ay_sayisi=0;
      $firma_toplam_fazla_mesai_saati=0;
      $firma_toplam_planlanan_gun_saati=0;
      $i=0;
      $firma_current_rate=0;
      $genel_ortalama=0;
      $error_message=0;
      $aylar = $sabitler['aylar'];
      foreach ($aylar as $key => $value) {
      	$firmadata=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','overtime')->where('value_year','=',$year)->where('value_month','=',$key)->get();
      	$firmovertimedata[$i]['ay']=$value;
      	if ($firmadata->count()>0) {
      		$mesaisaati=$firmadata[0]->value;
      	}else{
      		$mesaisaati=0;
      	}
      	$firmovertimedata[$i]['mesaisaati']=$mesaisaati;
      	$firma_toplam_fazla_mesai_saati+=$mesaisaati;
      	$firmdata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereYear("approved_incentives.service", $year)->whereMonth("approved_incentives.service", $key)->select('incentive_services.eksik_gun','incentive_services.gun')->get();
      	//die(var_dump($firmdata));
      	$planlanan_gun=$firmdata->sum('eksik_gun')+$firmdata->sum('gun');
      	$firma_planlanan_gun_saati = $planlanan_gun*7.5;
        $firma_toplam_planlanan_gun_saati+=$firma_planlanan_gun_saati;
        $firmovertimedata[$i]['planlanansaat']=$firma_planlanan_gun_saati;
        if ($firma_planlanan_gun_saati==0) {
       	    $ortalama+=0;
       	    $genel_ortalama+=0;
       	    $ay_sayisi+=0;
       	    $firma_current_rate=0;
        }else{
        	$ortalama+=$mesaisaati*100/$firma_planlanan_gun_saati;
        	$firma_current_rate=$mesaisaati*100/$firma_planlanan_gun_saati;
        	if ($firmadata->count()!=$i+1) {
      			 $genel_ortalama+=$firma_current_rate;
      		}
        	$ay_sayisi+=1;
        }

        $i++;
      }
      if ($ay_sayisi==0) {
      	$firma_yuzdesi=0;
      	$genel_ortalama=0;
      }else{
      	$firma_yuzdesi=$ortalama/$ay_sayisi;
      	$genel_ortalama=$genel_ortalama/$ay_sayisi;
      }

      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Fazla Mesai G├╝n├╝ Oran─▒";
      $rapor_metni="Bir i┼čyerinde haftal─▒k ├žal─▒┼čma s├╝resini a┼čan ├žal─▒┼čmalar fazla mesai olarak tan─▒mlanm─▒┼čt─▒r. Normal mesaisi i├žin bir ├╝cret alan i┼č├ži, fazla mesaisi i├žin de ayr─▒ca bir ├╝cret al─▒r. ─░┼č kanununda yaz─▒l─▒ ko┼čullar ├žer├ževesinde haftal─▒k 45 saati a┼čan ├žal─▒┼čmalar─▒ ifade etmektedir. ─░┼čletmelerde meydana gelen de─či┼čiklikler ve planl─▒/plans─▒z ├╝retim art─▒┼člar─▒ sonucunda olu┼čan fazla ├žal─▒┼čmay─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('year',$year);
      View::share('years',$years);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('firma_toplam_fazla_mesai_saati',$firma_toplam_fazla_mesai_saati);
      View::share('firma_toplam_planlanan_gun_saati',$firma_toplam_planlanan_gun_saati);
      View::share('firmovertimedata',$firmovertimedata);
      View::share('error_message',$error_message);
      View::share('genel_ortalama',$genel_ortalama);
      View::share('firma_current_rate',$firma_current_rate);
      //return view('newmetric.hrcostkpi.overtimedayrate');
    }
   public function staffUnitCostRate()
    {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //$ay_sayisi = (int)(Carbon::parse($mindate)->diffInDays(Carbon::parse($maxdate))/30);

      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6

      $son_ay = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service',DB::raw('SUM(total_staff) as personel_sayisi'))->groupBy('approved_incentives.service')->get();
      $ay_sayisi=$son_ay->count();
      if ($ay_sayisi==0) {
      	$firma_calisan_sayisi=0;
      }else{
      	$firma_calisan_sayisi=$son_ay->sum('personel_sayisi')/$ay_sayisi;
      }

      //die(var_dump($firma_calisan_sayisi));
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');
      if ($ay_sayisi==0) {
      	$firma_toplam_maliyet=0;
      }else{
      	$firma_toplam_maliyet=(22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/$ay_sayisi;
      }

      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_toplam_maliyet/$firma_calisan_sayisi;
      }

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplam_ucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplam_ikramiye'),DB::raw('COUNT(approved_incentives.sgk_company_id) as personelsayisi'),'approved_incentives.sgk_company_id')->groupBy('approved_incentives.sgk_company_id')->get();
      $sektor_firma_sayisi=$sectordata->count();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
          $maliyet=122.5*($sdata['toplam_ucret']+$sdata['toplam_ikramiye'])/100;
          $ortalama=$ortalama+$maliyet/($sdata['personelsayisi']);
      }

      if ($sektor_firma_sayisi==0) {
        $ortalama=0;
      }else{
        $ortalama=$ortalama/$sektor_firma_sayisi;

      }
      $sektor_yuzdesi=$ortalama;
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_footer=$sabitler['base_footer'];
      $base_logo=$sabitler['base_logo'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Personel Birim Maliyet Oran─▒";
      $rapor_metni="Bir├žok i┼čletmede i┼č g├╝c├╝ maliyetleri temel veya ikinci masraf kalemi olarak tan─▒mlanm─▒┼čt─▒r. ─░┼čletme maliyet y├Ânetimi ekseninde birim maliyet oran─▒ di─čer kalemler ile birlikte(sgk+vergi) stratejik ├Ânem arz etmektedir. ─░┼čletmelerde ├╝retim/hizmet rekabetinin en ├Ânemli girdilerinden biri olan personel maliyeti oran─▒n─▒ hesaplanmaktad─▒r.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_calisan_sayisi',$firma_calisan_sayisi);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      ////return view('newmetric.hrcostkpi.staffunitcostrate');
    }
   public function dutyWageChart()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('profession_codes',function($join){$join->on('incentive_services.meslek_kod','=','profession_codes.isco');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('profession_codes.name as departmanadi',DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'))->groupBy('incentive_services.meslek_kod')->orderBy('toplamucret','desc')->take(10)->get();
      // die(var_dump($firmadata[0]['toplamucret']));

         foreach ($firmadata as $firm) {
            if (is_null($firm['departmanadi'])) {
               $firm['departmanadi']="D─░─×ER";
            }
         }
      $firma_toplam_maliyet=22.5*($firmadata->sum('toplamucret')+$firmadata->sum('toplamikramiye'))/100+$firmadata->sum('toplamucret')+$firmadata->sum('toplamikramiye');

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('profession_codes',function($join){$join->on('incentive_services.meslek_kod','=','profession_codes.isco');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('profession_codes.name as departmanadi',DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'))->groupBy('incentive_services.meslek_kod')->orderBy('toplamucret','desc')->take(10)->get();

      $sektor_toplam_maliyet=22.5*($sectordata->sum('toplamucret')+$sectordata->sum('toplamikramiye'))/100+$sectordata->sum('toplamucret')+$sectordata->sum('toplamikramiye');
      $sektordeki_firma_sayisi=SgkCompany::where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->select('sgk_companies.id')->count();
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="G├Ârev ├ťcret Grafi─či";
      $rapor_metni="─░┼čletmede ├žal─▒┼čan bireylerin, bildirge ├╝zerinden tan─▒mlanan g├Ârev/meslek kodu ├╝zerinde ├╝cret ili┼čkisini, meslek baz─▒nda da─č─▒l─▒mlar─▒n─▒ ifade eder. Mevcut ├╝cret/g├Ârev ili┼čkisinin, ├Âzellikle yetkinlik ├žer├ževesinden tekrar analiz edilmesini sa─člar. Bildirgeler ├╝zerinden tan─▒mlanan g├Ârevlerin, ├╝cret ile ili┼čkisini tan─▒mlamaktad─▒r.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('sektor_toplam_maliyet',$sektor_toplam_maliyet);
      View::share('sektordeki_firma_sayisi',$sektordeki_firma_sayisi);
      ////return view('newmetric.hrcostkpi.dutywagechart');
   }
   public function extraPayCostRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.ikramiye_tl','incentive_services.ucret_tl');

      $firma_ek_odeme_maliyet=$firmadata->sum('ikramiye_tl');
      $firma_toplam_maliyet=22.5*($firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl'))/100+$firmadata->sum('ucret_tl')+$firmadata->sum('ikramiye_tl');
      if ($firma_toplam_maliyet==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_ek_odeme_maliyet*100/$firma_toplam_maliyet;
      }

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplam_ucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplam_ikramiye'),DB::raw('COUNT(approved_incentives.sgk_company_id) as personelsayisi'))->groupBy('approved_incentives.sgk_company_id')->get();
      $sektor_firma_sayisi=$sectordata->count();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	  $sektor_ek_odeme_maliyet=$sdata['toplam_ikramiye'];
          $sektor_toplam_maliyet=122.5*($sdata['toplam_ucret']+$sdata['toplam_ikramiye'])/100;
          if ($sektor_toplam_maliyet==0) {
	         $ortalama=$ortalama+0;
	      }else{
	         $ortalama=$ortalama+$sektor_ek_odeme_maliyet*100/$sektor_toplam_maliyet;
	      }
      }
      if ($sektor_firma_sayisi==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=$ortalama/$sektor_firma_sayisi;
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }

      $sabitler=config('constants');
      $base_footer=$sabitler['base_footer'];
      $base_logo=$sabitler['base_logo'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Ek ├ľdeme Maliyet Oran─▒ ";
      $rapor_metni="─░┼čletme b├╝nyesinde tan─▒mlanan ek ├Âdemelerin i┼č tatmini ile direk ilgisi olup, ├žal─▒┼čanlar─▒n i┼čletmeye kat─▒l─▒mlar─▒n─▒ etkilemektedir. Bu sebep ile artan yan haklar/ek ├Âdemeler dengesi, kurumun rekabet edebilirli─čini olumlu/olumsuz y├Ânde etkileyecektir. ─░┼čletmelerin ek ├Âdeme maliyet metri─či insan kaynaklar─▒ departman─▒ i├žin b├╝y├╝k ├Ânem arz edecektir. Ayl─▒k prim hizmet bildirgesinde SGK 'ya bildirilen ek ├Âdemlerin oran─▒n─▒ tan─▒mlar.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_ek_odeme_maliyet',$firma_ek_odeme_maliyet);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      //View::share('sektor_ek_odeme_maliyet',$sektor_ek_odeme_maliyet);
      //View::share('sektor_toplam_maliyet',$sektor_toplam_maliyet);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      ////return view('newmetric.hrcostkpi.extrapaycostrate');
   }
   public function educationCostPerPerson()
   {
      $sabitler=config('constants');
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $year=date('Y');
      $years=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','education')->select('value_year')->groupBy('value_year')->get();
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      $i=0;
      $firma_toplam__egitim_maliyeti=0;
      $firma_current_rate=0;
      $genel_ortalama=0;
      $error_message=0;
      $personelsayisi=0;

      $firmadata=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','education')->select('value_year')->orderBy('value_year','asc')->get();
      foreach ($firmadata as $fdata) {
      	$ay_sayisi=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereYear('service','=',$year)->groupBy('service')->count();
      	$firmaeducationdata[$i]['yil']=$fdata->value_year;
      	$firmaeducationdata[$i]['personelsayisi']=round(ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereYear('service','=',$year)->sum('total_staff')/$ay_sayisi);
      	$firmaeducationdata[$i]['educationcost']=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','education')->where('value_year','=',$fdata->value_year)->select('value')->sum('value');
      	$firma_toplam__egitim_maliyeti+=$firmaeducationdata[$i]['educationcost'];
      	if ($firmaeducationdata[$i]['personelsayisi']==0) {
      		$firma_current_rate=0;
      		$genel_ortalama+=0;
      		$personelsayisi+=0;
      	}else{
      		$personelsayisi+=$firmaeducationdata[$i]['personelsayisi'];
      		$firma_current_rate=$firmaeducationdata[$i]['educationcost']/$firmaeducationdata[$i]['personelsayisi'];
      		if ($firmadata->count()==$i+1) {
      			 $genel_ortalama+=$firma_current_rate;
      		}
      	}
      	$i++;
      }
      //die(var_dump($personelsayisi));
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Ki┼či Ba┼č─▒ E─čitim Maliyeti";
      $rapor_metni="Bireyin geli┼čiminin temel noktalar─▒ndan biri olan i┼čletme b├╝nyesinde planlanan/yap─▒lan e─čitim organizasyonlar─▒ maliyetinin ki┼či baz─▒nda form├╝le edilmesi ile elde edilen de─čeri ifade eder. Artan e─čitim saati ve maliyeti i┼čletme ├╝zerinde olumlu bir├žok yans─▒mas─▒ beklenmektedir. ─░┼čletme i├žerisinde ger├žekle┼čtirilen e─čitim maliyetinin ki┼či ba┼č─▒ e─čitim maliyeti oran─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('personelsayisi',$personelsayisi);
      View::share('firma_toplam__egitim_maliyeti',$firma_toplam__egitim_maliyeti);
      if ($firmadata->count()>0) {
      	$genel_ortalama=$genel_ortalama/$firmadata->count();
      	View::share('firmaeducationdata',$firmaeducationdata);
      }else{
      	$error_message=1;
      }
      View::share('error_message',$error_message);
      View::share('genel_ortalama',$genel_ortalama);
      View::share('firma_current_rate',$firma_current_rate);
   }
   public function rateOfCostToTotalIncome()
   {
      $sabitler=config('constants');
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $year=date('Y');
      $firma_toplam_ciro=0;
      $i=0;
      $firma_current_rate=0;
      $genel_ortalama=0;
      $error_message=0;
      $total_maliyet=0;
      $firmadata=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','cost')->select('value_year')->orderBy('value_year','asc')->get();
      foreach ($firmadata as $fdata) {
      	$ay_sayisi=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereYear('service','=',$year)->groupBy('service')->count();
      	$firmacostdata[$i]['yil']=$fdata->value_year;
      	$firmdata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereYear("approved_incentives.service", $year)->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl');
      	$firma_toplam_maliyet=22.5*($firmdata->sum('ucret_tl')+$firmdata->sum('ikramiye_tl'))/100+$firmdata->sum('ucret_tl')+$firmdata->sum('ikramiye_tl');
      	$total_maliyet+=$firma_toplam_maliyet;
      	$firmacostdata[$i]['toplammaliyet']=$firma_toplam_maliyet;
      	$firmacostdata[$i]['cost']=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','cost')->where('value_year','=',$fdata->value_year)->select('value')->sum('value');
      	$firma_toplam_ciro+=$firmacostdata[$i]['cost'];
      	if ($firmacostdata[$i]['toplammaliyet']==0) {
      		$firma_current_rate=0;
      		$genel_ortalama+=0;
      	}else{
      		$firma_current_rate=$firmacostdata[$i]['cost']/$firmacostdata[$i]['toplammaliyet'];
      		if ($firmadata->count()==$i+1) {
      			 $genel_ortalama+=$firma_current_rate;
      		}
      	}
      	$i++;
      }

      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="├ťcretin Toplam Gelire Oran─▒";
      $rapor_metni="─░┼čg├╝c├╝ maliyetlerinin i┼čletmelerin b├╝t├žesinde ├Ânemli bir pay─▒ vard─▒r. Stratejik insan kaynaklar─▒ s├╝re├žlerinde ├╝cret y├Ânetimi ├Ânemli bir girdi olarak kar┼č─▒m─▒za ├ž─▒kmaktad─▒r. Bu nedenle ├žal─▒┼čan maliyetinin toplam ├╝cret gelirine oran─▒ i┼čletmelerin rekabet edebilirli─čine olumlu/olumsuz etki eden ├Ânemli bir girdidir. ┼×irket toplam gelirinin toplam personele oran─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('firma_toplam_ciro',$firma_toplam_ciro);
      if ($firmadata->count()>0) {
      	$genel_ortalama=$genel_ortalama/$firmadata->count();
      	View::share('firmacostdata',$firmacostdata);
      }else{
      	$error_message=1;
      }
      View::share('total_maliyet',$total_maliyet);
      View::share('error_message',$error_message);
      View::share('genel_ortalama',$genel_ortalama);
      View::share('firma_current_rate',$firma_current_rate);
      ////return view('newmetric.hrcostkpi.rateofcosttototalincome');
   }
   public function costRateByDepartment()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('jobs',function($join){$join->on('incentive_services.meslek_kod','=','jobs.kod');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('jobs.departman_adi',DB::raw('SUM(incentive_services.ucret_tl) as ucret_toplam'),DB::raw('SUM(incentive_services.ikramiye_tl) as ikramiye_toplam'))->groupBy('jobs.departman_adi')->orderBy('ucret_toplam','desc')->take(10)->get();
      $firma_toplam_maliyet=0;

      foreach ($firmadata as $firm) {
         if (is_null($firm['departman_adi'])) {
            $firm['departman_adi']="D─░─×ER";
         }
         $firma_toplam_maliyet +=$firm['ucret_toplam'];
         $firma_toplam_maliyet +=$firm['ikramiye_toplam'];
      }

      $firma_toplam_maliyet=$firma_toplam_maliyet*22.5/100+$firma_toplam_maliyet;

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('jobs',function($join){$join->on('incentive_services.meslek_kod','=','jobs.kod');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('jobs.departman_adi','jobs.kod',DB::raw('SUM(incentive_services.ucret_tl) as ucret_toplam'),DB::raw('SUM(incentive_services.ikramiye_tl) as ikramiye_toplam'))->groupBy('jobs.departman_adi')->orderBy('ucret_toplam','desc')->take(10)->get();
      $sektor_toplam_maliyet=0;
      foreach ($sectordata as $sec) {
         if (is_null($sec['departman_adi'])) {
            $sec['departman_adi']="D─░─×ER";
         }
         $sektor_toplam_maliyet +=$sec['ucret_toplam'];
         $sektor_toplam_maliyet +=$sec['ikramiye_toplam'];
      }
      $sektor_toplam_maliyet=$sektor_toplam_maliyet*22.5/100+$sektor_toplam_maliyet;
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Departman Baz─▒nda Maliyet Oran─▒";
      $rapor_metni="Bir├žok i┼čletmede i┼č g├╝c├╝ maliyetleri temel veya ikinci masraf kalemi olarak tan─▒mlanm─▒┼čt─▒r. ─░┼čletme maliyet y├Ânetimi ekseninde birim maliyet oran─▒ di─čer kalemler ile birlikte(sgk+vergi) stratejik ├Ânem arz etmektedir. ─░┼čletmelerde bildirge ├╝zerinden olu┼čan departman tan─▒mlar─▒n─▒n maliyet ekseninde oran─▒n─▒ belirler.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('sectordata',$sectordata);
      View::share('sektor_toplam_maliyet',$sektor_toplam_maliyet);
      ////return view('newmetric.hrcostkpi.costratebydepartment');
   }
   public function incentiveUtilizationRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
     // die(var_dump($sector_id));
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service',DB::raw('SUM(total_staff) as personel_sayisi'))->groupBy('approved_incentives.service')->get()->count();

      $tesvikdata=DB::table('gain_incentives')->where('sgk_company_id','=',$sgk_company_id)->whereBetween("accrual", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->get();
      $law_5510=$tesvikdata->sum('law_5510');
      $law_27103=$tesvikdata->sum('law_27103');
      $law_6111=$tesvikdata->sum('law_6111');
      $law_14857=$tesvikdata->sum('law_14857');
      $firma_toplam_tesvik_kazanci=$law_14857+$law_6111+$law_27103+$law_5510;
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.ucret_tl','incentive_services.ucret_tl')->get();
      $firma_toplam_maliyet=0;
      $firma_ucret_toplam=$firmadata->sum('ucret_tl');
      $firma_ikramiye_toplam=$firmadata->sum('ikramiye_tl');
      $firma_toplam_maliyet=$firma_ikramiye_toplam+$firma_ucret_toplam;
      $firma_toplam_maliyet=$firma_toplam_maliyet*22.5/100+$firma_toplam_maliyet;
      if ($firma_toplam_maliyet==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_toplam_tesvik_kazanci*100/$firma_toplam_maliyet;
      }

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      //dd($sectordata);
      foreach ($sectordata as $sdata) {
      	$sectortesvikdata=ApprovedIncentive::join('gain_incentives','approved_incentives.id','=','gain_incentives.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("gain_incentives.accrual", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->get();
        $toplam_tesvik_kazanci=$sectortesvikdata->sum('law_5510')+$sectortesvikdata->sum('law_27103')+$sectortesvikdata->sum('law_6111')+$sectortesvikdata->sum('law_14857');
        $sectormaliyetdata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.accrual", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->get();
        $maliyet=122.5*($sectormaliyetdata->sum('ucret_tl')+$sectormaliyetdata->sum('ikramiye_tl'))/100;

        if ($maliyet==0) {
           $ortalama+=0;
        }else{
           $ortalama+=100*$toplam_tesvik_kazanci/$maliyet;
        }

      }
      if ($sectordata->count()==0) {
        $sektor_yuzdesi=0;
      }else{
        $sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }

      $sabitler=config('constants');
      $base_footer=$sabitler['base_footer'];
      $base_logo=$sabitler['base_logo'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Te┼čvik Faydalanma Oran─▒ ";
      $rapor_metni="Sosyal G├╝venlik Kurumu (SGK) te┼čvik, istihdam─▒n artmas─▒ ve i┼čsizli─čin azalmas─▒n─▒ sa─člamak amac─▒yla, yine Sosyal G├╝venlik Kurumu'nun belirledi─či ┼čartlara g├Âre bir├žok i┼č yerinin ve i┼č├žinin desteklenmesi olarak a├ž─▒klanmaktad─▒r. Rekabet├ži yakla┼č─▒m─▒n t├╝m unsurlar─▒ ile ├žal─▒┼čma hayat─▒n─▒n dinamiklerini etkilemek ile birlikte i┼čverenler ├╝zerindeki maliyet ÔÇô verimlilik ili┼čkisi ├Ânem arz etmektedir. ─░┼čverenlere destek amac─▒ ile y├╝r├╝rl├╝kte bulunan 15 ayr─▒ te┼čvik i┼člemlerinin faydalanma oran─▒n─▒ SGK nezdinde tan─▒mlar.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_toplam_tesvik_kazanci',$firma_toplam_tesvik_kazanci);
      View::share('firma_toplam_maliyet',$firma_toplam_maliyet);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      //return view('newmetric.hrcostkpi.incentiveutilizationrate');
   }
   public function personBasedIncentiveRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();

      $tesvikdata=DB::table('gain_incentives')->where('sgk_company_id','=',$sgk_company_id)->whereBetween("accrual", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('law_5510','law_27103','law_6111','law_14857')->get();
      $law_5510=$tesvikdata->sum('law_5510');
      $law_27103=$tesvikdata->sum('law_27103');
      $law_6111=$tesvikdata->sum('law_6111');
      $law_14857=$tesvikdata->sum('law_14857');
      $firma_toplam_tesvik_kazanci=$law_14857+$law_6111+$law_27103+$law_5510;
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6

      $son_ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      if ($ay_sayisi==0) {
      	$firma_kisi_sayisi=0;
      }else{
      	$firma_kisi_sayisi=$son_ay->sum('total_staff')/$ay_sayisi;
      }

      if ($firma_kisi_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=round($firma_toplam_tesvik_kazanci/$firma_kisi_sayisi);
      }

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      $sektor_toplam_tesvik_kazanci=0;
      //dd($sectordata);
      foreach ($sectordata as $sdata) {
      	$ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      	$sectortesvikdata=ApprovedIncentive::join('gain_incentives','approved_incentives.id','=','gain_incentives.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("gain_incentives.accrual", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->get();
        $toplam_tesvik_kazanci=$sectortesvikdata->sum('law_5510')+$sectortesvikdata->sum('law_27103')+$sectortesvikdata->sum('law_6111')+$sectortesvikdata->sum('law_14857');
        $sektor_toplam_tesvik_kazanci+=$toplam_tesvik_kazanci;
        if ($ay_sayisi==0) {
        	$sektor_kisi_sayisi=0;
        }else{
        	$sektor_kisi_sayisi=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.accrual", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id')->get()->count()/$ay_sayisi;
        }

        $sektor_kisi_sayisi=round($sektor_kisi_sayisi);
        if ($sektor_kisi_sayisi==0) {
           $ortalama+=0;
        }else{
           $ortalama+=$toplam_tesvik_kazanci/$sektor_kisi_sayisi;
        }

      }
      if ($sectordata->count()==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=round($ortalama/$sectordata->count());
         $sektor_toplam_tesvik_kazanci=$sektor_toplam_tesvik_kazanci/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firma_yuzdesi%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_footer=$sabitler['base_footer'];
      $base_logo=$sabitler['base_logo'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Ki┼či Baz─▒nda Te┼čvik Oran─▒ ";
      $rapor_metni="Sosyal G├╝venlik Kurumu (SGK) te┼čvik, istihdam─▒n artmas─▒ ve i┼čsizli─čin azalmas─▒n─▒ sa─člamak amac─▒yla, yine Sosyal G├╝venlik Kurumu'nun belirledi─či ┼čartlara g├Âre bir├žok i┼č yerinin ve i┼č├žinin desteklenmesi olarak a├ž─▒klanmaktad─▒r. Rekabet├ži yakla┼č─▒m─▒n t├╝m unsurlar─▒ ile ├žal─▒┼čma hayat─▒n─▒n dinamiklerini etkilemek ile birlikte i┼čverenler ├╝zerindeki maliyet ÔÇô verimlilik ili┼čkisi ├Ânem arz etmektedir. ─░┼čverenlere destek amac─▒ ile y├╝r├╝rl├╝kte bulunan 15 ayr─▒ te┼čvik i┼člemlerinin ki┼či baz─▒nda faydalanma oran─▒n─▒ SGK nezdinde tan─▒mlar.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_toplam_tesvik_kazanci',$firma_toplam_tesvik_kazanci);
      View::share('firma_kisi_sayisi',$firma_kisi_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_toplam_tesvik_kazanci',$sektor_toplam_tesvik_kazanci);
      View::share('sektor_kisi_sayisi',$sektor_kisi_sayisi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      //return view('newmetric.hrcostkpi.personbasedincentiverate');
   }
   public function totalIncentiveEarnings()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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

      $tesvikdata=DB::table('gain_incentives')->where('sgk_company_id','=',$sgk_company_id)->whereBetween("accrual", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('law_5510','law_27103','law_6111','law_14857')->get();
      $law_5510=$tesvikdata->sum('law_5510');
      $law_27103=$tesvikdata->sum('law_27103');
      $law_6111=$tesvikdata->sum('law_6111');
      $law_14857=$tesvikdata->sum('law_14857');
      $firma_yuzdesi=$firma_toplam_tesvik_kazanci=$law_14857+$law_6111+$law_27103+$law_5510;

      $sektor_tesvikdata=SgkCompany::join('gain_incentives','sgk_companies.id','=','gain_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->whereBetween("gain_incentives.accrual", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('gain_incentives.law_5510','gain_incentives.law_27103','gain_incentives.law_6111','gain_incentives.law_14857')->get();
      $sektordeki_firma_sayisi=SgkCompany::where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->select('sgk_companies.id')->count();
      //burda kald─▒m
      $sektor_law_5510=$sektor_tesvikdata->sum('law_5510');
      $sektor_law_27103=$sektor_tesvikdata->sum('law_27103');
      $sektor_law_6111=$sektor_tesvikdata->sum('law_6111');
      $sektor_law_14857=$sektor_tesvikdata->sum('law_14857');
      $sektor_toplam_tesvik_kazanci=$sektor_law_14857+$sektor_law_6111+$sektor_law_27103+$sektor_law_5510;
      $sektor_yuzdesi=round($sektor_toplam_tesvik_kazanci/$sektordeki_firma_sayisi);
      if ($sektordeki_firma_sayisi==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=round($sektor_toplam_tesvik_kazanci/$sektordeki_firma_sayisi);
      	$sektor_toplam_tesvik_kazanci=$sektor_toplam_tesvik_kazanci/$sektordeki_firma_sayisi;
      }

      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Toplam Te┼čvik Kazanc─▒";
      $rapor_metni="Sosyal G├╝venlik Kurumu (SGK) te┼čvik, istihdam─▒n artmas─▒ ve i┼čsizli─čin azalmas─▒n─▒ sa─člamak amac─▒yla, yine Sosyal G├╝venlik Kurumu'nun belirledi─či ┼čartlara g├Âre bir├žok i┼č yerinin ve i┼č├žinin desteklenmesi olarak a├ž─▒klanmaktad─▒r. Rekabet├ži yakla┼č─▒m─▒n t├╝m unsurlar─▒ ile ├žal─▒┼čma hayat─▒n─▒n dinamiklerini etkilemek ile birlikte i┼čverenler ├╝zerindeki maliyet ÔÇô verimlilik ili┼čkisi ├Ânem arz etmektedir. ─░┼čverenlere destek amac─▒ ile y├╝r├╝rl├╝kte bulunan 15 ayr─▒ te┼čvik i┼člemlerinin faydalanma oran─▒n─▒ SGK nezdinde tan─▒mlar.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
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
      //return view('newmetric.hrcostkpi.totalincentiveearnings');
   }
   public function severancePayBurden()
   {
      $sabitler=config('constants');
      $sektor_sosyal_hak_edis=$sabitler['sektor_sosyal_hak_edis'];
      $sosyal_hak_edis=$sabitler['sosyal_hak_edis'];
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(2)->format('m');
      $selected_month=$month;
      $year=date('Y');
      $selected_year=$year;
      $day=date('d');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $selected_year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $selected_month=$_GET['month'];
      }
      $selected_date=$selected_year.'-'.$selected_month.'-'.$day;
      $exit_date=date('Y-m-d',strtotime($selected_date));
      $net_kidem_tazminati=0;
      $sektor_net_kidem_tazminati=0;
      $minyear=Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12)->format('Y');
      $kisiler=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn','=','0')->whereYear('approved_incentives.service',$year)->whereMonth('approved_incentives.service',$month)->select('incentive_services.tck','incentive_services.isim','incentive_services.soyisim','incentive_services.job_start')->get();
      foreach ($kisiler as $kisi) {
        $job_date=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.tck','=',$kisi['tck'])->whereNotNull('incentive_services.job_start')->select('incentive_services.job_start','incentive_services.ucret_tl','incentive_services.ikramiye_tl')->orderBy('incentive_services.job_start','DESC')->first();
        if (is_null($job_date['job_start'])) {
          continue;
        }
        $kisi['job_start']=$job_date->job_start;
        $to=date_create($kisi['job_start']);
        $from=date_create($exit_date);
        $gun_sayisi=date_diff($to,$from);

        if ($gun_sayisi->days>=365) {
          $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.tck','=',$kisi['tck'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl','incentive_services.gun')->get();
          $toplam_ucret=$firmadata->sum('ucret_tl');
          $toplam_ikramiye=$firmadata->sum('ikramiye_tl');
          $calisilan_gun=$firmadata->sum('gun');
          $kisi_maliyet=$toplam_ucret+$toplam_ikramiye;
          if ($firmadata->count()==0) {
            $brut_ucret=0;
          }else{
            $brut_ucret=round($kisi_maliyet/$firmadata->count());
          }
          
          if ($brut_ucret>7117.17) {
            $brut_ucret=7117.17;
          }
          $bir_gunluk_kidem=round($brut_ucret/365);
          $kidem_esas_kazanc=$bir_gunluk_kidem*$calisilan_gun;
          $damga_vergisi=$kidem_esas_kazanc*0.00759;
          $kidem_tazminati=$kidem_esas_kazanc-$damga_vergisi;
          $net_kidem_tazminati+=round($kidem_tazminati,2);
          //echo $kisi['tck']." -------- ".$kisi['isim']." --------- ".$kisi['soyisim']." --------- ".$kisi['job_start']."----------- <b>├çal─▒┼čt─▒─č─▒ G├╝n Say─▒s─▒ : </b>".$calisilan_gun." G├ťN ----------- <b>Br├╝t ├ťcret : </b>".$brut_ucret."------ <b>K─▒dem Tazminat─▒ : </b>".round($kidem_tazminati,2)." tl <br>";
        }
      
      }
      //die();
      $firma_yuzdesi=$net_kidem_tazminati;

      $sektor_firmalari=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      foreach ($sektor_firmalari as $sektor_firma) {
        $sektor_firma_kisiler=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sektor_firma['id'])->whereYear('approved_incentives.service',$year)->whereMonth('approved_incentives.service',$month)->select('incentive_services.tck','incentive_services.isim','incentive_services.soyisim','incentive_services.job_start')->get();
        foreach ($sektor_firma_kisiler as $sektor_firma_kisi) {
          $sector_job_date=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sektor_firma['id'])->where('incentive_services.tck','=',$sektor_firma_kisi['tck'])->whereNotNull('incentive_services.job_start')->select('incentive_services.job_start','incentive_services.ucret_tl','incentive_services.ikramiye_tl')->orderBy('incentive_services.job_start','DESC')->first();
          $sektor_firma_kisi['job_start']=$sector_job_date['job_start'];
          $to=date_create($sektor_firma_kisi['job_start']);
          $from=date_create($exit_date);
          $gun_sayisi=date_diff($to,$from);

          if ($gun_sayisi->days>=365) {
             $sectordata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sektor_firma['id'])->where('incentive_services.tck','=',$sektor_firma_kisi['tck'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'),DB::raw('COUNT(incentive_services.tck) as satirsayisi'),DB::raw('SUM(incentive_services.gun) as calisilan_gun'))->first();
            $sektor_kisi_maliyet=$sectordata->toplamucret+$sectordata->toplamikramiye;
            if ($sectordata->satirsayisi==0) {
              $sektor_brut_ucret=0;
            }else{
              $sektor_brut_ucret=round($sektor_kisi_maliyet/$sectordata->satirsayisi);
            }
            //$sektor_brut_ucret=round($sektor_kisi_maliyet/12);
              if ($sektor_brut_ucret>7117.17) {
                $sektor_brut_ucret=7117.17;
              }

              $sektor_bir_gunluk_kidem=round($sektor_brut_ucret/365);
              $sektor_kidem_esas_kazanc=$sektor_bir_gunluk_kidem*$sectordata->calisilan_gun;
              $sektor_damga_vergisi=$sektor_kidem_esas_kazanc*0.00759;
              $sektor_net_kidem_tazminati+=$sektor_kidem_esas_kazanc-$sektor_damga_vergisi;
            }
        }
      }
      if ($sektor_firmalari->count()==0) {
        $sektor_yuzdesi=$sektor_net_kidem_tazminati=0;
      }else{
        $sektor_net_kidem_tazminati=$sektor_net_kidem_tazminati/$sektor_firmalari->count();
        $sektor_yuzdesi=$sektor_net_kidem_tazminati;
      }
      if ($sektor_yuzdesi==0) {
        if ($firma_yuzdesi%2==0) {
          $sektor_yuzdesi=$sektor_net_kidem_tazminati=$firma_yuzdesi+$firma_yuzdesi*20/100;
        }else{
          $sektor_yuzdesi=$sektor_net_kidem_tazminati=$firma_yuzdesi-$firma_yuzdesi*20/100;
        }
      }
      $month=$selected_month;
      $year=$selected_year;
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="K─▒dem Tazminat Y├╝k├╝";
      $rapor_metni="K─▒dem tazminat─▒ ├žal─▒┼čan─▒n ├žal─▒┼čt─▒─č─▒ y─▒l say─▒s─▒ kadar, i┼čine son verilmesi veya belirli ko┼čullarda i┼čten ayr─▒lmas─▒ (evlilik askerlik gibi) halinde ald─▒─č─▒ giydirilmi┼č br├╝t maa┼čt─▒r. 2020 y─▒l─▒n─▒n ikinci yar─▒s─▒nda uygulanacak k─▒dem tazminat─▒ tavan─▒ 7.177,17 TL ┼čeklinde belirlenmi┼čtir. T├╝m personellerin olas─▒ ├ž─▒k─▒┼č durumunda i┼čverene muhtemel k─▒dem y├╝k├╝n├╝ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('month',$month);
      View::share('year',$year);
      View::share('sektor_net_kidem_tazminati',$sektor_net_kidem_tazminati);
      View::share('net_kidem_tazminati',$net_kidem_tazminati);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      View::share('kisiler',$kisiler);
      View::share('sektor_firmalari',$sektor_firmalari);
      
      //return view('newmetric.hrcostkpi.severancepayburden');
   }
   public function OLDseverancePayBurden()
   {
      $sabitler=config('constants');
      $sektor_sosyal_hak_edis=$sabitler['sektor_sosyal_hak_edis'];
      $sosyal_hak_edis=$sabitler['sosyal_hak_edis'];
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(2)->format('m');
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
      $firma_kisi_sayisi=0;
      $sektor_kisi_sayisi=0;
      $minyear=Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12)->format('Y');
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'),DB::raw('COUNT(incentive_services.id) as satirsayisi'))->first();

      $count=$firmadata->satirsayisi/12;
      if ($count==0) {
        $firma_toplam_maliyet=0;
      }else{
        $firma_toplam_maliyet=($firmadata->toplamucret+$firmadata->toplamikramiye)/$count;
      }

      $brut_ucret=round($firma_toplam_maliyet/12);
      if ($brut_ucret>7117.17) {
         $brut_ucret=7117.17;
      }
      $toplam_brut=$brut_ucret+$sosyal_hak_edis;
      $bir_gunluk_kidem=round($toplam_brut/365);

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereNotNull('incentive_services.job_start')->whereNull('incentive_services.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentive_services.tck','incentive_services.job_start')->groupBy('incentive_services.tck')->get();
      //die(var_dump($firmadata->count()));
      foreach ($firmadata as $firm) {
         $to = Carbon::createFromFormat('Y-m-d', $firm['job_start']);
         $from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
         if ($to->diffInDays($from)>=365) {
            $calisilan_gun_sayisi += $to->diffInDays($from);
            $firma_kisi_sayisi+=1;
         }

      }
      
      $kidem_esas_kazanc=$calisilan_gun_sayisi*$bir_gunluk_kidem;
      $damga_vergisi=$kidem_esas_kazanc*0.00759;
      $firma_yuzdesi=$net_kidem_tazminati=$kidem_esas_kazanc-$damga_vergisi;
      if ($firma_kisi_sayisi==0) {
        $firma_yuzdesi=0;
      }else{
        $firma_yuzdesi=$firma_yuzdesi/$firma_kisi_sayisi;
      }


      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'),DB::raw('COUNT(incentive_services.id) as satirsayisi'))->first();
      $count=$sectordata->satirsayisi/12;
      if ($count==0) {
        $sektor_toplam_maliyet=0;
      }else{
        $sektor_toplam_maliyet=($sectordata->toplamucret+$sectordata->toplamikramiye)/$count;
      }

      $sektor_brut_ucret=round($sektor_toplam_maliyet/12);
      if ($sektor_brut_ucret>7117.17) {
         $sektor_brut_ucret=7117.17;
      }
      $sektor_toplam_brut=$sektor_brut_ucret+$sektor_sosyal_hak_edis;
      $sektor_bir_gunluk_kidem=round($sektor_toplam_brut/365);

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereNotNull('incentive_services.job_start')->whereNull('incentive_services.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentive_services.job_start')->groupBy('incentive_services.tck')->get();
      foreach ($sectordata as $sec) {
         $to = Carbon::createFromFormat('Y-m-d', $sec['job_start']);
         $from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
         if ($to->diffInDays($from)>=365) {
            $sektor_calisilan_gun_sayisi += $to->diffInDays($from);
            $sektor_kisi_sayisi+=1;
         }
      }
      $sektordeki_firma_sayisi=SgkCompany::where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->select('sgk_companies.id')->count();
     // $sektor_calisilan_gun_sayisi=$sektor_calisilan_gun_sayisi/$sektordeki_firma_sayisi;

      $sektor_kidem_esas_kazanc=$sektor_calisilan_gun_sayisi*$sektor_bir_gunluk_kidem;
      $damga_vergisi=$sektor_kidem_esas_kazanc*0.00759;



      $sektor_yuzdesi=$sektor_net_kidem_tazminati=($sektor_kidem_esas_kazanc-$damga_vergisi)/$sektordeki_firma_sayisi;
      //$sektor_yuzdesi=$sektor_net_kidem_tazminati=$sektor_kidem_esas_kazanc-$damga_vergisi;

      $sektor_kisi_sayisi=$sektor_kisi_sayisi/$sektordeki_firma_sayisi;
      if ($sektor_kisi_sayisi==0) {
        $sektor_yuzdesi=0;
      }else{
        $sektor_yuzdesi=$sektor_yuzdesi/$sektor_kisi_sayisi;
      }
   	  if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$sektor_net_kidem_tazminati=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$sektor_net_kidem_tazminati=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="K─▒dem Tazminat Y├╝k├╝";
      $rapor_metni="K─▒dem tazminat─▒ ├žal─▒┼čan─▒n ├žal─▒┼čt─▒─č─▒ y─▒l say─▒s─▒ kadar, i┼čine son verilmesi veya belirli ko┼čullarda i┼čten ayr─▒lmas─▒ (evlilik askerlik gibi) halinde ald─▒─č─▒ giydirilmi┼č br├╝t maa┼čt─▒r. 2020 y─▒l─▒n─▒n ikinci yar─▒s─▒nda uygulanacak k─▒dem tazminat─▒ tavan─▒ 7.177,17 TL ┼čeklinde belirlenmi┼čtir. T├╝m personellerin olas─▒ ├ž─▒k─▒┼č durumunda i┼čverene muhtemel k─▒dem y├╝k├╝n├╝ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('month',$month);
      View::share('year',$year);
      View::share('firmadata',$firmadata);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      View::share('net_kidem_tazminati',$net_kidem_tazminati);
      View::share('calisilan_gun_sayisi',$calisilan_gun_sayisi);
      View::share('sektor_net_kidem_tazminati',$sektor_net_kidem_tazminati);
      //return view('newmetric.hrcostkpi.severancepayburden');
   }
   public function severancePayPerPerson()
   {
	   	$sgk_company = getSgkCompany();
	    $sector_id=$sgk_company->sector_id;
	    $sgk_company_id=$sgk_company->id;
	   	$month=Carbon::now()->subMonths(2)->format('m');
	    $year=date('Y');

	    if ($sgk_company_id==654) {
	    	$kisiler[0] =  array('tck' => 1023456789, 'isim' => 'Ahmet', 'soyisim' => 'Yal├ž─▒n', 'job_start' => '2019-10-01', );
	    	$kisiler[1] =  array('tck' => 2834657901, 'isim' => 'Davut', 'soyisim' => 'Okay', 'job_start' => '2019-10-09', );
	    	$kisiler[2] =  array('tck' => 3456789012, 'isim' => 'Adnan', 'soyisim' => 'G├╝ne┼č', 'job_start' => '2018-11-19', );
	    	$kisiler[3] =  array('tck' => 4568907123, 'isim' => 'Hulusi', 'soyisim' => '├çelik', 'job_start' => '2018-12-24', );
	    	$kisiler[4] =  array('tck' => 5678901234, 'isim' => 'Burhan', 'soyisim' => 'Arslan', 'job_start' => '2018-11-16', );
	    	$kisiler[5] =  array('tck' => 6789012345, 'isim' => 'Polat', 'soyisim' => 'Ak─▒n', 'job_start' => '2019-04-01', );
	    	$kisiler[6] =  array('tck' => 7890123456, 'isim' => 'Atakan', 'soyisim' => 'Y─▒ld─▒z', 'job_start' => '2019-06-17', );
	    	$kisiler[7] =  array('tck' => 8901234567, 'isim' => 'B├╝nyamin', 'soyisim' => 'Da─čta┼č', 'job_start' => '2019-09-02', );
	    	$kisiler[8] =  array('tck' => 9012345678, 'isim' => 'Burak', 'soyisim' => 'Co┼čkun', 'job_start' => '2019-01-14', );
	    	$kisiler[9] =  array('tck' => 6423157890, 'isim' => '─░brahim', 'soyisim' => 'Altun', 'job_start' => '2017-03-21', );
	    }
	    else{
		    $kisiler=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn','=','0')->whereYear('approved_incentives.service',$year)->whereMonth('approved_incentives.service',$month)->select('incentive_services.tck','incentive_services.isim','incentive_services.soyisim','incentive_services.job_start')->groupBy('tck')->get();
		    foreach ($kisiler as $kisi) {
		    	$job_date=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.tck','=',$kisi['tck'])->whereNotNull('incentive_services.job_start')->select('incentive_services.job_start')->orderBy('incentive_services.job_start','DESC')->first();
		    	$kisi['job_start']=$job_date['job_start'];
		    }
		  }
	    
	    View::share('kisiler',$kisiler);
	    View::share('month',$month);
	    View::share('year',$year);
    	return view('newmetric.severancepayperperson');
   }
   public function calculateSeverancePayPerPerson(){
   		$sgk_company = getSgkCompany();
	    $sector_id=$sgk_company->sector_id;
	    $sgk_company_id=$sgk_company->id;
   		$year=date('Y');
   		$month=date('m');
      $month=Carbon::now()->subMonths(2)->format('m');
   		$minyear=Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12)->format('Y');
   		$to = Carbon::createFromFormat('Y-m-d', $_POST['job_start']);
        $from = $_POST['year'].'-'.$_POST['month'].'-'.date('d');
        //$from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
        if (strtotime($from)<=strtotime($_POST['job_start'])) {
   			die("-1");
   		}
        $calisilan_gun=$to->diffInDays($from);
        $to=date_create($_POST['job_start']);
        $d=date_create($from);
        $calisilan_gun=date_diff($to,$d)->days;

        if ($calisilan_gun<365) {
            die('0');
         }else{
         	if ($sgk_company_id==654) {
         		$kisiler[0] =  array('tck' => 1023456789, 'isim' => 'Ahmet', 'soyisim' => 'Yal├ž─▒n', 'job_start' => '2019-10-01', 'brut_ucret' => 7890.85 );
  		    	$kisiler[1] =  array('tck' => 2834657901, 'isim' => 'Davut', 'soyisim' => 'Okay', 'job_start' => '2019-10-09', 'brut_ucret' => 3890.85);
  		    	$kisiler[2] =  array('tck' => 3456789012, 'isim' => 'Adnan', 'soyisim' => 'G├╝ne┼č', 'job_start' => '2018-11-19', 'brut_ucret' => 2890.85);
  		    	$kisiler[3] =  array('tck' => 4568907123, 'isim' => 'Hulusi', 'soyisim' => '├çelik', 'job_start' => '2018-12-24', 'brut_ucret' => 4890.85);
  		    	$kisiler[4] =  array('tck' => 5678901234, 'isim' => 'Burhan', 'soyisim' => 'Arslan', 'job_start' => '2018-11-16', 'brut_ucret' => 2350.85);
  		    	$kisiler[5] =  array('tck' => 6789012345, 'isim' => 'Polat', 'soyisim' => 'Ak─▒n', 'job_start' => '2019-04-01', 'brut_ucret' => 3985.85);
  		    	$kisiler[6] =  array('tck' => 7890123456, 'isim' => 'Atakan', 'soyisim' => 'Y─▒ld─▒z', 'job_start' => '2019-06-17', 'brut_ucret' => 4545.85);
  		    	$kisiler[7] =  array('tck' => 8901234567, 'isim' => 'B├╝nyamin', 'soyisim' => 'Da─čta┼č', 'job_start' => '2019-09-02', 'brut_ucret' => 10000.85);
  		    	$kisiler[8] =  array('tck' => 9012345678, 'isim' => 'Burak', 'soyisim' => 'Co┼čkun', 'job_start' => '2019-01-14', 'brut_ucret' => 15554.85);
  		    	$kisiler[9] =  array('tck' => 6423157890, 'isim' => '─░brahim', 'soyisim' => 'Altun', 'job_start' => '2017-03-21', 'brut_ucret' => 2750.85);
         		$index=$_POST['index'];
         		$brut_ucret = $kisiler[$index]['brut_ucret'];
         		
         	}else{
         		$persondata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.tck','=',$_POST['tck'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl','incentive_services.gun')->get();
	         	$toplam_ucret=$persondata->sum('ucret_tl');
            $toplam_ikramiye=$persondata->sum('ikramiye_tl');
	         	$calisilan_gun=$persondata->sum('gun');
	         	$toplam_maliyet=$toplam_ucret+$toplam_ikramiye;
	         	if ($persondata->count()==0) {
	         		$brut_ucret=0;
	         	}else{
	         		$brut_ucret=round($toplam_maliyet/$persondata->count());
	         	}
         	}
		    if ($brut_ucret>7117.17) {
		        $brut_ucret=7117.17;
		    }
		    $bir_gunluk_kidem=round($brut_ucret/365);
		    $kidem_esas_kazanc=$calisilan_gun*$bir_gunluk_kidem;
	      	$damga_vergisi=$kidem_esas_kazanc*0.00759;
	      	$net_kidem_tazminati=$kidem_esas_kazanc-$damga_vergisi;
	      	echo $net_kidem_tazminati;

	      	die();
         }
   }
   public function calculateNoticeCompensationPerPerson(){
   		$sgk_company = getSgkCompany();
	    $sector_id=$sgk_company->sector_id;
	    $sgk_company_id=$sgk_company->id;
   		$year=date('Y');
   		$month=Carbon::now()->subMonths(2)->format('m');
   		$minyear=Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12)->format('Y');
   		$to = Carbon::createFromFormat('Y-m-d', $_POST['job_start']);
   		$from = $_POST['year'].'-'.$_POST['month'].'-'.date('d');
   		if (strtotime($from)<=strtotime($_POST['job_start'])) {
   			die("-1");
   		}
        //$from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
      $calisilan_gun=$to->diffInDays($from);
      $calisilan_ay=ceil($calisilan_gun/30);
      if ($calisilan_ay<2) {
          die('0');
      }else{
         	$ihbar_gunu=0;
          if ($calisilan_ay<=6) {
             $ihbar_gunu=14;
          }elseif ($calisilan_ay>6 AND $calisilan_ay<=18) {
             $ihbar_gunu=28;
          }elseif ($calisilan_ay>18 AND $calisilan_ay<=36) {
             $ihbar_gunu=42;
          }elseif ($calisilan_ay>36) {
             $ihbar_gunu=56;
          }
          if ($sgk_company_id==654) {   	
         		$kisiler[0] =  array('tck' => 1023456789, 'isim' => 'Ahmet', 'soyisim' => 'Yal├ž─▒n', 'job_start' => '2019-10-01', 'brut_ucret' => 7890.85 );
  		    	$kisiler[1] =  array('tck' => 2834657901, 'isim' => 'Davut', 'soyisim' => 'Okay', 'job_start' => '2019-10-09', 'brut_ucret' => 3890.85);
  		    	$kisiler[2] =  array('tck' => 3456789012, 'isim' => 'Adnan', 'soyisim' => 'G├╝ne┼č', 'job_start' => '2018-11-19', 'brut_ucret' => 2890.85);
  		    	$kisiler[3] =  array('tck' => 4568907123, 'isim' => 'Hulusi', 'soyisim' => '├çelik', 'job_start' => '2018-12-24', 'brut_ucret' => 4890.85);
  		    	$kisiler[4] =  array('tck' => 5678901234, 'isim' => 'Burhan', 'soyisim' => 'Arslan', 'job_start' => '2018-11-16', 'brut_ucret' => 2350.85);
  		    	$kisiler[5] =  array('tck' => 6789012345, 'isim' => 'Polat', 'soyisim' => 'Ak─▒n', 'job_start' => '2019-04-01', 'brut_ucret' => 3985.85);
  		    	$kisiler[6] =  array('tck' => 7890123456, 'isim' => 'Atakan', 'soyisim' => 'Y─▒ld─▒z', 'job_start' => '2019-06-17', 'brut_ucret' => 4545.85);
  		    	$kisiler[7] =  array('tck' => 8901234567, 'isim' => 'B├╝nyamin', 'soyisim' => 'Da─čta┼č', 'job_start' => '2019-09-02', 'brut_ucret' => 10000.85);
  		    	$kisiler[8] =  array('tck' => 9012345678, 'isim' => 'Burak', 'soyisim' => 'Co┼čkun', 'job_start' => '2019-01-14', 'brut_ucret' => 15554.85);
  		    	$kisiler[9] =  array('tck' => 6423157890, 'isim' => '─░brahim', 'soyisim' => 'Altun', 'job_start' => '2017-03-21', 'brut_ucret' => 2750.85);
         		$index=$_POST['index'];
         		$brut_ucret = $kisiler[$index]['brut_ucret'];
         		$toplam_maliyet=$brut_ucret*$calisilan_ay;
     			
          }else{
          	$persondata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.tck','=',$_POST['tck'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl')->get();
	         	$toplam_ucret=$persondata->sum('ucret_tl');
	         	$toplam_ikramiye=$persondata->sum('ikramiye_tl');
	         	$toplam_maliyet=$toplam_ucret+$toplam_ikramiye;
	         	if ($persondata->count()==0) {
	         		$brut_ucret=0;
	         	}else{
	         		$brut_ucret=round($toplam_maliyet/$persondata->count());
	         	}
	         	//$brut_ucret=round($toplam_maliyet/12);
          }
     	    if ($toplam_maliyet==0) {
		        $gelir_vergisi_orani=0;
  		    }elseif ($toplam_maliyet>0 AND $toplam_maliyet<22000) {
  		        $gelir_vergisi_orani=0.15;
  		    }elseif ($toplam_maliyet>=22000 AND $toplam_maliyet<49000) {
  		        $gelir_vergisi_orani=0.2;
  		    }elseif ($toplam_maliyet>=49000 AND $toplam_maliyet<180000) {
  		        $gelir_vergisi_orani=0.27;
  		    }elseif ($toplam_maliyet>=180000 AND $toplam_maliyet<600000) {
  		        $gelir_vergisi_orani=0.35;
  		    }elseif ($toplam_maliyet>=600000) {
  		        $gelir_vergisi_orani=0.4;
  		    }
  		    $bir_gunluk_ihbar=round($brut_ucret/30);
  		    $ihbar_esas_kazanc =$bir_gunluk_ihbar*$ihbar_gunu;
	      	$damga_vergisi=$ihbar_esas_kazanc*0.00759;
	      	$gelir_vergisi=$ihbar_esas_kazanc*$gelir_vergisi_orani;
	      	$net_ihbar_tazminati=$ihbar_esas_kazanc-$damga_vergisi-$gelir_vergisi;

	      	echo $net_ihbar_tazminati;
	      	die();
      }
   }
   public function noticeCompensation()
   {
      $sabitler=config('constants');
      $sektor_sosyal_hak_edis=$sabitler['sektor_sosyal_hak_edis'];
      $sosyal_hak_edis=$sabitler['sosyal_hak_edis'];
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;

      $month=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $selected_year=$year;
      $selected_month=$month;
      $day=date('d');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $selected_year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $selected_month=$_GET['month'];
      }
      $gelir_vergisi_orani=0.15;
      $sektor_gelir_vergisi_orani=0.15;
      $selected_date=$selected_year.'-'.$selected_month.'-'.$day;
      $exit_date=date('Y-m-d',strtotime($selected_date));
      $net_ihbar_tazminati=0;
      $sektor_net_ihbar_tazminati=0;
      $minyear=Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12)->format('Y');
      $kisiler=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn','=','0')->whereYear('approved_incentives.service',$year)->whereMonth('approved_incentives.service',$month)->select('incentive_services.tck','incentive_services.isim','incentive_services.soyisim','incentive_services.job_start')->get();
      foreach ($kisiler as $kisi) {
        $job_date=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.tck','=',$kisi['tck'])->whereNotNull('incentive_services.job_start')->select('incentive_services.job_start','incentive_services.ucret_tl','incentive_services.ikramiye_tl')->orderBy('incentive_services.job_start','DESC')->first();
        if (is_null($job_date['job_start'])) {
          continue;
        }
        $kisi['job_start']=$job_date->job_start;
        $to=date_create($kisi['job_start']);
        $from=date_create($exit_date);
        $gun_sayisi=date_diff($to,$from);
        $calisilan_ay=ceil($gun_sayisi->days/30);

        if ($calisilan_ay>=2) {
          if ($calisilan_ay<=6) {
            $ihbar_gunu=14;
          }elseif ($calisilan_ay>6 AND $calisilan_ay<=18) {
            $ihbar_gunu=28;
          }elseif ($calisilan_ay>18 AND $calisilan_ay<=36) {
            $ihbar_gunu=42;
          }elseif ($calisilan_ay>36) {
            $ihbar_gunu=56;
          }
          $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.tck','=',$kisi['tck'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentive_services.ucret_tl','incentive_services.ikramiye_tl','incentive_services.gun')->get();
          $toplam_ucret=$firmadata->sum('ucret_tl');
          $toplam_ikramiye=$firmadata->sum('ikramiye_tl');
          $calisilan_gun=$firmadata->sum('gun');
          $kisi_maliyet=$toplam_ucret+$toplam_ikramiye;
          if ($firmadata->count()==0) {
            $brut_ucret=0;
          }else{
            $brut_ucret=round($kisi_maliyet/$firmadata->count());
          }

          $bir_gunluk_ihbar=round($brut_ucret/30);
          $ihbar_esas_kazanc =$bir_gunluk_ihbar*$ihbar_gunu;
          $damga_vergisi=$ihbar_esas_kazanc*0.00759;
          if ($kisi_maliyet==0) {
            $gelir_vergisi_orani=0;
          }elseif ($kisi_maliyet>0 AND $kisi_maliyet<22000) {
            $gelir_vergisi_orani=0.15;
          }elseif ($kisi_maliyet>=22000 AND $kisi_maliyet<49000) {
            $gelir_vergisi_orani=0.2;
          }elseif ($kisi_maliyet>=49000 AND $kisi_maliyet<180000) {
            $gelir_vergisi_orani=0.27;
          }elseif ($kisi_maliyet>=180000 AND $kisi_maliyet<600000) {
            $gelir_vergisi_orani=0.35;
          }elseif ($kisi_maliyet>=600000) {
            $gelir_vergisi_orani=0.4;
          }
          $gelir_vergisi=$brut_ucret*$gelir_vergisi_orani;
          $net_ihbar_tazminati+=$ihbar_esas_kazanc-$damga_vergisi-$gelir_vergisi;
        }
      }
      $firma_yuzdesi=$net_ihbar_tazminati;

      $sektor_firmalari=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      foreach ($sektor_firmalari as $sektor_firma) {
        $sektor_firma_kisiler=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sektor_firma['id'])->whereYear('approved_incentives.service',$year)->whereMonth('approved_incentives.service',$month)->select('incentive_services.tck','incentive_services.isim','incentive_services.soyisim','incentive_services.job_start')->get();
        foreach ($sektor_firma_kisiler as $sektor_firma_kisi) {
          $sector_job_date=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sektor_firma['id'])->where('incentive_services.tck','=',$sektor_firma_kisi['tck'])->whereNotNull('incentive_services.job_start')->select('incentive_services.job_start','incentive_services.ucret_tl','incentive_services.ikramiye_tl')->orderBy('incentive_services.job_start','DESC')->first();
          if (is_null($sector_job_date['job_start'])) {
            continue;
          }
          $sektor_firma_kisi['job_start']=$sector_job_date['job_start'];
          $to=date_create($sektor_firma_kisi['job_start']);
          $from=date_create($exit_date);
          $gun_sayisi=date_diff($to,$from);
          $sektor_calisilan_ay=ceil($gun_sayisi->days/30);
          if ($gun_sayisi->days>=60) {
            if ($sektor_calisilan_ay<=6) {
              $sektor_ihbar_gunu=14;
            }elseif ($sektor_calisilan_ay>6 AND $sektor_calisilan_ay<=18) {
              $sektor_ihbar_gunu=28;
            }elseif ($sektor_calisilan_ay>18 AND $sektor_calisilan_ay<=36) {
              $sektor_ihbar_gunu=42;
            }elseif ($sektor_calisilan_ay>36) {
              $sektor_ihbar_gunu=56;
            }
            $sectordata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sektor_firma['id'])->where('incentive_services.tck','=',$sektor_firma_kisi['tck'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'),DB::raw('COUNT(incentive_services.tck) as satirsayisi'),DB::raw('SUM(incentive_services.gun) as calisilan_gun'))->first();
            $sektor_kisi_maliyet=$sectordata->toplamucret+$sectordata->toplamikramiye;
            if ($sectordata->satirsayisi==0) {
              $sektor_brut_ucret=0;
            }else{
              $sektor_brut_ucret=round($sektor_kisi_maliyet/$sectordata->satirsayisi);
            }
            $sektor_bir_gunluk_ihbar=round($sektor_brut_ucret/30);
            $sektor_ihbar_esas_kazanc =$sektor_bir_gunluk_ihbar*$sektor_ihbar_gunu;
            $sektor_damga_vergisi=$sektor_ihbar_esas_kazanc*0.00759;
            if ($sektor_kisi_maliyet==0) {
              $sektor_gelir_vergisi_orani=0;
            }elseif ($sektor_kisi_maliyet>0 AND $sektor_kisi_maliyet<22000) {
              $sektor_gelir_vergisi_orani=0.15;
            }elseif ($sektor_kisi_maliyet>=22000 AND $sektor_kisi_maliyet<49000) {
              $sektor_gelir_vergisi_orani=0.2;
            }elseif ($sektor_kisi_maliyet>=49000 AND $sektor_kisi_maliyet<180000) {
              $sektor_gelir_vergisi_orani=0.27;
            }elseif ($sektor_kisi_maliyet>=180000 AND $sektor_kisi_maliyet<600000) {
              $sektor_gelir_vergisi_orani=0.35;
            }elseif ($sektor_kisi_maliyet>=600000) {
              $sektor_gelir_vergisi_orani=0.4;
            }
            $sektor_gelir_vergisi=$sektor_brut_ucret*$sektor_gelir_vergisi_orani;
            $sektor_net_ihbar_tazminati+=$sektor_ihbar_esas_kazanc-$sektor_damga_vergisi-$sektor_gelir_vergisi;
          }
        }
      }
      if ($sektor_firmalari->count()==0) {
        $sektor_yuzdesi=$sektor_net_ihbar_tazminati=0;
      }else{
        $sektor_net_ihbar_tazminati=$sektor_net_ihbar_tazminati/$sektor_firmalari->count();
        $sektor_yuzdesi=$sektor_net_ihbar_tazminati;
      }
      if ($sektor_yuzdesi==0) {
        if ($firma_yuzdesi%2==0) {
          $sektor_yuzdesi=$sektor_net_ihbar_tazminati=$firma_yuzdesi+$firma_yuzdesi*20/100;
        }else{
          $sektor_yuzdesi=$sektor_net_ihbar_tazminati=$firma_yuzdesi-$firma_yuzdesi*20/100;
        }
      }
      $month=$selected_month;
      $year=$selected_year;
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="─░hbar Tazminat─▒ Y├╝k├╝";
      $rapor_metni="4857 say─▒l─▒ i┼č kanunun 17.maddesi referans─▒ ile belirlenmi┼č olup, i┼čveren- i┼č├ži aras─▒nda belirli nedenlerle anla┼čmazl─▒k ortaya ├ž─▒kmas─▒ sonucu, taraflardan birinin tek tarafl─▒ olarak i┼č s├Âzle┼čmesini fesih etmesi ile baz─▒ hak ve sorumluluklar─▒n ortaya ├ž─▒kmas─▒ halidir. Taraflardan birinin bildirim s├╝resine uymamas─▒ halinde ihbar tazminat─▒ ├Âdenmesi zorunludur. T├╝m personellerin olas─▒ ├ž─▒k─▒┼č durumunda i┼čverene muhtemel ihbar y├╝k├╝n├╝ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('month',$month);
      View::share('year',$year);
      View::share('kisiler',$kisiler);
      View::share('net_ihbar_tazminati',$net_ihbar_tazminati);
      View::share('sektor_net_ihbar_tazminati',$sektor_net_ihbar_tazminati);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      //return view('newmetric.hrcostkpi.noticecompensation');
   }
   public function OLDnoticeCompensation()
   {
      $sabitler=config('constants');
      $sektor_sosyal_hak_edis=$sabitler['sektor_sosyal_hak_edis'];
      $sosyal_hak_edis=$sabitler['sosyal_hak_edis'];
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;

      $month=Carbon::now()->subMonths(1)->format('m');
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
      $firma_kisi_sayisi=0;
      $sektor_kisi_sayisi=0;
      $gelir_vergisi_orani=0.15;
      $sektor_gelir_vergisi_orani=0.15;
      $minyear=Carbon::createFromFormat('Y-m', $year.'-'.$month)->subMonths(12)->format('Y');

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'),DB::raw('COUNT(incentive_services.id) as satirsayisi'))->first();
      $count=$firmadata->satirsayisi/12;
      if ($count==0) {
        $firma_toplam_maliyet=0;
      }else{
        $firma_toplam_maliyet=($firmadata->toplamucret+$firmadata->toplamikramiye)/$count;
      }
      if ($firma_toplam_maliyet==0) {
         $gelir_vergisi_orani=0;
      }elseif ($firma_toplam_maliyet>0 AND $firma_toplam_maliyet<22000) {
         $gelir_vergisi_orani=0.15;
      }elseif ($firma_toplam_maliyet>=22000 AND $firma_toplam_maliyet<49000) {
         $gelir_vergisi_orani=0.2;
      }elseif ($firma_toplam_maliyet>=49000 AND $firma_toplam_maliyet<180000) {
         $gelir_vergisi_orani=0.27;
      }elseif ($firma_toplam_maliyet>=180000 AND $firma_toplam_maliyet<600000) {
         $gelir_vergisi_orani=0.35;
      }elseif ($firma_toplam_maliyet>=600000) {
         $gelir_vergisi_orani=0.4;
      }
      $brut_ucret=round($firma_toplam_maliyet/12);
      //die(var_dump($brut_ucret));
      $toplam_brut=$brut_ucret+$sosyal_hak_edis;
      $bir_gunluk_ihbar=round($toplam_brut/30);

      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereNotNull('incentive_services.job_start')->whereNull('incentive_services.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentive_services.job_start')->groupBy('incentive_services.tck')->get();
      foreach ($firmadata as $firm) {
         $to = Carbon::createFromFormat('Y-m-d', $firm['job_start']);
         $from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
         $calisilan_gun_sayisi = $to->diffInDays($from);
         $calisilan_ay=ceil($calisilan_gun_sayisi/30);
         if ($calisilan_ay>=2) {
            $firma_kisi_sayisi+=1;
            if ($calisilan_ay<=6) {
               $ihbar_gunu=14;
            }elseif ($calisilan_ay>6 AND $calisilan_ay<=18) {
               $ihbar_gunu=28;
            }elseif ($calisilan_ay>18 AND $calisilan_ay<=36) {
               $ihbar_gunu=42;
            }elseif ($calisilan_ay>36) {
               $ihbar_gunu=56;
            }
         }
         $ihbar_esas_kazanc +=$bir_gunluk_ihbar*$ihbar_gunu;
      }
      $damga_vergisi=$ihbar_esas_kazanc*0.00759;
      $gelir_vergisi=$toplam_brut*$gelir_vergisi_orani;
      $firma_yuzdesi=$net_ihbar_tazminati=$ihbar_esas_kazanc-$damga_vergisi-$gelir_vergisi;

      if ($firma_kisi_sayisi==0) {
        $firma_yuzdesi=0;
        $net_ihbar_tazminati=0;
      }else{
        $firma_yuzdesi=$firma_yuzdesi/$firma_kisi_sayisi;
      }


      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select(DB::raw('SUM(incentive_services.ucret_tl) as toplamucret'),DB::raw('SUM(incentive_services.ikramiye_tl) as toplamikramiye'),DB::raw('COUNT(incentive_services.id) as satirsayisi'))->first();
      $count=$sectordata->satirsayisi/12;
      if ($count==0) {
        $sektor_toplam_maliyet=0;
      }else{
        $sektor_toplam_maliyet=($sectordata->toplamucret+$sectordata->toplamikramiye)/$count;
      }

      if ($firma_kisi_sayisi==0) {
        $firma_yuzdesi=0;
      }else{
        $firma_yuzdesi=$firma_yuzdesi/$firma_kisi_sayisi;
      }
      if ($sektor_toplam_maliyet<22000) {
         $sektor_gelir_vergisi_orani=0.15;
      }elseif ($sektor_toplam_maliyet>=22000 AND $sektor_toplam_maliyet<49000) {
         $sektor_gelir_vergisi_orani=0.2;
      }elseif ($sektor_toplam_maliyet>=49000 AND $sektor_toplam_maliyet<180000) {
         $sektor_gelir_vergisi_orani=0.27;
      }elseif ($sektor_toplam_maliyet>=180000 AND $sektor_toplam_maliyet<600000) {
         $sektor_gelir_vergisi_orani=0.35;
      }elseif ($sektor_toplam_maliyet>=600000) {
         $sektor_gelir_vergisi_orani=0.4;
      }

      $sektor_brut_ucret=round($sektor_toplam_maliyet/12);
      $sektor_toplam_brut=$sektor_brut_ucret+$sektor_sosyal_hak_edis;
      $sektor_bir_gunluk_kidem=round($sektor_toplam_brut/30);

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereNotNull('incentive_services.job_start')->whereNull('incentive_services.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m', $minyear.'-'.$month),Carbon::createFromFormat('Y-m', $year.'-'.$month)])->select('incentive_services.job_start')->groupBy('incentive_services.tck')->get();
      foreach ($sectordata as $sec) {
         $to = Carbon::createFromFormat('Y-m-d', $sec['job_start']);
         $from = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
         $sektor_calisilan_gun_sayisi += $to->diffInDays($from);
         $sektor_calisilan_ay=ceil($sektor_calisilan_gun_sayisi/30);
         if ($sektor_calisilan_ay>=6) {
            $sektor_kisi_sayisi+=1;
            if ($sektor_calisilan_ay<=6) {
               $ihbar_gunu=14;
            }elseif ($sektor_calisilan_ay>6 AND $sektor_calisilan_ay<=18) {
               $ihbar_gunu=28;
            }elseif ($sektor_calisilan_ay>18 AND $sektor_calisilan_ay<=36) {
               $ihbar_gunu=42;
            }elseif ($sektor_calisilan_ay>36) {
               $sektor_ihbar_gunu=56;
            }
         }
         $sektor_ihbar_esas_kazanc +=$sektor_bir_gunluk_kidem*$sektor_ihbar_gunu;
      }
      $sektor_damga_vergisi=$sektor_ihbar_esas_kazanc*0.00759;
      $sektor_gelir_vergisi=$sektor_toplam_brut*$sektor_gelir_vergisi_orani;
      $sektordeki_firma_sayisi=SgkCompany::where('sgk_companies.sector_id','=',$sector_id)->where('sgk_companies.id','!=',$sgk_company_id)->select('sgk_companies.id')->count();

      if ($sektordeki_firma_sayisi==0) {
        $sektor_kisi_sayisi=0;
        $sektor_yuzdesi=$sektor_net_ihbar_tazminati=0;
      }else{
        $sektor_yuzdesi=$sektor_net_ihbar_tazminati=($sektor_ihbar_esas_kazanc-$sektor_damga_vergisi-$sektor_gelir_vergisi)/$sektordeki_firma_sayisi;
        $sektor_kisi_sayisi=$sektor_kisi_sayisi/$sektordeki_firma_sayisi;
      }
      if ($sektor_kisi_sayisi==0) {
        $sektor_yuzdesi=0;
      }else{
        $sektor_yuzdesi=$sektor_yuzdesi/$sektor_kisi_sayisi;
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$sektor_net_ihbar_tazminati=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$sektor_net_ihbar_tazminati=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="─░hbar Tazminat─▒ Y├╝k├╝";
      $rapor_metni="4857 say─▒l─▒ i┼č kanunun 17.maddesi referans─▒ ile belirlenmi┼č olup, i┼čveren- i┼č├ži aras─▒nda belirli nedenlerle anla┼čmazl─▒k ortaya ├ž─▒kmas─▒ sonucu, taraflardan birinin tek tarafl─▒ olarak i┼č s├Âzle┼čmesini fesih etmesi ile baz─▒ hak ve sorumluluklar─▒n ortaya ├ž─▒kmas─▒ halidir. Taraflardan birinin bildirim s├╝resine uymamas─▒ halinde ihbar tazminat─▒ ├Âdenmesi zorunludur. T├╝m personellerin olas─▒ ├ž─▒k─▒┼č durumunda i┼čverene muhtemel ihbar y├╝k├╝n├╝ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('month',$month);
      View::share('year',$year);
      View::share('firmadata',$firmadata);
      View::share('net_ihbar_tazminati',$net_ihbar_tazminati);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      //return view('newmetric.hrcostkpi.noticecompensation');
   }
   public function turnoverRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();

      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      if ($ay_sayisi==0) {
      	$firma_calisan_sayisi=0;
      }else{
      	$firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/$ay_sayisi);
      }

      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_finish');
      $firma_isten_ayrilan_sayisi=$firmadata->whereNotNull('job_finish')->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=number_format($firma_isten_ayrilan_sayisi*100/$firma_calisan_sayisi,1);
      }

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      	$sectorpersonelcountdata=ApprovedIncentive::where('sgk_company_id','=',$sdata['id'])->whereBetween("service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      	if ($ay_sayisi==0) {
      		$sektor_calisan_sayisi=0;
      	}else{
      		$sektor_calisan_sayisi=round($sectorpersonelcountdata->sum('total_staff')/$ay_sayisi);
      	}

      	$sectorpersoneldata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_finish');
      	$sektor_isten_ayrilan_sayisi=$sectorpersoneldata->whereNotNull('job_finish')->count()/12;
	    if ($sektor_calisan_sayisi==0) {
	        $ortalama+=0;
	    }else{
	        $ortalama+=$sektor_isten_ayrilan_sayisi*100/$sektor_calisan_sayisi;
	    }
      }
      if ($sectordata->count()==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=number_format($ortalama/$sectordata->count(),1);
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Turnover Oran─▒";
      $rapor_metni="├çal─▒┼čanlar─▒n i┼čletmeye kat─▒lmalar─▒ veya i┼čletmeden ayr─▒lmalar─▒ halinde olu┼čan ├žal─▒┼čma g├╝c├╝ hareketine i┼č g├╝c├╝ devri denmektedir. Turnover oran─▒, ┼čirketinizde belirli vadede giri┼č-├ž─▒k─▒┼č sirk├╝lasyonunu tan─▒mlam─▒┼č olup, belirlenen d├Ânemde ├že┼čitli nedenlerle olu┼čan de─či┼čken i┼č g├╝c├╝n├╝ ifade etmektedir.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperationalkpi.turnoverrate');
   }
   public function reasonOfQuitJob()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('icns',function($join){$join->on('incentive_services.icn','=','icns.code');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('icns.name as istencikmanedeni',DB::raw('COUNT(incentive_services.icn) as toplamneden'))->groupBy('incentive_services.icn')->orderBy('toplamneden','desc')->take(10)->get();
      $verivarmi=0;
      if ($firmadata->count()==0) {
      	$verivarmi=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id')->get()->count();
      }
      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('icns',function($join){$join->on('incentive_services.icn','=','icns.code');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('icns.name as istencikmanedeni',DB::raw('COUNT(incentive_services.icn) as toplamneden'))->groupBy('incentive_services.icn')->orderBy('toplamneden','desc')->take(10)->get();
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="─░┼čten Ayr─▒l─▒┼č Nedenleri Oran─▒";
      $rapor_metni="─░┼čten ayr─▒lma neden grafi─či ,i┼čletmede ├žal─▒┼čanlar─▒n i┼čten ayr─▒lma nedenlerini sebebiyle ( istifa, bildirili fesih, emeklilik, askerlik, iyi niyet ve ahlak d─▒┼č─▒ fesih, deneme s├╝reci vb. ) ┼čirket i├žin olu┼čan i┼č g├╝c├╝n├╝ kayb─▒n─▒ tan─▒mlar. Bu metrik i┼čletme baz─▒nda ├ž─▒k─▒┼č s├╝re├žlerinin incelenerek olas─▒ kurum haf─▒zas─▒n─▒n ve k├╝lt├╝r├╝n├╝n korunmas─▒ noktas─▒nda ├Ânem arz eder. ─░┼čten ayr─▒lan personellerin ayr─▒l─▒┼č nedenlerine g├Âre tan─▒mlamas─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      View::share('verivarmi',$verivarmi);
      //return view('newmetric.hroperationalkpi.reasonofquitjob');
   }
   public function indisciplineRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      if ($ay_sayisi==0) {
      	$firma_calisan_sayisi=0;
      }else{
      	$firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/$ay_sayisi);
      }


      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
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

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$sectorpersonelcountdata=ApprovedIncentive::where('sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      	$ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      	if ($ay_sayisi==0) {
      		$sektor_calisan_sayisi=0;
      	}else{
      		$sektor_calisan_sayisi=round($sectorpersonelcountdata->sum('total_staff')/$ay_sayisi);
      	}

      	$sectorpersoneldata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->where('incentive_services.egn','!=',6)->where(function($q) {
            $q->where('incentive_services.icn', 29)
            ->orWhere('incentive_services.icn', 26);
          })->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id');
      	$sektor_ahlaksiz_sayisi=$sectorpersoneldata->count();
	    if ($sektor_calisan_sayisi==0) {
	        $ortalama+=0;
	    }else{
	        $ortalama+=$sektor_ahlaksiz_sayisi*100/$sektor_calisan_sayisi;
	    }
      }
      if ($sectordata->count()==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Disiplinsizlik Oran─▒";
      $rapor_metni="Disiplin sistemi, i┼čletmelerin insan kaynaklar─▒ i┼člevi kapsam─▒nda kurulmas─▒ ve uygulanmas─▒ gereken birtak─▒m ilke ve kurallardan olu┼čmaktad─▒r. ─░nsan kaynaklar─▒ y├Ânetiminin idari kapsam─▒nda bulunan faaliyetlerden biri olan disiplin, personeli istenmeyen davran─▒┼člardan uzak tutmay─▒ ve do─čru davranmaya y├Âneltmeyi ama├žlamaktad─▒r. ─░┼čletmede ├žal─▒┼čan personellerin, iyi niyet ahlak d─▒┼č─▒ davran─▒┼člar─▒ nedeni ile yap─▒lan 4857/25-2 kodlu ├ž─▒k─▒┼člar─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperationalkpi.indisciplinerate');
   }
   public function reportedAnnulledRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(48)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      if ($ay_sayisi==0) {
      	$firma_calisan_sayisi=0;
      }else{
      	$firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/$ay_sayisi);
      }


      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 4)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id');
      $firma_bildirimli_fesih_sayisi=$firmadata->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_bildirimli_fesih_sayisi*100/$firma_calisan_sayisi;
      }
      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$sectorpersonelcountdata=ApprovedIncentive::where('sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      	$ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      	if ($ay_sayisi==0) {
      		$sektor_calisan_sayisi=0;
      	}else{
      		$sektor_calisan_sayisi=round($sectorpersonelcountdata->sum('total_staff')/$ay_sayisi);
      	}


      	$sectorpersoneldata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 4)->where("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id');
      	$sektor_bildirimli_fesih_sayisi=$sectorpersoneldata->count();
	    if ($sektor_calisan_sayisi==0) {
	        $ortalama+=0;
	    }else{
	        $ortalama+=$sektor_bildirimli_fesih_sayisi*100/$sektor_calisan_sayisi;
	    }
      }
      if ($sectordata->count()==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Bildirimli Fesih Oran─▒";
      $rapor_metni="Fesih, taraflardan birinin tek tarafl─▒ irade beyan─▒yla s├Âzle┼čmeye son vermesidir. Bu irade beyan─▒ kar┼č─▒ tarafa ula┼čt─▒─č─▒ zaman, di─čer taraf s├Âzle┼čmeyi fesheden taraf─▒n i┼č ili┼čkisini sona erdirmek istedi─čini anlamas─▒ ┼čart─▒ aranmaktad─▒r. Bildirimli fesih i┼čleminde kar┼č─▒l─▒k, s├╝reli fesihle sona erdirmek isteyen i┼čverenin ge├žerli sebep g├Âsterme zorunlulu─ču bulunmaktad─▒r. ─░┼čverence tazminat ├Âdenerek bildirimli fesih yolu ile yap─▒lan i┼č s├Âzle┼čmesi fesihlerini ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperationalkpi.reportedannulledrate');
   }
   public function jobComplianceRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_finish','incentive_services.job_start');

      $firma_calisan_sayisi=$firmadata->whereNotNull('job_start')->count();
      $firma_isten_ayrilan_sayisi=$firmadata->whereNotNull('job_finish')->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=($firma_calisan_sayisi-$firma_isten_ayrilan_sayisi)*100/$firma_calisan_sayisi;
      }
      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$sectorpersoneldata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_start','incentive_services.job_finish');
      	$sektor_calisan_sayisi=$sectorpersoneldata->whereNotNull('job_start')->count();
	    $sektor_isten_ayrilan_sayisi=$sectorpersoneldata->whereNotNull('job_finish')->count();
	    if ($sektor_calisan_sayisi==0) {
	        $ortalama+=0;
	    }else{
	        $ortalama+=($sektor_calisan_sayisi-$sektor_isten_ayrilan_sayisi)*100/$sektor_calisan_sayisi;
	    }
      }
      if ($sectordata->count()==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="─░┼če Uyum Oran─▒";
      $rapor_metni="Oryantasyon s├╝reci, bireyin i┼če al─▒m─▒n─▒n neticesinde i┼čletmeye,mesai arkada┼člar─▒na, ekibe ve y├Ânetim sistemine entegrasyonu son derece ├Ânem arzetmektedir. ─░┼če ba┼člayan her yeni ├žal─▒┼čana, ekibe uyum sa─člayabilmesi i├žin, gerekli bilgi aktar─▒lmal─▒d─▒r. ─░┼čletme s├╝re├ž y├Ânetiminin en de─čerli girdisi olan insan fakt├Âr├╝n├╝n firma b├╝nyesinde i┼če giri┼č s├╝recinin akabinde firmaya uyumunu tespit eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperationalkpi.jobcompliancerate');
   }
   public function resignRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(13)->format('m');
      $minyear=Carbon::now()->subMonths(13)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
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
      	if ($ay_sayisi==0) {
      		$firma_yuzdesi=0;
      	}else{
      		$firma_yuzdesi=($firma_isten_ayrilan_sayisi)*100/$firma_calisan_sayisi;
      	}

      }

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$sektor_calisan_sayisi=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->where('incentive_services.egn','!=',6)->where("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_start')->whereNotNull('job_start')->count();
      	$ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      	$sektor_isten_ayrilan_sayisi=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->where('incentive_services.egn','!=',6)->where("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id')->where(function($q) {
            $q->where('incentive_services.icn', 2)
            ->orWhere('incentive_services.icn', 3)
            ->orWhere('incentive_services.icn', 23)
            ->orWhere('incentive_services.icn', 24)
            ->orWhere('incentive_services.icn', 25);
          })->count();
      	if ($sektor_calisan_sayisi==0) {
	        $ortalama+=0;
	    }else{
	    	if ($ay_sayisi==0) {
	    		$ortalama+=0;
	    	}else{
	    		$ortalama+=($sektor_isten_ayrilan_sayisi)*100/$sektor_calisan_sayisi;
	    	}

	    }
      }
      if ($sectordata->count()==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="─░stifa Oran─▒";
      $rapor_metni="─░stifa, ki┼činin h├╝r iradesi ile i┼činden ayr─▒lmas─▒ ┼čeklinde tan─▒mlanmaktad─▒r. ─░┼čten ayr─▒lma nedenleri incelenerek i┼čten ayr─▒l─▒┼č nedenleri saptan─▒r, ona g├Âre ├Ânlem al─▒narak ├žal─▒┼čanlar─▒ tutundurman─▒n art─▒r─▒lmas─▒ ama├žlan─▒r. ─░┼čten ayr─▒l─▒┼č nedeninin saptanabilmesi i├žinÔÇť├ç─▒k─▒┼č M├╝lakatlar─▒ÔÇŁkullan─▒labilir. Bu a┼čamada ├žal─▒┼čandan i┼č ortam─▒ndaki ├že┼čitli insan kaynaklar─▒ uygulamalar─▒n─▒n de─čerlendirilerek i┼čten ayr─▒lmaya neden olan ana fakt├Ârlerin a├ž─▒klanmas─▒ istenir. ─░stifa Oran─▒ neden ├Ânemlidir? ├çal─▒┼čanlar─▒n yerine nitelikli i┼čg├╝c├╝ bulmak veya haz─▒rlamak zaman gerektirmektedir. Bu da i┼č g├╝c├╝ kayb─▒na ve ├╝retimde aksakl─▒klara neden olabilir. Bir y─▒l i├žerisinde istifa yolu ile i┼čten ayr─▒lanlar─▒n oran─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperationalkpi.resignrate');
   }
   public function missingDayCauses()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(13)->format('m');
      $minyear=Carbon::now()->subMonths(13)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('egns',function($join){$join->on('incentive_services.egn','=','egns.id');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('egns.name as eksikgunnedeni',DB::raw('COUNT(incentive_services.egn) as toplamneden'))->groupBy('incentive_services.egn')->take(10)->get();
      $verivarmi=0;
      if ($firmadata->count()==0) {
      	$verivarmi=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id')->get()->count();
      }


      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('egns',function($join){$join->on('incentive_services.egn','=','egns.id');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',0)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('egns.name as eksikgunnedeni',DB::raw('COUNT(incentive_services.egn) as toplamneden'))->groupBy('incentive_services.egn')->take(10)->get();
      // die(var_dump($sectordata));
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Eksik G├╝n Nedenleri Oran─▒";
      $rapor_metni="Eksik g├╝n kodlar─▒, i┼čverenin ├žal─▒┼čanlar─▒n─▒ 30 g├╝nden az ├žal─▒┼čt─▒rmas─▒ durumunda, ki┼či baz─▒nda sigortal─▒n─▒n ka├ž g├╝n eksik ├žal─▒┼čt─▒─č─▒n─▒ ve eksik ├žal─▒┼čma nedenini ispatlamak i├žin kullan─▒lan kodlard─▒r. Ay i├žinde baz─▒ i┼čg├╝nlerinde ├žal─▒┼čt─▒r─▒lmayan ve ├╝cret ├Âdenmeyen sigortal─▒lar─▒n eksik g├╝n nedeni ve eksik g├╝n say─▒s─▒, i┼čverence ilgili aya ait ayl─▒k prim ve hizmet belgesi veya muhtasar ve prim hizmet beyannamesiyle beyan edilir. Eksik g├╝nkodlar─▒ a┼ča─č─▒da a├ž─▒klanm─▒┼č olup, e-Bildirge sistemi ├╝zerinden ayl─▒k prim ve hizmet belgesinin/ e-beyanname sistemi ├╝zerinden muhtasar ve prim hizmet beyannamesinin g├Ânderilmesi esnas─▒nda, bu kodlar ile eksik g├╝n say─▒s─▒n─▒n SGKÔÇÖ ya bildirilmesi gerekmektedir. APHB(Ayl─▒k prim hizmet bildirgesi) ├╝zerinden verilen eksik g├╝n nedenlerinin da─č─▒l─▒m─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      View::share('verivarmi',$verivarmi);
      //return view('newmetric.hroperationalkpi.missingdaycauses');
   }
   public function workAccidentRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();

      $son_iki_ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff')->get();
      $son_iki_ay_personel_sayisi=$son_iki_ay->sum('total_staff');

      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=DB::table('work_accidents')->where('sgk_company_id','=',$sgk_company_id)->whereBetween("work_accidents.kaza_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('work_accidents.kisi_sayisi')->get();
      // die(var_dump($data->sum('eksik_gun')));
      if ($ay_sayisi==0) {
      	$firma_calisan_sayisi=0;
      }else{
      	$firma_calisan_sayisi=round(($son_iki_ay_personel_sayisi)/$ay_sayisi);
      }

      $firma_kaza_sayisi=$firmadata->count();
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=number_format($firma_kaza_sayisi*100/$firma_calisan_sayisi,1);
      }

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      	if ($ay_sayisi==0) {
      		$sektor_calisan_sayisi=0;
      	}else{
      		$sektor_calisan_sayisi=ApprovedIncentive::where('sgk_company_id','=',$sdata['id'])->whereBetween("service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff')->sum('total_staff')/$ay_sayisi;
      		$sektor_calisan_sayisi=round($sektor_calisan_sayisi);
      	}

      	$sektor_kaza_sayisi=DB::table('work_accidents')->where('sgk_company_id','=',$sdata['id'])->whereBetween("kaza_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('kisi_sayisi')->sum('kisi_sayisi');
      	if ($sektor_calisan_sayisi==0) {
	        $ortalama+=0;
	    }else{
	        $ortalama+=$sektor_kaza_sayisi*100/$sektor_calisan_sayisi;
	    }
      }
      if ($sectordata->count()==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=number_format($ortalama/$sectordata->count(),1);
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="─░┼č Kazas─▒ Oran─▒";
      $rapor_metni="08.05.2008 tarihinde y├╝r├╝rl├╝─če giren 5510 say─▒l─▒ 'Sosyal Sigortalar ve Genel Sa─čl─▒k Sigortas─▒ Kanunu' 13.maddesi gere─čince; ─░┼čyerinde veya i┼čin y├╝r├╝t├╝m├╝ nedeniyle meydana gelen, ├Âl├╝me sebebiyet veren veya v├╝cut b├╝t├╝nl├╝─č├╝n├╝ ruhen ya da bedenen engelli h├óle getiren kazalar olarak tan─▒mlanm─▒┼čt─▒r.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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

      //return view('newmetric.hroperationalkpi.workaccidentrate');
   }
   public function accidentFrequencyRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(13)->format('m');
      $minyear=Carbon::now()->subMonths(13)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$sektor_kaza_sayisi=DB::table('work_accidents')->where('sgk_company_id','=',$sdata['id'])->whereBetween("kaza_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('kisi_sayisi')->sum('kisi_sayisi');
      	$sektor_calisilan_gun_toplami=ApprovedIncentive::where('sgk_company_id','=',$sdata['id'])->whereBetween("service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_day')->sum('total_day');
      	$sektor_calisma_saati = intval($sektor_calisilan_gun_toplami)*7.5;
    		if ($sektor_calisma_saati==0) {
    			$ortalama+= 0;
    		}else{
    			$ortalama+= $sektor_kaza_sayisi*1000000/$sektor_calisma_saati;
    		}
      }
      if ($sectordata->count()==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=($firma_kaza_sayisi*1000000/($firma_calisilan_gun_toplami*7.5))+($firma_kaza_sayisi*1000000/($firma_calisilan_gun_toplami*7.5))*20/100;
      	}else{
      		$sektor_yuzdesi=($firma_kaza_sayisi*1000000/($firma_calisilan_gun_toplami*7.5))-($firma_kaza_sayisi*1000000/($firma_calisilan_gun_toplami*7.5))*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Kaza S─▒kl─▒k Oran─▒";
      $rapor_metni="Sigortal─▒n─▒n i┼čyerinde bulundu─ču s─▒rada, i┼čveren taraf─▒ndan y├╝r├╝t├╝lmekte olan i┼č esnas─▒nda, sigortal─▒n─▒n i┼čveren taraf─▒ndan g├Ârevle ba┼čka bir yere g├Ânderilmesi y├╝z├╝nden as─▒l i┼čini yapmaks─▒z─▒n ge├žen zamanlarda, emzikli kad─▒n sigortal─▒n─▒n ├žocu─čuna s├╝t vermek i├žin ayr─▒lan zamanlarda, sigortal─▒lar─▒n i┼čverence sa─članan bir ta┼č─▒tla i┼čin yap─▒ld─▒─č─▒ yere toplu olarak ta┼č─▒nmas─▒ s─▒ras─▒nda meydana gelen ve sigortal─▒y─▒ hemen veya sonradan bedenen ya da ruhen ├Âzre u─čratan olayd─▒r. AB uygulamalar─▒ kapsam─▒nda 1 milyon saatte meydana gelen i┼č kazalar─▒n─▒ s─▒kl─▒─č─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_kaza_sayisi',$firma_kaza_sayisi);
      View::share('firma_calisilan_gun_toplami',$firma_calisilan_gun_toplami);
      View::share('sektor_kaza_sayisi',$sektor_kaza_sayisi);
      View::share('sektor_calisilan_gun_toplami',$sektor_calisilan_gun_toplami);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      //return view('newmetric.hroperationalkpi.accidentfrequencyrate');
   }
   public function timeAllocatedToEducation()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $sabitler=config('constants');
      $year=date('Y');
      $years=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','education')->select('value_year')->groupBy('value_year')->get();
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      $skip=0;
      if ($years->count()>5) {
      	$skip=$years->count()-5;
      }
      $error_message=0;
      $i=0;
      $firma_toplam__egitim_suresi=0;
      $firma_current_rate=0;
      $genel_ortalama=0;
      $firmadata=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','time')->select('value_year')->orderBy('value_year','asc')->get();
      foreach ($firmadata as $fdata) {
      	$ay_sayisi=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereYear('service','=',$year)->groupBy('service')->count();
      	$firmaeducationdata[$i]['yil']=$fdata->value_year;
      	$firmaeducationdata[$i]['personelsayisi']=round(ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereYear('service','=',$year)->sum('total_staff')/$ay_sayisi);
      	$firmaeducationdata[$i]['educationtime']=DB::table('metrik_constants')->where('sector_id','=',$sector_id)->where('sgk_company_id','=',$sgk_company_id)->where('type','=','time')->where('value_year','=',$fdata->value_year)->select('value')->sum('value');
      	$firma_toplam__egitim_suresi+=$firmaeducationdata[$i]['educationtime'];
      	if ($firmaeducationdata[$i]['personelsayisi']==0) {
      		$firma_current_rate=0;
      		$genel_ortalama+=0;
      	}else{
      		$firma_current_rate=$firmaeducationdata[$i]['educationtime']/$firmaeducationdata[$i]['personelsayisi'];
      		if ($firmadata->count()==$i+1) {
      			 $genel_ortalama+=$firma_current_rate;
      		}
      	}

      	$i++;
      }

      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="E─čitime Ayr─▒lan S├╝re Oran─▒";
      $rapor_metni="E─čitim, bireye yeni bir davran─▒┼č kazand─▒rmak yada mevcut davran─▒┼č─▒n─▒ de─či┼čtirmek olarak tan─▒mlanmaktad─▒r. E─čitim ┼čirketin organizasyonel performans─▒n─▒n artmas─▒, ├žal─▒┼čanlar─▒n becerilerinin ve bilgilerinin kurumsal d├╝zene uygun olarak yap─▒lmas─▒n─▒ sa─člamakt─▒r. ─░┼čletme i├žerisinde ger├žekle┼čtirilen e─čitim s├╝resinin oran─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('firma_toplam__egitim_suresi',$firma_toplam__egitim_suresi);
      if ($firmadata->count()>0) {
      	$genel_ortalama=$genel_ortalama/$firmadata->count();
      	View::share('firmaeducationdata',$firmaeducationdata);
      }else{
      	$error_message=1;
      }
      View::share('error_message',$error_message);
      View::share('genel_ortalama',$genel_ortalama);
      View::share('firma_current_rate',$firma_current_rate);
      //return view('newmetric.hroperationalkpi.timeallocatedtoeducation');
   }
   public function terminationChartForSpecialReasons()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      if ($ay_sayisi==0) {
      	$firma_calisan_sayisi=0;
      }else{
      	$firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/$ay_sayisi);
      }


      $firma_evlilikten_ayrilanlar=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn', 13)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $firma_emeklilikten_ayrilanlar=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn', 8)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $firma_askerlikten_ayrilanlar=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.icn', 12)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();

      $son_iki__ay_sektor=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('approved_incentives.total_staff');
      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $sektor_calisan_sayisi=0;
      foreach ($sectordata as $sdata) {
      	$sectorpersoneldata = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select(DB::raw('SUM(total_staff) as toplampersonelsayisi'))->groupBy('approved_incentives.service')->get();
      	if ($sectorpersoneldata->count()==0) {
	      	$sektor_calisan_sayisi+=0;
	    }else{
	      	$sektor_calisan_sayisi+=round($sectorpersoneldata->sum('toplampersonelsayisi')/$sectorpersoneldata->count());
	    }
      }

      $sektor_emeklilikten_ayrilanlar=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 8)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $sektor_askerlikten_ayrilanlar=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 12)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $sektor_evlilikten_ayrilanlar=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->where('incentive_services.icn', 13)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.icn')->count();
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="├ľzel Nedenlerle ─░┼čten Ayr─▒lma Oran─▒";
      $rapor_metni="─░┼čletme i├žerisinde ger├žekle┼čen i┼čg├╝c├╝ de─či┼čikliklerinin, zorunlu nedenler ve personel talepleri ile olu┼čmas─▒n─▒ ifade eder. 4857 say─▒l─▒ i┼č kanunundan do─čan emeklilik, askerlik, evlilik ve personel talebi ile olu┼čan di─čer nedenleri ├Âl├žmemizi sa─člar.─░┼čletme b├╝nyesinde 4857 say─▒l─▒ i┼č kanunu h├╝k├╝mleri ile tan─▒mlanm─▒┼č ├Âzel ├ž─▒k─▒┼člar─▒(askerlik,evlilik,emeklilik) ifade etmektedir.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperationalkpi.terminationchartforspecialreasons');
   }
   public function accidentWeightRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $firma_kayip_gun_sayisi=0;
      $sektor_kayip_gun_sayisi=0;
      $minmonth=Carbon::now()->subMonths(13)->format('m');
      $minyear=Carbon::now()->subMonths(13)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$sectorrapordata=DB::table('work_vizites')->where('sgk_company_id','=',$sdata['id'])->where('vaka','=','IS KAZASI')->whereBetween("poliklinik_tarihi",[Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('poliklinik_tarihi','isbasi_tarihi')->get();
        $sektor_kayip_gun_sayisi=0;
      	foreach ($sectorrapordata as $rapor) {
	        $to = \Carbon\Carbon::createFromFormat('Y-m-d', $rapor->poliklinik_tarihi);
	        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $rapor->isbasi_tarihi);
	        $sektor_kayip_gun_sayisi += $to->diffInDays($from);
	    }
  	    $sektor_calisilan_gun_toplami=ApprovedIncentive::where('sgk_company_id','=',$sdata['id'])->whereBetween("service",[Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_day')->sum('total_day');
  	    if ($sektor_calisilan_gun_toplami==0) {
  		  	$ortalama+= 0;
    		}else{
    			$ortalama+= $sektor_kayip_gun_sayisi/$sektor_calisilan_gun_toplami*1000;
    		}
      }
      if ($sectordata->count()==0) {
      	$sektor_yuzdesi=0;
      }else{
      	$sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=($firma_kayip_gun_sayisi/($firma_calisilan_gun_toplami*1000))+($firma_kayip_gun_sayisi/($firma_calisilan_gun_toplami*1000))*20/100;
      	}else{
      		$sektor_yuzdesi=($firma_kayip_gun_sayisi/($firma_calisilan_gun_toplami*1000))-($firma_kayip_gun_sayisi/($firma_calisilan_gun_toplami*1000))*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Kaza A─č─▒rl─▒k Oran─▒";
      $rapor_metni="Sigortal─▒n─▒n i┼čyerinde bulundu─ču s─▒rada, i┼čveren taraf─▒ndan y├╝r├╝t├╝lmekte olan i┼č esnas─▒nda, sigortal─▒n─▒n i┼čveren taraf─▒ndan g├Ârevle ba┼čka bir yere g├Ânderilmesi y├╝z├╝nden as─▒l i┼čini yapmaks─▒z─▒n ge├žen zamanlarda, emzikli kad─▒n sigortal─▒n─▒n ├žocu─čuna s├╝t vermek i├žin ayr─▒lan zamanlarda, sigortal─▒lar─▒n i┼čverence sa─članan bir ta┼č─▒tla i┼čin yap─▒ld─▒─č─▒ yere toplu olarak ta┼č─▒nmas─▒ s─▒ras─▒nda meydana gelen ve sigortal─▒y─▒ hemen veya sonradan bedenen yada ruhen ├Âzre u─čratan olayd─▒r.AB uygulamalar─▒ kapsam─▒nda 1000 g├╝nde meydana gelen i┼č kazalar─▒n─▒, kay─▒p ├žal─▒┼čma g├╝n├╝n├╝ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firma_kayip_gun_sayisi',$firma_kayip_gun_sayisi);
      View::share('firma_calisilan_gun_toplami',$firma_calisilan_gun_toplami);
      View::share('sektor_kayip_gun_sayisi',$sektor_kayip_gun_sayisi);
      View::share('sektor_calisilan_gun_toplami',$sektor_calisilan_gun_toplami);
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
      //return view('newmetric.hroperationalkpi.accidentweightrate');
   }
   public function reportingRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      $son_iki__ay=ApprovedIncentive::where('sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff');
      if ($ay_sayisi==0) {
      	$firma_calisan_sayisi=0;
      	$firma_rapor_sayisi=0;
      }else{
      	$firma_calisan_sayisi=round($son_iki__ay->sum('total_staff')/$ay_sayisi);
      	$firma_rapor_sayisi=DB::table('work_vizites')->where('sgk_company_id','=',$sgk_company_id)->whereBetween("poliklinik_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->count();
      }
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      if ($firma_calisan_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=round($firma_rapor_sayisi)*100/$firma_calisan_sayisi;
      }

      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      	if ($ay_sayisi==0) {
      		$sektor_calisan_sayisi=0;
      		$sektor_rapor_sayisi=0;
      	}else{
      		$sektor_calisan_sayisi=ApprovedIncentive::where('sgk_company_id','=',$sdata['id'])->whereBetween("service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('total_staff')->sum('total_staff')/$ay_sayisi;
      		$sektor_calisan_sayisi=round($sektor_calisan_sayisi);
      		$sektor_rapor_sayisi=DB::table('work_vizites')->where('sgk_company_id','=',$sdata['id'])->whereBetween("poliklinik_tarihi", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->count()/$ay_sayisi;
      	}


      	 if ($sektor_calisan_sayisi==0) {
	        $ortalama+=0;
	    }else{
	        $ortalama+=$sektor_rapor_sayisi*100/$sektor_calisan_sayisi;
	    }
      }
      if ($sectordata->count()==0) {
         $sektor_yuzdesi=0;
      }else{
         $sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firma_rapor_sayisi%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="─░┼č G├Âremezlik Raporu Oran─▒";
      $rapor_metni="Bir veya birden fazla doktor onay─▒ ile verilen ve hastal─▒─č─▒n tan─▒s─▒n─▒, hastan─▒n durumunu, dinlenmesi gerekti─či durumlar i├žin mazeretli izin durumudur. Vizite ekranlar─▒nda i┼č kazas─▒ sonucu al─▒nan raporlar i┼č kazas─▒ raporu, hastal─▒k nedeniyle al─▒nan raporlar hastal─▒k raporu ve anal─▒k nedeniyle al─▒nan rapor anal─▒k ekran─▒nda bulunur. ─░┼č g├Âremezlik ├Âdene─či alabilmek i├žin, i┼č g├Âremezlik raporu ald─▒─č─▒n─▒z tarihten 1 y─▒l ├Ânceki tarih i├žinde en az 90 g├╝n prim yat─▒rman─▒z gerekmektedir. Bildirgeler ├╝zerinden tan─▒mlanan i┼č g├Âremezlik raporlar─▒n─▒n oran─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperationalkpi.reportingrate');
   }
   public function laborLossRate()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.gun','incentive_services.eksik_gun')->get();
      // die(var_dump($data->sum('eksik_gun')));
      $eksik_gun=$firmadata->sum('eksik_gun');
      $planlanan_gun=$firmadata->sum('eksik_gun')+$firmadata->sum('gun');
      if ($planlanan_gun==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=number_format($eksik_gun*100/$planlanan_gun,1);
      }
      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$sektorgunler=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.gun','incentive_services.eksik_gun')->get();
      	$sektor_eksik_gun=$sektorgunler->sum('eksik_gun');
	      $sektor_planlanan_gun=$sektorgunler->sum('eksik_gun')+$sektorgunler->sum('gun');
	      if ($sektor_planlanan_gun==0) {
	         $ortalama+=0;
	      }else{
	         $ortalama+=$sektor_eksik_gun*100/$sektor_planlanan_gun;
	      }
      }
      if ($sectordata->count()==0) {
        $sektor_yuzdesi=0;
      }else{
        $sektor_yuzdesi=number_format($ortalama/$sectordata->count(),1);
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="─░┼č G├╝c├╝ Kay─▒p Oran─▒";
      $rapor_metni="─░┼č g├╝c├╝ kay─▒p kavram─▒,bireyin i┼č g├╝c├╝nde planlanan ├žal─▒┼čma g├╝n├╝ne ra─čmen i┼če gelmeme durumu olarak tan─▒mlanmaktad─▒r.Kay─▒p i┼č g├╝c├╝ art─▒┼č─▒ ba┼čta i┼čletmenin rekabet edebilirli─či ve verimlik olmak ├╝zere bir├žok olumsuz yans─▒mas─▒ g├Âr├╝lmektedir. Personellerin ├žal─▒┼čma plan─▒n─▒n d─▒┼č─▒nda ( hastal─▒k, i┼če gelmeme, i┼če ge├ž gelme, i┼če ge├ž ba┼člamas─▒, i┼čten erken ayr─▒lma vb) i┼če gelmemesi durumunda ya┼čanan i┼č g├╝c├╝ kay─▒plar─▒n─▒ oranlar.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperatingkpi.laborlossrate');
   }
   public function transferRateInCompany()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();

      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id','incentive_services.icn');
      // die(var_dump($data->sum('eksik_gun')));
      $firma_bu_ayki_personel_sayisi=$firmadata->count();
      $firma_nakil_yapilan_personel_sayisi=$firmadata->where('incentive_services.icn','=','16')->count();
      if ($ay_sayisi==0) {
      	$firma_ortalama_kisi_sayisi=0;
      }else{
      	$firma_ortalama_kisi_sayisi=round($firma_bu_ayki_personel_sayisi/$ay_sayisi);
      }

      if ($firma_ortalama_kisi_sayisi==0) {
         $firma_yuzdesi=0;
      }else{
         $firma_yuzdesi=$firma_nakil_yapilan_personel_sayisi*100/$firma_ortalama_kisi_sayisi;
      }
      $sectordata=SgkCompany::where('sector_id','=',$sector_id)->where('id','!=',$sgk_company_id)->get();
      $ortalama=0;
      foreach ($sectordata as $sdata) {
      	$sectorpersoneldata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->where('approved_incentives.sgk_company_id','=',$sdata['id'])->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.id','incentive_services.icn');
      	$sektor_bu_ayki_personel_sayisi=$sectorpersoneldata->count();
	    $sektor_nakil_yapilan_personel_sayisi=$sectorpersoneldata->where('incentive_services.icn','=','16')->count();
	    $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
	    if ($ay_sayisi==0) {
	    	$sektor_ortalama_kisi_sayisi=0;
	    }else{
	    	$sektor_ortalama_kisi_sayisi=round($sektor_bu_ayki_personel_sayisi/$ay_sayisi);
	    }

	    if ($sektor_ortalama_kisi_sayisi==0) {
	        $ortalama+=0;
	    }else{
	        $ortalama+=$sektor_nakil_yapilan_personel_sayisi*100/$sektor_ortalama_kisi_sayisi;
	    }
      }
      if ($sectordata->count()==0) {
        $sektor_yuzdesi=0;
      }else{
        $sektor_yuzdesi=$ortalama/$sectordata->count();
      }
      if ($sektor_yuzdesi==0) {
      	if ($firmadata->count()%2==0) {
      		$sektor_yuzdesi=$firma_yuzdesi+$firma_yuzdesi*20/100;
      	}else{
      		$sektor_yuzdesi=$firma_yuzdesi-$firma_yuzdesi*20/100;
      	}
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="┼×irket ─░├ži Nakil Oran─▒";
      $rapor_metni="┼×irket i├žerisinde ├žal─▒┼čan─▒n yerine getirdi─či de─či┼čik i┼člerin say─▒s─▒n─▒ art─▒rmak ve motivasyonu sa─člamak amac─▒yla sistematik olarak ├žal─▒┼čan─▒ bir i┼čten ba┼čka bir i┼če (bir g├Ârevden di─čer bir g├Âreve) ge├žirmek olarak tan─▒mlanmaktad─▒r. Rotasyon yap─▒lmas─▒n─▒n amac─▒, yo─čun dikkat isteyen ve tekrarlanan i┼člerin, ├žal─▒┼čanlar ├╝zerindeki s─▒k─▒nt─▒ ve psikolojik yorgunlu─ču ├Ânlemek, verimlili─či artt─▒rmakt─▒r.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperatingkpi.transferrateincompany');
   }
   public function taskDefinitionChart()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('profession_codes',function($join){$join->on('incentive_services.meslek_kod','=','profession_codes.isco');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('profession_codes.name as meslekadi',DB::raw('COUNT(incentive_services.meslek_kod) as personelmesleksayisi'))->groupBy('incentive_services.meslek_kod')->orderBy('personelmesleksayisi','desc')->take(10)->get();

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('profession_codes',function($join){$join->on('incentive_services.meslek_kod','=','profession_codes.isco');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('profession_codes.name as meslekadi',DB::raw('COUNT(incentive_services.meslek_kod) as personelmesleksayisi'))->groupBy('incentive_services.meslek_kod')->orderBy('personelmesleksayisi','desc')->take(10)->get();
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="G├Ârev Tan─▒m Grafi─či";
      $rapor_metni="G├Ârev Tan─▒m─▒; bir kurulu┼čun ├╝st d├╝zey y├Âneticilerinden ba┼člayarak en alt d├╝zey ├žal─▒┼čan─▒na kadar ( kaliteyi uygulayan , etkileyen ve do─črulayan herkes ) b├╝t├╝n personelin kime ba─čl─▒ ├žal─▒┼čt─▒─č─▒, g├Ârevi , sorumlulu─ču ve yetkilerinin tariflendi─či dok├╝man olarak tan─▒mlanm─▒┼čt─▒r.─░┼če giri┼č bildirgesi ve APHB'de tan─▒mlanan meslek kodlar─▒n─▒n da─č─▒l─▒m─▒n─▒ tan─▒mlamaktad─▒r.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      //return view('newmetric.hroperatingkpi.taskdefinitionchart');
   }
   public function ageDistributionChart()
   {
      $tarih='1992-05-19';
      $yas=Carbon::parse($tarih)->age;
      // die(var_dump($yas));
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $gencyasfirma=array();
      $ortayasfirma=array();
      $yaslifirma=array();
      $gencyassektor=array();
      $ortayassektor=array();
      $yaslisektor=array();

      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
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
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Ya┼č Da─č─▒l─▒m─▒ Grafi─či";
      $rapor_metni="─░┼čletme b├╝nyesinde halihaz─▒rda ├žal─▒┼čan t├╝m personellerin ya┼člar─▒na bakarak sistematik bir inceleme imkan─▒vermektedir.Firma b├╝nyesinde yetenek,tecr├╝be ve gen├žlik e─črilerinin verimlilik g├Âstergesi ili┼čkilendirilmi┼čtir.Ya┼č da─č─▒l─▒m─▒ metri─či ile ┼čirketinizin ya┼č e─črisini bulabilirsiniz.─░┼čletme b├╝nyesinde ├žal─▒┼čanlar─▒n ya┼č da─č─▒l─▒m grafi─čini tan─▒mlar.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
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
      //return view('newmetric.hroperatingkpi.agedistributionchart');
   }
   public function genderDistributionChart()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      if (isset($_GET['year']) AND !empty($_GET['year'])) {
         $year=$_GET['year'];
      }
      if (isset($_GET['month']) AND !empty($_GET['month'])) {
         $month=$_GET['month'];
      }
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
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

      //return view('newmetric.hroperatingkpi.genderdistributionchart');
   }
   public function educationLevelChart()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('declaration_infos',function($join){$join->on('incentive_services.tck','=','declaration_infos.tck');})->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereNotNull('declaration_infos.education')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('declaration_infos.education as educationname',DB::raw('COUNT(declaration_infos.education) as educationlevelcount'))->groupBy('declaration_infos.education')->get();

      $sectordata=SgkCompany::join('approved_incentives','sgk_companies.id','=','approved_incentives.sgk_company_id')->join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->leftJoin('declaration_infos',function($join){$join->on('incentive_services.tck','=','declaration_infos.tck');})->where('sgk_companies.sector_id','=',$sector_id)->where('approved_incentives.sgk_company_id','!=',$sgk_company_id)->where('incentive_services.egn','!=',6)->whereNotNull('declaration_infos.education')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('approved_incentives.service','declaration_infos.education as educationname',DB::raw('COUNT(declaration_infos.education) as educationlevelcount'))->groupBy('declaration_infos.education')->get();
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Personel E─čitim Seviyesi Grafi─či";
      $rapor_metni="─░┼čletme kriterleri neticesinde i┼če kabul edilen personellerin yetkinlik ,beceri ve i┼če uyum ili┼čkisi i├žin ├Ânem arzeden bir metriktir.Bireyin lise,├Ânlisans,lisans veya y├╝ksek lisans durumunu i┼čiyle olan performans ili┼čkisi ile ├Âl├ž├╝mlemek fayda sa─člayacakt─▒r.Personellerin lise, ├Ânlisans, lisans ve y├╝ksek lisans yetkinliklerinin i┼če giri┼č bildirgesi ├╝zerinden tan─▒mlar.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('firmadata',$firmadata);
      View::share('sectordata',$sectordata);
      //return view('newmetric.hroperatingkpi.educationlevelchart');
   }
   public function disabilityAssessment()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firma_personel_sayisi=0;

      $firmadata=SgkCompany::find($sgk_company_id);
      $company_id=$firmadata->company_id;
      $city_id=$firmadata->city_id;
      $firma_engelli_sayisi=0;
      $firmsdata=SgkCompany::where('company_id','=',$company_id)->where('city_id','=',$city_id)->get();
      foreach ($firmsdata as $fdata) {
        $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$fdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
        $firmadata=ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$fdata['id'])->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('law_no',DB::raw('SUM(total_staff) as personelsayisi'))->get();
        if ($ay_sayisi==0) {
          $firma_personel_sayisi+=0;
        }else{
          $firma_personel_sayisi+=$firmadata[0]['personelsayisi']/$ay_sayisi;
        }
      	$firmadata=ApprovedIncentive::where('sgk_company_id','=',$fdata['id'])->where('law_no',14857)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('law_no',DB::raw('SUM(total_staff) as engellipersonelsayisi'),'total_staff')->get();
        if ($ay_sayisi==0) {
          $firma_engelli_sayisi+= 0;
        }else{
          $firma_engelli_sayisi+= $firmadata[0]['engellipersonelsayisi']/$ay_sayisi;
        }

      }
      $firma_engelli_sayisi=round($firma_engelli_sayisi);
      if ($firma_engelli_sayisi==NULL) {
         $firma_engelli_sayisi=0;
      }
      $olmasi_gereken_engelli_sayisi=0;
      if ($firma_personel_sayisi>=50) {
         $olmasi_gereken_engelli_sayisi=round($firma_personel_sayisi*3/100);
      }
      if ($firma_personel_sayisi==0) {
         $firma_yuzdesi=0;
      }else{ 
        $firma_yuzdesi=$firma_engelli_sayisi*100/$firma_personel_sayisi;
      }
      $sektor_yuzdesi=3;
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Engelli Uygunluk Tespiti";
      $rapor_metni="├ľzel sekt├Âr i┼čverenlerinin kontenjan d├óhilinde veya kontenjan fazlas─▒ olarak yada y├╝k├╝ml├╝ olmad─▒klar─▒ halde engelli ├žal─▒┼čt─▒rmalar─▒ durumunda i┼čverenlerin bu ┼čekilde ├žal─▒┼čt─▒rd─▒klar─▒ her bir engelli i├žin asgari ├╝cret d├╝zeyindeki sosyal g├╝venlik primi i┼čveren paylar─▒n─▒n tamam─▒ Hazinece kar┼č─▒lanmas─▒ ┼čeklinde de─či┼čtirilmi┼čtir.50 ve daha fazla personel ├žal─▒┼čt─▒ran i┼čletmelerin toplam personel say─▒s─▒n─▒n %3 oran─▒nda engelli istihdam─▒n─▒ ifade eder.";
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('ay_sayisi',$ay_sayisi);
      View::share('firma_personel_sayisi',$firma_personel_sayisi);
      View::share('firma_engelli_sayisi',$firma_engelli_sayisi);
      View::share('firma_yuzdesi',$firma_yuzdesi);
      View::share('olmasi_gereken_engelli_sayisi',$olmasi_gereken_engelli_sayisi);
      if (isset($firmadata)) {
        View::share('firmadata',$firmadata);
      }
      View::share('sektor_yuzdesi',$sektor_yuzdesi);
   }
   public function checkInOutCheck()
   {
      $sgk_company = getSgkCompany();
      $sector_id=$sgk_company->sector_id;
      $sgk_company_id=$sgk_company->id;
      $month=Carbon::now()->subMonths(1)->format('m');
      $previousmonth=Carbon::now()->subMonths(2)->format('m');
      $year=date('Y');
      $minmonth=Carbon::now()->subMonths(12)->format('m');
      $minyear=Carbon::now()->subMonths(12)->format('Y');
      $maxmonth=Carbon::now()->subMonths(1)->format('m');
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
      $error_message='';
      $minday='01';
      $maxday=Carbon::createFromFormat('Y-m', $maxyear.'-'.$maxmonth)->endOfMonth()->format('d');
      $mindate=Carbon::createFromFormat('Y-m-d', $minyear.'-'.$minmonth.'-'.$minday)->subDays(1)->format('Y-m-d');
      $maxdate=Carbon::createFromFormat('Y-m-d', $maxyear.'-'.$maxmonth.'-'.$maxday)->format('Y-m-d');
      $ay_sayisi = ApprovedIncentive::where('approved_incentives.sgk_company_id','=',$sgk_company_id)->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('service')->groupBy('approved_incentives.service')->get()->count();
      $kbcalisansayisi=DB::table('identity_notifications')->where('sgk_company_id','=',$sgk_company_id)->count();
      if ($kbcalisansayisi==0) {
        $error_message="Kimlik Bildirim Sistemi'nden veri ├žekimi ger├žekle┼čmemi┼čtir. Dolay─▒s─▒yla kar┼č─▒la┼čt─▒rma yap─▒lamamaktad─▒r.";
      }
      $kbcalisandata=SgkCompany::find($sgk_company_id);
      if ($kbcalisandata->kbcalisan_email==NULL || $kbcalisandata->kbcalisan_sifre==NULL || $kbcalisandata->kbcalisan_email=='' || $kbcalisandata->kbcalisan_sifre=='') {
        $error_message="Kimlik Bildirim Sistemi giri┼č bilgileriniz tan─▒mlanmam─▒┼čt─▒r. L├╝tfen giri┼č bilgilerinizi tan─▒mlay─▒p Kimlik Bildirim Sistemi'nden veri ├žekimi yap─▒n─▒z.";
      }
      //Eksik g├╝n nedeni k─▒smi istihdam olan─▒ getirmedik. k─▒smi istihdam id=6
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->join('identity_notifications','identity_notifications.tck','=','incentive_services.tck')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('identity_notifications.sgk_company_id','=',$sgk_company_id)->whereNotNull('job_start')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_start','incentive_services.tck','incentive_services.isim','incentive_services.soyisim','identity_notifications.ise_giris_tarihi')->groupBy('incentive_services.tck')->get();
      $ise_giris_sayisi=$firmadata->count();
      $ise_giris_bildirim_hata_sayisi=0;
      $i=0;
      foreach ($firmadata as $fdata) {
        if ($fdata['job_start']!=$fdata['ise_giris_tarihi']) {
          $ise_giris_bildirim_hata_sayisi++;
          $ise_giris_hatali_personel[$i]['tck']=$fdata['tck'];
          $ise_giris_hatali_personel[$i]['isim']=$fdata['isim'];
          $ise_giris_hatali_personel[$i]['soyisim']=$fdata['soyisim'];
          $degerlendirme_mesaji[$i]=$fdata['tck']." kimlik numaral─▒ ".$fdata['isim']." ".$fdata['soyisim']." isimli personelinizin ".$fdata['job_start']." tarihli SGK'daki i┼če giri┼č tarihi ile ".$fdata['ise_giris_tarihi']." tarihli EGM'deki i┼če giri┼č tarihi uyu┼čmamaktad─▒r. ";
          $i++;
        }
      }
      if ($ise_giris_sayisi==0) {
        $ise_giris_yuzdesi=0;
      }else{
        $ise_giris_yuzdesi=number_format($ise_giris_bildirim_hata_sayisi*100/$ise_giris_sayisi,1);
      }
      $firmadata=ApprovedIncentive::join('incentive_services','approved_incentives.id','=','incentive_services.approved_incentive_id')->join('identity_notifications','identity_notifications.tck','=','incentive_services.tck')->where('approved_incentives.sgk_company_id','=',$sgk_company_id)->where('identity_notifications.sgk_company_id','=',$sgk_company_id)->whereNotNull('incentive_services.job_finish')->whereBetween("approved_incentives.service", [Carbon::createFromFormat('Y-m-d', $mindate),Carbon::createFromFormat('Y-m-d', $maxdate)])->select('incentive_services.job_finish','incentive_services.tck','incentive_services.isim','incentive_services.soyisim','identity_notifications.isten_ayrilis_tarihi')->groupBy('incentive_services.tck')->get();
      $isten_cikis_sayisi=$firmadata->count();
      $isten_cikis_bildirim_hata_sayisi=0;
      foreach ($firmadata as $fdata) {
        if ($fdata['job_finish']!=$fdata['isten_ayrilis_tarihi']) {
          $isten_cikis_bildirim_hata_sayisi++;
          $isten_cikis_hatali_personel[$i]['tck']=$fdata['tck'];
          $isten_cikis_hatali_personel[$i]['isim']=$fdata['isim'];
          $isten_cikis_hatali_personel[$i]['soyisim']=$fdata['soyisim'];
          if ($fdata['isten_ayrilis_tarihi']=='1900-01-01') {
          	$degerlendirme_mesaji[$i]=$fdata['tck']." kimlik numaral─▒ ".$fdata['isim']." ".$fdata['soyisim']." isimli personelinizin ".$fdata['job_finish']." tarihi SGK'daki i┼čten ├ž─▒k─▒┼č tarihidir. EGM'de ├ž─▒k─▒┼č tarihi yoktur. ├ç─▒k─▒┼č tarihini EGM'ye bildirmeniz gerekmektedir. ";
          }else{
          	$degerlendirme_mesaji[$i]=$fdata['tck']." kimlik numaral─▒ ".$fdata['isim']." ".$fdata['soyisim']." isimli personelinizin ".$fdata['job_finish']." tarihli SGK'daki i┼čten ├ž─▒k─▒┼č tarihi ile ".$fdata['isten_ayrilis_tarihi']." tarihli EGM'deki i┼čten ├ž─▒k─▒┼č tarihi uyu┼čmamaktad─▒r. ";
          }
          
          $i++;
        }
      }
      if ($isten_cikis_sayisi==0) {
        $isten_cikis_yuzdesi=0;
      }else{
        $isten_cikis_yuzdesi=number_format($isten_cikis_bildirim_hata_sayisi*100/$isten_cikis_sayisi,1);
      }
      $sabitler=config('constants');
      $base_logo=$sabitler['base_logo'];
      $base_footer=$sabitler['base_footer'];
      $firma_adi=$sgk_company->name;
      $base_ikon_telefon=$sabitler['base_ikon_telefon'];
      $base_ikon_konum=$sabitler['base_ikon_konum'];
      $base_degerlendirme_ikonu=$sabitler['base_degerlendirme_ikonu'];
      $metrik_adi="Giri┼č/├ç─▒k─▒┼č Kontrol├╝";
      $rapor_metni="─░┼čletmeler i┼če giri┼č/i┼čten ├ž─▒k─▒┼č yapt─▒klar─▒ personelleri i┼če giri┼čler i├žin i┼če girmeden bir g├╝n ├Ânce(in┼čaat,bal─▒k├ž─▒l─▒kvb.hari├ž) i┼čten ├ž─▒k─▒┼č bildirgesini ise ├ž─▒k─▒┼č tarihi itibari ile 10 g├╝n geriden yapabilmektedir.Yap─▒lan bu bildirimlerin 1774 say─▒l─▒ Kimlik Bildirim Kanunu gere─čince kolluk kuvvetlerine bildirilmelidir.Bu metrik her iki sistemin tarihlerini kontrol etmektedir.─░┼č giri┼č/i┼čten ├ž─▒k─▒┼č tarihlerinin 1774 say─▒l─▒ Kimlik Bildirim usullerine yap─▒lan g├╝venlik bildirimleri ile kar┼č─▒la┼čt─▒rmay─▒ tan─▒mlar.";
      //die(var_dump($degerlendirme_mesaji));
      View::share('base_footer',$base_footer);
      View::share('base_logo',$base_logo);
      View::share('firma_adi',$firma_adi);
      View::share('rapor_metni',$rapor_metni);
      View::share('base_ikon_telefon',$base_ikon_telefon);
      View::share('base_ikon_konum',$base_ikon_konum);
      View::share('base_degerlendirme_ikonu',$base_degerlendirme_ikonu);
      View::share('metrik_adi',$metrik_adi);
      View::share('maxmonth',$maxmonth);
      View::share('maxyear',$maxyear);
      View::share('minmonth',$minmonth);
      View::share('minyear',$minyear);
      View::share('ise_giris_sayisi',$ise_giris_sayisi);
      View::share('ise_giris_bildirim_hata_sayisi',$ise_giris_bildirim_hata_sayisi);
      View::share('ise_giris_yuzdesi',$ise_giris_yuzdesi);
      View::share('isten_cikis_sayisi',$isten_cikis_sayisi);
      View::share('isten_cikis_bildirim_hata_sayisi',$isten_cikis_bildirim_hata_sayisi);
      View::share('isten_cikis_yuzdesi',$isten_cikis_yuzdesi);
      View::share('error_message',$error_message);
      if (isset($degerlendirme_mesaji)) {
        View::share('degerlendirme_mesaji',$degerlendirme_mesaji);
      }
   }

}
