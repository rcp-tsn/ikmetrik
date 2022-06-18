<?php

namespace App\Http\Controllers\Payrolls;

use App\DataTables\EmployeeDataTable;
use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeFileRequest;

use App\Http\Requests\EmployeeFileUpdate;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeFile;
use App\Models\EmployeeFileCalender;
use App\Models\EmployeeFileService;
use App\Models\EmployeeProtest;
use App\Models\Payroll;
use App\Models\PayrollNotification;
use App\Models\EmployeeFileType;
use App\Models\PayrollProtest;
use App\Models\PayrollService;
use App\Models\SgkCompany;
use App\Models\SmsUser;
use App\Models\ZamaneUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use PhpOffice\PhpWord\TemplateProcessor;
use Treinetic\ImageArtist\lib\Shapes\PolygonShape;
use QrCode;
use PDF;
use View;
use Treinetic\ImageArtist\lib\Shapes\Triangle;
use Curl;
use const http\Client\Curl\AUTH_ANY;


class PersonelFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeeDataTable $dataTable)
    {
        return  $dataTable->render('standards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->hasAnyRole('kvkk','e-bordro'))
        {
            return back()->with('danger','Buraya Giriş İzniniz Yoktur');
        }
        $employees = Employee::where('company_id',Auth::user()->company_id)->where('status',1)->get()->pluck('full_name','id');
        $file_types = EmployeeFileType::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        $departments = Department::where('company_id',Auth::user()->company_id)->get()->pluck('name','id')->toArray();
        $departments[0] = 'Seçiniz';


        return view('personelFiles.create',compact('employees','file_types','departments','sgk_companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeFileRequest $request)
    {

        $employee = Employee::where('id',$request->employee)->first();
        if (!$employee)
        {
            return back()->with('danger','Personel Bulunamadı');
        }
            $file_random = rand(0,9999999999);
            $fileName = public_path('employee_files' . DIRECTORY_SEPARATOR . Auth::user()->company_id . DIRECTORY_SEPARATOR . $employee->id);
            $file =  $request->file('file')->move($fileName , $file_random.'.pdf');
             $readfile = 'employee_files/'.Auth::user()->company_id.'/'.$employee->id.'/'.$file_random.'.pdf';

               $pdf = new \Gufy\PdfToHtml\Pdf($readfile);

        $employee_file = false;
        for ($i = 1; $i <= $pdf->getPages(); $i++) {

            $pdfMerger = PdfMerger::init(); //Initialize the merger
            $pdfMerger->addPDF($readfile, $i);
            $pdfMerger->merge();
            $pdfMerger->save(public_path().'/employee_files/'.Auth::user()->company_id.'/'.$employee->id.'/'.$file_random.'_'.$i.'.pdf');

            $employee_file = EmployeeFile::create([
                'employee_id' => $employee->id,
                'file_type_id'=> $request->file_type_id,
                'file'=>  '/employee_files/'.Auth::user()->company_id.'/'.$employee->id.'/'.$file_random.'_'.$i.'.pdf',
                'date'=>date('Y/m/d'),
                'page' => $i,
                'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                'notification' => $request->notification,
                'sms_status'=> 1,
                'zamane_accept'=>1
            ]);


        }


        if ($employee_file)
        {
            $company = Company::find(Auth::user()->company_id);
            $loginUrl = env('APP_URL').'/kvkk/login/'.$employee->email.'/'.$employee->token.'/'.createHashId($employee->id);
            $sms = SmsUser::where('company_id',$employee->company_id)->first();
            if (isset($sms->username))
            {
                $message =  $company->name.' '. 'KVKK(Kişisel Verilerin Korunması Kanunun) ilgili onay vermeniz formlar sisteme yüklenmiştir onay vermeniz önemle rica olunur.'.' Giriş Link:'.$loginUrl;

            }

            sendSms(Auth::user()->company_id,$sms,null,$employee,$message);
            return redirect(route('personelFiles.index'))->with('success','Kayıt İşlemi Başarılı İnceledikten Sonra Gönderebilirsiniz');
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
        $id = HashingSlug::decodeHash($id);

        if (Auth::user()->hasAnyRole('e-bordro','kvkk'))
        {
            $personelFiles = EmployeeFile::where('employee_id',$id)->get();
        }
        else
        {
            $personelFiles = EmployeeFile::where('employee_id',$id)->where('sms_status','1')->get();
        }
        $protests = EmployeeProtest::where('employee_id',$id)->get();
        return view('personelFiles.show',compact('personelFiles','protests'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = HashingSlug::decodeHash($id);
        $personelFile =EmployeeFile::find($id);
        $employee = Employee::find($personelFile->employee_id);
        if (!$personelFile)
        {
            return back()->with('danger','Dökümanlara Ulaşılamadı');
        }
        $employees = Employee::where('id',$personelFile->employee_id)->where('status',1)->get()->pluck('full_name','id');
        $file_types = EmployeeFileType::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        return view('personelFiles.edit',compact('id','personelFile','file_types','employees','employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeFileUpdate $request, $id)
    {
        $personelFile = EmployeeFile::find($id);
        if (!$personelFile)
        {
            return back()->with('danger','Dökümanlara Ulaşılamadı');
        }

        $employee = Employee::where('id',$personelFile->employee_id)->first();




        if (!$employee)
        {
            return back()->with('danger','Personel Bulunamadı');
        }


                $ramdom1 = rand(0,999999999999999);
                if (strtolower($request->file->getClientOriginalExtension()) == "pdf") {
                    $fileName = public_path('employee_files' . DIRECTORY_SEPARATOR . Auth::user()->company_id. DIRECTORY_SEPARATOR. $employee->id);
                    $file =  $request->file('file')->move($fileName , Auth::user()->company_id.'-'.$ramdom1.'.pdf');
                }


                $ramdom = rand(0,999999999999999);

                $pdfMerger = PdfMerger::init(); //Initialize the merger
                $pdfMerger->addPDF(public_path().'/employee_files/'.Auth::user()->company_id.'/'.$employee->id.'/'.Auth::user()->company_id.'-'.$ramdom1.'.pdf', 'all');
                $pdfMerger->merge();
                $pdfMerger->save(public_path().'/employee_files/'.Auth::user()->company_id.'/'.$employee->id.'/'.$employee->id.'_'.$ramdom.'.pdf');

                $notification =  EmployeeFile::where('id',$id)->update([
                    'employee_id'=>$employee->id,
                    'file_type_id'=> $request->file_type_id,
                    'file'=> '/employee_files/'.Auth::user()->company_id.'/'.$employee->id.'/'.$employee->id.'_'.$ramdom.'.pdf',
                    'date'=>$personelFile->date,
                    'notification'=>$request->notification,
                    'zamane_accept'=> 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);




        if ($request->sms == 'on' )
        {
            $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();

            if ($sms)
            {
                $message = $personelFile->date->format('d/m/Y').' Dönemine de tarafınıza gönderilen '.$personelFile->notification.' Döküman Revize İşlemi Yapılmıştır Onaylamanız/Reddetmeniz Önemle Rica OLunur';
                sendSms(Auth::user()->company_id,$sms,null,$employee,$message);
//                $data = [
//                    'username' => $sms->username,
//                    'password' => $sms->password,
//                    'sdate' => null,
//                    'speriod' => '48',
//                    'message' =>
//                        [
//                            'sender' => $sms->sender,
//                            'text' =>  $personelFile->date->format('d/m/Y').' Dönemine de tarafınıza gönderilen '.$personelFile->notification.' Döküman Revize İşlemi Yapılmıştır Onaylamanız/Reddetmeniz Önemle Rica OLunur',
//                            'utf8' => '1',
//                            'gsm' => [
//                                $employee->mobile
//                            ]
//                        ]
//                ];
//
//                Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);
            }

        }

        if ($notification)
        {
            return redirect(route('personelFiles.index'))->with('success','Güncelleme İşlemi Başarılı İnceledikten Sonra Gönderebilirsiniz');
        }
        else
        {
            return back()->with('danger','Güncelleme İşlemi başarısız');
        }
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

    public function FileEmployeeSms($id)
    {
        $id = (int)$id;
        $file = EmployeeFile::find($id);
        if (!$file)
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

        $working = Employee::find($file->employee_id);

        $message = $company->name.' '.$file->notification. ' Nedeniyle Onay/Reddetme İşlemini Yapmanız Gerekmektedir '.' Link:'.$datas;
        sendSms(Auth::user()->company_id,$sms,null,$working,$message);

        EmployeeFile::where('id',$file->id)->update([
            'sms_status'=>1,
            'zamane_accept'=>1
        ]);

        return redirect(route('personelFiles.show',createHashId($file->employee->id)))->with('success','Gönderme İşlemi Başarılı');

    }
    public function employee_accept(Request $request)
    {
        $company = Company::find(Auth::user()->company_id);
        $working_id = HashingSlug::decodeHash($request->employee_id);
        $working = Employee::find($working_id);
        $zamane = ZamaneUser::where('company_id',Auth::user()->company_id)->first();
        $notification = EmployeeFile::find($request->file_id);
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
        if (!$zamane)
        {
            return back()->with('danger','Zaman Damgasında Bi Hatayla Karşılaşıldı Onaylama İşlemi Başarısız İK ile görüşün');
        }
        if (!$company)
        {
            return back()->with('danger','Firma Bulunamadı');
        }

        if (strstr($notification->file,'.docx') and $notification->file_type->name == 'KVKK')
        {
            $file = explode('/',$notification->file);
            $random_employe = rand(0,999999999);
            $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$working->id.'/'.$working->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$working->id.'_'.$notification->document_id.'_2'.'.png'));
            $template = new TemplateProcessor(public_path().$notification->file);
            $template->setValue('metin2', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
            $template->setValue('date2', \date('d/m/Y H:i:s'));
            $template->setImageValue('qr_code2', array(public_path('qr_code/'.$working->id.'_'.$notification->document_id.'_2'.'.png'), 'height' => 90, 'width' => 100));
            $template->saveAs('employee_files/'.$company->id.'/'.$working->id.'/'.$working->id.'_'.$random_employe.'.docx');

            $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.public_path('employee_files/'.$company->id.'/'.$working->id.'/'.$working->id.'_'.$random_employe.'.docx').' '.$zamane->link.' '.$zamane->port.' '.$zamane->kullanici_adi.' '.$zamane->password.' '.$zamane->damga_type);


            $degerFile =  EmployeeFile::where('employee_id',$working->id)->where('document_id',$notification->document_id)->first();
            $working_file = EmployeeFile::where('employee_id',$working->id)->where('document_id',$notification->document_id)->delete();

            $employee_file = EmployeeFile::create([
                'employee_id' => $working->id,
                'file_type_id' =>$notification->file_type_id,
                'notification' => $notification->notification,
                'document_id' => $notification->document_id,
                'file' => '/employee_files/'.$company->id.'/'.$working->id.'/'.$working->id.'_'.$random_employe.'.docx',
                'upload_date'=> $degerFile->uplaod_date ,
                'accept_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                'sms_status' =>'1',
                'zamane_accept'=>'2'

            ]);


            if ($employee_file) {
                return back()->with('success','İşlem Başarıyla Gerçekleşmiştir');
            } else
            {
                return back()->with('danger', 'Kayıt İşlemi Başarısız');
            }

        }
        elseif(strstr($notification->file,'.pdf'))
        {
              $file = explode('/',$notification->file);

            //$fileName = public_path('company_payrolls' . DIRECTORY_SEPARATOR . Auth::user()->company_id.  DIRECTORY_SEPARATOR .'zamane' .DIRECTORY_SEPARATOR. $file2[3].'_2.pdf');

              $randoms = rand(0,99999999999);
              $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.Auth::user()->company_id.'/'.$working_id.'/'.$file[4],public_path('qr_code/payrolls'.$file[4].'_2.png'));
            // $code2 =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/company_notifications/'.Auth::user()->company_id.'/zamane/'.$file2[0].'_2.pdf',public_path('qr_code/payrolls'.$file2[0].'_2.png'));


            $qrcode2 = public_path('qr_code/payrolls'.$file[4].'_2.png');
            $qrcode1 = public_path('qr_code/payrolls'.$file[4].'_2.png');
            $date = date('d/m/Y h:s:i');
            $date2 = date('d/m/Y h:s:i');
            $metin = '5070 Sayılı Elektronik İmza Kanuna Uygun Damgalanmıştır';
            $company = Company::find(Auth::user()->company_id);
            $sgk_company = SgkCompany::find(8355);
            $pdf = PDF::loadView('payrolls.zamane.test',compact('qrcode1','qrcode2','date','date2','metin','company','working','sgk_company'));
            $header = View::make('payrolls.zamane.header', ['title' => 'Morepayroll' ])->render();
            $footer =  View::make('payrolls.zamane.footer')->render();
            $pdf->save(public_path().'/test_zamane/'.$randoms.'.pdf');


            $pdfMerger = PdfMerger::init(); //Initialize the merger
            $pdfMerger->addPDF(public_path().'/employee_files/'.Auth::user()->company_id.'/'.$working_id.'/'.$file[4], 'all');
            $pdfMerger->addPDF(public_path().'/test_zamane/'.$randoms.'.pdf', 'all');
            $pdfMerger->merge();
            $pdfMerger->save(public_path().'/employee_files/'.Auth::user()->company_id.'/'.$working_id.'/'.$file[4]);




            $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.' '.public_path().'/employee_files/'.Auth::user()->company_id.'/'.$working_id.'/'.$file[4].' '.$zamane->link.' '.$zamane->port.' '.$zamane->kullanici_adi.' "'.$zamane->password.'" '.$zamane->damga_type);


            if ($deger)
            {
                $file = '/employee_files/'.Auth::user()->company_id.'/'.$working_id.'/'.$file[4];
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
        else
        {
            return back()->with('danger','Dosya Formatı Hatalı Kontrol Edip Tekrar Yükleyiniz');
        }

    }
    public function employee_file_protest(Request $request)
    {
        $id = HashingSlug::decodeHash($request->file_id);
        $file = EmployeeFile::find($id);

        if (!$file)
        {
            return back()->with('error','beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        $employee = Employee::find($file->employee->id);
        if (!$employee)
        {
            return back()->with('error','Bilgilerinize Ulaşılamadı İK ile Görüşün');
        }

        $protest = EmployeeProtest::create([
            'company_id'=>Auth::user()->company_id,
            'employee_file_id'=>$id,
            'employee_id'=>$employee->id,
            'date' => date('Y-m-d'),
            'notification'=>$request->notification
        ]);
                EmployeeFile::where('id',$id)
                ->update([
                    'zamane_accept' => 0
                ]);
        $company = Company::find(Auth::user()->company_id);
        if ($protest)
        {
           // $user =  User::where('company_id',Auth::user()->company_id)->where('user_type_id',1)->orWhere('user_type_id',2)->where('company_id',Auth::user()->company_id)->first();
            //  Mail::to($user->email)->send(new PayrollMail($working,$company,$protest,$payroll));
            return back()->with('success','İşleminiz Başarılı');
        }
        else
        {
            return back()->with('error','İşleminiz Başarısız');
        }
    }

    public function fileUpload(Request $request)
    {
        $personelFile = EmployeeFile::find($request->id);
        if (!$personelFile)
        {
            return back()->with('danger','Kayıt Bulunamadı');
        }
        $ramdom1 = rand(0,99999999999);
        if (strtolower($request->file->getClientOriginalExtension()) == "pdf") {
            $fileName = public_path('employee_files' . DIRECTORY_SEPARATOR . Auth::user()->company_id. DIRECTORY_SEPARATOR. $personelFile->employee_id);
            $file =  $request->file('file')->move($fileName , Auth::user()->company_id.'-'.$ramdom1.'.pdf');
        }
        else
        {
            return back()->with('danger','Dosya Formatı PDF değildir');
        }

        $notification =  EmployeeFile::where('id', $request->id)->update([
            'employee_id'=>$personelFile->employee_id,
            'file_type_id'=> $personelFile->file_type_id,
            'file'=> '/employee_files/'.Auth::user()->company_id.'/'.$personelFile->employee_id.'/'.Auth::user()->company_id.'-'.$ramdom1.'.pdf',
            'date'=>$personelFile->date,
            'notification'=>$personelFile->notification,
            'zamane_accept'=> 2,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($notification)
        {
            return back()->with('success','Kayıt İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Kayıt İşlemi Başarısız');
        }
    }
}
