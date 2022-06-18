<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\SgkCompaniesDataTable;
use App\Helpers\HashingSlug;
use App\Http\Requests\SgkCompaniesRequest;
use App\Models\CompanyAssignment;
use App\Models\SgkCompany;
use App\User;
use Auth;
use Illuminate\Http\Request;

class CompanyAssignmentController extends ApplicationController
{
    protected $hashId = true;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */

    public function index()
    {

        $users = User::where('company_id', Auth::user()->company_id)->get();
        return view('sgk_companies.assignment_index', compact('users'));
    }


    /**
     * @param $user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit($user_id)
    {
        $user_id = HashingSlug::decodeHash($user_id);
        $users = User::where('company_id', Auth::user()->company_id)->get();
        $user = User::find($user_id);

        if (Auth::user()->company_id == config('app.main_company_id')) {
            $sgk_companies = SgkCompany::orderBy('id', 'ASC')->get();
        } else {
            $sgk_companies = SgkCompany::where('company_id', Auth::user()->company_id)->orderBy('id', 'ASC')->get();
        }

        $currentAssignments = CompanyAssignment::where('user_id', $user_id)->pluck('sgk_company_id')->toArray();

        return view('sgk_companies.assignment_edit', compact('users', 'currentAssignments', 'sgk_companies', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $user_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function update(Request $request, $user_id)
    {
        //dd($request);
        $user_id_orj = $user_id;
        $user_id = HashingSlug::decodeHash($user_id);

        if ($request->isMethod('put')) {
            $messages = array(
                'sgk_companies.required' => 'Firma seçimi gereklidir.',
            );
            $this->validate($request, [
                'sgk_companies' => 'required'
            ], $messages);
        }
       // dd($request->sgk_companies);
        $sgk_companies = $request->sgk_companies;
        CompanyAssignment::where('user_id', $user_id)->delete([]);
        foreach($sgk_companies as $key => $sgk_company_id) {
            CompanyAssignment::create([
                'sgk_company_id' => $sgk_company_id,
                'user_id' => $user_id
            ]);
        }
        return redirect(route('company_assignments.index'));
    }
}
