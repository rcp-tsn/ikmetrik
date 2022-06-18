<?php

namespace App\Http\Controllers;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CrmNotification;
use App\Models\Employee;
use App\Models\ModelHasRole;
use App\Models\Payroll;
use App\Models\PayrollCalender;
use App\Models\PayrollNotEmployee;
use App\Models\PayrollService;
use App\Models\SgkCompany;
use App\Models\SmsUser;
use App\Models\ZamaneUser;
use App\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use MongoDB\Driver\Session;

class ApiController extends Controller
{
    public function Login(Request $request)
    {
        $login = $request->only('email', 'password');

        if (Auth::attempt($login)) {
            $user = User::where('email', $request->email)->first();
            $role = ModelHasRole::where('model_id', $user->id)->get()->toArray();


            User::where('id', $user->id)
                ->update([
                    'remember_token' => $request->session()->token()
                ]);


            return response()->json([
                'status' => true,
                'data' => [
                    'email' => $user->email,
                    'name' => $user->name,
                    'company' => $user->company->name,
                    'companyLogo' => $user->company->logo,
                    'userImage' => $user->picture,
                    'created_at' => date('d/m/Y', strtotime($user->created_at)),
                    'token' => $request->session()->token(),
                    'role' => $role,

                ],
                'message' => 'Kullanıcı Girişi Başarılı',

            ]);

        } else {

            return 'kullanıcı Adı veya Şifre Hatalı';
        }

    }

    public function payrollsIndex($search, $page, $token)
    {
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => '0',
                'type' => 'error',
                'message' => 'Kullanıcı Bilgilerine Ulaşılamadı'
            ]);
        }
        $employee = Employee::where('id', $user->employee_id)->first();
        if (!$employee) {
            return response()->json([
                'status' => '0',
                'type' => 'error',
                'message' => 'Çalışan Bilgilerine Ulaşılamadı'
            ]);

        }

        $role = ModelHasRole::where('model_id', $user->id)
            ->where('role_id', 30)
            ->count();

        $infos = [];
        $data = [];
        if ($role > 0) {

            $period = [];
            $payrolls = Payroll::where('company_id', $user->company_id)->get();
            $dates = ['01' => 'OCAK', '02' => 'ŞUBAT', '03' => 'MART', '04' => 'NİSAN', '05' => 'MAYIS', '06' => 'HAZİRAN', '07' => 'TEMMUZ', '08' => 'AĞUSTOS', '09' => 'EYLÜL', '10' => 'EKİM', '11' => 'KASIM', '12' => 'ARALIK'];


            foreach ($payrolls as $key => $payroll) {
                if ($page == 0) {
                    if ($key <= 20) {
                        $data[] = [

                            'date' => $payroll->date->format('d/m/Y'),
                            'send_sms' => $payroll->sms_status,
                            'period' => $dates[$payroll->date->format('m')],
                            'payroll_id' => $payroll->id,
                            'employee_count' => $payroll->employee_count
                        ];
                    }
                }


                //  $period[$payroll->id] = $dates[$payroll->date->format('m')];


            }

            $infos = [
                'status' => 'true',
                'data' => $data,
                'message' => 'Listeleme Başarılı Bir Şekilde Yapıldı',
                'totalCount' => $payrolls->count(),
                'role' => 'e-bordro',

            ];
            return $infos;


        } else {


            $payroll_service = PayrollService::where('employee_id', $employee->id)->get()->pluck('payroll_id')->toArray();
            $payrolls = Payroll::whereIn('id', $payroll_service)->where('sms_status', 1)->get();

            $dates = ['01' => 'OCAK', '02' => 'ŞUBAT', '03' => 'MART', '04' => 'NİSAN', '05' => 'MAYIS', '06' => 'HAZİRAN', '07' => 'TEMMUZ', '08' => 'AĞUSTOS', '09' => 'EYLÜL', '10' => 'EKİM', '11' => 'KASIM', '12' => 'ARALIK'];

            foreach ($payrolls as $key => $payroll) {
                if ($page == 0) {
                    if ($key <= 20) {
                        $data[] =
                            [

                                'date' => $payroll->date->format('d/m/Y'),
                                'send_sms' => $payroll->sms_status,
                                'period' => $dates[$payroll->date->format('m')],
                                'payroll_id' => $payroll->id,
                                'employee_count' => $payroll->employee_count
                            ];
                    }

                } elseif ($page == 1) {
                    if ($key > 20 and $key <= 40) {
                        $infos[] = [
                            'status' => 'true',

                            'data' =>
                                [

                                    'date' => $payroll->date->format('d/m/Y'),
                                    'send_sms' => $payroll->sms_status,
                                    'period' => $dates[$payroll->date->format('m')],
                                    'payroll_id' => $payroll->id,
                                    'employee_count' => $payroll->employee_count
                                ],


                        ];
                    }
                } elseif ($page == 2) {
                    if ($key > 40 and $key <= 60) {
                        $infos[] = [
                            'status' => 'true',
                            'title' => 'Bordrolarım',
                            'data' =>
                                [

                                    'date' => $payroll->date->format('d/m/Y'),
                                    'send_sms' => $payroll->sms_status,
                                    'period' => $dates[$payroll->date->format('m')],
                                    'payroll_id' => $payroll->id,
                                    'employee_count' => $payroll->employee_count
                                ],


                        ];
                    }
                }

            }

            $infos = [
                'status' => 'true',
                'data' => $data,
                'message' => 'Listeleme Başarılı Bir Şekilde Yapıldı',
                'totalCount' => $payrolls->count(),
                'role' => 'Employee',

            ];
            return $infos;

        }


    }

    public function crmNotification($token)
    {
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => '0',
                'type' => 'error',
                'message' => 'Kullanıcıya Ulaşılamadı.'
            ]);
        }
        $newspapers = CrmNotification::where('company_id', $user->company_id)->get();
        $data = [];
        if (count($newspapers) == 0)
        {

            $data[] = ['title' => 'E-Bordro Slider',
                'message' => 'E-Bordro',
                'image' => 'https://ik.ikmetrik.com/assets/media/bg/ebordroslider.jpg'
            ];

            $data[] = ['title' => 'Performans Slider',
                'message' => 'Performans',
                'image' => 'https://ik.ikmetrik.com\assets\media\bg\performansslider.jpg'
            ];

            $data[] = ['title' => 'KVKK Slider',
                'message' => 'KVKK',
                'image' => 'https://ik.ikmetrik.com\assets\media\bg\kvkkslider.jpg'
            ];

            $news = [
                'status' => 'true',
                'title' => 'Haberler',
                'data' => $data
            ];
            return $news;
        } else {

            foreach ($newspapers as $newspaper) {
                $data[] =
                    [
                        'title' => $newspaper->title,
                        'message' => $newspaper->message,
                        'image' => env('APP_URL') . '/' . $newspaper->image
                    ];
            }

            $news = [
                'status' => 'true',
                'title' => 'Haberler',
                'data' => $data
            ];
            return $news;
        }

    }

    public function sgkCompany($token)
    {
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => '0',
                'type' => 'error',
                'message' => 'Kullanıcıya Ulaşılamadı.'
            ]);
        }
        $sgk_companies = SgkCompany::where('company_id', $user->company_id)->get();
        $data = [];
        if (count($sgk_companies) == 0) {
            $data[] =
                [
                    'title' => 'Firmaya ait şube bulunamadı.',
                    'message' => 'Şubeler'
                ];

            $news[] = [
                'status' => 'true',
                'title' => 'Şubeler',
                'data' => $data
            ];
            return $news;
        } else {

            foreach ($sgk_companies as $sgk_company) {
                $data[] =
                    [
                        'id' => $sgk_company->id,
                        'name' => $sgk_company->name,
                        'company_id' => $sgk_company->company_id,
                        'tax_number' => $sgk_company->tax_number,
                        'domain_name' => $sgk_company->domain_name
                    ];
            }

            $news[] = [
                'status' => 'true',
                'title' => 'Şubeler',
                'data' => $data
            ];
            return $news;
        }

    }

    public function cities($token)
    {
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => '0',
                'type' => 'error',
                'message' => 'Kullanıcıya Ulaşılamadı.'
            ]);
        }
        $cities = City::all();
        $data = [];
        if (count($cities) == 0) {
            $data[] =
                [
                    'message' => 'Şehir listesine ulaşılamadı'
                ];

            $news[] = [
                'status' => 'true',
                'data' => $data
            ];
            return $news;
        } else {

            foreach ($cities as $city) {
                $data[] =
                    [
                        'id' => $city->id,
                        'name' => $city->name
                    ];
            }

            $news[] = [
                'status' => 'true',
                'message' => 'Şehirler başarıyla listelendi',
                'data' => $data
            ];
            return $news;
        }

    }

    public function Logout(Request $request, $token)
    {
        $user = User::where('remember_token', $token)->first();

        if ($user)
        {
            User::where('id', $user->id)
                ->update([
                    'remember_token' => null
                ]);

            return response()->json([
                'status' => '1',
                'type' => 'success',
                'message' => 'Çıkış İşlemi Başarılı'
            ]);
        }
        else
        {
            return response()->json([
                'status' => '0',
                'type' => 'error',
                'message' => 'Çıkış Yapılamadı'
            ]);
        }
    }

    public function payroll_show($id,$token)
    {
        $user = User::where('remember_token',$token)->first();

        if (!isset($user->id))
        {
            return [
                'status'=>'0',
                'type'=>'error',
                'message' => 'Kullanıcı Bulunamadı'
            ];
        }
        $payroll = Payroll::where('id',$id)->first();
        if(!$payroll)
        {
            return [
                'status'=>'0',
                'message'=>'Sonuç Bulunamadı'
            ];
        }
        $employee = Employee::where('id',$user->employee_id)->first();
        if ($user->hasRole('e-bordro'))
        {
            $payrolls = PayrollService::where('payroll_id',$id)->get();
            $accept_payrolls = PayrollService::where('payroll_id',$id)->where('zamane_accept','1')->count();
            $danger_payrolls = PayrollService::where('payroll_id',$id)->where('zamane_accept','2')->count();
            $warning_payrolls = PayrollService::where('payroll_id',$id)->where('zamane_accept','0')->count();
//            $protests = PayrollProtest::where('payroll_id',$id)->get();
            $data = [];
            foreach ($payrolls as $payroll)
            {
                $data[] =
                    [
                        'name'=>$payroll->employee->full_name,
                        'payroll_id'=>$payroll->payroll_id,
                        'payroll_service_id'=>$payroll->id,
                        'employee_id'=>$employee->id,
                        'zamane_accept'=>$payroll->zamane_accept,
                        'accept_payrolls' => $accept_payrolls,
                        'danger_payrolls' => $danger_payrolls,
                        'warning_payrolls' => $warning_payrolls,
                        'file'=> env('APP_URL').'/'.$payroll->file,
                    ];
            }

            return  $infos = [
                'status'=>'1',
                'type'=>'success',
                'data'=>$data,
                'message'=>'Veri Çekme İşlemi Başarılı'
            ];

        }
        else
        {
            $employee = Employee::where('email',$user->employee_id)->first();
            if (!$employee)
            {
                return [
                    'status'=>'0',
                    'type'=>'error',
                    'message'=>'Çalışan Sonuç Bulunamadı'
                ];
            }
            $payrolls = PayrollService::where('payroll_id',$id)->where('employee_id',$employee->id)->get();
            $data = [];
            foreach ($payrolls as $payroll)
            {
                $data[] =
                    [
                        'name'=>$payroll->employee->full_name,
                        'payroll_id'=>$payroll->payroll_id,
                        'payroll_service_id'=>$payroll->id,
                        'employee_id'=>$employee->id,
                        'zamane_accept'=>$payroll->zamane_accept,
                        'file'=> env('APP_URL').'/'.$payroll->file,
                    ];
            }

            return  $infos = [
                'status'=>'1',
                'type'=>'success',
                'data'=>$data,
                'message'=>'Veri Çekme İşlemi Başarılı'
            ];


            // $protests = PayrollProtest::where('payroll_id',$id)->get();


        }

    }

    public function sendSMS($id,$token)
    {

        $user = User::where('remember_token',$token)->first();

        $employee = Employee::where('id',$user->employee_id)->first();
        if (!$employee)
        {
            return response()->json(
                [
                    'type'=>'error',
                    'message'=>'Personel Bulunmadı',
                ]
            );
        }
        $random = rand(0,999999);
        $employee->update([
            'sms_password' => $random
        ]);

        $sms = SmsUser::where('company_id',$employee->company_id)->first();

        if (!$sms)
        {
            return response()->json(
                [
                    'type'=>'error',
                    'message'=>'Sms Gönderilemedi',
                ]
            );
        }

        $message =   'Evrak Onaylamak/Reddetmek İçin Şifreniz: '.$random;
        sendSms($employee->company_id,$sms,null,$employee,$message);

        return response()->json([
            'type' => 'success',
            'message' => 'SMS Gönderimi Başarılı',
        ]);
    }

    public function employee_accept(Request $request,$token,$id)
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
        $src1->compositeImage($src2, \Imagick::COMPOSITE_MATHEMATICS, 0, 2780);
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


    public function payrollNotIn($id,$token)
    {

        $user = User::where('remember_token',$token)->first();
        //deneme yazısı
        if ($user->hasRole('e-bordro'))
        {
            if (!isset($user->id))
            {
                return [
                    'status'=>'0',
                    'type'=>'error',
                    'message' => 'Kullanıcı Bulunamadı'
                ];
            }
            $payroll = Payroll::where('id',$id)->first();
            if(!$payroll)
            {
                return [
                    'status'=>'0',
                    'type'=>'error',
                    'message'=>'Sonuç Bulunamadı'
                ];
            }
            $payrolls = PayrollNotEmployee::where('payroll_id',$id)->get();
            $datas = [];
            foreach ($payrolls as $payrolll){
                $datas[] =
                    [
                        'tc'=>$payrolll->tc,
                        'page'=>$payroll->page
                    ];
            }
            return   $infos =
                [
                    'status'=>'1',
                    'type'=>'success',
                    'data'=>$datas,
                    'message'=>'İşlem Başarılı'
                ];

        }
        else
        {
            return [
                'status'=>'0',
                'type'=>'error',
                'message' =>'Giriş Yetkiniz Yoktur'
            ];
        }


    }



}
