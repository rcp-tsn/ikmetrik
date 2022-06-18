<?php

namespace App\Http\Controllers\Kvkk;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Notification;
use App\Models\Reguests;
use App\Models\Employee;
use App\Models\EmployeePersonalInfo;
use App\Models\RequestNotifications;
use App\Models\SgkCompany;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\ZipArchive;
use PhpOffice\PhpWord\Shared\Converter;


class RequestController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('kvkk'))
        {
            $requests = Reguests::where('company_id',Auth::user()->company_id)->get();
            return view('kvkk.plaint.controller',compact('requests'));
        }
        else
        {
            $requests = Reguests::where('employee_id',Auth::user()->employee_id)->get();
            return view('kvkk.plaint.controller',compact('requests'));
        }


    }

    public function index2()
    {
        $requests = Reguests::where('company_id',Auth::user()->company_id)->get();

        return view('kvkk.plaint.controller',compact('requests'));
    }

    public function controller(Request $request)
    {
        $employee = Employee::where('id',Auth::user()->employee_id)->first();
        $control = EmployeePersonalInfo::where('employee_id',$employee->id)->where('identity_number',$request->no)->first();

        if ($control)
        {

            $working = EmployeePersonalInfo::where('identity_number', $request->no)->count();

            if ($working == 1)
            {
                $working = EmployeePersonalInfo::where('identity_number', $request->no)->first();
                $companies = Company::where('id', Auth::user()->company_id)->first();

                return view('kvkk.plaint.working_index', compact('working', 'companies'))->with('success', 'Kayıtlarınıza Ulaşıldı Aşağıdan Firmanızı Seçip Formu Doldurunuz');
            }

            else
            {
                return back()->with('danger', 'Böyle bir Çalışan Yoktur');
            }
        }
        else
        {
            return back()->with('danger', 'Bilgilerinizi kontrol ediniz.');
        }

    }

    public function request_working_create(Request $request)
    {

        if ($request->company_contact==1)
        {
            $company_contact = "Evet";

        }
        else
        {
            $company_contact = "Hayır";
        }


        $company = Company::where('id',$request->company_id)->first();

        Settings::setOutputEscapingEnabled(true);
        $random = rand(0, mt_getrandmax());
        $template = new TemplateProcessor('company_kvkk_documents/talep_formu.docx');
        $template->setValue('name', $request->name);
        $template->setValue('tc', $request->no);
        $template->setValue('address', $request->address);
        $template->setValue('back', $request->back);
        $template->setValue('phone', $request->phone);
        $template->setValue('email', $request->email);
        $template->setValue('company_contact_type', $request->company_contact_type);
        $template->setValue('company_contact',$company_contact);
        $template->setValue('customer_request', $request->customer_request);
        $template->setValue('date', date('d-m-Y',strtotime($request->date)));
        $template->setValue('company_name', $company->company_name);
        $template->setImageValue('company_logo', array(public_path('/uploads/'. $company->id . '/' . $company->company_logo), 'height' => 150, 'width' => 150));
        $template->saveAs('company_requests/' . $random . '.docx');

        $employee_id = $request->working_id;

        $request = Reguests::create([
            'employee_id'=>$request->working_id,
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'type'=>'Çalışan',
            'company_id'=>$request->company_id,
            'date'=>$request->date,
            'company_contact'=>$request->company_contact,
            'company_contact_type'=>$request->company_contact_type,
            'customer_request'=>$request->customer_request,
            'back'=>$request->back,
            'file' => $random . '.docx'
        ]);
        if ($request)
        {
                    $notification = RequestNotifications::create([
                        'employee_id' => $employee_id,
                        'company_id' => $request->company_id,
                        'notifications' => $request->customer_request,
                        'request_name' => $request->name,
                        'customer_type' => $request->company_contact_type,
                        'reader' => 0
                    ]);

            $employee = Employee::where('company_id',$request->company_id)->first();

            if ($employee)
            {
                return redirect(route('request.companyy'));
            }
            else
            {

                $requests = Reguests::where('company_id',Auth::user()->company_id)->get();

                return redirect(route('request.companyy'));

            }


        }
        else
        {
            return back()->with('danger','Talep Alınamadı');
        }
    }

    public function company_index()
    {

        $user = Auth::user();
        $data = Auth::user()->employee_id;

        if($user->hasAnyRole('kvkk'))
        {

            $requests = Reguests::where('company_id',Auth::user()->company_id)->get();

            return view('kvkk.plaint.index',compact('requests'));
        }
        elseif($user->hasAnyRole('Employee'))
        {
            $employee = Employee::where('id',$data)->first();
            if (!$employee)
            {
                return back()->with('errors','Beklenmeyen Bir Hatayla Karşılaşıldı');
            }

            $requests = Reguests::where('employee_id',$data)->get();


            return view('kvkk.plaint.index',compact('requests'));
        }
        else
        {
            return back()->with('danger','Giriş Yetkiniz Yoktur');
        }

    }

    public  function company_return (Request $request)
    {
        $working = Reguests::where('id',$request->id)->count();


        if ($working == 1)
        {

            Settings::setOutputEscapingEnabled(true);
            $date = date('d/m/Y');
            $reguest = Reguests::where('id',$request->id)->first();
            $id = $reguest->employee_id;

            $working = EmployeePersonalInfo::where('employee_id',$id)->first();

            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('company_kvkk_documents/talep_sonuc.docx');
            $template->setValue('name', $reguest->name);
            $template->setValue('date', $date);
            $template->setValue('basvuru_date',date('d-m-Y',strtotime($reguest->date)));
            $template->setValue('tc',$working->identity_number);
            $template->setValue('cevap',$request->notification);
            $template->saveAs('company_requests/request_reply/' . $random . '.docx');

            $company_update = Reguests::where('company_id',$request->company_id)->where('employee_id',$reguest->employee_id)->update([

                'company_request_file' => $random.'.docx',
                'status'=> '1'
            ]);
            if ($company_update)
            {
                return redirect(route('request.company'));
            }
            else
            {
                return back()->with('danger','İşlem Gerçekleştirilemedi');
            }
        }
        else
        {
            Settings::setOutputEscapingEnabled(true);
            $date = date('d/m/Y');
            $reguest = Reguests::where('id',$request->id)->first();

            $working = Employee::where('id',$reguest->employee_id)->first();


            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('company_kvkk_documents/talep_sonuc.docx');
            $template->setValue('name', $reguest->name);
            $template->setValue('date', $date);
            $template->setValue('basvuru_date',date('d-m-Y',strtotime($reguest->date)));
            $template->setValue('tc',$working->identity_number);
            $template->setValue('cevap',$request->notification);
            $template->saveAs('company_kvkk_documents/request_reply/' . $random . '.docx');

            $company_update = Reguests::where('company_id',$request->company_id)->where('employee_id',$reguest->employee_id)->update([

                'company_request_file' => $random.'.docx',
                'status'=> '1'
            ]);
            if ($company_update)
            {
                return redirect(route('request.companyy'));

            }
            else
            {
                return back()->with('danger','İşlem Gerçekleştirilemedi');
            }
        }


    }


}
