<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\CrmSupportsDataTable;
use App\Http\Requests\CrmSupportsRequest;
use App\Models\CrmSupport;
use Illuminate\Http\Request;
use Auth;

class CrmSupportController extends ApplicationController
{
    protected $hashId = true;
    /**
     * Display a listing of the resource.
     *
     * @param CrmSupportsDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(CrmSupportsDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EgnsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CrmSupportsRequest $request)
    {
        $class = new CrmSupport();

        return $this->createFlashRedirect($class, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  CrmSupport  $crm_support
     * @return \Illuminate\Http\Response
     */
    public function edit(CrmSupport $crm_support)
    {
        return view('standards.edit', ['crm_support' => $crm_support]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CrmSupport $crm_support
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, CrmSupport $crm_support)
    {
        //dd($request);
        if ($request->isMethod('put')) {

            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
            ]);
        }

        return $this->saveFlashRedirect($crm_support, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CrmSupport $crm_support
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(CrmSupport $crm_support)
    {
        return $this->destroyFlashRedirect($crm_support);
    }

    public function ajaxStore(Request $request)
    {
        $input['contact_by'] = $request->contact_by;
        $input['is_customer'] = 1;
        $input['company'] = Auth::user()->company->name;
        $input['ip'] = request()->ip();
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['message'] = $request->message;
        $input['phone'] = $request->phone;
        $crm_support = CrmSupport::create($input);
        if ($crm_support) {
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }

    }
}
