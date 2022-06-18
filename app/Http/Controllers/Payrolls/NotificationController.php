<?php

namespace App\Http\Controllers\Payrolls;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollNotificationRequest;
use App\Mail\PayrollMail;
use App\Models\Company;
use App\Models\Payroll;
use App\Models\PayrollNotification;
use App\Models\PayrollProtest;
use App\Models\PayrollService;
use App\Models\SmsUser;
use App\Models\Employee;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Treinetic\ImageArtist\lib\Shapes\PolygonShape;
use QrCode;
use PDF;
use Treinetic\ImageArtist\lib\Shapes\Triangle;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Org_Heigl\Ghostscript\Ghostscript;
use Treinetic\ImageArtist\lib\Image;
use View;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::user()->hasAnyRole('e-bordro'))
        {
            $notifications = PayrollNotification::all();
            return view('payrolls.notifications.index',compact('notifications'));

        }
        elseif(Auth::user()->hasRole('Employee'))
        {
            $employee = Employee::where('email',Auth::user()->email)->first();
            if (!$employee)
            {
                return back()->with('errors','Çalışan Bilgilerinize Ulaşılamadı');
            }

            $notifications = PayrollNotification::where('employee_id',$employee->id)->where('sms_status',1)->get();
            return view('payrolls.notifications.index',compact('notifications'));

        }
        else
        {
            return back()->with('danger','Giriş Yetkiniz Yoktur');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::where('company_id',Auth::user()->company_id)->orderBy('first_name')->get()->pluck('full_name','id');
        return view('payrolls.notifications.create',compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayrollNotificationRequest $request)
    {
        $workings = Employee::whereIn('id',$request->employee)->get();

        if (count($workings)<=0)
        {
            return back()->with('danger','Personel Bulunamadı');
        }
        if (isset($request->pdf_parse))
        {
            return back()->with('error','Pdf Parçalama Bölümü Henüz Aktif Değildir');
        }
        else
        {
            $ramdom1 = rand(0,999999999999999);
            if (strtolower($request->file->getClientOriginalExtension()) == "pdf") {
                $fileName = public_path('company_notifications' . DIRECTORY_SEPARATOR . Auth::user()->company_id);
                $file =  $request->file('file')->move($fileName , Auth::user()->company_id.'-'.$ramdom1.'.pdf');
            }

            foreach ($workings as $working)
            {
                $ramdom = rand(0,999999999999999);

                $pdfMerger = PdfMerger::init(); //Initialize the merger
                $pdfMerger->addPDF(public_path().'/company_notifications/'.Auth::user()->company_id.'/'.Auth::user()->company_id.'-'.$ramdom1.'.pdf', 'all');
                $pdfMerger->merge();
                $pdfMerger->save(public_path().'/company_notifications/'.Auth::user()->company_id.'/'.$working->id.'_'.$ramdom.'.pdf');

                $notification =  PayrollNotification::create([
                    'employee_id'=>$working->id,
                    'name'=>$request->name,
                    'file'=> '/company_notifications/'.Auth::user()->company_id.'/'.$working->id.'_'.$ramdom.'.pdf',
                    'date'=>date('Y/m/d'),
                    'notification'=>$request->notification
                ]);
            }

        }

        if ($notification)
        {
            return redirect(route('notifications.index'))->with('success','Kayıt İşlemi Başarılı İnceledikten Sonra Gönderebilirsiniz');
        }
        else
        {
            return back()->with('danger','Kayıt İşlemi başarısız');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function NotificationEmployeeSms($id)
    {
        $id = (int)$id;
        $notification = PayrollNotification::find($id);
        if (!$notification)
        {
            return back()->with('error','Hata İşlem Yapılamadı');
        }
        $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
        $company = Company::where('id',Auth::user()->company_id)->first();
        if (!$sms)
        {
            return back()->with('error','Kayıt Başarılı Fakat Sms Hizmeti Kayıtlı Değil');
        }
        $link = env('APP_URL');
        $datas = $link;

            $working = Employee::find($notification->employee_id);
            $data = [
                'username' => $sms->username,
                'password' => $sms->password,
                'sdate' => null,
                'speriod' => '48',
                'message' =>
                    [
                        'sender' => $sms->sender,
                        'text' =>  $company->name.' '.$notification->name.' İsimli Evrak ' . $notification->notification. ' Nedeniyle Onay/Reddetme İşlemini Yapmanız Gerekmektedir '.' Link:'.$datas,
                        'utf8' => '1',
                        'gsm' => [
                            $working->mobile
                        ]
                    ]
            ];

            Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);
            PayrollNotification::where('id',$notification->id)->update([
                'sms_status'=>1,
                'zamane_accept'=>1
            ]);

        return redirect(route('notifications.index'))->with('success','Gönderme İşlemi Başarılı');

    }
    public function employee_accept(Request $request)
    {

        $working_id =   HashingSlug::decodeHash($request->working_id);
        $working = Employee::find($working_id);
        $notification = PayrollNotification::find($request->notification_id);
        if (!$working)
        {
            return back()->with('danger','Kayıt Bulunamadı İş Yeriniz İle İletişime Geçiniz');
        }

        if ($working->sms_password != $request->code)
        {
            return back()->with('danger','Kod Hatalı Tekrar Kod Talep Ediniz');
        }

        if (!$notification)
        {
            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }

        $file = explode('/',$notification->file);
        $file2 = explode('.pdf',$file[3]);

        //$fileName = public_path('company_payrolls' . DIRECTORY_SEPARATOR . Auth::user()->company_id.  DIRECTORY_SEPARATOR .'zamane' .DIRECTORY_SEPARATOR. $file2[3].'_2.pdf');

        $randoms = rand(0,99999999999);
        $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/company_notifications/'.Auth::user()->company_id.'/zamane/'.$file2[0].'_2.pdf',public_path('qr_code/payrolls'.$file2[0].'_2.png'));
       // $code2 =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/company_notifications/'.Auth::user()->company_id.'/zamane/'.$file2[0].'_2.pdf',public_path('qr_code/payrolls'.$file2[0].'_2.png'));


        $qrcode2 = public_path('qr_code/payrolls'.$file2[0].'_2.png');
        $qrcode1 = public_path('qr_code/payrolls'.$file2[0].'_2.png');
        $date = date('d/m/Y h:s:i');
        $date2 = date('d/m/Y h:s:i');
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
        $im->readimage(public_path().$notification->file);
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
        $im->writeImage(public_path().'/company_notifications/'.Auth::user()->company_id.'/zamane/'.$file2[0].'_2.pdf');
        $im->clear();
        $im->destroy();

        $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.' '.public_path().'/company_notifications/'.Auth::user()->company_id.'/zamane/'.$file2[0].'_2.pdf'.' http://tzdsha512.kamusm.gov.tr 80 2941 91UrFaUv sha-512');

        if ($deger)
        {
            $file = '/company_notifications/'.Auth::user()->company_id.'/zamane/'.$file2[0].'_2.pdf';
            $notification->where('id',$notification->id)->update([
                'zamane_accept' => 2,
                'file'=> $file
            ]);
            return back()->with('success','Onaylama İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Üzgünüz Hatayla Karşılaşıldı İK ile Görüşün');
        }
    }
    public function employee_notification_protest(Request $request)
    {

        $working_id =  Hashids::decode($request->working_id);
        $working_id = $working_id[0];
        $payroll_id = $request->payroll_id;
        $page = $request->page;
        $payroll = Payroll::find($payroll_id);

        if (!$payroll)
        {
            return back()->with('error','beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        $working = Working::find($working_id);
        if (!$working)
        {
            return back()->with('error','Bilgilerinize Ulaşılamadı İK ile Görüşün');
        }

        $protest = PayrollProtest::create([
            'company_id'=>Auth::user()->company_id,
            'payroll_id'=>$payroll_id,
            'working_id'=>$working_id,
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
            $user =  User::where('company_id',Auth::user()->company_id)->where('user_type_id',1)->orWhere('user_type_id',2)->where('company_id',Auth::user()->company_id)->first();
          //  Mail::to($user->email)->send(new PayrollMail($working,$company,$protest,$payroll));
            return back()->with('success','İşleminiz Başarılı');
        }
        else
        {
            return back()->with('error','İşleminiz Başarısız');
        }
    }
}
