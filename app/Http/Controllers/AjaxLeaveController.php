<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeeTopManager;
use App\Models\Leave;
use App\Models\SmsUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxLeaveController extends Controller
{
    public function leave_status($leave,$employeeLeave,$type,$status,$notification)
    {
        $leavee = Leave::find($leave);

        if(!$leavee)
        {
            return back()->with('danger','İzin Bilgilerine Ulaşılamadı İK ile Görüşün');
        }
        $employeeLeavee = EmployeeLeave::find($employeeLeave);
        if(!$employeeLeavee)
        {
            return back()->with('danger','İzin Bilgilerine Ulaşılamadı İK ile Görüşün');
        }
        $employee = Employee::where('id',$leavee->employee_id)->first();
        if(!$employee)
        {
            return back()->with('danger','Personel Bilgilerine Ulaşılamadı İK ile Görüşün');
        }

        if ($status == 1)
        {

            if ($type != 3)
            {
               Leave::where('id',$leave)->update([
                   'status'=>'2',
                   'accept_date' => Carbon::now()->timezone('Europe/Istanbul')
               ]);
               $topmanagerControl = EmployeeTopManager::where('employee_id',$employee->id)
                   ->where('type_id',2)
                   ->count();
                $topmanager = EmployeeTopManager::where('employee_id',$employee->id)
                    ->where('type_id',2)
                    ->first();
               if ($topmanagerControl == 0)
               {
                   $leave_save = Leave::create([
                       'company_id'=>Auth::user()->company_id,
                       'employee_leave_id'=>$employeeLeave,
                       'employee_id'=>$employee->id,
                       'user_id' => 8003,
                       'status'=>'2',
                       'type_authority'=> 2 ,
                       'accept_date' => Carbon::now()->timezone('Europe/Istanbul')
                   ]);

                   $thereeTopManageControll = EmployeeTopManager::where('employee_id',$employee->id)
                       ->where('type_id',3)
                       ->count();

                   $thereeTopManage = EmployeeTopManager::where('employee_id',$employee->id)
                       ->where('type_id',3)
                       ->first();

                   if ($thereeTopManageControll == 0)
                   {
                       $leave_save = Leave::create([
                           'company_id'=>Auth::user()->company_id,
                           'employee_leave_id'=>$employeeLeave,
                           'employee_id'=>$employee->id,
                           'user_id' => 8003,
                           'status'=>'2',
                           'type_authority'=> 3 ,
                           'accept_date' => Carbon::now()->timezone('Europe/Istanbul')
                       ]);

                      EmployeeLeave::where('id',$employeeLeave)->update([
                          'status'=>'2'
                      ]);


                       return redirect(route('leaves_status'))->with('success','Onayınız Başarıyla Alınmıştır');
                   }
                   else
                   {
                       $leave_save =  Leave::create([
                           'company_id'=>Auth::user()->company_id,
                           'employee_leave_id'=>$employeeLeave,
                           'employee_id'=>$employee->id,
                           'user_id' => $thereeTopManage->userId(),
                           'status'=>'1',
                           'type_authority'=>$thereeTopManage->type_id,
                       ]);

                       $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
                       $top_manager = Employee::find($thereeTopManage->manager_id);
                       if($thereeTopManage and $sms)
                       {
                           $loginUrl = env('APP_URL').'/leave/login/'.$top_manager->token.'/'.createHashId($employeeLeavee->id);

                           $message =  $employee->full_name.' İsimli Personel '.date('d/m/Y',strtotime($employeeLeavee->start_date)).' '.$employeeLeavee->start_time.' - '.date('d/m/Y',strtotime($employeeLeavee->job_start_date)).' '.$employeeLeavee->job_start_time .'Tarihleri Arasında İzin Talebi Oluşturmuştur.İncelemeniz Önemle Rica Olunur. Giriş İçin: '.$loginUrl;

                           sendSms(Auth::user()->company_id,$sms,null,$top_manager,$message);
                       }


                       return redirect(route('leaves_status'))->with('success','Onayınız Başarıyla Alınmıştır');
                   }


               }
               else
               {
                  $leave_save =  Leave::create([
                       'company_id'=>Auth::user()->company_id,
                       'employee_leave_id'=>$employeeLeave,
                       'employee_id'=>$employee->id,
                       'user_id' => $topmanager->userId(),
                       'status'=>'1',
                       'type_authority'=>$topmanager->type_id,
                   ]);


                   $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
                   $top_manager = Employee::find($topmanager->manager_id);
                   if($top_manager and $sms)
                   {
                       $loginUrl = env('APP_URL').'/leave/login/'.$top_manager->token.'/'.createHashId($employeeLeave->id);

                       $message =  $employee->full_name.' İsimli Personel '.$employeeLeavee->start_date.' '.$employeeLeavee->start_time.' - '.$employeeLeavee->job_start_date.' '.$employeeLeavee->job_start_time .'Tarihleri Arasında İzin Talebi Oluşturmuştur.İncelemeniz Önemle Rica Olunur. Giriş İçin: '.$loginUrl;

                       sendSms(Auth::user()->company_id,$sms,null,$top_manager,$message);
                   }


                   return redirect(route('leaves_status'))->with('success','Onayınız Başarıyla Alınmıştır');


               }
            }
            else
            {
                $manager = EmployeeTopManager::where('type_id',$type)->where('employee_id',$employee->id)->first();
                if (!$manager)
                {
                    return back()->with('danger','İzin Onaylama İşlemi Başarısız');
                }
               Leave::where('id',$leave)->update([
                   'accept_date'=>Carbon::now()->timezone('Europe/Istanbul'),
                   'status'=>'2'
               ]);

                EmployeeLeave::where('id',$employeeLeave)->update([
                    'status'=>'2'
                ]);

                return redirect(route('leaves_status'))->with('success','Onaylama İşlem, Başarılı');

            }



        }
        else
        {
            Leave::where('id',$leave)->update([
                'status'=>'0',
                'decline_date' =>date('Y-m-d'),
                'declineNotification' => !empty($notification) ? $notification : ''
            ]);

            $leave = EmployeeLeave::where('id',$employeeLeave)->update([
                'status' => '0',
                'declineNotification' => !empty($notification) ? $notification : ''

            ]);


            if ($leave)
            {

                $message = 'Sayın : '.$employee->full_name.' '.$employeeLeavee->start_date.' '.$employeeLeavee->start_time.' - '.$employeeLeavee->job_start_date.' '.$employeeLeavee->job_start_time .'Tarihleri Arasında Oluşturmuş olduğunuz izin Talebi '.$notification.' Nedeni ile  reddedilmiştir';
                $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
                if ($sms)
                {
                    sendSms(Auth::user()->company_id,$sms,null,$employee,$message);
                }

                return redirect(route('leaves_status'))->with('success','İşlem, Başarılı Bir Şekilde Gerçekleştirildi ');
            }



        }
    }
}
