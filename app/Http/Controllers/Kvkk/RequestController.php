<?php

namespace App\Http\Controllers\Kvkk;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ModelHasRole;
use App\Models\SmsUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reguests;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('kvkk'))
        {
            $requests = Reguests::where('company_id',Auth::user()->company_id)->get();
            return view('kvkk.requests.index',compact('requests'));
        }
        else
        {
            $requests = Reguests::where('employee_id',Auth::user()->employee_id)->get();
            return view('kvkk.requests.index',compact('requests'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $employee = Employee::find(Auth::user()->employee_id)->first();
       if(!$employee)
       {
           return back()->with('danger','Şuan Talep Oluşturulamıyor');
       }
        return view('kvkk.requests.create',compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $employee = Employee::where('id',Auth::user()->employee_id)->first();

        if ($request->company_contact_status==1)
        {
            $company_contact_status = "Evet";

        }
        else
        {
            $company_contact_status = "Hayır";
        }

        Settings::setOutputEscapingEnabled(true);
        $random = rand(0, mt_getrandmax());
        $template = new TemplateProcessor('company_kvkk_documents/talep_formu.docx');
        $template->setValue('name', $employee->full_name);
        $template->setValue('tc', $employee->employee_personel->identity_number);
        $template->setValue('address', $employee->address);
        $template->setValue('back', $request->request_types);
        $template->setValue('phone', $employee->mobile);
        $template->setValue('email', $employee->email);
        $template->setValue('company_contact_type', $request->company_contact_type);
        $template->setValue('company_contact',$company_contact_status);
        $template->setValue('customer_request', $request->customer_request);
        $template->setValue('date', date('d/m/Y'));
        $template->setValue('company_name', $employee->company->name);
        $template->setImageValue('company_logo', array(public_path('/uploads/'. $employee->company->id . '/' . $employee->company->company_logo), 'height' => 150, 'width' => 150));
        $template->saveAs('company_requests/'.$employee->company->id.'/'. $random . '.docx');

        $request = Reguests::create([
            'employee_id'=>$employee->id,
            'name'=>$employee->full_name,
            'phone'=>$employee->mobile,
            'address'=>$employee->address,
            'type'=>$request->company_contact_type,
            'company_id'=>$employee->company->id,
            'date'=> date('Y-m-d'),
            'company_contact'=>$request->company_contact_status,
            'company_contact_type'=>$request->company_contact_type,
            'customer_request'=>$request->customer_request,
            'back'=>$request->request_types,
            'file' => 'company_requests/'.$employee->company->id.'/'. $random . '.docx'
        ]);

        if ($request) {
            $message = $employee->full_name.' Adlı Personelin KVKK Talep/Şikayet Sistemine Talebi Düşmüştür. 30 Gün İçerisinde Cevap Vermeniz Önemle Rica Olunur.';
            $sms = SmsUser::where('company_id',$employee->company->id)->first();
            $users = User::where('company_id',$employee->company_id)->get()->pluck('id')->toArray();
            $modelHasRole = ModelHasRole::whereIn('model_id',$users)->where('role_id',31)->first();
            $smsUser = User::find($modelHasRole->model_id);
            $smsEmployee = Employee::find($smsUser->employee_id);

            sendSms(Auth::user()->company_id,$sms,null,$smsEmployee,$message);


            return back()->with('success','Kaydınız Başarıyla Alınmıştır. İrtibat Kişisine İletilmiştir.');

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

    public function company_return(Request $request)
    {

        Settings::setOutputEscapingEnabled(true);
        $date = date('d/m/Y');

        $reguest = Reguests::where('id',$request->request_id)->first();

        $id = $request->id;
        $working = Employee::where('id',$id)->first();


        $random = rand(0, mt_getrandmax());
        $template = new TemplateProcessor('documents/talep_sonuc.docx');
        $template->setValue('name', $reguest->name);
        $template->setValue('date', $date);
        $template->setValue('basvuru_date',$reguest->date);
        $template->setValue('tc',$working->employee_personel->identity_number);
        $template->setValue('cevap',$request->notification);
        $template->saveAs('company_kvkk_documents/'.Auth::user()->company_id . $random . '.docx');
        $company_update = Reguests::where('company_id',Auth::user()->company_id)->where('id',$reguest->id)->update([
            'company_request_file' => 'company_kvkk_documents/'.Auth::user()->company_id . $random . '.docx',
            'status'=> '1'
        ]);
        if ($company_update)
        {
            return back()->with('success','Geri Dönüş İletilmiştir');
        }
        else
        {
            return back()->with('error','İşlem Gerçekleştirilemedi');
        }
    }
}
