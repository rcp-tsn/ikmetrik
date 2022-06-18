<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\UsersDataTable;
use App\DataTables\DemoUsersDataTable;
use App\Http\Requests\UserRequest;
use App\Mail\NewDemoUserMail;
use App\Mail\NewUserMail;
use App\Models\CompanyAssignment;
use App\Models\CustomerEmail;
use App\Models\Company;
use App\Models\ModulePacket;
use App\Models\SmsUser;
use App\User;
use App\Helpers\ImageHelper;
use Auth;
use Mail;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends ApplicationController
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    public function demo(DemoUsersDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    public function createDemoUser ()
    {
        $customer_emails = CustomerEmail::where('send_email', 0)->orderBy('customer_id', 'ASC')->get();

        return view('users.create_demo', compact('customer_emails'));
    }

    public function editDemoUser($id)
    {
        $customer_email = CustomerEmail::find($id);

        return view('users.edit_demo', compact('customer_email'));
    }

    public function updateDemoUser($id, Request $request)
    {
        $user = User::where('email', mb_strtolower($request->email))->first();
        if (!$user) {
            $customer_email = CustomerEmail::find($id);
            $customer_email->update([
                'customer_official' => $request->customer_official,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'phone' => $request->phone,
            ]);
        }
        return redirect(route('users.create-demo'));
    }


    public function saveDemoUser(Request $request)
    {
        if (!isset($request['demousers']) || count($request['demousers']) < 1) {
            session()->flash('danger', "İşlem yapılacak kayıt seçiminde hata yapıldı. Lütfen seçim yapınız!");
            return back();
        }

        foreach ($request['demousers'] as $demo_id) {
            $random_password = str_random(8);
            $demodata = CustomerEmail::find($demo_id);
            $user = User::create([
                'name' => $demodata->customer_official,
                'email' => $demodata->email,
                'password' => bcrypt($random_password),
                'department_id' => 23,
                'is_demo' => 1,
                'company_id' => 134,
            ]);


            $user->roles()->sync([15]);

            $company = Company::find(134);

            CompanyAssignment::create([
                'sgk_company_id' => 654,
                'user_id' => $user->id
            ]);

            if ($user) {
                $demodata->update([
                    'send_email' => 1
                ]);
                $imageHelper = new ImageHelper();
                $imageHelper->createUserImage($user);
            }
            try {
                Mail::to([$user->email])->send(new NewDemoUserMail(
                    $company, $user, $random_password
                ));
            } catch (Swift_TransportException $e) {

            }
        }
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();

        if (Auth::user()->hasRole('Admin')) {
            $roles = Role::pluck('title', 'id')->all();
        } elseif(Auth::user()->hasRole('Owner')) {
            $roles = Role::whereNotIn('name', ['Admin', 'Metrik', 'Teşvik'])
                ->pluck('title', 'id')->all();
        } else {
            $roles = Role::whereNotIn('name', ['Admin', 'Owner', 'Metrik', 'Teşvik'])
                ->pluck('title', 'id')->all();
        }

        $data = [
            'user' => $user,
            'roles' => $roles,
            'selectCompanies' => $this->getCompanySelectList(),
            'currentCompany' => null,
            'currentRole' => null,
            'selectModules' => [],
        ];
        return view('standards.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {

        // creating user and send mail
        $is_demo = 0;
        $random_password = str_random(8);
        if (Auth::user()->company_id == 12) {
            $company_id = $request->company_id;
            if ($company_id == 134) {
                $is_demo = 1;
            } else {
                $is_demo = 0;
            }
        } else {
            $company_id = Auth::user()->company_id;

        }
        $company = Company::find($company_id);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($random_password),
            'department_id' => 23,
            'is_demo' => $is_demo,
            'company_id' => $company->id,
        ]);


        // company owner save
        $user->roles()->sync([14]);

        if (isset($user->id)) {
            $company->owner_id = $user->id;
            $company->save();
        }

        if ($user) {
            $imageHelper = new ImageHelper();
            $imageHelper->createUserImage($user);
        }

        try {
            if ( filter_var($request->email, FILTER_VALIDATE_EMAIL) ){
                Mail::to([$request->email])->send(new NewUserMail(
                    $company, $user, $random_password
                ));
            }

        } catch (Swift_TransportException $e) {

        }




        return $this->flashRedirect(route('users.index'));
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
    public function edit(User $user)
    {


        if (Auth::user()->hasRole('Admin')) {
            $roles = Role::pluck('title', 'id')->all();
        } elseif(Auth::user()->hasRole('Owner')) {
            $roles = Role::whereNotIn('name', ['Admin', 'Metrik', 'Teşvik'])
                ->pluck('title', 'id')->all();
        } else {
            $roles = Role::whereNotIn('name', ['Admin', 'Owner', 'Metrik', 'Teşvik'])
                ->pluck('title', 'id')->all();
        }

        $data = [
            'user' => $user,
            'roles' => $roles,
            'selectCompanies' => $this->getCompanySelectList(),
            'currentCompany' => $user->company_id,
            'currentRole' => count($user->roles) ? $user->roles->pluck('id')->toArray() : null,
        ];
        //dd($user->roles->pluck('id'));
        return view('standards.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->isMethod('put')) {

            $messages = array(
                'name.required' => 'İsim soyisim alanı gereklidir',
                'email.required' => 'E-posta alanı gereklidir',
                'email.unique' => 'E-posta sistemde mevcutdur.',
            );
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|unique:users,email,'.$user->id,
            ], $messages);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        if ($user) {
            $imageHelper = new ImageHelper();
            $imageHelper->createUserImage($user);
        }

        $ids = [];
        foreach ($request->role as $key => $roleId) {
            $ids[] = $roleId;
        }

        $user->roles()->sync($ids);

        return $this->flashRedirect(route('users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('owner') && ! \Auth::user()->hasRole('admin')) {
            Alert::error('Şirket sahibi silinemez.', 'Başarısız');
            return redirect()->back();
        }

//        return $this->destroyFlashRedirect($user, 'picture');
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxNotification()
    {
        \Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'status' => true
        ]);
    }


    /**
     * Login with user
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginWithUser(User $user)
    {
        \Auth::loginUsingId($user->id);

        return redirect()->route('home');
    }


}
