<?php

namespace App\Http\Controllers;

use App\Helpers\HashingSlug;
use App\Models\Company;
use App\Models\Payroll;
use App\Models\PayrollCalender;
use App\Models\PayrollService;
use App\Models\Employee;
use App\Models\SgkCompany;
use App\Models\PdkService;
use App\Models\ZamaneUser;
use App\User;
use Carbon\Carbon;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use QrCode;
use PDF;
use Treinetic\ImageArtist\lib\Shapes\PolygonShape;
use View;
use Treinetic\ImageArtist\lib\Shapes\Triangle;
use SoapClient;

class ZamaneController extends Controller
{
    public function employee_accept(Request $request)
    {
        $sgk_company_id = Auth::user()->sgk_company_id;
        $sgk_company = SgkCompany::find($sgk_company_id);
        $employee_id =  HashingSlug::decodeHash($request->working_id);
        $page = $request->page;
        $working = Employee::find($employee_id);
        $payrollService = PayrollService::find($request->payroll_id);
        $payroll = Payroll::find($payrollService->payroll_id);
        if (!$payroll)
        {
            return back()->with('danger','Bordro Bulunamadı');
        }

        $zamane = ZamaneUser::where('company_id',Auth::user()->company_id)->first();
        if (!$working)
        {
            return back()->with('error','Kayıt Bulunamadı İş Yeriniz İle İletişime Geçiniz');
        }
        if ($working->sms_password != $request->code)
        {
            return back()->with('error','Kod Hatalı Tekrar Kod Talep Ediniz');
        }

        $payroll_service = PayrollService::where('payroll_id',$payrollService->payroll_id)
            ->where('employee_id',$employee_id)
            ->where('page',$page)
            ->first();

        if (!$payroll_service)
        {
            return back()->with('error','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }

//        $client = new \SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");
//        try {
//            $result = $client->TCKimlikNoDogrula([
//                'TCKimlikNo' => $working->employee_personel->identity_number,
//                'Ad' => mb_strtoupper($working->first_name),
//                'Soyad' => mb_strtoupper($working->last_name),
//                'DogumYili' => $working->employee_personel->birth_date->year
//            ]);
//            if (!$result->TCKimlikNoDogrulaResult) {
//                return back()->with('danger', 'Damgalama İşlemi Yapılamıyor 3D Security TC KİMLİK NO DOĞRULANAMADI');
//            }
//
//        } catch (\Exception $e) {
//            return back()->with('danger','Damgalama İşlemi Yapılamıyor 3D Security TC KİMLİK DOĞRULANAMADI');
//        }

        $file = explode('.pdf',$payroll_service->file);
        $file2 = explode('/',$file[0]);

        File::makeDirectory('company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d'),0755,true,true);

        $fileName = public_path('company_payrolls' . DIRECTORY_SEPARATOR . Auth::user()->company_id.  DIRECTORY_SEPARATOR .'zamane'. DIRECTORY_SEPARATOR .$sgk_company_id.DIRECTORY_SEPARATOR.\date_format($payroll->date,'Y-m-d') .$file2[3].'_2.pdf');

        $randoms = rand(0,99999999999);
        $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf',public_path('qr_code/payrolls'.$file2[3].'_2.png'));


        $qrcode2 = public_path('qr_code/payrolls'.$file2[3].'_2.png');
        $qrcode1 = public_path('qr_code/payrolls'.$file2[3].'_2.png');
        $date = $payroll->updated_at;
        $date2 =  Carbon::now()->timezone('UTC');;
        $metin = '5070 Sayılı Elektronik İmza Kanuna Uygun Damgalanmıştır';
        $company = Company::find(Auth::user()->company_id);

        $pdf = PDF::loadView('payrolls.zamane.test',compact('qrcode1','qrcode2','date','date2','metin','company','working','sgk_company'));
        $header = View::make('payrolls.zamane.header', ['title' => 'Morepayroll' ])->render();
        $footer =  View::make('payrolls.zamane.footer')->render();
        $pdf->save(public_path().'/test_zamane/'.$randoms.'.pdf');


        $im2 = new \Imagick();
        $im2->setResolution(300,300);
        $im2->readimage(public_path().'/test_zamane/'.$randoms.'.pdf');
        $im2->setImageFormat('png');
        $im2->setSize(200, 700);
        $im2->writeImage('thumb2.png');
        $im2->clear();
        $im2->destroy();


        $im = new \Imagick();
        $im->setResolution(300,300);
        $im->readimage(public_path().'/company_payrolls'.$payroll_service->file);
        $im->setImageFormat('png');
        $im2->setSize(200, 1000);
        $im->writeImage('thumb.png');
        $im->clear();
        $im->destroy();

        $src1 = new \Imagick("thumb.png");
        $src2 = new \Imagick("thumb2.png");
        $src1->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        $src1->setImageArtifact('compose:args', "1,0,-0.5,0.5");
        $src1->compositeImage($src2, \Imagick::COMPOSITE_MATHEMATICS, 0, 2720);
//        $src1->compositeImage($src2, \Imagick::COMPOSITE_MATHEMATICS, 0, $src1->getImageHeight()/2);
        $src1->writeImage(public_path()."/oldu.png");



        $im = new \Imagick();
        $im->setResolution(300,300);
        $im->readimage(public_path().'/oldu.png');
        $im->setImageFormat('pdf');
        $im->writeImage(public_path().'/company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf');
        $im->clear();
        $im->destroy();

        $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.' '.public_path().'/company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf'.' '.$zamane->link.' '.$zamane->port.' '.$zamane->kullanici_adi.' '.$zamane->password.' '.$zamane->damga_type);

        if ($deger)
        {
            $file = '/company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf';
            $payroll_service->where('id',$payroll_service->id)->update([
                'zamane_accept' => 1,
                'file'=> $file,
                'updated_at'=> Carbon::now()->timezone('Europe/Istanbul')

            ]);

            $calender = PayrollCalender::where('payroll_service_id',$payroll_service->id)->update([
                'accept_date' => Carbon::now()->timezone('Europe/Istanbul'),
                'note'=>'Kişi Kendisi İmzaladı'
            ]);
            return back()->with('success','Onaylama İşlemi Başarılı');
        }
        else
        {
            return back()->with('error','Üzgünüz Hatayla Karşılaşıldı İK ile Görüşün');
        }

    }
    public function zamaneCron(Request $request)
    {

        $company_id = 228;

        $payrolllServicess = PayrollService::where('payroll_id',99)->where('zamane_accept',0)->get();
        foreach ($payrolllServicess as $payrollService)
        {

            $employee_id =  $payrollService->employee_id;
            $page = $payrollService->page;
            $working = Employee::find($employee_id);
            $payrollService = PayrollService::find($payrollService->id);
            $payroll = Payroll::find($payrollService->payroll_id);
            $sgk_company_id = $payroll->sgk_company_id;
            if (!$payroll)
            {

            }
            $zamane = ZamaneUser::where('company_id',$company_id)->first();

            if (!$working)
            {

            }
            if (!$zamane)
            {

            }

            $payroll_service = PayrollService::where('payroll_id',$payrollService->payroll_id)
                ->where('employee_id',$employee_id)
                ->where('page',$page)
                ->first();



            if (!$payroll_service)
            {

            }

//            $client = new \SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");
//            try {
//                $result = $client->TCKimlikNoDogrula([
//                    'TCKimlikNo' => $working->employee_personel->identity_number,
//                    'Ad' => mb_strtoupper($working->first_name),
//                    'Soyad' => mb_strtoupper($working->last_name),
//                    'DogumYili' => \date('Y',$working->birth_day)
//                ]);
//                if (!$result->TCKimlikNoDogrulaResult) {
//                    return back()->with('danger', 'Tc kimlik No Hatalı');
//                }
//
//            } catch (\Exception $e) {
//              return back()->with('danger','Damgalama İşlemi Yapılamıyor 3D TC KİMLİK NO DOĞRULANAMADI');
//            }
//            dd('ok');

            $file = explode('.pdf',$payroll_service->file);
            $file2 = explode('/',$file[0]);
            File::makeDirectory('company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d'),0755,true,true);
            $fileName = public_path('company_payrolls' . DIRECTORY_SEPARATOR . Auth::user()->company_id.  DIRECTORY_SEPARATOR .'zamane'. DIRECTORY_SEPARATOR .$sgk_company_id.DIRECTORY_SEPARATOR.\date_format($payroll->date,'Y-m-d') .$file2[3].'_2.pdf');
            $randoms = rand(0,99999999999);
            $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf',public_path('qr_code/payrolls'.$file2[3].'_2.png'));


            $qrcode2 = public_path('qr_code/payrolls'.$file2[3].'_2.png');
            $qrcode1 = public_path('qr_code/payrolls'.$file2[3].'_2.png');
            $date = $payroll->updated_at;
            $date2 =  Carbon::now()->timezone('UTC');;
            $metin = '5070 Sayılı Elektronik İmza Kanuna Uygun Damgalanmıştır';
            $company = Company::find(Auth::user()->company_id);

            $pdf = PDF::loadView('payrolls.zamane.test',compact('qrcode1','qrcode2','date','date2','metin','company','working'));
            $header = View::make('payrolls.zamane.header', ['title' => 'Morepayroll' ])->render();
            $footer =  View::make('payrolls.zamane.footer')->render();
            $pdf->save(public_path().'/test_zamane/'.$randoms.'.pdf');


            $im2 = new \Imagick();
            $im2->setResolution(300,300);
            $im2->readimage(public_path().'/test_zamane/'.$randoms.'.pdf');
            $im2->setImageFormat('png');
            $im2->setSize(200, 700);
            $im2->writeImage('thumb2.png');
            $im2->clear();
            $im2->destroy();


            $im = new \Imagick();
            $im->setResolution(300,300);
            $im->readimage(public_path().'/company_payrolls'.$payroll_service->file);
            $im->setImageFormat('png');
            $im2->setSize(200, 1000);
            $im->writeImage('thumb.png');
            $im->clear();
            $im->destroy();

            $tr1 = new Triangle("thumb.png");
            $tr2 = new Triangle("thumb2.png");
            $img = $tr1->merge($tr2,0,1200);
            $img->save('oldu.png',IMAGETYPE_PNG);


            $im = new \Imagick();
            $im->setResolution(300,300);
            $im->readimage(public_path().'/oldu.png');
            $im->setImageFormat('pdf');
            $im->writeImage(public_path().'/company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf');
            $im->clear();
            $im->destroy();


            $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.' '.public_path().'/company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'.pdf'.' '.$zamane->link.' '.$zamane->port.' '.$zamane->kullanici_adi.' '.$zamane->password.' '.$zamane->damga_type);
            User::create([
                'name'=>'293',
                'password'=>'293',
                'email'=>'recep293@morepayroll.com'
            ]);
            if ($deger)
            {
                $file = '/company_payrolls/'.Auth::user()->company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf';
                $payroll_service->where('id',$payroll_service->id)->update([
                    'zamane_accept' => 1,
                    'file'=> $file,
                    'updated_at'=> Carbon::now()->timezone('Europe/Istanbul')

                ]);

                $calender = PayrollCalender::where('payroll_service_id',$payroll_service->id)->update([
                    'accept_date' => Carbon::now()->timezone('Europe/Istanbul'),
                    'note'=>'Kişi Kendisi İmzaladı'
                ]);
                User::create([
                    'name'=>'293',
                    'password'=>'198',
                    'email'=>'recep565@morepayroll.com'
                ]);
            }
            else
            {
                User::create([
                    'name'=>'302',
                    'password'=>'198',
                    'email'=>'recep565@morepayroll.com'
                ]);
            }
        }



    }


    public function pdkAccept(Request $request)
    {

        File::makeDirectory('company_pdks/'.Auth::user()->company_id .'/zamane', 0777, true, true);
        $zamane = ZamaneUser::where('company_id',Auth::user()->company_id)->first();
        $employee_id =  HashingSlug::decodeHash($request->working_id);
        $page = $request->page;
        $working = Employee::find($employee_id);
        $pdkService = PdkService::find($request->payroll_id);
        if (!$working)
        {
            return back()->with('error','Kayıt Bulunamadı İş Yeriniz İle İletişime Geçiniz');
        }
        if ($working->sms_password != $request->code)
        {
            return back()->with('error','Kod Hatalı Tekrar Kod Talep Ediniz');
        }

        if (!$zamane)
        {
            return back()->with('danger','Zaman Damgasında Bi Hatayla Karşılaşıldı Onaylama İşlemi Başarısız İK ile görüşün');
        }

        $pdk_service = PdkService::where('pdk_id',$pdkService->pdk_id)
            ->where('employee_id',$employee_id)
            ->where('page',$page)
            ->first();

        if (!$pdk_service)
        {
            return back()->with('error','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }

        $file = explode('.pdf',$pdk_service->file);
        $file2 = explode('/',$file[0]);


        $fileName = public_path('company_pdks' . DIRECTORY_SEPARATOR . Auth::user()->company_id.  DIRECTORY_SEPARATOR .'zamane' .DIRECTORY_SEPARATOR. $file2[3].'_2.pdf');

        $randoms = rand(0,99999999999);
        $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/company_pdk/'.Auth::user()->company_id.'/zamane/'.$file2[3].'_2.pdf',public_path('qr_code/payrolls'.$file2[3].'_2.png'));


        $qrcode2 = public_path('qr_code/payrolls'.$file2[3].'_2.png');
        $qrcode1 = public_path('qr_code/payrolls'.$file2[3].'_2.png');
        $date = date('d/m/Y h:s:i');
        $date2 = date('d/m/Y h:s:i');
        $metin = '5070 Sayılı Elektronik İmza Kanuna Uygun Damgalanmıştır';
        $company = Company::find(Auth::user()->company_id);

        $pdf = PDF::loadView('payrolls.zamane.test',compact('qrcode1','qrcode2','date','date2','metin','company','working'));
        $header = View::make('payrolls.zamane.header', ['title' => 'Morepayroll' ])->render();
        $footer =  View::make('payrolls.zamane.footer')->render();
        $pdf->save(public_path().'/test_zamane/'.$randoms.'.pdf');


        $im2 = new \Imagick();
        $im2->setResolution(100,100);
        $im2->readimage(public_path().'/test_zamane/'.$randoms.'.pdf');
        $im2->setImageFormat('png');
        $im2->setSize(100, 100);
        $im2->writeImage('thumb2.png');
        $im2->clear();
        $im2->destroy();


        $im = new \Imagick();
        $im->setResolution(100,100);
        $im->readimage(public_path().$pdk_service->file);
        $im->setImageFormat('png');
        $im2->setSize(100, 100);
        $im->writeImage('thumb.png');
        $im->clear();
        $im->destroy();

        $tr1 = new Triangle("thumb.png");
        $tr2 = new Triangle("thumb2.png");
        $img = $tr1->merge($tr2,0,700);
        $img->save('oldu.png',IMAGETYPE_PNG);


        $im = new \Imagick();
        $im->setResolution(100,100);
        $im2->setSize(100, 100);
        $im->readimage(public_path().'/oldu.png');
        $im->setImageFormat('pdf');
        $im->writeImage(public_path().'/company_pdks/'.Auth::user()->company_id.'/zamane/'.$file2[3].'_2.pdf');
        $im->clear();
        $im->destroy();

        $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.' '.public_path().'/company_pdks/'.Auth::user()->company_id.'/zamane/'.$file2[3].'_2.pdf'.' '.$zamane->link.' '.$zamane->port.' '.$zamane->kullanici_adi.' '.$zamane->password.' '.$zamane->damga_type);

        if ($deger)
        {
            $file = '/company_pdks/'.Auth::user()->company_id.'/zamane/'.$file2[3].'_2.pdf';
            $pdk_service->where('id',$pdk_service->id)->update([
                'zamane_accept' => 1,
                'file'=> $file
            ]);
            return back()->with('success','Onaylama İşlemi Başarılı');
        }
        else
        {
            return back()->with('error','Üzgünüz Hatayla Karşılaşıldı İK ile Görüşün');
        }
    }



    public function serviceAccept(Request $request)
    {

        $token = 'ABUGHJ-25684-5655-AR569255';
        if (empty($request->_token))
        {
            return  \response()->json([
                'code'=>'110',
                'message'=>'Baglanti Kurulurken Hata Olustu. Lutfen "Token" bilgilerinizi giriniz.'
            ]);
        }
        if ($request->_token != $token)
        {
            return  \response()->json([
                'code'=>'111',
                'message'=>'Baglanti Kurulurken Hata Olustu. Lutfen "Token" bilgilerinizi kontrol ediniz.'
            ]);
        }

        if (empty($request->company_name))
        {
            return  \response()->json([
                'code'=>'113',
                'message'=>'HATA! Lutfen "Firma Adi" bilgisini giriniz.'
            ]);
        }

        if (empty($request->company_no))
        {
            return  \response()->json([
                'code'=>'114',
                'message'=>'HATA! Lutfen "Firma Vergi Numarasi" bilgisini giriniz.'
            ]);
        }

        if (empty($request->first_name))
        {
            return  \response()->json([
                'code'=>'115',
                'message'=>'HATA! Lutfen "Personel Adi" bilgisini giriniz.'
            ]);
        }

        if (empty($request->last_name))
        {
            return  \response()->json([
                'code'=>'116',
                'message'=>'HATA! Lutfen "Personel Soyadi" bilgisini giriniz.'
            ]);
        }

        if (empty($request->tc))
        {
            return  \response()->json([
                'code'=>'117',
                'message'=>'HATA! Lutfen "Personel TC Kimlik" bilgisini giriniz.'
            ]);
        }

        if (empty($request->birthday))
        {
            return  \response()->json([
                'code'=>'118',
                'message'=>'HATA! Lutfen "Personel Dogum Tarihi" bilgisini giriniz.'
            ]);
        }

        if (empty($request->zamane_id))
        {
            return  \response()->json([
                'code'=>'119',
                'message'=>'HATA! Lutfen Zaman Damgasi "Musteri ID" bilgisini giriniz.'
            ]);
        }

        if (empty($request->zamane_password))
        {
            return  \response()->json([
                'code'=>'120',
                'message'=>'HATA! Lutfen Zaman Damgasi "Sifre" bilgisini giriniz.'
            ]);
        }

        if (empty($request->zamane_link))
        {
            return  \response()->json([
                'code'=>'121',
                'message'=>'HATA! Lutfen Zaman Damgasi "Link" bilgisini giriniz.'
            ]);
        }

        if (empty($request->zamane_port))
        {
            return  \response()->json([
                'code'=>'122',
                'message'=>'HATA! Lutfen Zaman Damgasi "Port" bilgisini giriniz.'
            ]);
        }

        if (empty($request->zamane_type))
        {
            return  \response()->json([
                'code'=>'123',
                'message'=>'HATA! Lutfen Zaman Damgasi "Type" bilgisini giriniz.'
            ]);
        }

        $file = $request->file('file');

        if ($file) {

            $tck = $request->tc;
            $fileDate = date('Y-m-d');
            $destinationPath = 'payroll_services/'. 'disciplines/';
            $file->move($destinationPath, $tck.'-'.$fileDate.'.pdf');

            $filePath = $tck.'-'.$fileDate.'.pdf';
        }
        else
        {
            return  \response()->json([
                'code'=>'112',
                'message'=>'HATA! PDF dosyasi bulunamadi. Lutfen dosya secimi yaparak tekrar deneyiniz.'
            ]);
        }

        $birthday = $request->birthday;
        $zamane_id = $request->zamane_id;
        $zamane_password = $request->zamane_password;
        $zamane_link = $request->zamane_link;
        $zamane_port = $request->zamane_port;
        $zamane_type = $request->zamane_type;


//         $client = new \SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");
//
//             $result = $client->TCKimlikNoDogrula([
//                 'TCKimlikNo' => $request->tc,
//                 'Ad' => mb_strtoupper($request->first_name),
//                 'Soyad' => mb_strtoupper($request->last_name),
//                 'DogumYili' => $birthday
//
//             ]);
//
//        if (!$result->TCKimlikNoDogrulaResult) {
//            return 'Damgalama İşlemi Yapılamıyor 3D Security TC KİMLİK NO DOĞRULANAMADI';
//        }


        $pdftext = file_get_contents(public_path().'/payroll_services/disciplines/'.$filePath);

        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);

        if ($num > 1)
        {
            return  \response()->json([
                'code'=>'124',
                'message'=>'HATA! Sayfa sayısı fazla. Lüften sadece 1 sayfalık PDF yükleyiniz.'
            ]);
        }

        File::makeDirectory('company_payrolls/services/zamane/'.\date('Y-m-d'),0755,true,true);

        $path = '/company_payrolls/services/zamane/'.$filePath;

        $randoms = rand(0,99999999999);
        $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').$path.'_2.pdf',public_path('qr_code/payrolls/'. $tck.'-'.$fileDate.'_2.png'));

        $qrcode1 = public_path('qr_code/payrolls/'. $tck.'-'.$fileDate.'_2.png');
        $qrcode2 = public_path('qr_code/payrolls/'. $tck.'-'.$fileDate.'_2.png');

        $date =  Carbon::now()->timezone('UTC'); // $request->post_date;
        $date2 =  Carbon::now()->timezone('UTC');
        $metin = '5070 Sayılı Elektronik İmza Kanuna Uygun Damgalanmıştır';
        $company['name'] = $request->company_name;
        $company['no'] = $request->company_no;
        $working['first_name'] = $request->first_name;
        $working['last_name'] = $request->last_name;
        $working['tc'] = $request->tc;

        $pdf = PDF::loadView('payrolls.zamane.test2',compact('qrcode1','qrcode2','date','date2','metin','company','working'));

        $pdf->save(public_path().'/test_zamane/'.$randoms.'.pdf');


        $im2 = new \Imagick();
        $im2->setResolution(300,300);
        $im2->readimage(public_path().'/test_zamane/'.$randoms.'.pdf');
        $im2->setImageFormat('png');
        $im2->setSize(300, 500);
        $im2->writeImage('thumb3.png');
        $im2->clear();
        $im2->destroy();


        $im = new \Imagick();
        $im->setResolution(300,300);
        $im->readimage(public_path().'/'.$destinationPath.$filePath);
        $im->setImageFormat('png');
        $im->setSize(300, 500);
        $im->writeImage('thumb2.png');
        $im->clear();
        $im->destroy();

//        $src1 = new Triangle("thumb2.png");
//        $src2 = new Triangle("thumb3.png");
//        $img = $src1->merge($src2,0,$src1->getHeight());
//        $img->save('oldu2.png',IMAGETYPE_PNG);

        $src1 = new \Imagick("thumb2.png");
        $src2 = new \Imagick("thumb3.png");
        $src1->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        $src1->setImageArtifact('compose:args', "1,0,-0.5,0.5");
        $src1->compositeImage($src2, \Imagick::COMPOSITE_MATHEMATICS, 0, 3000);
        $src1->writeImage(public_path()."/oldu2.png");


        $im = new \Imagick();
        $im->setResolution(300,300);
        $im->readimage(public_path().'/oldu2.png');
        $im->setImageFormat('pdf');
        $im->writeImage(public_path().'/company_payrolls/services/zamane/'.\date('Y-m-d').'/'.$tck.'-'.$fileDate.'_2.pdf');
        $im->clear();
        $im->destroy();

        $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.' '.public_path().'/company_payrolls/services/zamane/'.\date('Y-m-d').'/'.$tck.'-'.$fileDate.'_2.pdf'.' '.$zamane_link.' '.$zamane_port.' '.$zamane_id.' "'.$zamane_password.'" '.$zamane_type);

        $pdfpath = public_path().'/company_payrolls/services/zamane/'.\date('Y-m-d').'/'.$tck.'-'.$fileDate.'_2.pdf';
        $zdpath = public_path().'/company_payrolls/services/zamane/'.\date('Y-m-d').'/'.$tck.'-'.$fileDate.'_2.pdf.zd';

        $zipname = $tck.'-'.$fileDate.'.zip' ;
        $zip = new \ZipArchive();
        $zip->open($zipname, \ZipArchive::CREATE);
        $zip->addEmptyDir('Bordro');
        $zip->addFile($pdfpath,'Bordro/'.$tck.'-'.$fileDate.'_2.pdf');
        $zip->addFile($zdpath,'Bordro/'.$tck.'-'.$fileDate.'_2.pdf.zd');
        $zip->close();


        $download_path = public_path().'/'.$zipname ;
        return response()->download($download_path);

//        $download_path = public_path().'/company_payrolls/services/zamane/'.\date('Y-m-d').'/'.$tck.'-'.$fileDate.'_2.pdf' ;
//
//        return response()->download($download_path);


    }


}
