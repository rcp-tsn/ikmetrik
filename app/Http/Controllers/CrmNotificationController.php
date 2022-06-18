<?php

namespace App\Http\Controllers;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Mail\EmergencyMail;
use App\Mail\NewUserMail;
use App\Models\CrmNotification;
use App\Models\CrmNotificationService;
use App\Models\Department;
use App\Models\Employee;
use App\Models\SgkCompany;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class CrmNotificationController extends Controller
{
    public function index()
    {

        $newspapers = CrmNotification::where('company_id',Auth::user()->company_id)->get();
        return view('crm.index',compact('newspapers'));
    }
    public function create()
    {
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        $departments = Department::where('company_id',Auth::user()->company_id)->get()->pluck('name','id')->toArray();
        $departments[0] = 'Seçiniz';

        return view('crm.notifications.create',compact('sgk_companies','departments'));
    }
    public function store(Request $request)
    {


        $message = $request->message;
        if ($request->type == 1)
        {
            $message = strip_tags($request->message);
        }
        if ($request->type !=3) {
            if (count($request->employees) <= 0) {
                return back()->with('danger', 'Personel Seçilemedi');
            }
        }
        $random = rand(0,999999999);
        if($request->hasFile('image')){
            File::makeDirectory('company_notifications/'.Auth::user()->company_id,0755,true,true);
            $validatedData = $request->validate([
                'image' => 'image|max:5000|mimes:jpeg,jpg,png',
            ]);
            $imagename =str_slug($request->title).'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('/company_notifications/'.Auth::user()->company_id),$random.'_'.$imagename);
            $fileName = '/company_notifications/'.Auth::user()->company_id.'/'.$random.'_'.$imagename;
        }


        if ($request->type !=3)
        {
            if (count($request->employees) <=0)
            {
                return back()->with('danger','Personel Seçilmedi');
            }
        }

        try {
            $notification =  CrmNotification::create([
                'company_id'=> Auth::user()->company_id,
                'sgk_company_id'=> $request->sgk_company_id,
                'title'=>$request->title,
                'message'=>$message,
                'type'=>$request->type,
                'image'=>isset($fileName) ? $fileName : null
            ]);
        }
        catch (\Exception $ex)
        {
            return back()->with('danger','Beklenmeyen Bir Durumla Karşılaşıldı'.$ex->getMessage());
        }
        if ($notification)
        {
            $title = $request->title;
            $message = $request->message;
            if ($request->type == 3)
            {
                $employee = Employee::where('id',Auth::user()->employee_id)->first();
                if (isset($employee))
                {
                    if (!empty($employee->top_manager_id))
                    {
                        $top_manager = Employee::find($employee->top_manager_id);
                        if ($top_manager)
                        {

                            if ( filter_var($top_manager->email, FILTER_VALIDATE_EMAIL) ){
                                Mail::to([$top_manager->email])->send(new EmergencyMail(
                                    $top_manager,$employee,$title,$message
                                ));
                              $top_managerUser = User::where('employee_id',$top_manager->employee_id)->first();
                              $employeeUser = User::where('employee_id',$employee->id)->first();
                                CrmNotificationService::create([
                                    'crm_notification_id'=>$notification->id,
                                    'employee_id'=>$top_manager->id,
                                    'user_id'=>$top_managerUser->id
                                ]);
                                CrmNotificationService::create([
                                    'crm_notification_id'=>$notification->id,
                                    'employee_id'=>$employee->id,
                                    'user_id'=>$employeeUser->id
                                ]);
                            }

                        }
                    }
                }
            }

            if ($request->type != 3 )
            {
                foreach ($request->employees as $employee_id)
                {
                    $user = \App\User::where('employee_id',$employee_id)->first();
                    if (!$user)
                    {
                        $user_id = 0;
                    }
                    else
                    {
                        $user_id = $user->id;
                    }

                    CrmNotificationService::create([
                        'crm_notification_id'=>$notification->id,
                        'employee_id'=>$employee_id,
                        'user_id'=>$user_id
                    ]);
                }
            }

            return redirect(route('home'))->with('success','İşleminiz Başarıyla Gerçekleşmiştir');
        }
        else
        {
            return back()->with('danger','Sistemdeki bakım sebebiyle işleminiz yapılamamaktadır');
        }
    }

    public function ajax_read()
    {
        CrmNotificationService::where('user_id',Auth::user()->id)
            ->update([
                'read'=>1
            ]);
        return true;
    }
    public function detail($id)
    {
        $id = HashingSlug::decodeHash($id);
        $newspaper = CrmNotification::find($id);
        if (!$newspaper)
        {
            return back()->with('danger','Haber Detayına Ulaşılamadı');
        }
        return view('crm.notifications.detail',compact('newspaper'));
    }

    public function delete($id)
    {
        $id = HashingSlug::decodeHash($id);
        $newspaper = CrmNotification::find($id);
        if (!$newspaper){
            return back()->with('danger','İşlem yapılamadı');
        }
        $delete = CrmNotification::where('id',$id)->delete([]);
        CrmNotificationService::where('crm_notification_id',$id)->delete([]);
        if ($delete)
        {
            return back()->with('success','Silme İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Silme İşlemi Başarısız');
        }
    }

    public function edit($id)
    {
        $id = HashingSlug::decodeHash($id);
        $notification = CrmNotification::find($id);
        if (!$notification)
        {
            return back()->with('danger','Beklenmeyen Bir Sorunla Karşılaşıldı');
        }
        $newspapers_services =CrmNotificationService::where('crm_notification_id',$id)->get();
        return view('crm.notifications.edit',compact('notification','newspapers_services'));
    }
}
