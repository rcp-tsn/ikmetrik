<?php

namespace App\Http\Controllers\Payrolls;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Model\Working;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeePersonalInfo;
use App\Models\Payroll;
use App\Models\PayrollCalender;
use App\Models\PayrollNotEmployee;
use App\Models\PayrollNotification;
use App\Models\PayrollPage;
use App\Models\PayrollProtest;
use App\Models\PayrollService;
use App\Models\PoldyPayroll;
use App\Models\SgkCompany;
use App\Models\SmsUser;
use App\Models\ZamaneUser;
use App\User;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Gufy\PdfToHtml\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use QrCode;
use Vinkla\Hashids\Facades\Hashids;
use function Couchbase\defaultDecoder;


//use PDF;
use View;

class PayrollController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = Auth::user()->email;
        $sgk_company = getSgkCompany();

        if($user->hasAnyRole('e-bordro'))
        {

            $period = [];
            $payrolls =  Payroll::where('company_id',Auth::user()->company_id)->where('sgk_company_id',$sgk_company->id)->get();
            $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];

            foreach ($payrolls as $payroll)
            {
                $period[$payroll->id] = $dates[$payroll->date->format('m')];
            }

            return view('payrolls.index',compact('payrolls','period'));
        }
        elseif($user->hasAnyRole('Employee'))
        {
            $employee = Employee::where('email',$data)->first();
            if (!$employee)
            {
                return back()->with('errors','Beklenmeyen Bir Hatayla Karşılaşıldı');
            }

            $payroll_service = PayrollService::where('employee_id',$employee->id)->get()->pluck('payroll_id')->toArray();
            $payrolls = Payroll::whereIn('id',$payroll_service)->where('sms_status',1)->get();

            $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];

            foreach ($payrolls as $payroll)
            {
                $period[$payroll->id] = $dates[$payroll->date->format('m')];
            }

            return view('payrolls.index',compact('payrolls','period'));
        }
        else
        {
            return back()->with('danger','Giriş Yetkiniz Yoktur');
        }

    }
    public function update(Request $request)
    {

        $working_id = HashingSlug::decodeHash($request->employee_id);
        $payroll = Payroll::find($request->payroll_id);
        if (!$payroll)
        {
            return back()->with('danger','Bordro Bulunmadı');
        }
        $working = Employee::find($working_id);
        if (!$working)
        {
            return back()->with('danger','Çalışan Bulunamadı');
        }
        $company = Company::find(Auth::user()->company_id);

          $payroll_service = PayrollService::where('payroll_id',$payroll->id)->where('page',$request->page_id)
            ->where('employee_id',$working_id)
            ->first();

        if (!$payroll_service)
        {
            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        if (!empty($payroll->sgk_company_id))
        {
            $sgk_company = SgkCompany::find($payroll->sgk_company_id);
        }
        else
        {
            $sgk_company = SgkCompany::find(Auth::user()->sgk_company_id);
        }


       $file = explode('.pdf',$payroll_service->file);
       $file2 = explode('/',$file[0]);
       $file3 = explode('-',$file2[3]);



      // $incitementDate = $file3[1].'-'.$file3[2].'-'.$file3[3];
       $incitementDate = $payroll->date;

        if (strtolower($request->pdf->getClientOriginalExtension()) == "pdf") {
            $fileName = public_path('company_payrolls' . DIRECTORY_SEPARATOR . Auth::user()->company_id .DIRECTORY_SEPARATOR.   $sgk_company->id . DIRECTORY_SEPARATOR.  $sgk_company->id. '-'. $incitementDate);
            $request->file('pdf')->move($fileName , $request->page_id.'-'.$incitementDate.'.pdf');
        }
        if ($request->sms == 'on' )
        {
            $payroll_service->update([
                'zamane_accept'=>0,
            ]);
            $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
            $link = env('APP_URL');
            $datas = $link;

            if ($sms and $company)
            {

                $loginUrl = env('APP_URL').'/payroll/login/'.'more'.'/'.$working->token.'/'.createHashId($payroll->id);
                $message =  $company->name.' '.$payroll->date->format('d/m/Y').' '.$request->notification.'  Onaylamanız/Reddetmeniz Önemle Rica OLunur'.' Link:'.$loginUrl;
                sendSms($company->id,$sms,$payroll,$working,$message);
            }
            return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');

        }
        else
        {
            $payroll_service->update([
                'zamane_accept' => 0,
            ]);
            return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');
        }
    }
    public function pdfUpload()
    {
        return view('payrolls.create');
    }
    public function pdfUploadSave(Request $request)
    {
        $sgk_company = getSgkCompany();
        session(['payroll_id' => '']);
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
            $fileName = public_path('company_payrolls' . DIRECTORY_SEPARATOR . Auth::user()->company_id .DIRECTORY_SEPARATOR.   $sgk_company->id . DIRECTORY_SEPARATOR.  $sgk_company->id. '-'. $incitementDate);
            $request->file('pdf')->move($fileName , $incitementDate.'.pdf');
        }


            session(['file_name'=> $fileName.'/'.$incitementDate.'.pdf']);
            session(['date'=>$incitementDate]);
            $payroll = Payroll::where("date", $incitementDate)
            ->where("company_id", Auth::user()->company_id)
            ->where('sgk_company_id',$sgk_company->id)
            ->first();





        if (!$payroll)
        {

            $pdf = new Pdf($fileName.'/'.$incitementDate.'.pdf');
            $payroll =  Payroll::create([
                'company_id' => Auth::user()->company_id,
                'sgk_company_id' => $sgk_company->id,
                'date' => $incitementDate,
                'create_user_id' => Auth::user()->id,
                'employee_count'=> $pdf->getPages(),
            ]);

        }

        session(['payroll_id' => $payroll->id]);
        $readfile = $fileName.'/'.$incitementDate.'.pdf';

        $pdf = new Pdf($readfile);
        for ($i = 1; $i <= $pdf->getPages(); $i++) {
            $control = PayrollPage::where('payroll_id',$payroll->id)->where('page',$i)->count();
            if ($control == 0)
            {
                PayrollPage::create([
                    'payroll_id'=> $payroll->id,
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
        $payroll_id = session()->get('payroll_id');
        $pages = PayrollPage::where('payroll_id',$payroll_id)->where('parse',0)->get()->pluck('page')->toArray();
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
                            'step' => 'pdf_Set',
                        ]);
                }

                                $pdfMerger = PdfMerger::init(); //Initialize the merger
                                $pdfMerger->addPDF($readfile, $page);
                                $pdfMerger->merge();
                                $pdfMerger->save( $path.'/'.$page.'-'.$incitementDate.'.pdf');

                $update = PayrollPage::where('payroll_id',$payroll_id)->where('page',$page)->update([
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
                'step' => 'payrollParse2'
            ]);
       // return redirect(route('bordrolama.index'))->with('success','İşlem Bşarılı');
    }
    public function pdfUploadStore2()
    {

        $sgk_company = getSgkCompany();
        $readfile = session()->get('file_name');
        $incitementDate = session()->get('date');
        $path = session()->get('pdf_path');
        $payroll_id = session()->get('payroll_id');
        $pages = PayrollPage::where('payroll_id', $payroll_id)->where('parse2', 0)->get()->pluck('page')->toArray();

        if (count($pages) > 0) {

            $limit = -3;
            $pageCount = count($pages);
            $timeOutLimit = 3;
            $payroll_id = session()->get('payroll_id');
            $pages = PayrollPage::where('payroll_id', $payroll_id)->where('parse2', 0)->get()->pluck('page')->toArray();
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
                                'step' => 'payrollParse2',
                            ]);
                    }
                    $readfile = $path.'/'.$page.'-'.$incitementDate.'.pdf';

                    $pdf = new Pdf($readfile);
                    $html = $pdf->html();


                    $identity_number =  employeeTcPdf($html);


                    if ($identity_number == false)
                    {

                        Payroll::where('id',$payroll_id)->delete([]);
                        PayrollPage::where('payroll_id',$payroll_id)->delete([]);
                        return redirect(route('bordrolama.index'))->with('danger','Bordro Taraması Yapılamıyor Daha Sonra Tekrar Deneyiniz');
                    }

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
                                    $control = Payroll::where("date", $incitementDate)
                                        ->where("company_id", Auth::user()->company_id)
                                        ->where("sgk_company_id", $sgk_company->id)
                                        ->first();

                                    $file = $readfile;
                                    $save = new PayrollService();
                                    $save->employee_id = $employee->id;
                                    $save->payroll_id = $control->id;
                                    $save->file = '/'.Auth::user()->company_id.'/'.$sgk_company->id.'/'.$sgk_company->id.'-'.$incitementDate.'/'.$page.'-'.$incitementDate.'.pdf';
                                    $save->page = $page;
                                    $save->save();

                                    $calender = PayrollCalender::create([
                                        'payroll_id'=>$control->id,
                                        'payroll_service_id'=>$save->id,
                                        'upload_date'=> Carbon::now()->timezone('Europe/Istanbul')
                                    ]);
                                }
                                else
                                {
                                    if(is_numeric(trim($identity_number)))
                                    {
                                        PayrollNotEmployee::create([
                                            'payroll_id' => $payroll_id,
                                            'page' => $page,
                                            'tc' => trim($identity_number)
                                        ]);
                                    }

                                }



//                            }
//                        }
//                    }

                    $update = PayrollPage::where('payroll_id', $payroll_id)->where('page', $page)->update([
                        'parse2' => 1
                    ]);
                }
            }

            return response()
                ->json([
                    'code' => 'FINISH',
                    'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
                    'progress' => 100,
                    'url' => route('bordrolama.index')
                ]);
        }
        else
            return response()
                ->json([
                    'code' => 'FINISH',
                    'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
                    'progress' => 100,
                    'url' => route('bordrolama.index')
                ]);    }
    public function payroll_show($id)
    {
        $id = HashingSlug::decodeHash($id);
        $payroll = Payroll::find($id);
        if (!$payroll)
        {
            return back()->with('danger','Kayıt Bulunamadı');
        }

        if (Auth::user()->hasRole('e-bordro'))
        {
            $payrolls = PayrollService::where('payroll_id',$id)->get();
            $accept_payrolls = PayrollService::where('payroll_id',$id)->where('zamane_accept','1')->count();
            $danger_payrolls = PayrollService::where('payroll_id',$id)->where('zamane_accept','2')->count();
            $warning_payrolls = PayrollService::where('payroll_id',$id)->where('zamane_accept','0')->count();
            $protests = PayrollProtest::where('payroll_id',$id)->get();

            if (count($payrolls) > 0)
            {
                return view('payrolls.show',compact('payrolls','accept_payrolls','danger_payrolls','warning_payrolls','protests','id'));
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
            $payrolls = PayrollService::where('payroll_id',$id)->where('employee_id',$employee->id)->get();
            $protests = PayrollProtest::where('payroll_id',$id)->get();
            return view('payrolls.show',compact('payrolls','protests','id'));

        }


    }
    public function employeeSmsStart($id)
    {
        session()->forget('progress');
        session(['payrollSmsId'=>'']);
        session(["progress" => 0]);
        $payroll = Payroll::find($id);
        if (!$payroll)
        {
            return response()->json(
                [
                    'code'=>'LOGIN_FAIL',
                    'message'=>'Bordro Bulunamadı',
                    'progress' => '10',
                    'step' => '',
                ]
            );
        }

        session(['payrollSmsId'=>$id]);

        return response()->json(
            [
                'code'=>'SUCCESS',
                'message'=>'Bordrolar Gönderim Başlatılıyor',
                'progress' => '10',
                'step' => 'PayrollSms',
            ]
        );
        session(["progress" => 10]);

    }
    public function employee_sms()
    {


         $id = session()->get('payrollSmsId');
        $payroll = Payroll::find($id);

        if (!$payroll)
        {
            return back()->with('danger','Hata İşlem Yapılamadı');
        }
        $payroll_service = PayrollService::where('payroll_id',$id)->where('sms_status',0)->limit(20)->get();

        $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
        $company = Company::where('id',Auth::user()->company_id)->first();
        if (!$sms)
        {
            return back()->with('danger','Kayıt Başarılı Fakat Sms Hizmeti Kayıtlı Değil');
        }
        $link = env('APP_URL');
        $datas = $link;

        $timeOutLimit = 5;
        $limit = 1;

        foreach ($payroll_service as $service)
        {
            $progress = session()->get('progress') + 5;
            $limit++;
            if ($limit > $timeOutLimit) {
                session(["progress" => $progress]);
                return response()
                    ->json([
                        'code' => 'TIMEOUT',
                        'message' => 'Bordrolar Gönderiliyor.',
                        'progress' => $progress,
                        'step' => 'PayrollSms',
                    ]);
            }


            sleep(7);

            $working = Employee::find($service->employee_id);

            $sgk_company = SgkCompany::find($working->sgk_company_id);
            if (!isset($sgk_company->name))
            {
                $name = $company->name;
            }
            else
            {
                $name = $sgk_company->name;
            }

            $loginUrl = env('APP_URL').'/payroll/login/'.'more'.'/'.$working->token.'/'.createHashId($id);
            if( Auth::user()->company_id== 63 || Auth::user()->company_id== 276  )
            {
                $message = 'Mayıs '.$payroll->date->format('Y').' Dönemine Ait Bordronuz Yüklenmiştir Görüntüleyebilir/Onaylayabilirsiniz. '.$loginUrl; //Demo plastik mesajı
            }
            else
            {
                $message =  $company->name.' '.$payroll->date->format('d/m/Y').' Dönemine Ait Bordronuz Yüklenmiştir İncelemeniz/Onaylamanız Önemle Rica Olunur'.' Link:'.$datas.' '.'Kullanıcı Adınız : '.' '.$working->email.' '.'Şifreniz  : Tc Kimlik Numaranız Doğrudan Giriş İçin '.$loginUrl;
            }
//            $message =  $name.' '.$payroll->date->format('d/m/Y').' Dönemine Ait Bordronuz Yüklenmiştir İncelemeniz/Onaylamanız Önemle Rica OLunur'.' Link:'.$datas.' '.'Kullanıcı Adınız : '.' '.$working->email.' '.'Şifreniz  : Tc Kimlik Numaranız Doğrudan Giriş İçin '.$loginUrl;

//            $message = $payroll->date->format('d/m/Y').'Dönemi Bordronuz Yüklenmiştir '.$loginUrl; //weteks mesajı
            sendSms(Auth::user()->company_id,$sms,null,$working,$message);

            PayrollService::where('id',$service->id)->update([
                'sms_status'=>1,

            ]);

            PayrollCalender::where('payroll_service_id',$service->id)->update([
                'sms_date'=> Carbon::now()->timezone('Europe/Istanbul')
            ]);




        }
        $date = Carbon::now()->timezone('Europe/Istanbul');

      $count = PayrollService::where('payroll_id',$id)
          ->where('sms_status',0)
          ->count();
       if ($count <= 0)
       {
           Payroll::where('id',$id)->update([
               'sms_status'=>1,
               'updated_at' => $date,


           ]);
           return response()
               ->json([
                   'code' => 'FINISH',
                   'message' => 'Bordrolar önderildi.',
                   'progress' => '100',
                   'url' => route('bordrolama.index'),
               ]);

       }




    }
    public function employee_payroll_protest(Request $request)
    {

        $working_id = HashingSlug::decodeHash($request->working_id);
        $payroll_id = $request->payroll_id;
        $page =$request->page;
        $payroll = Payroll::find($payroll_id);

        if (!$payroll)
        {
            return back()->with('error','beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        $working = Employee::find($working_id);
        if (!$working)
        {
            return back()->with('error','Bilgilerinize Ulaşılamadı İK ile Görüşün');
        }


        $protest = PayrollProtest::create([
            'company_id'=>Auth::user()->company_id,
            'payroll_id'=>$payroll_id,
            'employee_id'=>$working_id,
            'date' => date('Y-m-d'),
            'notification'=>$request->notification
        ]);
        $payroll_service =PayrollService::where('payroll_id',$payroll_id)
            ->where('employee_id',$working_id)
            ->where('page',$page)
            ->first();
        if ($payroll_service)
        {
            PayrollService::where('payroll_id',$payroll_id)
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
//            Mail::to($user->email)->send(new PayrollMail($working,$company,$protest,$payroll));
            return back()->with('success','İşleminiz Başarılı');
        }
        else
        {
            return back()->with('error','İşleminiz Başarısız');
        }
    }
    public function payroll_protest_form($id)
    {
        $id = \Vinkla\Hashids\Facades\Hashids::decode($id);
        $id = $id[0];
        $protests = PayrollProtest::where('company_id',Auth::user()->company_id)->where('payroll_id',$id)->get();
        return view('payrolls.protest',compact('protests'));
    }
    public function payroll_protest_accept($id)
    {
        $protest = PayrollProtest::find($id);
        if (!$protest)
        {
            return response()->json([
                'title'=>'Hata',
                'type'=>'error',
                'message'=>'Kayıt Bulunmadı'
            ]);
        }
        $a = PayrollProtest::where('id',$id)->update([
            'status'=>1
        ]);
        if ($a)
        {
            return response()->json([
                'title' => 'Başarılı',
                'type'=>'success',
                'message'=>'Kayıt Başarılı'
            ]);
        }
    }
    public function payrollNotIn($id)
    {
        $id = HashingSlug::decodeHash($id);
        $payrolls = PayrollNotEmployee::where('payroll_id',$id)->get();

        if (count($payrolls) <=0)
        {
            return back()->with('danger','Tüm Bordrolar Eşleşti');
        }
        return view('payrolls.not_in',compact('payrolls'));
    }
    public function delete($id)
    {
        $id = HashingSlug::decodeHash($id);
        $control = Payroll::find($id);
        if (!$control)
        {
            return back()->with('danger','Bordrolar Bulunamadı');
        }
         $payroll = Payroll::where('id',$id)->delete([]);
         PayrollService::where('payroll_id',$id)->delete([]);
         PayrollPage::where('payroll_id',$id)->delete([]);
         PayrollNotEmployee::where('payroll_id',$id)->delete([]);
         PayrollProtest::where('payroll_id',$id)->delete([]);
         if ($payroll)
         {
             return redirect(route('bordrolama.index'))->with('success','Silme İşlemi Başarılı');
         }
         else
         {
             return back()->with('danger','Silme işlemi Başarısız');
         }
    }
    public function sendSms(Request $request)
    {

        $id = HashingSlug::decodeHash($request->working_id);


        $employee = Employee::find($id);
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
            return back()->with('danger','Sms Gönderilemedi');
        }

        $message =   'Evrak Onaylamak/Reddetmek İçin Şifreniz: '.$random;
        sendSms(Auth::user()->company_id,$sms,null,$employee,$message);

    }


    public function payrollLogin($username,$password,$id)
    {
        $password = $password;
        $id = HashingSlug::decodeHash($id);
        $payroll = Payroll::find($id);

        if (!$payroll)
        {
            return back()->with('danger','Bordro Bulunamadı');
        }

        $employee = Employee::where('token',$password)->first();
        if(!$employee)
        {
            return back()->with('error','Sistemde Yaşanan Aksaklık Nedeniyle İşleminiz Devam Edemiyor.Lütfen Daha Sonra Tekrar Deneyin.');
        }
        $user = User::where('employee_id',$employee->id)->first();
        if(!$user)
        {
            return back()->with('error','Sistemde Yaşanan Aksaklık Nedeniyle İşleminiz Devam Edemiyor.Lütfen Daha Sonra Tekrar Deneyin.');
        }

        $login =  Auth::loginUsingId($user->id);

        if($login)
        {
            return redirect(route('employee_payroll_show',createHashId($id)));
        }
        else
        {
            return back()->with('error','Sistemde Yaşanan Aksaklık Nedeniyle İşleminiz Devam Edemiyor.Lütfen Daha Sonra Tekrar Deneyin.');
        }




    }

    public function fullFile($id)
    {
        $id = HashingSlug::decodeHash($id);

        $payroll = Payroll::find($id);
        if (!$payroll)
        {
            return back()->with('danger','Bordro Bulunamadı');
        }
        $company = Company::find($payroll->company_id);

        $payroll_services = PayrollService::where('payroll_id',$payroll->id)->get();

        $zipname = $company->name.'-'.$payroll->date->format('d-m-Y').'.zip';
        $zip = new \ZipArchive();
        $zip->open($zipname, \ZipArchive::CREATE);

        foreach ($payroll_services as $key => $file)
        {

            $zip->addEmptyDir('/Bordrolar');
            if ($file->zamane_accept == 0)
            {
                $pdfFile = '/company_payrolls'.$file->file;
            }
            else
            {
                $pdfFile = $file->file;
                $zip->addFile(public_path().$pdfFile, 'Bordrolar/'.$pdfFile.'.zd');
            }

        $zip->addFile(public_path().$pdfFile, 'Bordrolar/'.$pdfFile);

        }
        $zip->close();


        return response()->download(public_path().'/'.$company->name.'-'.$payroll->date->format('d-m-Y').'.zip');



      //  return response()->download(public_path($company->company_name.'.zip'));

    }


    // public function serviceAccept(Request $request)
    // {

    //     $token = 'ABUGHJ-25684-5655-AR569255';
    //     if (empty($request->_token))
    //     {
    //         return 'Bağlantı Kurulurken Hata Oluştu';
    //     }
    //     if ($request->_token != $token)
    //     {
    //         return 'Bağlantı Kurulurken Hata Oluştu';
    //     }

    //     // dd('ok');

    //     // $client = new \SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");
    //     // try {
    //     //     $result = $client->TCKimlikNoDogrula([
    //     //         'TCKimlikNo' => $request->identity_number,
    //     //         'Ad' => mb_strtoupper($request->first_name),
    //     //         'Soyad' => mb_strtoupper($request->last_name),
    //     //         'DogumYili' => $request->DogumYili
    //     //     ]);
    //     //     if (!$result->TCKimlikNoDogrulaResult) {
    //     //         return back()->with('danger', 'Damgalama İşlemi Yapılamıyor 3D Security TC KİMLİK NO DOĞRULANAMADI');
    //     //     }

    //     // } catch (\Exception $e) {
    //     //     return back()->with('danger','Damgalama İşlemi Yapılamıyor 3D Security TC KİMLİK DOĞRULANAMADI');
    //     // }

    //     $file = $request->file('files');

    //     if ($file) {

    //         $tck = '2464328554';
    //         $fileDate = date('Y-m-d');
    //         $destinationPath = 'payroll_services/'. 'disciplines/';
    //         $file->move($destinationPath, $tck.'-'.$fileDate.'.pdf');

    //         $filePath = $tck.'-'.$fileDate.'.pdf';

    //     }
    //     else
    //     {
    //         return 'Bağlantı Kurulurken Hata Oluştu';
    //     }

    //     // dd($fileName);

    //     File::makeDirectory('company_payrolls/services/zamane/'.\date('Y-m-d'),0755,true,true);


    //     $path = '/company_payrolls/services/zamane/'.$filePath;



    //     $randoms = rand(0,99999999999);
    //     $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').$path.'_2.pdf',public_path('qr_code/payrolls/'. $tck.'-'.$fileDate.'_2.png'));



    //     $qrcode1 = public_path('qr_code/payrolls/'. $tck.'-'.$fileDate.'_2.png');
    //      $qrcode2 = public_path('qr_code/payrolls/'. $tck.'-'.$fileDate.'_2.png');


    //     $date =  Carbon::now()->timezone('UTC'); // $request->post_date;
    //     $date2 =  Carbon::now()->timezone('UTC');
    //     $metin = '5070 Sayılı Elektronik İmza Kanuna Uygun Damgalanmıştır';
    //     $company['name'] = 'MOREPAYROLL FİRMASI';
    //     $company['no'] = '1234567891';
    //     $working['first_name'] = 'Recep';
    //     $working['last_name'] = 'Tosun';
    //     $working['tc'] = '24643528554';

    //      $pdf = PDF::loadView('payrolls.zamane.test2',compact('qrcode1','qrcode2','date','date2','metin','company','working'));
    //     $header = View::make('payrolls.zamane.header', ['title' => 'Morepayroll' ])->render();
    //     $footer =  View::make('payrolls.zamane.footer')->render();
    //     $pdf->save(public_path().'/test_zamane/'.$randoms.'.pdf');


    //     $im2 = new \Imagick();
    //     $im2->setResolution(300,300);
    //     $im2->readimage(public_path().'/test_zamane/'.$randoms.'.pdf');
    //     $im2->setImageFormat('png');
    //     $im2->setSize(200, 700);
    //     $im2->writeImage('thumb2.png');
    //     $im2->clear();
    //     $im2->destroy();


    //     $im = new \Imagick();
    //     $im->setResolution(300,300);
    //     $im->readimage(public_path().'/'.$destinationPath.$filePath);
    //     $im->setImageFormat('png');
    //     $im2->setSize(200, 1000);
    //     $im->writeImage('thumb.png');
    //     $im->clear();
    //     $im->destroy();

    //     $src1 = new \Imagick("thumb.png");
    //     $src2 = new \Imagick("thumb2.png");
    //     $src1->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
    //     $src1->setImageArtifact('compose:args', "1,0,-0.5,0.5");
    //     $src1->compositeImage($src2, \Imagick::COMPOSITE_MATHEMATICS, 0, $src1->getImageHeight()/2);
    //     $src1->writeImage(public_path()."/oldu.png");



    //     $im = new \Imagick();
    //     $im->setResolution(300,300);
    //     $im->readimage(public_path().'/oldu.png');
    //     $im->setImageFormat('pdf');
    //     $im->writeImage(public_path().'/company_payrolls/services/zamane/'.\date('Y-m-d').'/'.$randoms.'-'.$fileName.'_2.pdf');
    //     $im->clear();
    //     $im->destroy();

    //     $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.' '.public_path().'/company_payrolls/services/zamane/'.\date('Y-m-d').'/'.$randoms.'-'.$fileName.'_2.pdf'.' '.$zamane->link.' '.$zamane->port.' '.$zamane->kullanici_adi.' '.$zamane->password.' '.$zamane->damga_type);



    // }


    public function payrollreport()
    {
        $data = session()->get('selectedCompany');

        $payrolls = Payroll::where('sms_status', 1)->orderBy('id', 'DESC')->get();

        $period = [];
        $dates = ['01'=>'OCAK','02'=>'ŞUBAT','03'=>'MART','04'=>'NİSAN','05'=>'MAYIS','06'=>'HAZİRAN','07'=>'TEMMUZ','08'=>'AĞUSTOS','09'=>'EYLÜL','10'=>'EKİM','11'=>'KASIM','12'=>'ARALIK'];

        foreach ($payrolls as $payroll)
        {
            $period[$payroll['id']] = $dates[date("m", strtotime($payroll['date']))];
        }

        return view('payrolls/payroll_report',compact('payrolls','period'));

    }


    public function invoiceupdate(Request $request)
    {
        $id = $request->payroll_id;

        $invoice=Payroll::where('id',$id)->update(
            [
                'invoice' => '1'
            ]);

        return back();

    }

    public function Oneemployee_sms($id,$employee_id)
    {

        $id =HashingSlug::decodeHash($id); //payroll_id
        $employee_id = HashingSlug::decodeHash($employee_id); // employee_id
        $working = Employee::find($employee_id); // employee
        $payroll = Payroll::find($id);

        if (!$payroll)
        {
            return back()->with('danger','Hata İşlem Yapılamadı');
        }

        if (!$working)
        {
            return back()->with('danger','Hata İşlem Yapılamadı');
        }
        $payroll_service = PayrollService::where('payroll_id',$id)->where('employee_id',$working->id)->get();

        $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
        $company = Company::where('id',Auth::user()->company_id)->first();
        if (!$sms)
        {
            return back()->with('danger','Kayıt Başarılı Fakat Sms Hizmeti Kayıtlı Değil');
        }
        $link = env('APP_URL');
        $datas = $link;



        foreach ($payroll_service as $service)
        {

            sleep(5);

            $loginUrl = env('APP_URL').'/payroll/login/'.'more'.'/'.$working->token.'/'.createHashId($id);

            if(Auth::user()->company_id== 277 || Auth::user()->company_id== 63   )
            {
                $message = 'Mayıs '.$payroll->date->format('Y').' Dönemine Ait Bordronuz Yüklenmiştir'.' Kullanıcı Adınız : '.' '.$working->email.' '.'Şifreniz  : Tc Kimlik Numaranız Doğrudan Giriş İçin '.'Linke Tıklayarak Görüntüleyebilir/Onaylayabilirsiniz.  '.$loginUrl; //Demo plastik mesajı
            }
//            elseif ( Auth::user()->company_id == 276)
//            {
//                $message =  $company->name.' '.$payroll->date->format('d/m/Y').' Dönemine Ait Bordronuz Yüklenmiştir. İncelemeniz/Onaylamanız Önemle Rica OLunur '.$loginUrl;
//
//            }
            else
            {
                $message =  $company->name.' '.$payroll->date->format('d/m/Y').' Dönemine Ait Bordronuz Yüklenmiştir İncelemeniz/Onaylamanız Önemle Rica OLunur'.' Link:'.$datas.' '.'Kullanıcı Adınız : '.' '.$working->email.' '.'Şifreniz  : Tc Kimlik Numaranız Doğrudan Giriş İçin '.$loginUrl;
            }
            sendSms(Auth::user()->company_id,$sms,null,$working,$message);

        }
        $date = Carbon::now()->timezone('Europe/Istanbul');

        return back()->with('success','Sms Gönderildi');
    }



}
