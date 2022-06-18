<?php

namespace App\Http\Controllers;

use App\DataTables\CompaniesDataTable;
use App\Base\ApplicationController;
use App\Helpers\HashingSlug;
use App\Helpers\ImageHelper;
use App\Http\Requests\CompanyRequest;
use App\Mail\NewUserMail;
use App\Models\ApprovedIncentive;
use App\Models\Module;
use App\Models\OrganizationChart;
use App\Models\PacketCompany;
use App\Models\PerformanceQuestion;
use App\Models\SgkCompany;
use App\Scopes\ActiveScope;
use App\Scopes\CompanyScope;
use App\User;
use App\Models\Company;
use App\Models\BillingInformation;
use App\Models\Packet;
use App\Scopes\NotCompleteScope;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use App\Helpers\Slug;
use Mail;
use Auth;
use DB;
use Swift_TransportException;

class CompanyController extends ApplicationController
{
    use Slug;
    protected $hashId = true;
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('role:Admin')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(CompaniesDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        $subCompany = false;


        $users = User::where('company_id', null)->pluck('name', 'id')->all();
        $users[0] = 'Seçiniz';
        ksort($users);

        $company = new Company();
        $company->billingInformation = new BillingInformation();


        $packets = Packet::get()->pluck('title', 'id')->all();
        $selectPacket = null;

        $data = [
            'company' => $company,
            'packets' => $packets,
            'selectPacket' => $selectPacket,
            'user' => new User(),
            'users' => $users,
            'subCompany' => $subCompany,
            'selectModules' => [],
        ];

        return view('companies.create', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CompanyRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CompanyRequest $request)
    {

        // creating company
        $companies = $request->companies;
        $slug = $this->createSlug($companies['name'], new Company());
        $companies['slug'] = $slug;


        if (isset($companies['start_date'])) {
            if(strlen($companies['start_date']) == 10) {
                $d = explode('-', $companies['start_date']);// 0 g 1 ay  2 yil
                $companies['start_date'] = $d[2].'-'.$d[1].'-'.$d[0];
            }
        }

        $company = Company::create($companies);

        // uploading company logo
        $file = $request->file('companies.logo');
        if ($file) {
            $imageHelper = new ImageHelper();
            $fullFilePath = $imageHelper->upload($request, 'companies.logo');

            $company->update([
                'logo' => $fullFilePath,
            ]);
        }

        if (! isset($request->companies['company_id'])) {
            $company->update([
                'company_id' => $company->id,
                'type' => 'parent',
            ]);

            $redirect = route('companies.index');
        } else {
            $redirect = route('companies.sub_company', ['id' => $request->companies['company_id']]);
        }

        // creating user and send mail
        $random_password = str_random(8);

        $user = User::create([
            'name' => $request->users['name'],
            'email' => $request->users['email'],
            'password' => bcrypt($random_password),
            'department_id' => 23,
            'company_id' => $company->id,
        ]);


        // company owner save
        $user->roles()->sync([14]);
        $companyPacketIds = Auth::user()->company->packets;
        if (isset($user->id)) {
            $company->owner_id = $user->id;
            $company->save();
        }

        if ($user) {
            $imageHelper = new ImageHelper();
            $imageHelper->createUserImage($user);
        }
        try {
            Mail::to([$request->users['email']])->send(new NewUserMail(
                $company, $user, $random_password
            ));
        } catch (Swift_TransportException $e) {

        }

        // organization chart update or create
        if ($user->id) {
            OrganizationChart::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'parent_id' => 0,
                'company_id' => $company->id,
            ]);
        }

        if ($company->id) {
            // creating billing information
            BillingInformation::create([
                'company_id' => $company->id,
                'trade_name' => $request->billing['trade_name'],
                'mersis_no' => $request->billing['mersis_no'],
                'tax_office' => $request->billing['tax_office'],
                'tax_number' => $request->billing['tax_number'],
            ]);

            // packet update or create
            PacketCompany::where('company_id',$company->id)->delete([]);
            foreach ($request->packet as $packetUserType => $packet) {
                PacketCompany::create([
                    'company_id'=>$company->id,
                    'status'=>1,
                    'packet_id'=>$packet[0]
                ]);
//                if ($packet[0]) {
//                    PacketCompany::updateOrCreate(
//                        ['company_id' => $company->id, 'status' => 1],
//                        ['packet_id' => $packet[0]]
//                    );
//                } else {
//                    $packet1 = PacketCompany::where('company_id', $company->id)
//                        ->where('status', 1)
//                        ->first();
//                    if ($packet1) {
//                        $packet1->update(['status' => 0]);
//                    }
//                }
            }
        }

        return $this->flashRedirect($redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $users = User::pluck('name', 'id')->all();
        $users[0] = 'Seçiniz';
        ksort($users);


        $subCompany = false;
        if ($company->company_id != $company->id) {
            $subCompany = $company->company_id;
        }

        $user = new User();
        if ($company->owner_id) {
            $user = User::findOrFail($company->owner_id);
        }

        $modules = Module::all()->pluck('title', 'id')->all();
        $selectModules = $company->modules()->get()->pluck('id')->all();


        $packets = Packet::get()->pluck('title', 'id')->all();
        $selectPackets = PacketCompany::where('company_id', $company->id)
            ->where('status', 1)
            ->get()->pluck('packet_id');
        $data = [
            'company' => $company,
            'user' => $user,
            'packets' => $packets,
            'users' => $users,
            'subCompany' => $subCompany,
            'modules' => $modules,
            'selectModules' => $selectModules,
            'selectPackets' => $selectPackets,
        ];

        return view('companies.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Company $company)
    {
        
        // company update
        $oldLogo = $company->logo;
        $companies = $request->companies;
        $companies['slug'] = $this->createSlug($companies['name'], new Company(), $company->id);

        if (isset($companies['start_date'])) {
            if(strlen($companies['start_date']) == 10) {
                $d = explode('/', $companies['start_date']);// 0 g 1 ay  2 yil
                $companies['start_date'] = $d[2].'-'.$d[1].'-'.$d[0];
            }
        }

        $company->update($companies);

        // company logo update

        $file = $request->file('companies.logo');
        if ($file) {
            $destinationPath = 'uploads/' . $company->id;
            $fileName = time() . '-' . $file->getClientOriginalName();
            $fullFilePath = $destinationPath . '/' . $fileName;

            $file->move($destinationPath, $fileName);

            if ($oldLogo) {
                \File::delete($oldLogo);
            }

            $company->update([
                'logo' => $fullFilePath,
            ]);
        }


        // company billing information update or create
        BillingInformation::updateOrCreate(
            ['company_id' => $company->id],
            [
                'trade_name' => $request->billing['trade_name'],
                'mersis_no' => $request->billing['mersis_no'],
                'tax_office' => $request->billing['tax_office'],
                'tax_number' => $request->billing['tax_number'],
            ]
        );

        // company owner save
        $user = new User();
        if ($request->users['id']) {
            $user = User::findOrFail($request->users['id']);
            $user->company_id = $company->id;
            $user->save();
        }
        if (isset($user->id)) {
            $company->owner_id = $user->id;
            $company->save();
        }

        // packet update or create
       // dd($request);

        PacketCompany::where('company_id',$company->id)
          ->delete([]);

        foreach ($request->packet as $packetUserType => $packet) {

            PacketCompany::create(
                [
                    'packet_id'=>$packet,
                    'company_id'=>$company->id,
                    'status' => 1
                ]


                );

//            if ($packet[0]) {
//                PacketCompany::updateOrCreate(
//                    ['company_id' => $company->id, 'status' => 1],
//                    ['packet_id' => $packet[0]]
//                );
//            } else {
//                $packet1 = PacketCompany::where('company_id', $company->id)
//                    ->where('status', 1)
//                    ->first();
//                if ($packet1) {
//                    $packet1->update(['status' => 0]);
//                }
//            }
        }

        return $this->flashRedirect(route('companies.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = $id;
        //$company = Company::find(HashingSlug::decodeHash($id));

        if (env('MAIN_COMPANY_ID') == $company->id) {
            Alert::error('Bu firma silinemez.', 'Başarısız');

            return redirect()->route('companies.index');
        }

        DB::transaction(function () use ($company) {

            // delete my tests
            $tests = $company->tests()->withoutGlobalScope(ActiveScope::class)->get();
            foreach ($tests as $test) {
                foreach ($test->allPurchases as $purchase) {
                    CurrentTransaction::where('purchase_id', $purchase->id)->delete();
                    $purchase->delete();
                }

                $testAssignments = $test->testAssignments()->withoutGlobalScope(CompanyScope::class)->get();
                foreach ($testAssignments as $testAssignment) {
                    UserQuizResult::where('test_assignment_id', $testAssignment->id)->delete();
                    Quiz::where('test_assignment_id', $testAssignment->id)->delete();
                    UserForeignLanguage::where('test_assignment_id', $testAssignment->id)->delete();
                    $testAssignment->delete();
                }
                $test->price()->delete();
                $test->testScales()->delete();
                $test->testRatings()->delete();
                $test->testQuestions()->delete();
                OpenPositionTest::where('test_id', $test->id)->delete();

                echo $test->id . 'idli test silindi <br>';
                $test->delete();
            }
            echo $tests->count() . ' test silindi <br>';

            // delete test assignments
            $testAssignments = TestAssignment::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id)->get();
            foreach ($testAssignments as $testAssignment) {
                UserQuizResult::where('test_assignment_id', $testAssignment->id)->delete();
                Quiz::where('test_assignment_id', $testAssignment->id)->delete();
                UserForeignLanguage::where('test_assignment_id', $testAssignment->id)->delete();

                $purchases = $testAssignment->test->allPurchases()->where('company_id', $company->id)->get();
                foreach ($purchases as $purchase) {
                    CurrentTransaction::where('purchase_id', $purchase->id)->delete();
                    $purchase->delete();
                }
                $testAssignment->delete();
            }
            echo $testAssignments->count() . ' test atamasi silindi <br>';

            // delete my trainings
            $trainings = $company->trainings()->withoutGlobalScope(ActiveScope::class)->get();
            foreach ($trainings as $training) {
                foreach ($training->allPurchases as $purchase) {
                    CurrentTransaction::where('purchase_id', $purchase->id)->delete();
                    $purchase->delete();
                }

                $trainingAssignments = $training->trainingAssignments()->withoutGlobalScope(CompanyScope::class)->get();
                foreach ($trainingAssignments as $trainingAssignment) {
                    $course = Course::where('training_assignment_id', $trainingAssignment->id)->first();

                    if ($course) {
                        CourseLesson::where('course_id', $course->id)->delete();
                    }

                    Course::where('training_assignment_id', $trainingAssignment->id)->delete();
                    $trainingAssignment->delete();
                }
                $training->price()->delete();
                $training->trainingRatings()->delete();

                echo $training->id . ' idli egitim silindi <br>';
                $training->delete();
            }
            echo $trainings->count() . ' egitim silindi <br>';

            // delete training assignments
            $trainingAssignments = TrainingAssignment::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id)->get();
            foreach ($trainingAssignments as $trainingAssignment) {
                $course = Course::where('training_assignment_id', $trainingAssignment->id)->first();

                if ($course) {
                    CourseLesson::where('course_id', $course->id)->delete();
                }

                Course::where('training_assignment_id', $trainingAssignment->id)->delete();

                $purchases = $trainingAssignment->training->allPurchases()->where('company_id', $company->id)->get();
                foreach ($purchases as $purchase) {
                    CurrentTransaction::where('purchase_id', $purchase->id)->delete();
                    $purchase->delete();
                }
                PartnerCourse::where('training_assignment_id', $trainingAssignment->id)->delete();
                $trainingAssignment->delete();
            }
            echo $trainingAssignments->count() . ' egitim atamasi silindi <br>';

            // delete my surveys
            $surveys = Survey::where('company_id', $company->id)->get();
            foreach ($surveys as $survey) {

                $surveyAssignments = $survey->surveyAssignments()->withoutGlobalScope(CompanyScope::class)->get();
                foreach ($surveyAssignments as $surveyAssignment) {
                    $surveyAssignment->delete();
                }

                echo $survey->id . ' idli anket silindi <br>';
                $survey->delete();
            }
            echo $surveys->count() . ' anket silindi <br>';

            // delete open positions
            $openPositions = OpenPosition::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id)->get();
            foreach ($openPositions as $openPosition) {
                OpenPositionUser::withoutGlobalScope(NotCompleteScope::class)->where('open_position_id', $openPosition->id)->delete();
                OpenPositionTest::where('open_position_id', $openPosition->id)->delete();
                $openPosition->delete();
            }
            echo $openPositions->count() . ' acik pozisyon silindi <br>';

            // delete competences
            foreach ($company->competences as $competence) {
                $competence->delete();
            }

            // delete organization charts
            $company->organizationCharts()->delete();

            PurchaseRequest::where('company_id', $company->id)->delete();

            $performances = Performance::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id)->get();
            foreach ($performances as $performance) {
                $performance->delete();
            }

            PacketCompany::where('company_id', $company->id)->delete();

            // delete groups of company
            $groups = Group::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id)->get();
            foreach ($groups as $group) {
                $group->delete();
            }

            // delete purchases
            $purchases = Purchase::where('company_id', $company->id)->get();
            foreach ($purchases as $purchase) {
                CurrentTransaction::where('purchase_id', $purchase->id)->delete();
                $purchase->delete();
            }

            // delete interviews of company
            $interviews = Interview::where('company_id', $company->id)->get();
            foreach ($interviews as $interview) {
                $interview->delete();
            }
            InterviewQuestion::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id)->delete();

            BillingInformation::where('company_id', $company->id)->delete();

            Balance::where('company_id', $company->id)->delete();

            UserComparison::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id)->delete();

            // delete transactions of company
            $currentTransactions = CurrentTransaction::where('company_id', $company->id)->get();
            foreach ($currentTransactions as $currentTransaction) {
                CurrentTransaction::where('purchase_id', $currentTransaction->purchase_id)->delete();
                Purchase::where('id', $currentTransaction->purchase_id)->delete();
            }

            // delete questions of company
            $questions = PerformanceQuestion::where('company_id', $company->id)->get();
            foreach ($questions as $question) {
                $question->delete();
            }

            // delete user and user's details
            foreach ($company->users as $user) {
                $user->cvPersonalDetail()->delete();
                $user->cvExperiences()->delete();
                $user->cvLanguages()->delete();
                $user->cvSkills()->delete();
                $user->cvReferences()->delete();
                $user->cvCertificates()->delete();
                $user->cvTrainings()->delete();
                $user->additionalDetail()->delete();
                $user->departmentChanges()->delete();
                $user->notifications()->delete();

                $testAssignments = TestAssignment::withoutGlobalScope(CompanyScope::class)->where('user_id', $user->id)->get();
                foreach ($testAssignments as $testAssignment) {
                    UserQuizResult::where('test_assignment_id', $testAssignment->id)->delete();
                    Quiz::where('test_assignment_id', $testAssignment->id)->delete();
                    UserForeignLanguage::where('test_assignment_id', $testAssignment->id)->delete();

                    $testAssignment->delete();
                }

                $trainingAssignments = TrainingAssignment::withoutGlobalScope(CompanyScope::class)->where('user_id', $user->id)->get();
                foreach ($trainingAssignments as $trainingAssignment) {
                    $course = Course::where('training_assignment_id', $trainingAssignment->id)->first();
                    if ($course) {
                        CourseLesson::where('course_id', $course->id)->delete();
                    }
                    Course::where('training_assignment_id', $trainingAssignment->id)->delete();
                    PartnerCourse::where('training_assignment_id', $trainingAssignment->id)->delete();
                    $trainingAssignment->delete();
                }

                $interviews = Interview::where('user_id', $user->id)->get();
                foreach ($interviews as $interview) {
                    $interview->delete();
                }

                ExpertAssignment::where('user_id', $user->id)->delete();

                UserForeignLanguage::where('user_id', $user->id)->delete();

                UserCompetence::where('adder_id', $user->id)->delete();

                TestRating::where('user_id', $user->id)->delete();
                TrainingRating::where('user_id', $user->id)->delete();
                ExpertRating::where('user_id', $user->id)->delete();

                Message::where('user_id', $user->id)->delete();
                Message::where('recipient_user_id', $user->id)->delete();

                $messageParticipants = MessageParticipant::where('user_id', $user->id)->get();
                foreach ($messageParticipants as $messageParticipant) {
                    MessageParticipant::where('parent_id', $messageParticipant->parent_id)->delete();
                    $deleteMP = MessageParticipant::find($messageParticipant->parent_id);
                    $deleteMP->delete();
                }

                $user->delete();
            }

            \File::deleteDirectory('uploads/' . $company->id);

            $company->delete();
        });

        Alert::success('Kayıt başarılı bir şekilde silindi', 'Başarılı');

        /*$hasUsers = User::where('company_id', $company->id)->count();

        if ($hasUsers == 0) {
            $company->delete();
            Alert::success('Kayıt başarılı bir şekilde silindi', 'Başarılı');
        } else {
            Alert::error('Firmaya bağlı çalışan bulunmaktadır.', 'Başarısız');
        }*/

        return redirect()->route('companies.index');
    }

    /**
     * Organization chart
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function organizationChart()
    {
        $customBreadcrumbs = [
            [
                'title' => __('companies.organization_chart'),
                'url' => '#',
                'active' => true,
            ]
        ];

        $parentCharts = $this->user->company->organizationCharts()->where('parent_id', 0)->get();

        return view('companies.organization_chart', compact('parentCharts', 'customBreadcrumbs'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subCompany($id)
    {
        $companies = Company::where('company_id', HashingSlug::decodeHash($id))->where('id', '!=', HashingSlug::decodeHash($id))->get();

        $createNew = route('companies.create', ['company_id' => HashingSlug::decodeHash($id)]);

        return view('companies.sub_company', ['object' => $companies, 'customCreateNew' => $createNew]);
    }

    /**
     * Save the companies ordering
     *
     * @param Request $request
     */
    public function postOrder(Request $request)
    {
        if ($request->ajax()) {
            foreach (json_decode($request->getContent()) as $p) {
                $page = Company::findOrFail($p->id);
                $page->lft = $p->lft;
                $page->rgt = $p->rgt;
                $page->parent_id = $p->parent_id != "" ? $p->parent_id : null;
                $page->depth = $p->depth;
                $page->save();
            }
        }
    }

    /**
     * Edit the company information by user
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function editByUser(Request $request)
    {
        $user = $this->user;
        $company = $user->company;
        $customBreadcrumbs = [
            [
                'title' => 'Şirket bilgilerini düzenle',
                'url' => '#',
                'active' => true,
            ]
        ];

        // Saving post operations
        if ($request->method() === 'POST') {
            $this->validate($request, [
                'companies.logo' => 'image|mimes:jpeg,bmp,png,gif|max:2048',
                'companies.name' => 'required'
            ]);

            $oldLogo = $company->logo;

            $company->update($request->companies);

            $file = $request->file('companies.logo');
            if ($file) {

                $imageHelper = new ImageHelper();
                $fullFilePath = $imageHelper->upload($request, 'companies.logo');

                if ($oldLogo) {
                    \File::delete($oldLogo);
                }

                $company->update([
                    'logo' => $fullFilePath,
                ]);
            }

            BillingInformation::updateOrCreate(
                ['company_id' => $company->id],
                [
                    'trade_name' => $request->billing['trade_name'],
                    'mersis_no' => $request->billing['mersis_no'],
                    'tax_office' => $request->billing['tax_office'],
                    'tax_number' => $request->billing['tax_number'],
                ]
            );

            return $this->flashRedirect();
        }

        return view('companies.edit_by_user', compact('user', 'company', 'customBreadcrumbs'));
    }

    /**
     * Packet detail of company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function packetDetail()
    {
        $customBreadcrumbs = [
            [
                'title' => __('companies.packet_detail'),
                'url' => '#',
                'active' => true,
            ]
        ];

        $packetCandidate = $this->user->company->packets(1)->first();
        $candidateLimit = $this->user->company->users()->withRole('candidate')->get()->count();

        $packetEmployee = PacketCompany::where('company_id', $this->user->company_id)
            ->where('status', 1)
            ->first();

        $employeeMaxUser = $packetEmployee ? $packetEmployee->packet->max_user_number : 0;
        $employeeLimit = $this
            ->user
            ->company
            ->users()
            ->withRole(['owner', 'human_resources', 'leader', 'employee'])
            ->get()
            ->count();

        return view('companies.packet_detail', compact(
            'customBreadcrumbs',
            'packetCandidate',
            'candidateLimit',
            'packetEmployee',
            'employeeMaxUser',
            'employeeLimit'
        ));
    }

    /**
     * Current transactions show
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function currentTransactions()
    {
        $user = \Auth::user();
        $transactions = $user->company->currentTransactions;

        $customBreadcrumbs = [
            [
                'title' => 'Cari İşlemler',
                'url' => '#',
                'active' => true,
            ]
        ];

        return view('companies.transactions',
            compact(
                'customBreadcrumbs',
                'transactions'
            )
        );
    }

    public function profile()
    {
        $user = \Auth::user();
        $company = Company::find($user->company_id);
        $company_users = User::where('company_id', $company->id)->get();

        $owner = User::find($company->owner_id);
        if (!$owner) {
            $owner = $user;
        }

        $months = [];

        if (isset($company->start_date)) {
            $months = $this->getMonthListFromDate($company->start_date->format('Y-m-d'));
        } else {
            echo 'Start date error. Check again or contact your IT department!';
            die();
        }
        $start_date = $company->start_date->startOfMonth()->format('Y-m-d');
        $sgk_company_count = SgkCompany::where('company_id', $company->id)->count();

        $sgk_companies = SgkCompany::where('company_id', $company->id)->pluck('id');
        $total_staffs = ApprovedIncentive::whereIn('sgk_company_id', $sgk_companies)->where('accrual', '>=', $start_date)->groupBy('accrual')->selectRaw('sum(total_staff) as staff, accrual ')->orderBy('accrual', 'DESC')->get();
        $chart_text = '';
        foreach($total_staffs as $total_staff) {
            $d = explode('-', $total_staff->accrual);
            $chart_text .= '{
                        "country": "'.getFullMonthName($d[1]).'/'.$d[0].'",
                        "visits": '.$total_staff->staff.'
                    },';
        }
        $chart_text = remove_last_string($chart_text, ',');

        return view('companies.profile',
            compact(
                'user',
                'company',
                'company_users',
                'owner',
                'sgk_company_count',
                'total_staffs',
                'chart_text',
                'months'
            )
        );
    }


    public function getMonthListFromDate($date)
    {
        $start    = (new DateTime($date))->modify('first day of this month');
        $end      = (new DateTime(Carbon::now()))->modify('first day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);

        $months = array();

        foreach ($period as $dt) {
            $months[] = $dt->format("F Y");
        }


        return $months;
    }
}
