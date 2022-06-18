<?php

namespace App\Http\Controllers;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Performances\EmployeeManager;
use App\Models\ApprovedIncentive;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\MetricReport;
use App\Models\Packet;
use App\Models\PacketCompany;
use App\Models\Performance;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramType;
use App\Models\SgkCompany;
use Carbon\Carbon;
use Hash;
use Auth;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Response;
use Curl;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {




        if (!Auth::user()->hasRole('Admin')) {
            $sgk_company = SgkCompany::where('id', Auth::user()->sgk_company_id)->first();
            if ($sgk_company) {
                session(['selectedCompany' => $sgk_company->toArray()]);
            }

        }



        $performances = PerformanceProgram::where('company_id', Auth::user()->company_id)->where('status', '1')->get();

        if (!empty($performances)) {
            foreach ($performances as $performance) {
                $control = PerformanceApplicant::where('employee_id', Auth::user()->employee_id)->where('performance_program_id', $performance->id)->first();

                if ($control) {
                    $id = $performance->id;
                }
            }
            if (isset($id)) {
                $applicants = PerformanceApplicant::where('performance_program_id', $id)->get()->pluck('employee_id')->toArray();
                $performance_types = PerformanceProgramType::where('performance_program_id', $id)->orderBy('performance_type_id', 'ASC')->get();

            } else {
                $performance_types = [];
                session(['performance_types' => $performance_types]);
                session(['toplam_puan' => 0]);
            }
        } else {
            $performance_types = [];
            session(['performance_types' => $performance_types]);
            session(['toplam_puan' => 0]);
        }

        foreach ($performance_types as $type) {
            $deger = $type->performance_type_puan(Auth::user()->employee_id, $type->performance_program_id, $type->performance_type_id);
            if ((float)$deger) {
                $a = str_replace(',', '.', $deger);

                $toplam[] = $a;
            }

        }

        if (isset($toplam)) {
            if (count($toplam) > 0) {

                session(['toplam_puan' => array_sum($toplam)]);
            }

            session(['performance_types' => $performance_types]);

        }

        $Packets = Packet::all();
        $companyPackets = PacketCompany::where('company_id',Auth::user()->company_id)->get()->pluck('packet_id')->toArray();

        return view('home',compact('companyPackets','Packets'));
    }

        public function getAjaxMetric($metric_name)
        {
            $result = MetricReport::where('name', $metric_name)->first();
            if ($result) {
                return Response::json(['success' => true, 'metric' => $result->slug_en]);
            } else {
                return Response::json(['success' => false]);
            }

        }

        public
        function getAjaxIncentive($metric_name)
        {
            $monthNames = ["01" => "OCAK", "02" => "ŞUBAT", "03" => "MART", "04" => "NİSAN", "05" => "MAYIS", "06" => "HAZİRAN", "07" => "TEMMUZ", "08" => "AĞUSTOS", "09" => "EYLÜL", "10" => "EKİM", "11" => "KASIM", "12" => "ARALIK"];
            $r = explode(' ', $metric_name);
            $ay = $r[0];
            $yil = $r[1];

            foreach ($monthNames as $key => $value) {
                if ($ay == $value) {
                    return Response::json(['success' => true, 'tesvik' => $yil . '-' . $key . '-01']);
                }
            }
            return Response::json(['success' => false]);

        }


        public
        function faq()
        {
//            return view('faq');
            return response()->download(public_path('İK METRİK Kullanım Klavuzu.pdf'));
        }


        public
        function changePassword()
        {
            $user = Auth::user();

            return view('users.change_password', compact('user'));
        }

        public
        function savePassword(Request $request)
        {
            $user = Auth::user();
            $messages = array(
                'password.nullable' => 'Parola alanı gereklidir',
                'password.string' => 'Parola alanı hatalı girildi',
                'password.min' => 'Parola en az 6 karakter olmalıdır.',
                'password.confirmed' => 'Parola ile parola kontrol aynı olmalıdır.',
            );
            $this->validate($request, [
                'password' => 'nullable|string|min:6|confirmed',
            ], $messages);


            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            return redirect("/change-password")->with("message", "Parola başarılı bir şekilde değiştirilmiştir.");
        }


        public
        function controlIndex($accrual)
        {
            $active_company_ids = Company::where('active', 1)->pluck('id');
            for ($i = 1; $i <= 12; $i++) {
                $select_date[] = Carbon::now()->startOfMonth()->subMonth($i)->format('Y-m-d');
            }

            $date_array = explode('-', $accrual);
            if (isset($date_array) && count($date_array) == 3) {
                $donem = $date_array[1] . '/' . $date_array[0];
                $approved_ids = ApprovedIncentive::where('accrual', $accrual)->distinct('sgk_company_id')->pluck('sgk_company_id');
                $sgk_companies = SgkCompany::whereIn('company_id', $active_company_ids)->whereIn('id', $approved_ids)->get();
                $un_sgk_companies = SgkCompany::whereIn('company_id', $active_company_ids)->whereNotIn('id', $approved_ids)->get();
                return view('incentives.control_gain_incentives', compact('sgk_companies', 'donem', 'accrual', 'select_date', 'un_sgk_companies'));
            }
            return back();
        }

        public function tokenUpdate()
        {
            $employees = Employee::all();
            foreach ($employees as $employee)
            {

                $token = md5(uniqid(mt_rand(), true));
                Employee::where('id',$employee->id)->update([
                    'token'=>$token
                ]);

            }
            dd('ok');
        }

    }
