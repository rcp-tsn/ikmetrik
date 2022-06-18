<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\CompaniesDataTable;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollCalender;
use App\Models\PayrollService;
use App\Models\ZamaneUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use QrCode;
use PDF;
use View;
use Imagick;

class TesterController extends Controller
{
    public function index()
    {
        $payroll_services1 = PayrollService::where('zamane_accept',0)->get();

        foreach ($payroll_services1 as $payroll_service1)
        {
            $dt = Carbon::createFromDate(date('Y-m-d', strtotime($payroll_service1->updated_at)));
            $now = Carbon::now();
            $day = $now->diffInDays($dt);
            $payroll = Payroll::find($payroll_service1->payroll_id);

            $company_id = $payroll->company_id;

            if ($day > 5)
            {
                $say = count($payroll_services1);


                $employee_id =  $payroll_service1->employee_id;
                $page = $payroll_service1->page;
                $working = Employee::find($employee_id);
                $payrollService = PayrollService::find($payroll_service1->id);
                $payroll = Payroll::find($payroll_service1->payroll_id);
                $sgk_company_id = $payroll->sgk_company_id;

                if (!$payroll)
                {

                }
                $zamane = ZamaneUser::where('company_id',$company_id)->first();

                if (!$working)
                {

                }
                if (!$zamane)
                {

                }

                $payroll_service = PayrollService::where('payroll_id',$payrollService->payroll_id)
                    ->where('employee_id',$employee_id)
                    ->where('page',$page)
                    ->first();

                if (!$payroll_service)
                {

                }

                $file = explode('.pdf',$payroll_service->file);
                $file2 = explode('/',$file[0]);
                File::makeDirectory('company_payrolls/'.$company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d'),0755,true,true);
                $fileName = public_path('company_payrolls' . DIRECTORY_SEPARATOR . $company_id.  DIRECTORY_SEPARATOR .'zamane'. DIRECTORY_SEPARATOR .$sgk_company_id.DIRECTORY_SEPARATOR.\date_format($payroll->date,'Y-m-d') .$file2[3].'_2.pdf');
                $randoms = rand(0,99999999999);
                $code =   QrCode::size(100)->format('png')->generate(env('APP_URL').'/company_payrolls/'.$company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf',public_path('qr_code/payrolls'.$file2[3].'_2.png'));

                $qrcode2 = public_path('qr_code/payrolls'.$file2[3].'_2.png');
                $qrcode1 = public_path('qr_code/payrolls'.$file2[3].'_2.png');
                $date = $payroll->updated_at;
                $date2 =  Carbon::now()->timezone('UTC');;
                $metin = '5070 Sayılı Elektronik İmza Kanuna Uygun Damgalanmıştır';
                $company = Company::find($company_id);

                $pdf = PDF::loadView('payrolls.zamane.test2',compact('qrcode1','qrcode2','date','date2','metin','company','working'));
                $header = View::make('payrolls.zamane.header', ['title' => 'Morepayroll' ])->render();
                $footer =  View::make('payrolls.zamane.footer')->render();
                $pdf->save(public_path().'/test_zamane/'.$randoms.'.pdf');


                $im2 = new Imagick();
                $im2->setResolution(300,300);
                $im2->readimage(public_path().'/test_zamane/'.$randoms.'.pdf');
                $im2->setImageFormat('png');
                $im2->setSize(200, 700);
                $im2->writeImage(public_path().'/thumb2.png');
                $im2->clear();
                $im2->destroy();

                $im = new Imagick();

                $im->setResolution(300,300);
                $im->readimage(public_path().'/company_payrolls'.$payroll_service->file);
                $im->setImageFormat('png');
                $im->setSize(200, 1000);
                $im->writeImage(public_path().'/thumb.png');
                $im->clear();
                $im->destroy();

                dd("ok");


                $src1 = new \Imagick(public_path()."/thumb.png");
                $src2 = new \Imagick(public_path()."/thumb2.png");
                $src1->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
                $src1->setImageArtifact('compose:args', "1,0,-0.5,0.5");
                $src1->compositeImage($src2, Imagick::COMPOSITE_MATHEMATICS, 0, 1200);
                $src1->writeImage(public_path()."/oldu.png");

                $im = new \Imagick();
                $im->setResolution(300,300);
                $im->readimage(public_path().'/oldu.png');
                $im->setImageFormat('pdf');
                $im->writeImage(public_path().'/company_payrolls/'.$company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf');
                $im->clear();
                $im->destroy();


                $deger = shell_exec('java -jar tss-client-console-3.1.17.jar -z '.' '.public_path().'/company_payrolls/'.$company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf'.' '.$zamane->link.' '.$zamane->port.' '.$zamane->kullanici_adi.' '.$zamane->password.' '.$zamane->damga_type);

                if ($deger)
                {
                    $file = '/company_payrolls/'.$company_id.'/zamane/'.$sgk_company_id.'/'.\date_format($payroll->date,'Y-m-d').'/'.$page.'-'.$file2[3].'_2.pdf';
                    $payroll_service->where('id',$payroll_service->id)->update([
                        'zamane_accept' => 1,
                        'file'=> $file,
                        'updated_at'=> Carbon::now()->timezone('Europe/Istanbul')

                    ]);

                    $calender = PayrollCalender::where('payroll_service_id',$payroll_service->id)->update([
                        'accept_date' => Carbon::now()->timezone('Europe/Istanbul'),
                        'note'=>'Sistem Kendisi Onayladı'
                    ]);

                }
                else
                {

                }

            }

        }

        dd("ok");
    }
}
