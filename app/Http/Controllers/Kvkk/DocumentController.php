<?php

namespace App\Http\Controllers\Kvkk;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\CompanyHasDocument;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeFile;
use App\Models\EmployeeFileCalender;
use App\Models\EmployeeFileType;
use App\Models\SgkCompany;
use App\Models\SmsUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;
use QrCode;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get();
        $sgk_company_document_count = [];
        foreach ($sgk_companies as $sgk_company)
        {
            $sgk_company_document_count[$sgk_company->id] =  CompanyHasDocument::where('sgk_company_id',$sgk_company->id)->count();

        }
        return view('kvkk.documents.index',compact('sgk_companies','sgk_company_document_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $id = HashingSlug::decodeHash($id);
        $documents = Document::all();
        $currentAssignments = CompanyHasDocument::where('sgk_company_id',$id)->get()->pluck('document_id')->toArray();
        $sgk_company = SgkCompany::find($id);
        return view('kvkk.documents.edit',compact('documents','currentAssignments','sgk_company'));
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
        $sgk_company_id = HashingSlug::decodeHash($id);
        $company = Company::find(Auth::user()->company_id);
        $file_type = EmployeeFileType::where('name','KVKK')->where('company_id',$company->id)->first();
        if (!$file_type)
        {
            $file_type = EmployeeFileType::create([
                'name'=>'KVKK',
                'company_id'=>$company->id
            ]);
        }
        if (!$company)
        {
            return back()->with('danger','Firma Bulunamadı');
        }
        $employees = Employee::where('sgk_company_id',$sgk_company_id)->get();
        $documents = CompanyHasDocument::where('sgk_company_id',$sgk_company_id)->get()->pluck('document_file')->toArray();
        if (count($documents) > 0)
        {
            foreach ($documents as $file)
            {
                unlink(public_path().$file);
            }

        }

        $delete = CompanyHasDocument::where('sgk_company_id',$sgk_company_id)->delete([]);
        if (count($request->documents) > 0)
        {
            try {
                foreach ($request->documents as $key => $document_id) {
                    company_kvkk_file_create($document_id,$company,$sgk_company_id);


                    foreach ($employees as $employee)
                    {
                        File::makeDirectory('employee_files/'.$company->id .'/'.$employee->id, 0777, true, true);

                        $files = EmployeeFile::where('employee_id',$employee->id)->where('document_id',$document_id)->first();
                        if (!$files) {
                            Settings::setOutputEscapingEnabled(true);
                            if ($document_id == 29) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'29'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/29.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setValue('vn', $company->name);
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'29'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');

                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Araç Takip Tahütnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);



                            }


                            if ($document_id == 3) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'3'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/3.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'3'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->setValue('tc', !empty($employee->employee_personel->identity_number) ? $employee->employee_personel->identity_number : null);
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');

                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Kişisel Verileri İşlenme Muvafakatnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }
                            if ($document_id == 4) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'4'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/4.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'4'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->setValue('tc', !empty($employee->employee_personel->identity_number) ? $employee->employee_personel->identity_number : null);
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Sağlık Muvafakatnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }
                            if ($document_id == 5) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'5'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/5.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'5'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->setValue('tc', !empty($employee->employee_personel->identity_number) ? $employee->employee_personel->identity_number : null);
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Veri İşleyen Tahhütnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }

                            if ($document_id == 7) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'7'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/7.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'7'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Veri İşleyen Gören Tahhütnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }

                            if ($document_id == 12) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'12'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/12.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'12'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Kamera İzleme Tahhütnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }

                            if ($document_id == 25) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'25'.'_1'.'.png'));

                                $template = new TemplateProcessor('employee_files/kvkk_documents/25.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'25'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Çalışan Aydınlatma Metni",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);


                            }
                        }

                        if (isset($has_documnet))
                        {

                            $loginUrl = env('APP_URL').'/kvkk/login/'.$employee->email.'/'.$employee->token.'/'.createHashId($employee->id);
                            $sms = SmsUser::where('company_id',$employee->company_id)->first();
                            if (isset($sms->username))
                            {
                                $message =  $company->name.' '. 'KVKK(Kişisel Verilerin Korunması Kanunun) ilgili onay vermeniz formlar sisteme yüklenmiştir onay vermeniz önemle rica olunur.'.' Giriş Link:'.$loginUrl;

                            }

                            sendSms(Auth::user()->company_id,$sms,null,$employee,$message);
                        }
                    }
                }
            }
            catch (Swift_TransportException $e)
            {

            }
        }

        else
        {
            return redirect(route('documents.index'))->with('success','İşleminiz Başarıyla Gerçekleşmiştir');
        }
            return redirect(route('documents.index'))->with('success','İşleminiz Başarıyla Gerçekleşmiştir');
    }


    public static function KvkkDocuman($id,$employee_id)
    {

        $sgk_company_id = HashingSlug::decodeHash($id);
        $company = Company::find(Auth::user()->company_id);
        $file_type = EmployeeFileType::where('name','KVKK')->where('company_id',$company->id)->first();
        if (!$file_type)
        {
            $file_type = EmployeeFileType::create([
                'name'=>'KVKK',
                'company_id'=>$company->id
            ]);
        }
        if (!$company)
        {
            return back()->with('danger','Firma Bulunamadı');
        }
        $employees = Employee::where('id',$employee_id)->get();
        $documents = CompanyHasDocument::where('sgk_company_id',$id)->get()->pluck('document_file')->toArray();

//        if (count($documents) > 0)
//        {
//            foreach ($documents as $file)
//            {
//                unlink(public_path().$file);
//            }
//
//        }
//
//        $delete = CompanyHasDocument::where('sgk_company_id',$sgk_company_id)->delete([]);
        if (count($documents) > 0)
        {

            try {
                foreach ($documents as $key => $document_id) {
                    company_kvkk_file_create($document_id,$company,$id);


                    foreach ($employees as $employee)
                    {
                        File::makeDirectory('employee_files/'.$company->id .'/'.$employee->id, 0777, true, true);

                        $files = EmployeeFile::where('employee_id',$employee->id)->where('document_id',$document_id)->first();
                        if (!$files) {
                            Settings::setOutputEscapingEnabled(true);
                            if ($document_id == 29) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'29'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/29.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setValue('vn', $company->name);
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'29'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');

                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Araç Takip Tahütnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);



                            }


                            if ($document_id == 3) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'3'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/3.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'3'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->setValue('tc', !empty($employee->employee_personel->identity_number) ? $employee->employee_personel->identity_number : null);
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');

                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Kişisel Verileri İşlenme Muvafakatnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }
                            if ($document_id == 4) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'4'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/4.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'4'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->setValue('tc', !empty($employee->employee_personel->identity_number) ? $employee->employee_personel->identity_number : null);
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Sağlık Muvafakatnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }
                            if ($document_id == 5) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'5'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/5.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'5'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->setValue('tc', !empty($employee->employee_personel->identity_number) ? $employee->employee_personel->identity_number : null);
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Veri İşleyen Tahhütnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }

                            if ($document_id == 7) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'7'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/7.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'7'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Veri İşleyen Gören Tahhütnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }

                            if ($document_id == 12) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'12'.'_1'.'.png'));
                                $template = new TemplateProcessor('employee_files/kvkk_documents/12.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'12'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Kamera İzleme Tahhütnamesi",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);
                            }

                            if ($document_id == 25) {
                                $random_employe = rand(0,999999999);
                                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe.'.docx',public_path('qr_code/'.$employee->id.'_'.'25'.'_1'.'.png'));

                                $template = new TemplateProcessor('employee_files/kvkk_documents/25.docx');
                                $template->setValue('name', $company->name);
                                $template->setValue('first_name', $employee->first_name);
                                $template->setValue('last_name', $employee->last_name);
                                $template->setValue('phone', $employee->mobile);
                                $template->setValue('metin1', '5070 sayılı elektronik imza kanuna uygun olarak hazırlanmıştır');
                                $template->setValue('vn', $company->company_no);
                                $template->setValue('date', \date('d/m/Y'));
                                $template->setImageValue('qr_code1', array(public_path('qr_code/'.$employee->id.'_'.'25'.'_1'.'.png'), 'height' => 90, 'width' => 100));
                                $template->setImageValue('company_logo', array(public_path('/'.$company->logo), 'height' => 150, 'width' => 150));
                                $template->saveAs('employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx');
                                $has_documnet = EmployeeFile::create([
                                    'file' =>'/employee_files/'.$company->id.'/'.$employee->id.'/'.$employee->id.'_'.$random_employe . '.docx',
                                    'file_type_id' => $file_type->id,
                                    'employee_id' => $employee->id,
                                    'notification' => "Çalışan Aydınlatma Metni",
                                    'document_id'=>$document_id,
                                    'upload_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                                    'sms_status'=>'1',
                                    'zamane_accept'=>'1'

                                ]);


                            }
                        }

                        if (isset($has_documnet))
                        {

                            $loginUrl = env('APP_URL').'/kvkk/login/'.$employee->email.'/'.$employee->token.'/'.createHashId($employee->id);
                            $sms = SmsUser::where('company_id',$employee->company_id)->first();
                            if (isset($sms->username))
                            {
                                $message =  $company->name.' '. 'KVKK(Kişisel Verilerin Korunması Kanunun) ilgili onay vermeniz formlar sisteme yüklenmiştir onay vermeniz önemle rica olunur.'.' Giriş Link:'.$loginUrl;

                            }

                            sendSms(Auth::user()->company_id,$sms,null,$employee,$message);
                            return redirect(route('employee.index'))->with('success','Çalışan Başarıyla Eklendi Performans Modülü Kullanılması için  Personelin Üst Ast ve Eşdeğer Atamalarını Yapınız');
                        }
                    }
                }
            }
            catch (Swift_TransportException $e)
            {

            }
        }

        else
        {
            return redirect(route('documents.index'))->with('success','İşleminiz Başarıyla Gerçekleşmiştir');
        }
        return redirect(route('documents.index'))->with('success','İşleminiz Başarıyla Gerçekleşmiştir');
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

    public function kvkkLogin($username,$password,$id)
    {
        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find($id);
        if(!$employee)
        {
            return back()->with('danger','Çalışan Bulunamdı');
        }

        $user = User::where('employee_id',$id)->first();

        if (!$user)
        {
            return back()->with('danger','Kullanıcı Kaydı Yoktur');
        }

        $login =  Auth::loginUsingId($user->id);

        if($login)
        {
            return redirect(route('personelFiles.show',createHashId($id)));
        }
        else
        {
            return back()->with('error','Sistemde Yaşanan Aksaklık Nedeniyle İşleminiz Devam Edemiyor.Lütfen Daha Sonra Tekrar Deneyin.');
        }
    }



}
