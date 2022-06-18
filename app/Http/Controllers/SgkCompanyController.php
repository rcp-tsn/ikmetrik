<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\SgkCompaniesDataTable;
use App\Http\Requests\SgkCompaniesRequest;
use App\Mail\CanStartUsingMail;
use App\Models\MetrikConstant;
use App\Models\SgkCompany;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\HashingSlug;
use DB;
use Mail;
use Auth;
use Redirect;

class SgkCompanyController extends ApplicationController
{
    protected $hashId = true;
    /**
     * Display a listing of the resource.
     *
     * @param SgkCompaniesDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(SgkCompaniesDataTable $dataTable)
    {
        if (Auth::user()->company_id == 134) {
            return back();
        }
        return $dataTable->render('standards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){


        $data = [
            'sgk_company' => new SgkCompany(),
            'parent_sgk_companies' => $this->getCompaniesSelectList(),
            'sectors' => $this->getSectorsSelectList(),
            'cities' => $this->getCitiesSelectList()
        ];

        return view('standards.create', $data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param SgkCompaniesRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SgkCompaniesRequest $request)
    {
        if (Auth::user()->company->is_demo) {
            return Redirect::back()->with('warning', 'Demo hesabında yeni kayıt oluşturma yetkisi bulunmamaktadır.  ');
        }
        $class = new SgkCompany();

        return $this->createFlashRedirect($class, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  SgkCompany  $sgk_company
     * @return \Illuminate\Http\Response
     */
    public function edit(SgkCompany $sgk_company)
    {
        if (Auth::user()->company->is_demo) {
            return Redirect::back()->with('warning', 'Demo hesabında yeni kayıt oluşturma yetkisi bulunmamaktadır.  ');
        }
        $data = [
            'sgk_company' => $sgk_company,
            'parent_sgk_companies' => $this->getCompaniesSelectList(),
            'sectors' => $this->getSectorsSelectList(),
            'cities' => $this->getCitiesSelectList()
        ];
        return view('standards.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SgkCompany $sgk_company
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, SgkCompany $sgk_company)
    {

        if (Auth::user()->company->is_demo) {
            return Redirect::back()->with('warning', 'Demo hesabında yeni kayıt oluşturma yetkisi bulunmamaktadır.  ');
        }
        if ($request->isMethod('put')) {
            $messages = array(
                'name.required' => 'Firma adı alanı gereklidir.',
                'sector_id.required' => 'Sektör alanı gereklidir.',
                'company_id.required' => 'Ana Firma seçim yapılmalıdır.',
                'name.unique' => 'Firma adı sistemde mevcutdur.',
            );
            $this->validate($request, [
                'name' => 'required|unique:sgk_companies,name,'.$sgk_company->id,
                'sector_id' => 'required',
                'company_id' => 'required',
            ], $messages);
        }

        if(isset($request->is_completed) && $request->is_completed == 1) {
            //mail gönderilecek
            $user = User::find($sgk_company->created_by);
            try {
                Mail::to([$user->email])->send(new CanStartUsingMail(
                    $sgk_company, $user
                ));
            } catch (Swift_TransportException $e) {

            }
        }

        return $this->saveFlashRedirect($sgk_company, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Sector $sector
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(SgkCompany $sgk_company)
    {
        dd('kontrol edelim');
        return $this->destroyFlashRedirect($sgk_company);
    }

    public function show(SgkCompany $sgk_company)
    {
        $metrik_overtimes = MetrikConstant::where('sgk_company_id', $sgk_company->id)->where('type', 'overtime')->orderBy('value_year', 'DESC')->orderBy('value_month', 'DESC')->get();
        $metrik_educations = MetrikConstant::where('sgk_company_id', $sgk_company->id)->where('type', 'education')->orderBy('value_year', 'DESC')->get();
        $metrik_times = MetrikConstant::where('sgk_company_id', $sgk_company->id)->where('type', 'time')->orderBy('value_year', 'DESC')->get();
        $metrik_costs = MetrikConstant::where('sgk_company_id', $sgk_company->id)->where('type', 'cost')->orderBy('value_year', 'DESC')->get();
        $data = [
            'sgk_company' => $sgk_company,
            'metrik_overtimes' => $metrik_overtimes,
            'metrik_educations' => $metrik_educations,
            'metrik_times' => $metrik_times,
            'metrik_costs' => $metrik_costs,
        ];
        return view('standards.show', $data);
    }

    public function createModal ($id, $type)
    {
        $sgk_company_id = HashingSlug::decodeHash($id);
        $sgk_company = SgkCompany::find($sgk_company_id);
        return view('modals.constant', compact('sgk_company', 'type'));
    }

    public function editModal ($id)
    {
        $metrik_constant_id = HashingSlug::decodeHash($id);
        $metrik_constant = MetrikConstant::find($metrik_constant_id);
        $type = $metrik_constant->type;
        return view('modals.constant_edit', compact('metrik_constant', 'type'));
    }

    public function storeModal ($sgk_company_id, $type, Request $request)
    {
        $sgk_company_id = HashingSlug::decodeHash($sgk_company_id);
        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company) {
            return response()->json([
                'status' => false,
                'message' => 'İşlem gerçekleştirilemedi. Hatalı kayıt seçimi yapıldı!!!'
            ]);
        }
        if($type == 'overtime') {
            $rules=[
                'overtime_date' => 'required',
                'value' => "required",
            ];
            $messages = array(
                'overtime_date.required' => 'Fazla Mesai Dönemi için seçim gereklidir.',
                'value.required' => 'Fazla Mesai Süresi bilgisi gereklidir.',
            );
        } else {
            $rules=[
                'value_year' => 'required|digits:4',
                'value' => "required",
            ];
        }


        if($type == 'education') {
            $messages = array(
                'value_year.required' => 'Fazla Mesai Dönemi için yıl seçim gereklidir.',
                'value.required' => 'Eğitim Maliyeti bilgisi gereklidir.',
                'value_year.digits' => 'Yıl bilgisi hatalı girildi.',
            );
        }

        if($type == 'time') {
            $messages = array(
                'value_year.required' => 'Eğitime Ayrılan Süre için yıl seçim gereklidir.',
                'value.required' => 'Eğitime Ayrılan Süre bilgisi gereklidir.',
                'value_year.digits' => 'Yıl bilgisi hatalı girildi.',
            );
        }

        if($type == 'cost') {

            $messages = array(
                'value_year.required' => 'Ciro için yıl seçim gereklidir.',
                'value.required' => 'Ciro bilgisi gereklidir.',
                'value_year.digits' => 'Yıl bilgisi hatalı girildi.',
            );
        }

        $this->validate($request , $rules, $messages);
        if($type == 'overtime') {
            $date_array = explode('-', $request['overtime_date']);
            $input['value_year'] = $date_array[1];
            $input['value_month'] = $date_array[0];
            $input['value'] = $request['value'];
        } else {
            $input['value_month'] = null;
            $input['value'] = $request['value'];
            $input['value_year'] = $request['value_year'];
        }
        $input['type'] = $type;
        $input['sector_id'] = $sgk_company->sector_id;

        $input['sgk_company_id'] = $sgk_company->id;
        if($type == 'overtime') {
            $has = DB::table('metrik_constants')->where('sgk_company_id', $sgk_company->id)->where('type', $type)->where('value_month', $input['value_month'])->where('value_year', $input['value_year'])->first();
        } else {
            $has = DB::table('metrik_constants')->where('sgk_company_id', $sgk_company->id)->where('type', $type)->where('value_year', $input['value_year'])->first();
        }

        if (!$has) {
            MetrikConstant::create($input);
        }

        return response()->json([
            'status' => 'success',
            'redirect_url' => route('sgk_companies.show', createHashId($sgk_company->id)),
        ]);
    }

    public function updateModal ($metrik_constant_id, Request $request)
    {
        $metrik_constant_id = HashingSlug::decodeHash($metrik_constant_id);
        $metrik_constant = MetrikConstant::find($metrik_constant_id);
        if (!$metrik_constant) {
            return response()->json([
                'status' => false,
                'message' => 'İşlem gerçekleştirilemedi. Hatalı kayıt seçimi yapıldı!!!'
            ]);
        }

        if($metrik_constant->type == 'overtime') {
            $rules=[
                'overtime_date' => 'required',
                'value' => "required",
            ];
            $messages = array(
                'overtime_date.required' => 'Fazla Mesai Dönemi için seçim gereklidir.',
                'value.required' => 'Fazla Mesai Süresi bilgisi gereklidir.',
            );
        } else {
            $rules=[
                'value_year' => 'required|digits:4',
                'value' => "required",
            ];
        }


        if($metrik_constant->type == 'education') {
            $messages = array(
                'value_year.required' => 'Fazla Mesai Dönemi için yıl seçim gereklidir.',
                'value.required' => 'Eğitim Maliyeti bilgisi gereklidir.',
                'value_year.digits' => 'Yıl bilgisi hatalı girildi.',
            );
        }

        if($metrik_constant->type == 'time') {
            $messages = array(
                'value_year.required' => 'Eğitime Ayrılan Süre için yıl seçim gereklidir.',
                'value.required' => 'Eğitime Ayrılan Süre bilgisi gereklidir.',
                'value_year.digits' => 'Yıl bilgisi hatalı girildi.',
            );
        }

        if($metrik_constant->type == 'cost') {

            $messages = array(
                'value_year.required' => 'Ciro için yıl seçim gereklidir.',
                'value.required' => 'Ciro bilgisi gereklidir.',
                'value_year.digits' => 'Yıl bilgisi hatalı girildi.',
            );
        }
        $this->validate($request , $rules, $messages);

        if($metrik_constant->type == 'overtime') {
            $date_array = explode('-', $request['overtime_date']);
            $input['value_year'] = $date_array[1];
            $input['value_month'] = $date_array[0];
            $input['value'] = $request['value'];
        } else {
            $input['value_month'] = null;
            $input['value'] = $request['value'];
            $input['value_year'] = $request['value_year'];
        }
        if($metrik_constant->type == 'overtime') {
            $has = DB::table('metrik_constants')->where('id', '!=', $metrik_constant->id)->where('sgk_company_id', $metrik_constant->sgk_company_id)->where('type', $metrik_constant->type)->where('value_year', $input['value_year'])->where('value_month', $input['value_month'])->first();
        } else {
            $has = DB::table('metrik_constants')->where('id', '!=', $metrik_constant->id)->where('sgk_company_id', $metrik_constant->sgk_company_id)->where('type', $metrik_constant->type)->where('value_year', $input['value_year'])->first();
        }

        if (!$has) {
            $metrik_constant->update($input);
        }

        return response()->json([
            'status' => 'success',
            'redirect_url' => route('sgk_companies.show', createHashId($metrik_constant->sgk_company_id)),
        ]);
    }

    public function destroyModal($id)
    {
        $metrik_overtime_id = HashingSlug::decodeHash($id);
        $metrik_overtime = MetrikConstant::find($metrik_overtime_id);
        if (!$metrik_overtime) {
            return response()->json([
                'status' => false,
                'message' => 'İşlem gerçekleştirilemedi. Hatalı kayıt seçimi yapıldı!!!'
            ]);
        }
        $sgk_company_id = $metrik_overtime->sgk_company_id;
        $metrik_overtime->delete();
        return redirect(route('sgk_companies.show', createHashId($sgk_company_id)));
    }
}
