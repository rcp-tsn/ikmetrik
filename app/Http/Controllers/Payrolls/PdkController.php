<?php
namespace App\Http\Controllers\Payrolls;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeePersonalInfo;
use App\Models\Payroll;
use App\Models\PayrollNotEmployee;
use App\Models\PayrollNotification;
use App\Models\PayrollPage;
use App\Models\PayrollProtest;
use App\Models\PdkNotEmployee;
use App\Models\PdkPage;
use App\Models\PdkProtest;
use App\Models\PdkService;
use App\Models\Pdk;
use App\Models\SmsUser;
use App\User;
use Carbon\Carbon;
use Gufy\PdfToHtml\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use QrCode;
use Vinkla\Hashids\Facades\Hashids;
use const http\Client\Curl\AUTH_ANY;

class PdkController extends Controller
{
    public function index()
    {
        session(['any_excel'=>false]);
        $user = Auth::user();
        $data = Auth::user()->email;

        if($user->hasAnyRole('e-bordro'))
        {

            $period = [];
            $pdks =  Pdk::where('company_id',Auth::user()->company_id)->get();
            $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];

            foreach ($pdks as $pdk)
            {
                $period[$pdk->id] = $dates[$pdk->date->format('m')];
            }

            return view('pdks.index',compact('pdks','period'));
        }
        elseif($user->hasAnyRole('Employee'))
        {

            $employee = Employee::where('email',$data)->first();

            if (!$employee)
            {
                return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
            }

            $pdks_service = PdkService::where('employee_id',$employee->id)->get()->pluck('pdk_id')->toArray();
            $pdks = Pdk::whereIn('id',$pdks_service)->where('sms_status',1)->get();

            $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];

            foreach ($pdks as $pdk)
            {
                $period[$pdk->id] = $dates[$pdk->date->format('m')];
            }

            return view('pdks.index',compact('pdks','period'));
        }
        else
        {
            return back()->with('danger','Giriş Yetkiniz Yoktur');
        }

    }

    public function pdk_show($id)
    {
        $id = HashingSlug::decodeHash($id);
        $payroll = Pdk::find($id);
        if (!$payroll)
        {
            return back()->with('danger','Kayıt Bulunamadı');
        }

        if (Auth::user()->hasRole('e-bordro'))
        {
            $pdks = PdkService::where('pdk_id',$id)->get();
            $accept_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','1')->count();
            $danger_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','2')->count();
            $warning_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','0')->count();
            $protests = PdkProtest::where('pdk_id',$id)->get();

            if (count($pdks) > 0)
            {
                return view('pdks.show',compact('pdks','accept_pdks','danger_pdks','warning_pdks','protests'));
            }
            else
            {
                return back()->with('danger','Eşleştirme Yapılamamıştır Bordronuzda Değişiklik Yapılmış Olabilir');
            }
        }
        else
        {
            $employee = Employee::where('email',Auth::user()->email)->first();
            if (!$employee)
            {
                return back()->with('errors','Beklenmeyen Bir Hatayla Karşılaşıldı');
            }
            $pdks = PdkService::where('pdk_id',$id)->where('employee_id',$employee->id)->get();
            $protests = PdkProtest::where('pdk_id',$id)->get();
            return view('pdks.show',compact('pdks','protests'));

        }
    }
    public function create()
    {
        return view('pdks.create');
    }
    public function pdfUploadSave(Request $request)
    {

        session(['errorss' => null]);
        $company = Company::find(Auth::user()->company_id);
        if (!$company)
        {
            return response()->json([
                'code' => 'ERROR',
                'message' => 'FİRMA BULUNAMADI',
                'progress' => 0,
                'step' => ''
            ]);
        }
        $incitementDate = Carbon::now()->startofMonth()->subMonth()->toDateString();
        if (strtolower($request->pdf->getClientOriginalExtension()) == "pdf") {
            $fileName = public_path('company_pdks' . DIRECTORY_SEPARATOR . Auth::user()->company_id .DIRECTORY_SEPARATOR. Auth::user()->company_id. '-'. $incitementDate);
            $request->file('pdf')->move($fileName , $incitementDate.'.pdf');
        }

        session(['file_name'=> $fileName.'/'.$incitementDate.'.pdf']);
        session(['date'=>$incitementDate]);
        $pdk = Pdk::where("date", $incitementDate)
            ->where("company_id", Auth::user()->company_id)
            ->first();


        if (!$pdk)
        {
            $pdf = new Pdf($fileName.'/'.$incitementDate.'.pdf');
            $pdk =  Pdk::create([
                'company_id' => Auth::user()->company_id,
                'date' => $incitementDate,
                'create_user_id' => Auth::user()->id,
                'employee_count'=> $pdf->getPages()

            ]);
        }

        session(['pdk_id' => $pdk->id]);
        $readfile = $fileName.'/'.$incitementDate.'.pdf';

        $pdf = new Pdf($readfile);
        for ($i = 1; $i <= $pdf->getPages(); $i++) {
            $control = PdkPage::where('pdk_id',$pdk->id)->where('page',$i)->count();
            if ($control == 0)
            {
                PdkPage::create([
                    'pdk_id'=> $pdk->id,
                    'page' => $i
                ]);
            }

        }
        session(["progress" => 0]);
        session(['pdf_path' => $fileName]);
        return response()->json([
            'ver' => "1.0",
            'ret' => true,
            'errmsg' => null,
            'errcode' => 0,
            'data' => [
                'status' => 'success',
                'count' => '',
                'progress' => 10,
            ]
        ]);

    }
    public function pdfUploadStore()
    {

        $readfile = session()->get('file_name');
        $incitementDate = session()->get('date');
        $path = session()->get('pdf_path');
        $pdf = new Pdf($readfile);
        $limit = -3;
        $timeOutLimit = 3;
        $pdks_id = session()->get('pdk_id');
        $pages = PdkPage::where('pdk_id',$pdks_id)->where('parse',0)->get()->pluck('page')->toArray();
        $pageCount = count($pages);

        if (count($pages) > 0)
        {
            foreach ($pages as $page)
            {
                $limit++;
                if ($limit > $timeOutLimit) {
                    $progress = session()->get('progress') + 1;
                    session(["progress" => $progress]);
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => 'Bordrolar Ayrımı Yapılıyor',
                            'progress' => $progress,
                            'step' => 'Pdk_pdf_Set',
                        ]);
                }

                $pdfMerger = PdfMerger::init(); //Initialize the merger
                $pdfMerger->addPDF($readfile, $page);
                $pdfMerger->merge();
                $pdfMerger->save( $path.'/'.$page.'-'.$incitementDate.'.pdf');

                $update = PdkPage::where('pdk_id',$pdks_id)->where('page',$page)->update([
                    'parse'=>1
                ]);

            }
        }

        session(["progress" => 50]);
        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
                'progress' => 50,
                'step' => 'pdkParse2'
            ]);
        // return redirect(route('bordrolama.index'))->with('success','İşlem Bşarılı');
    }
    public function pdfUploadStore2()
    {
        $readfile = session()->get('file_name');
        $incitementDate = session()->get('date');
        $path = session()->get('pdf_path');
        $pdk_id = session()->get('pdk_id');
        $pages = PdkPage::where('pdk_id', $pdk_id)->where('parse2', 0)->get()->pluck('page')->toArray();

        if (count($pages) > 0) {

            $limit = -3;
            $pageCount = count($pages);
            $timeOutLimit = 3;
            $pdk_id = session()->get('pdk_id');
            $pages = PdkPage::where('pdk_id', $pdk_id)->where('parse2', 0)->get()->pluck('page')->toArray();
            if (count($pages) > 0) {
                foreach ($pages as $page) {

                    $limit++;
                    if ($limit > $timeOutLimit) {
                        $progress = session()->get('progress') + 1;
                        session(["progress" => $progress]);
                        return response()
                            ->json([
                                'code' => 'TIMEOUT',
                                'message' => 'Bordrolar Eşleştirme Yapılıyor.',
                                'progress' => $progress,
                                'step' => 'pdkParse2',
                            ]);
                    }
                    $readfile = $path.'/'.$page.'-'.$incitementDate.'.pdf';
                    $pdf = new Pdf($readfile);
                    $html = $pdf->html();
                    $identity_number =  pdkEmployeeTcPdf($html);

                    //pdk için yeni yazılacak

                    $employee_tc = EmployeePersonalInfo::where('identity_number', substr(trim($identity_number),0,11))->first();
                    if ($employee_tc)
                    {
                        $employee = Employee::where('id',$employee_tc->employee_id)->where('company_id',Auth::user()->company_id)->first();
                    }
                    else
                    {
                        $employee = false;
                    }
                    if ($employee) {
                        $control = Pdk::where("date", $incitementDate)
                            ->where("company_id", Auth::user()->company_id)
                            ->first();

                        $file = $readfile;
                        $save = new PdkService();
                        $save->employee_id = $employee->id;
                        $save->pdk_id = $control->id;
                        $save->file = '/company_pdks/'.Auth::user()->company_id.'/'.Auth::user()->company_id.'-'.$incitementDate.'/'.$page.'-'.$incitementDate.'.pdf';
                        $save->page = $page;
                        $save->save();
                    }
                    else
                    {
                        if(is_numeric(trim($identity_number)))
                        {
                            PdkNotEmployee::create([
                                'pdk_id' => $pdk_id,
                                'page' => $page,
                                'tc' => trim($identity_number)
                            ]);
                        }
                    }

//                            }
//                        }
//                    }

                    $update = PdkPage::where('pdk_id', $pdk_id)->where('page', $page)->update([
                        'parse2' => 1
                    ]);
                }
            }

            return response()
                ->json([
                    'code' => 'FINISH',
                    'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
                    'progress' => 100,
                    'url' => route('pdks.index')
                ]);
        }
        else
        {
            return response()
                ->json([
                    'code' => 'FINISH',
                    'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
                    'progress' => 100,
                    'url' => route('pdks.index')
                ]);
        }

    }
    public function pdk_not_in($id)
    {
        $id = HashingSlug::decodeHash($id);
        $pdks = PdkNotEmployee::where('pdk_id',$id)->get();

        if (count($pdks) <=0)
        {
            return back()->with('danger','Tüm Puantajlar Eşleşti');
        }
        return view('pdks.not_in',compact('pdks'));
    }
    public function employee_sms($id)
    {
        $id = (int)$id;
        $pdk = Pdk::find($id);
        if (!$pdk)
        {
            return back()->with('error','Hata İşlem Yapılamadı');
        }
        $payroll_service = PdkService::where('pdk_id',$id)->where('sms_status',0)->get();
        $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
        $company = Company::where('id',Auth::user()->company_id)->first();
        if (!$sms)
        {
            return back()->with('error','Kayıt Başarılı Fakat Sms Hizmeti Kayıtlı Değil');
        }
        $link = env('APP_URL');
        $datas = $link;

        foreach ($payroll_service as $service)
        {
            $working = Employee::find($service->employee_id);
            $message =  $company->name.' '.$pdk->date->format('d/m/Y').' Dönemine Ait PUANTAJ (GİRİŞ ÇIKIŞ BİLGİLERİNİZ) Yüklenmiştir İncelemeniz/Onaylamanız Önemle Rica OLunur'.' Link:'.$datas.' '.'Kullanıcı Adınız : '.' '.$working->email.' '.'Şifreniz  :'.$working->login_password;
            sendSms($company->id,$sms,$pdk,$working,$message);
//            $data = [
//                'username' => $sms->username,
//                'password' => $sms->password,
//                'sdate' => null,
//                'speriod' => '48',
//                'message' =>
//                    [
//                        'sender' => $sms->sender,
//                        'text' =>  "" ,
//                        'utf8' => '1',
//                        'gsm' => [
//                            $working->mobile
//                        ]
//                    ]
//            ];
//
//           $return =  Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);

            PdkService::where('id',$service->id)->update([
                'sms_status'=>1
            ]);
        }

        Pdk::where('id',$id)->update([
            'sms_status'=>1
        ]);

        return redirect(route('pdks.index'))->with('success','SMS GÖNDERİM SAĞLANDI');

    }
    public function employee_pdk_protest(Request $request)
    {
        $working_id = HashingSlug::decodeHash($request->working_id);
        $pdk_id = $request->payroll_id;
        $page = $request->page;
        $payroll = Pdk::find($pdk_id);

        if (!$payroll)
        {
            return back()->with('error','beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        $working = Employee::find($working_id);
        if (!$working)
        {
            return back()->with('error','Bilgilerinize Ulaşılamadı İK ile Görüşün');
        }

        $protest = PdkProtest::create([
            'company_id'=>Auth::user()->company_id,
            'pdk_id'=>$pdk_id,
            'employee_id'=>$working_id,
            'date' => date('Y-m-d'),
            'notification'=>$request->notification
        ]);
        $pdk_service = PdkService::where('pdk_id',$pdk_id)
            ->where('employee_id',$working_id)
            ->where('page',$page)
            ->first();

        if ($pdk_service)
        {
            PdkService::where('pdk_id',$pdk_id)
                ->where('employee_id',$working_id)
                ->where('page',$page)
                ->update([
                    'zamane_accept' => 2
                ]);
        }
        $company = Company::find(Auth::user()->company_id);
        if ($protest)
        {
             //  $user =  User::where('company_id',Auth::user()->company_id)->where('user_type_id',1)->orWhere('user_type_id',2)->where('company_id',Auth::user()->company_id)->first();
            //  Mail::to($user->email)->send(new PayrollMail($working,$company,$protest,$payroll));
            return back()->with('success','İşleminiz Başarılı');
        }
        else
        {
            return back()->with('error','İşleminiz Başarısız');
        }
    }
    public function update(Request $request)
    {
        $working_id = HashingSlug::decodeHash($request->employee_id);
        $pdk = Pdk::find($request->payroll_id);
        if (!$pdk)
        {
            return back()->with('danger','Puantaj Bulunmadı');
        }
        $working = Employee::find($working_id);
        if (!$working)
        {
            return back()->with('danger','Çalışan Bulunamadı');
        }
        $company = Company::find(Auth::user()->company_id);

        $pdk_service = PdkService::where('pdk_id',$pdk->id)->where('page',$request->page_id)
            ->where('employee_id',$working_id)
            ->first();

        if (!$pdk_service)
        {
            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        $file = explode('.pdf',$pdk_service->file);
        $file2 = explode('/',$file[0]);
        $file3 = explode('-',$file2[3]);

        $incitementDate = $file3[1].'-'.$file3[2].'-'.$file3[3];

        if (strtolower($request->pdf->getClientOriginalExtension()) == "pdf") {
            $fileName = public_path('company_pdks' . DIRECTORY_SEPARATOR . Auth::user()->company_id .DIRECTORY_SEPARATOR. Auth::user()->company_id. '-'. $incitementDate);
            $request->file('pdf')->move($fileName , $request->page_id.'-'.$incitementDate.'.pdf');
        }
        if ($request->sms == 'on' )
        {
            $pdk_service->update([
                'zamane_accept'=>0,
            ]);
            $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();

            if ($sms and $company)
            {
                $message = $pdk->date->format('d/m/Y').' Dönemine Ait Puantaj Revize İşlemi Yapılmıştır Onaylamanız/Reddetmeniz Önemle Rica OLunur';
                try {
                    sendSms($company->id,$sms,null,$working,$message);
                }
                catch (\Exception $ex)
                {
                    return back()->with('danger','İşleminiz Gerçekleştirildi fakat sms gönderilemedi');
                }


//                $data = [
//                    'username' => $sms->username,
//                    'password' => $sms->password,
//                    'sdate' => null,
//                    'speriod' => '48',
//                    'message' =>
//                        [
//                            'sender' => $sms->sender,
//                            'text' =>  $payroll->date->format('d/m/Y').' Dönemine Ait Bordronuz Revize İşlemi Yapılmıştır Onaylamanız/Reddetmeniz Önemle Rica OLunur',
//                            'utf8' => '1',
//                            'gsm' => [
//                                $working->mobile
//                            ]
//                        ]
//                ];
//
//                Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);
            }
            return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');

        }
        else
        {
            $pdk_service->update([
                'zamane_accept'=>0,
            ]);
            return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');
        }
    }
    public function delete($id)
    {
        $id = HashingSlug::decodeHash($id);
        $control = Pdk::find($id);
        if (!$control)
        {
            return back()->with('danger','Bordrolar Bulunamadı');
        }
        $pdk = Pdk::where('id',$id)->delete([]);
        PdkService::where('pdk_id',$id)->delete([]);
        PdkPage::where('pdk_id',$id)->delete([]);
        PdkNotEmployee::where('pdk_id',$id)->delete([]);
        PdkProtest::where('pdk_id',$id)->delete([]);
        if ($pdk)
        {
            return redirect(route('pdks.index'))->with('success','Silme İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Silme işlemi Başarısız');
        }
    }

    public function pdkAccept($id)
    {
        dd($id);
    }
}





// <?php

// namespace App\Http\Controllers\Payrolls;

// use App\Helpers\HashingSlug;
// use App\Http\Controllers\Controller;
// use App\Models\Company;
// use App\Models\Employee;
// use App\Models\EmployeePersonalInfo;
// use App\Models\Payroll;
// use App\Models\PayrollNotEmployee;
// use App\Models\PayrollNotification;
// use App\Models\PayrollPage;
// use App\Models\PayrollProtest;
// use App\Models\PdkNotEmployee;
// use App\Models\PdkPage;
// use App\Models\PdkProtest;
// use App\Models\PdkService;
// use App\Models\Pdk;
// use App\Models\SmsUser;
// use App\User;
// use Carbon\Carbon;
// use Gufy\PdfToHtml\Pdf;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Storage;
// use LynX39\LaraPdfMerger\Facades\PdfMerger;
// use QrCode;
// use Vinkla\Hashids\Facades\Hashids;
// use const http\Client\Curl\AUTH_ANY;

// class PdkController extends Controller
// {
//     public function index()
//     {
//         session(['any_excel'=>false]);
//         $user = Auth::user();
//         $data = Auth::user()->email;

//         if($user->hasAnyRole('e-bordro'))
//         {

//             $period = [];
//             $pdks =  Pdk::where('company_id',Auth::user()->company_id)->get();
//             $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];

//             foreach ($pdks as $pdk)
//             {
//                 $period[$pdk->id] = $dates[$pdk->date->format('m')];
//             }

//             return view('pdks.index',compact('pdks','period'));
//         }
//         elseif($user->hasAnyRole('Employee'))
//         {

//             $employee = Employee::where('email',$data)->first();

//             if (!$employee)
//             {
//                 return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
//             }

//             $pdks_service = PdkService::where('employee_id',$employee->id)->get()->pluck('pdk_id')->toArray();
//             $pdks = Pdk::whereIn('id',$pdks_service)->where('sms_status',1)->get();

//             $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];

//             foreach ($pdks as $pdk)
//             {
//                 $period[$pdk->id] = $dates[$pdk->date->format('m')];
//             }

//             return view('pdks.index',compact('pdks','period'));
//         }
//         else
//         {
//             return back()->with('danger','Giriş Yetkiniz Yoktur');
//         }

//     }

//     public function pdk_show($id)
//     {
//         $id = HashingSlug::decodeHash($id);
//         $payroll = Pdk::find($id);
//         if (!$payroll)
//         {
//             return back()->with('danger','Kayıt Bulunamadı');
//         }

//         if (Auth::user()->hasRole('e-bordro'))
//         {
//             $pdks = PdkService::where('pdk_id',$id)->get();
//             $accept_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','1')->count();
//             $danger_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','2')->count();
//             $warning_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','0')->count();
//             $protests = PdkProtest::where('pdk_id',$id)->get();

//             if (count($pdks) > 0)
//             {
//                 return view('pdks.show',compact('pdks','accept_pdks','danger_pdks','warning_pdks','protests'));
//             }
//             else
//             {
//                 return back()->with('danger','Eşleştirme Yapılamamıştır Bordronuzda Değişiklik Yapılmış Olabilir');
//             }
//         }
//         else
//         {
//             $employee = Employee::where('email',Auth::user()->email)->first();
//             if (!$employee)
//             {
//                 return back()->with('errors','Beklenmeyen Bir Hatayla Karşılaşıldı');
//             }
//             $pdks = PdkService::where('pdk_id',$id)->where('employee_id',$employee->id)->get();
//             $protests = PdkProtest::where('pdk_id',$id)->get();
//             return view('pdks.show',compact('pdks','protests'));

//         }
//     }
//     public function create()
//     {
//         return view('pdks.create');
//     }



//     public function pdfUploadSave(Request $request)
//     {

//         session(['errorss' => null]);
//         $company = Company::find(Auth::user()->company_id);
//         if (!$company)
//         {
//             return response()->json([
//                 'code' => 'ERROR',
//                 'message' => 'FİRMA BULUNAMADI',
//                 'progress' => 0,
//                 'step' => ''
//             ]);
//         }
//         $incitementDate = Carbon::now()->startofMonth()->subMonth()->toDateString();
//         if (strtolower($request->pdf->getClientOriginalExtension()) == "pdf") {
//             $fileName = public_path('company_pdks' . DIRECTORY_SEPARATOR . Auth::user()->company_id .DIRECTORY_SEPARATOR. Auth::user()->company_id. '-'. $incitementDate);
//             $request->file('pdf')->move($fileName , $incitementDate.'.pdf');
//         }

//         session(['file_name'=> $fileName.'/'.$incitementDate.'.pdf']);
//         session(['date'=>$incitementDate]);
//         $pdk = Pdk::where("date", $incitementDate)
//             ->where("company_id", Auth::user()->company_id)
//             ->first();


//         if (!$pdk)
//         {
//             $pdf = new Pdf($fileName.'/'.$incitementDate.'.pdf');
//             $pdk =  Pdk::create([
//                 'company_id' => Auth::user()->company_id,
//                 'date' => $incitementDate,
//                 'create_user_id' => Auth::user()->id,
//                 'employee_count'=> $pdf->getPages()

//             ]);
//         }

//         session(['pdk_id' => $pdk->id]);
//         $readfile = $fileName.'/'.$incitementDate.'.pdf';

//         $pdf = new Pdf($readfile);
//         for ($i = 1; $i <= $pdf->getPages(); $i++) {
//             $control = PdkPage::where('pdk_id',$pdk->id)->where('page',$i)->count();
//             if ($control == 0)
//             {
//                 PdkPage::create([
//                     'pdk_id'=> $pdk->id,
//                     'page' => $i
//                 ]);
//             }

//         }
//         session(["progress" => 0]);
//         session(['pdf_path' => $fileName]);
//         return response()->json([
//             'ver' => "1.0",
//             'ret' => true,
//             'errmsg' => null,
//             'errcode' => 0,
//             'data' => [
//                 'status' => 'success',
//                 'count' => '',
//                 'progress' => 10,
//             ]
//         ]);

//     }

//     public function pdfUploadStore()
//     {

//         $readfile = session()->get('file_name');
//         $incitementDate = session()->get('date');
//         $path = session()->get('pdf_path');
//         $pdf = new Pdf($readfile);
//         $limit = -3;
//         $timeOutLimit = 3;
//         $pdks_id = session()->get('pdk_id');
//         $pages = PdkPage::where('pdk_id',$pdks_id)->where('parse',0)->get()->pluck('page')->toArray();
//         $pageCount = count($pages);

//         if (count($pages) > 0)
//         {
//             foreach ($pages as $page)
//             {
//                 $limit++;
//                 if ($limit > $timeOutLimit) {
//                     $progress = session()->get('progress') + 1;
//                     session(["progress" => $progress]);
//                     return response()
//                         ->json([
//                             'code' => 'TIMEOUT',
//                             'message' => 'Bordrolar Ayrımı Yapılıyor',
//                             'progress' => $progress,
//                             'step' => 'Pdk_pdf_Set',
//                         ]);
//                 }

//                 $pdfMerger = PdfMerger::init(); //Initialize the merger
//                 $pdfMerger->addPDF($readfile, $page);
//                 $pdfMerger->merge();
//                 $pdfMerger->save( $path.'/'.$page.'-'.$incitementDate.'.pdf');

//                 $update = PdkPage::where('pdk_id',$pdks_id)->where('page',$page)->update([
//                     'parse'=>1
//                 ]);

//             }
//         }

//         session(["progress" => 50]);
//         return response()
//             ->json([
//                 'code' => 'SUCCESS',
//                 'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
//                 'progress' => 50,
//                 'step' => 'pdkParse2'
//             ]);
//         // return redirect(route('bordrolama.index'))->with('success','İşlem Bşarılı');
//     }
//     public function pdfUploadStore2()
//     {
//         $readfile = session()->get('file_name');
//         $incitementDate = session()->get('date');
//         $path = session()->get('pdf_path');
//         $pdk_id = session()->get('pdk_id');
//         $pages = PdkPage::where('pdk_id', $pdk_id)->where('parse2', 0)->get()->pluck('page')->toArray();

//         if (count($pages) > 0) {

//             $limit = -3;
//             $pageCount = count($pages);
//             $timeOutLimit = 3;
//             $pdk_id = session()->get('pdk_id');
//             $pages = PdkPage::where('pdk_id', $pdk_id)->where('parse2', 0)->get()->pluck('page')->toArray();
//             if (count($pages) > 0) {
//                 foreach ($pages as $page) {

//                     $limit++;
//                     if ($limit > $timeOutLimit) {
//                         $progress = session()->get('progress') + 1;
//                         session(["progress" => $progress]);
//                         return response()
//                             ->json([
//                                 'code' => 'TIMEOUT',
//                                 'message' => 'Bordrolar Eşleştirme Yapılıyor.',
//                                 'progress' => $progress,
//                                 'step' => 'pdkParse2',
//                             ]);
//                     }
//                     $readfile = $path.'/'.$page.'-'.$incitementDate.'.pdf';
//                     $pdf = new Pdf($readfile);
//                     $html = $pdf->html();

//                     $identity_number =  employeeTcPdf($html);
//                     //pdk için yeni yazılacak

//                     $employee_tc = EmployeePersonalInfo::where('identity_number', substr(trim($identity_number),0,11))->first();
//                     if ($employee_tc)
//                     {
//                         $employee = Employee::where('id',$employee_tc->employee_id)->where('company_id',Auth::user()->company_id)->first();
//                     }
//                     else
//                     {
//                         $employee = false;
//                     }
//                     if ($employee) {
//                         $control = Pdk::where("date", $incitementDate)
//                             ->where("company_id", Auth::user()->company_id)
//                             ->first();

//                         $file = $readfile;
//                         $save = new PdkService();
//                         $save->employee_id = $employee->id;
//                         $save->pdk_id = $control->id;
//                         $save->file = '/company_pdks/'.Auth::user()->company_id.'/'.Auth::user()->company_id.'-'.$incitementDate.'/'.$page.'-'.$incitementDate.'.pdf';
//                         $save->page = $page;
//                         $save->save();
//                     }
//                     else
//                     {
//                         if(is_numeric(trim($identity_number)))
//                         {
//                             PdkNotEmployee::create([
//                                 'pdk_id' => $pdk_id,
//                                 'page' => $page,
//                                 'tc' => trim($identity_number)
//                             ]);
//                         }
//                     }

// //                            }
// //                        }
// //                    }

//                     $update = PdkPage::where('pdk_id', $pdk_id)->where('page', $page)->update([
//                         'parse2' => 1
//                     ]);
//                 }
//             }

//             return response()
//                 ->json([
//                     'code' => 'FINISH',
//                     'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
//                     'progress' => 100,
//                     'url' => route('pdks.index')
//                 ]);
//         }
//         else
//         {
//             return response()
//                 ->json([
//                     'code' => 'FINISH',
//                     'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
//                     'progress' => 100,
//                     'url' => route('pdks.index')
//                 ]);
//         }

//     }
//     public function pdk_not_in($id)
//     {
//         $id = HashingSlug::decodeHash($id);
//         $pdks = PdkNotEmployee::where('pdk_id',$id)->get();

//         if (count($pdks) <=0)
//         {
//             return back()->with('danger','Tüm Puantajlar Eşleşti');
//         }
//         return view('pdks.not_in',compact('pdks'));
//     }

//     public function employee_sms($id)
//     {
//         $id = (int)$id;
//         $pdk = Pdk::find($id);
//         if (!$pdk)
//         {
//             return back()->with('error','Hata İşlem Yapılamadı');
//         }
//         $payroll_service = PdkService::where('pdk_id',$id)->where('sms_status',0)->get();
//         $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
//         $company = Company::where('id',Auth::user()->company_id)->first();
//         if (!$sms)
//         {
//             return back()->with('error','Kayıt Başarılı Fakat Sms Hizmeti Kayıtlı Değil');
//         }
//         $link = env('APP_URL');
//         $datas = $link;

//         foreach ($payroll_service as $service)
//         {
//             $working = Employee::find($service->employee_id);
//             $message =  $company->name.' '.$pdk->date->format('d/m/Y').' Dönemine Ait PUANTAJ (GİRİŞ ÇIKIŞ BİLGİLERİNİZ) Yüklenmiştir İncelemeniz/Onaylamanız Önemle Rica OLunur'.' Link:'.$datas.' '.'Kullanıcı Adınız : '.' '.$working->email.' '.'Şifreniz  :'.$working->login_password;
//             sendSms($company->id,$sms,$pdk,$working,$message);
// //            $data = [
// //                'username' => $sms->username,
// //                'password' => $sms->password,
// //                'sdate' => null,
// //                'speriod' => '48',
// //                'message' =>
// //                    [
// //                        'sender' => $sms->sender,
// //                        'text' =>  "" ,
// //                        'utf8' => '1',
// //                        'gsm' => [
// //                            $working->mobile
// //                        ]
// //                    ]
// //            ];
// //
// //           $return =  Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);

//             PdkService::where('id',$service->id)->update([
//                 'sms_status'=>1
//             ]);
//         }

//         Pdk::where('id',$id)->update([
//             'sms_status'=>1
//         ]);

//         return redirect(route('pdks.index'))->with('success','SMS GÖNDERİM SAĞLANDI');

//     }
//     public function employee_pdk_protest(Request $request)
//     {
//         $working_id = HashingSlug::decodeHash($request->working_id);
//         $pdk_id = $request->payroll_id;
//         $page = $request->page;
//         $payroll = Pdk::find($pdk_id);

//         if (!$payroll)
//         {
//             return back()->with('error','beklenmeyen Bir Hatayla Karşılaşıldı');
//         }
//         $working = Employee::find($working_id);
//         if (!$working)
//         {
//             return back()->with('error','Bilgilerinize Ulaşılamadı İK ile Görüşün');
//         }

//         $protest = PdkProtest::create([
//             'company_id'=>Auth::user()->company_id,
//             'pdk_id'=>$pdk_id,
//             'employee_id'=>$working_id,
//             'date' => date('Y-m-d'),
//             'notification'=>$request->notification
//         ]);
//         $pdk_service = PdkService::where('pdk_id',$pdk_id)
//             ->where('employee_id',$working_id)
//             ->where('page',$page)
//             ->first();

//         if ($pdk_service)
//         {
//             PdkService::where('pdk_id',$pdk_id)
//                 ->where('employee_id',$working_id)
//                 ->where('page',$page)
//                 ->update([
//                     'zamane_accept' => 2
//                 ]);
//         }
//         $company = Company::find(Auth::user()->company_id);
//         if ($protest)
//         {
//              //  $user =  User::where('company_id',Auth::user()->company_id)->where('user_type_id',1)->orWhere('user_type_id',2)->where('company_id',Auth::user()->company_id)->first();
//             //  Mail::to($user->email)->send(new PayrollMail($working,$company,$protest,$payroll));
//             return back()->with('success','İşleminiz Başarılı');
//         }
//         else
//         {
//             return back()->with('error','İşleminiz Başarısız');
//         }
//     }
//     public function update(Request $request)
//     {
//         $working_id = HashingSlug::decodeHash($request->employee_id);
//         $pdk = Pdk::find($request->payroll_id);
//         if (!$pdk)
//         {
//             return back()->with('danger','Puantaj Bulunmadı');
//         }
//         $working = Employee::find($working_id);
//         if (!$working)
//         {
//             return back()->with('danger','Çalışan Bulunamadı');
//         }
//         $company = Company::find(Auth::user()->company_id);

//         $pdk_service = PdkService::where('pdk_id',$pdk->id)->where('page',$request->page_id)
//             ->where('employee_id',$working_id)
//             ->first();

//         if (!$pdk_service)
//         {
//             return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
//         }
//         $file = explode('.pdf',$pdk_service->file);
//         $file2 = explode('/',$file[0]);
//         $file3 = explode('-',$file2[3]);

//         $incitementDate = $file3[1].'-'.$file3[2].'-'.$file3[3];

//         if (strtolower($request->pdf->getClientOriginalExtension()) == "pdf") {
//             $fileName = public_path('company_pdks' . DIRECTORY_SEPARATOR . Auth::user()->company_id .DIRECTORY_SEPARATOR. Auth::user()->company_id. '-'. $incitementDate);
//             $request->file('pdf')->move($fileName , $request->page_id.'-'.$incitementDate.'.pdf');
//         }
//         if ($request->sms == 'on' )
//         {
//             $pdk_service->update([
//                 'zamane_accept'=>0,
//             ]);
//             $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();

//             if ($sms and $company)
//             {
//                 $message = $pdk->date->format('d/m/Y').' Dönemine Ait Puantaj Revize İşlemi Yapılmıştır Onaylamanız/Reddetmeniz Önemle Rica OLunur';
//                 try {
//                     sendSms($company->id,$sms,null,$working,$message);
//                 }
//                 catch (\Exception $ex)
//                 {
//                     return back()->with('danger','İşleminiz Gerçekleştirildi fakat sms gönderilemedi');
//                 }


// //                $data = [
// //                    'username' => $sms->username,
// //                    'password' => $sms->password,
// //                    'sdate' => null,
// //                    'speriod' => '48',
// //                    'message' =>
// //                        [
// //                            'sender' => $sms->sender,
// //                            'text' =>  $payroll->date->format('d/m/Y').' Dönemine Ait Bordronuz Revize İşlemi Yapılmıştır Onaylamanız/Reddetmeniz Önemle Rica OLunur',
// //                            'utf8' => '1',
// //                            'gsm' => [
// //                                $working->mobile
// //                            ]
// //                        ]
// //                ];
// //
// //                Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);
//             }
//             return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');

//         }
//         else
//         {
//             $pdk_service->update([
//                 'zamane_accept'=>0,
//             ]);
//             return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');
//         }
//     }

//     public function delete($id)
//     {
//         $id = HashingSlug::decodeHash($id);
//         $control = Pdk::find($id);
//         if (!$control)
//         {
//             return back()->with('danger','Bordrolar Bulunamadı');
//         }
//         $pdk = Pdk::where('id',$id)->delete([]);
//         PdkService::where('pdk_id',$id)->delete([]);
//         PdkPage::where('pdk_id',$id)->delete([]);
//         PdkNotEmployee::where('pdk_id',$id)->delete([]);
//         PdkProtest::where('pdk_id',$id)->delete([]);
//         if ($pdk)
//         {
//             return redirect(route('pdks.index'))->with('success','Silme İşlemi Başarılı');
//         }
//         else
//         {
//             return back()->with('danger','Silme işlemi Başarısız');
//         }
//     }
// }



//
//namespace App\Http\Controllers\Payrolls;
//
//use App\Helpers\HashingSlug;
//use App\Http\Controllers\Controller;
//use App\Models\Company;
//use App\Models\Employee;
//use App\Models\EmployeePersonalInfo;
//use App\Models\Payroll;
//use App\Models\PayrollNotEmployee;
//use App\Models\PayrollNotification;
//use App\Models\PayrollPage;
//use App\Models\PayrollProtest;
//use App\Models\PdkNotEmployee;
//use App\Models\PdkPage;
//use App\Models\PdkProtest;
//use App\Models\PdkService;
//use App\Models\Pdk;
//use App\Models\SmsUser;
//use App\User;
//use Carbon\Carbon;
//use Gufy\PdfToHtml\Pdf;
//use Illuminate\Support\Facades\Mail;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Http;
//use Illuminate\Support\Facades\Storage;
//use LynX39\LaraPdfMerger\Facades\PdfMerger;
//use QrCode;
//use Vinkla\Hashids\Facades\Hashids;
//use const http\Client\Curl\AUTH_ANY;
//
//class PdkController extends Controller
//{
//    public function index()
//    {
//        session(['any_excel'=>false]);
//        $user = Auth::user();
//        $data = Auth::user()->email;
//
//        if($user->hasAnyRole('e-bordro'))
//        {
//
//            $period = [];
//            $pdks =  Pdk::where('company_id',Auth::user()->company_id)->get();
//            $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];
//
//            foreach ($pdks as $pdk)
//            {
//                $period[$pdk->id] = $dates[$pdk->date->format('m')];
//            }
//
//            return view('pdks.index',compact('pdks','period'));
//        }
//        elseif($user->hasAnyRole('Employee'))
//        {
//
//            $employee = Employee::where('email',$data)->first();
//
//            if (!$employee)
//            {
//                return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
//            }
//
//            $pdks_service = PdkService::where('employee_id',$employee->id)->get()->pluck('pdk_id')->toArray();
//            $pdks = Pdk::whereIn('id',$pdks_service)->where('sms_status',1)->get();
//
//            $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];
//
//            foreach ($pdks as $pdk)
//            {
//                $period[$pdk->id] = $dates[$pdk->date->format('m')];
//            }
//
//            return view('pdks.index',compact('pdks','period'));
//        }
//        else
//        {
//            return back()->with('danger','Giriş Yetkiniz Yoktur');
//        }
//
//    }
//
//    public function pdk_show($id)
//    {
//        $id = HashingSlug::decodeHash($id);
//        $payroll = Pdk::find($id);
//        if (!$payroll)
//        {
//            return back()->with('danger','Kayıt Bulunamadı');
//        }
//
//        if (Auth::user()->hasRole('e-bordro'))
//        {
//            $pdks = PdkService::where('pdk_id',$id)->get();
//            $accept_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','1')->count();
//            $danger_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','2')->count();
//            $warning_pdks = PdkService::where('pdk_id',$id)->where('zamane_accept','0')->count();
//            $protests = PdkProtest::where('pdk_id',$id)->get();
//
//            if (count($pdks) > 0)
//            {
//                return view('pdks.show',compact('pdks','accept_pdks','danger_pdks','warning_pdks','protests'));
//            }
//            else
//            {
//                return back()->with('danger','Eşleştirme Yapılamamıştır Bordronuzda Değişiklik Yapılmış Olabilir');
//            }
//        }
//        else
//        {
//            $employee = Employee::where('email',Auth::user()->email)->first();
//            if (!$employee)
//            {
//                return back()->with('errors','Beklenmeyen Bir Hatayla Karşılaşıldı');
//            }
//            $pdks = PdkService::where('pdk_id',$id)->where('employee_id',$employee->id)->get();
//            $protests = PdkProtest::where('pdk_id',$id)->get();
//            return view('pdks.show',compact('pdks','protests'));
//
//        }
//    }
//    public function create()
//    {
//        return view('pdks.create');
//    }
//
//
//
//    public function pdfUploadSave(Request $request)
//    {
//
//        session(['errorss' => null]);
//        $company = Company::find(Auth::user()->company_id);
//        if (!$company)
//        {
//            return response()->json([
//                'code' => 'ERROR',
//                'message' => 'FİRMA BULUNAMADI',
//                'progress' => 0,
//                'step' => ''
//            ]);
//        }
//        $incitementDate = Carbon::now()->startofMonth()->subMonth()->toDateString();
//        if (strtolower($request->pdf->getClientOriginalExtension()) == "pdf") {
//            $fileName = public_path('company_pdks' . DIRECTORY_SEPARATOR . Auth::user()->company_id .DIRECTORY_SEPARATOR. Auth::user()->company_id. '-'. $incitementDate);
//            $request->file('pdf')->move($fileName , $incitementDate.'.pdf');
//        }
//
//        session(['file_name'=> $fileName.'/'.$incitementDate.'.pdf']);
//        session(['date'=>$incitementDate]);
//        $pdk = Pdk::where("date", $incitementDate)
//            ->where("company_id", Auth::user()->company_id)
//            ->first();
//
//
//        if (!$pdk)
//        {
//            $pdf = new Pdf($fileName.'/'.$incitementDate.'.pdf');
//            $pdk =  Pdk::create([
//                'company_id' => Auth::user()->company_id,
//                'date' => $incitementDate,
//                'create_user_id' => Auth::user()->id,
//                'employee_count'=> $pdf->getPages()
//
//            ]);
//        }
//
//        session(['pdk_id' => $pdk->id]);
//        $readfile = $fileName.'/'.$incitementDate.'.pdf';
//
//        $pdf = new Pdf($readfile);
//        for ($i = 1; $i <= $pdf->getPages(); $i++) {
//            $control = PdkPage::where('pdk_id',$pdk->id)->where('page',$i)->count();
//            if ($control == 0)
//            {
//                PdkPage::create([
//                    'pdk_id'=> $pdk->id,
//                    'page' => $i
//                ]);
//            }
//
//        }
//        session(["progress" => 0]);
//        session(['pdf_path' => $fileName]);
//        return response()->json([
//            'ver' => "1.0",
//            'ret' => true,
//            'errmsg' => null,
//            'errcode' => 0,
//            'data' => [
//                'status' => 'success',
//                'count' => '',
//                'progress' => 10,
//            ]
//        ]);
//
//    }
//
//    public function pdfUploadStore()
//    {
//
//        $readfile = session()->get('file_name');
//        $incitementDate = session()->get('date');
//        $path = session()->get('pdf_path');
//        $pdf = new Pdf($readfile);
//        $limit = -3;
//        $timeOutLimit = 3;
//        $pdks_id = session()->get('pdk_id');
//        $pages = PdkPage::where('pdk_id',$pdks_id)->where('parse',0)->get()->pluck('page')->toArray();
//        $pageCount = count($pages);
//
//        if (count($pages) > 0)
//        {
//            foreach ($pages as $page)
//            {
//                $limit++;
//                if ($limit > $timeOutLimit) {
//                    $progress = session()->get('progress') + 1;
//                    session(["progress" => $progress]);
//                    return response()
//                        ->json([
//                            'code' => 'TIMEOUT',
//                            'message' => 'Bordrolar Ayrımı Yapılıyor',
//                            'progress' => $progress,
//                            'step' => 'Pdk_pdf_Set',
//                        ]);
//                }
//
//                $pdfMerger = PdfMerger::init(); //Initialize the merger
//                $pdfMerger->addPDF($readfile, $page);
//                $pdfMerger->merge();
//                $pdfMerger->save( $path.'/'.$page.'-'.$incitementDate.'.pdf');
//
//                $update = PdkPage::where('pdk_id',$pdks_id)->where('page',$page)->update([
//                    'parse'=>1
//                ]);
//
//            }
//        }
//
//        session(["progress" => 50]);
//        return response()
//            ->json([
//                'code' => 'SUCCESS',
//                'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
//                'progress' => 50,
//                'step' => 'pdkParse2'
//            ]);
//        // return redirect(route('bordrolama.index'))->with('success','İşlem Bşarılı');
//    }
//    public function pdfUploadStore2()
//    {
//        $readfile = session()->get('file_name');
//        $incitementDate = session()->get('date');
//        $path = session()->get('pdf_path');
//        $pdk_id = session()->get('pdk_id');
//        $pages = PdkPage::where('pdk_id', $pdk_id)->where('parse2', 0)->get()->pluck('page')->toArray();
//
//        if (count($pages) > 0) {
//
//            $limit = -3;
//            $pageCount = count($pages);
//            $timeOutLimit = 3;
//            $pdk_id = session()->get('pdk_id');
//            $pages = PdkPage::where('pdk_id', $pdk_id)->where('parse2', 0)->get()->pluck('page')->toArray();
//            if (count($pages) > 0) {
//                foreach ($pages as $page) {
//
//                    $limit++;
//                    if ($limit > $timeOutLimit) {
//                        $progress = session()->get('progress') + 1;
//                        session(["progress" => $progress]);
//                        return response()
//                            ->json([
//                                'code' => 'TIMEOUT',
//                                'message' => 'Bordrolar Eşleştirme Yapılıyor.',
//                                'progress' => $progress,
//                                'step' => 'pdkParse2',
//                            ]);
//                    }
//                    $readfile = $path.'/'.$page.'-'.$incitementDate.'.pdf';
//                    $pdf = new Pdf($readfile);
//                    $html = $pdf->html();
//
//                    $identity_number =  employeeTcPdf($html);
//                    //pdk için yeni yazılacak
//
//                    $employee_tc = EmployeePersonalInfo::where('identity_number', substr(trim($identity_number),0,11))->first();
//                    if ($employee_tc)
//                    {
//                        $employee = Employee::where('id',$employee_tc->employee_id)->where('company_id',Auth::user()->company_id)->first();
//                    }
//                    else
//                    {
//                        $employee = false;
//                    }
//                    if ($employee) {
//                        $control = Pdk::where("date", $incitementDate)
//                            ->where("company_id", Auth::user()->company_id)
//                            ->first();
//
//                        $file = $readfile;
//                        $save = new PdkService();
//                        $save->employee_id = $employee->id;
//                        $save->pdk_id = $control->id;
//                        $save->file = '/company_pdks/'.Auth::user()->company_id.'/'.Auth::user()->company_id.'-'.$incitementDate.'/'.$page.'-'.$incitementDate.'.pdf';
//                        $save->page = $page;
//                        $save->save();
//                    }
//                    else
//                    {
//                        if(is_numeric(trim($identity_number)))
//                        {
//                            PdkNotEmployee::create([
//                                'pdk_id' => $pdk_id,
//                                'page' => $page,
//                                'tc' => trim($identity_number)
//                            ]);
//                        }
//                    }
//
////                            }
////                        }
////                    }
//
//                    $update = PdkPage::where('pdk_id', $pdk_id)->where('page', $page)->update([
//                        'parse2' => 1
//                    ]);
//                }
//            }
//
//            return response()
//                ->json([
//                    'code' => 'FINISH',
//                    'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
//                    'progress' => 100,
//                    'url' => route('pdks.index')
//                ]);
//        }
//        else
//        {
//            return response()
//                ->json([
//                    'code' => 'FINISH',
//                    'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
//                    'progress' => 100,
//                    'url' => route('pdks.index')
//                ]);
//        }
//
//    }
//    public function pdk_not_in($id)
//    {
//        $id = HashingSlug::decodeHash($id);
//        $pdks = PdkNotEmployee::where('pdk_id',$id)->get();
//
//        if (count($pdks) <=0)
//        {
//            return back()->with('danger','Tüm Puantajlar Eşleşti');
//        }
//        return view('pdks.not_in',compact('pdks'));
//    }
//
//    public function employee_sms($id)
//    {
//        $id = (int)$id;
//        $pdk = Pdk::find($id);
//        if (!$pdk)
//        {
//            return back()->with('error','Hata İşlem Yapılamadı');
//        }
//        $payroll_service = PdkService::where('pdk_id',$id)->where('sms_status',0)->get();
//        $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
//        $company = Company::where('id',Auth::user()->company_id)->first();
//        if (!$sms)
//        {
//            return back()->with('error','Kayıt Başarılı Fakat Sms Hizmeti Kayıtlı Değil');
//        }
//        $link = env('APP_URL');
//        $datas = $link;
//
//        foreach ($payroll_service as $service)
//        {
//            $working = Employee::find($service->employee_id);
//            $message =  $company->name.' '.$pdk->date->format('d/m/Y').' Dönemine Ait PUANTAJ (GİRİŞ ÇIKIŞ BİLGİLERİNİZ) Yüklenmiştir İncelemeniz/Onaylamanız Önemle Rica OLunur'.' Link:'.$datas.' '.'Kullanıcı Adınız : '.' '.$working->email.' '.'Şifreniz  :'.$working->login_password;
//            sendSms($company->id,$sms,$pdk,$working,$message);
////            $data = [
////                'username' => $sms->username,
////                'password' => $sms->password,
////                'sdate' => null,
////                'speriod' => '48',
////                'message' =>
////                    [
////                        'sender' => $sms->sender,
////                        'text' =>  "" ,
////                        'utf8' => '1',
////                        'gsm' => [
////                            $working->mobile
////                        ]
////                    ]
////            ];
////
////           $return =  Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);
//
//            PdkService::where('id',$service->id)->update([
//                'sms_status'=>1
//            ]);
//        }
//
//        Pdk::where('id',$id)->update([
//            'sms_status'=>1
//        ]);
//
//        return redirect(route('pdks.index'))->with('success','SMS GÖNDERİM SAĞLANDI');
//
//    }
//    public function employee_pdk_protest(Request $request)
//    {
//        $working_id = HashingSlug::decodeHash($request->working_id);
//        $pdk_id = $request->payroll_id;
//        $page = $request->page;
//        $payroll = Pdk::find($pdk_id);
//
//        if (!$payroll)
//        {
//            return back()->with('error','beklenmeyen Bir Hatayla Karşılaşıldı');
//        }
//        $working = Employee::find($working_id);
//        if (!$working)
//        {
//            return back()->with('error','Bilgilerinize Ulaşılamadı İK ile Görüşün');
//        }
//
//        $protest = PdkProtest::create([
//            'company_id'=>Auth::user()->company_id,
//            'pdk_id'=>$pdk_id,
//            'employee_id'=>$working_id,
//            'date' => date('Y-m-d'),
//            'notification'=>$request->notification
//        ]);
//        $pdk_service = PdkService::where('pdk_id',$pdk_id)
//            ->where('employee_id',$working_id)
//            ->where('page',$page)
//            ->first();
//
//        if ($pdk_service)
//        {
//            PdkService::where('pdk_id',$pdk_id)
//                ->where('employee_id',$working_id)
//                ->where('page',$page)
//                ->update([
//                    'zamane_accept' => 2
//                ]);
//        }
//        $company = Company::find(Auth::user()->company_id);
//        if ($protest)
//        {
//             //  $user =  User::where('company_id',Auth::user()->company_id)->where('user_type_id',1)->orWhere('user_type_id',2)->where('company_id',Auth::user()->company_id)->first();
//            //  Mail::to($user->email)->send(new PayrollMail($working,$company,$protest,$payroll));
//            return back()->with('success','İşleminiz Başarılı');
//        }
//        else
//        {
//            return back()->with('error','İşleminiz Başarısız');
//        }
//    }
//    public function update(Request $request)
//    {
//        $working_id = HashingSlug::decodeHash($request->employee_id);
//        $pdk = Pdk::find($request->payroll_id);
//        if (!$pdk)
//        {
//            return back()->with('danger','Puantaj Bulunmadı');
//        }
//        $working = Employee::find($working_id);
//        if (!$working)
//        {
//            return back()->with('danger','Çalışan Bulunamadı');
//        }
//        $company = Company::find(Auth::user()->company_id);
//
//        $pdk_service = PdkService::where('pdk_id',$pdk->id)->where('page',$request->page_id)
//            ->where('employee_id',$working_id)
//            ->first();
//
//        if (!$pdk_service)
//        {
//            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
//        }
//        $file = explode('.pdf',$pdk_service->file);
//        $file2 = explode('/',$file[0]);
//        $file3 = explode('-',$file2[3]);
//
//        $incitementDate = $file3[1].'-'.$file3[2].'-'.$file3[3];
//
//        if (strtolower($request->pdf->getClientOriginalExtension()) == "pdf") {
//            $fileName = public_path('company_pdks' . DIRECTORY_SEPARATOR . Auth::user()->company_id .DIRECTORY_SEPARATOR. Auth::user()->company_id. '-'. $incitementDate);
//            $request->file('pdf')->move($fileName , $request->page_id.'-'.$incitementDate.'.pdf');
//        }
//        if ($request->sms == 'on' )
//        {
//            $pdk_service->update([
//                'zamane_accept'=>0,
//            ]);
//            $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
//
//            if ($sms and $company)
//            {
//                $message = $pdk->date->format('d/m/Y').' Dönemine Ait Puantaj Revize İşlemi Yapılmıştır Onaylamanız/Reddetmeniz Önemle Rica OLunur';
//                try {
//                    sendSms($company->id,$sms,null,$working,$message);
//                }
//                catch (\Exception $ex)
//                {
//                    return back()->with('danger','İşleminiz Gerçekleştirildi fakat sms gönderilemedi');
//                }
//
//
////                $data = [
////                    'username' => $sms->username,
////                    'password' => $sms->password,
////                    'sdate' => null,
////                    'speriod' => '48',
////                    'message' =>
////                        [
////                            'sender' => $sms->sender,
////                            'text' =>  $payroll->date->format('d/m/Y').' Dönemine Ait Bordronuz Revize İşlemi Yapılmıştır Onaylamanız/Reddetmeniz Önemle Rica OLunur',
////                            'utf8' => '1',
////                            'gsm' => [
////                                $working->mobile
////                            ]
////                        ]
////                ];
////
////                Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);
//            }
//            return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');
//
//        }
//        else
//        {
//            $pdk_service->update([
//                'zamane_accept'=>0,
//            ]);
//            return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');
//        }
//    }
//
//    public function delete($id)
//    {
//        $id = HashingSlug::decodeHash($id);
//        $control = Pdk::find($id);
//        if (!$control)
//        {
//            return back()->with('danger','Bordrolar Bulunamadı');
//        }
//        $pdk = Pdk::where('id',$id)->delete([]);
//        PdkService::where('pdk_id',$id)->delete([]);
//        PdkPage::where('pdk_id',$id)->delete([]);
//        PdkNotEmployee::where('pdk_id',$id)->delete([]);
//        PdkProtest::where('pdk_id',$id)->delete([]);
//        if ($pdk)
//        {
//            return redirect(route('pdks.index'))->with('success','Silme İşlemi Başarılı');
//        }
//        else
//        {
//            return back()->with('danger','Silme işlemi Başarısız');
//        }
//    }
//}
