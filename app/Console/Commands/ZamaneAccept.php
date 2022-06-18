<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollCalender;
use App\Models\PayrollService;
use App\Models\SgkCompany;
use App\Models\ZamaneUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Imagick;
use PDF;
use QrCode;
use View;

class ZamaneAccept extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zamane:accept';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatik Zaman Damga İşlemleri Gerçekleştirme';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateCarbon = Carbon::now()->subDays(5)->toDateString();

        $payroll_post = Payroll::where('sms_status',1)->where('updated_at','<=',$dateCarbon)->whereNotIn('company_id',[211,213,214])->get()->pluck('id')->toArray();


         $payroll_services = PayrollService::where('zamane_accept',0)->where('sms_status',1)->whereIn('payroll_id',$payroll_post)->get()->pluck('id')->toArray();


        $collection = collect($payroll_services);

        $shuffled = $collection->shuffle();


        Log::info($shuffled->first());
        $payroll_service = PayrollService::find($shuffled->first());

        $dt = Carbon::createFromDate(date('Y-m-d', strtotime($payroll_service->updated_at)));
        $now = Carbon::now();
        $day = $now->diffInDays($dt);
        $payroll = Payroll::find($payroll_service->payroll_id);
//                dd($payroll_service);
        $company_id = $payroll->company_id;

        Log::info($day);
        if ($day > 5)
        {
            $employee_id =  $payroll_service->employee_id;
            $page = $payroll_service->page;
            $working = Employee::find($employee_id);
            $payrollService = PayrollService::find($payroll_service->id);
            $payroll = Payroll::find($payroll_service->payroll_id);
            $sgk_company = SgkCompany::find($payroll->sgk_company_id);
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

            $pdf = PDF::loadView('payrolls.zamane.test',compact('qrcode1','qrcode2','date','date2','metin','company','working','sgk_company'));
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


            $src1 = new \Imagick(public_path()."/thumb.png");
            $src2 = new \Imagick(public_path()."/thumb2.png");
            $src1->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
            $src1->setImageArtifact('compose:args', "1,0,-0.5,0.5");
            $src1->compositeImage($src2, Imagick::COMPOSITE_MATHEMATICS, 0, 2780);
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
}
