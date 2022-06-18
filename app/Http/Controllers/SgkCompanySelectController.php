<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\SgkCompanySelectsDataTable;
use App\Models\SgkCompany;
use Illuminate\Http\Request;
use App\Helpers\HashingSlug;

class SgkCompanySelectController extends ApplicationController
{
    protected $hashId = true;
    /**
     * Display a listing of the resource.
     *
     * @param SgkCompaniesDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function select(SgkCompanySelectsDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    public function selectListStore($id)
    {
        $sgk_company = SgkCompany::find(HashingSlug::decodeHash($id));
        if (!$sgk_company) {
            return $this->flashRedirect(route('sgk_company_select'), 'danger', 'İşlem gerçekleştirilemedi. Kayıt bulunamadı!');
        }

        session(['selectedCompany' => $sgk_company->toArray()]);
        return $this->flashRedirect(route('sgk_company_select'), 'success', 'Firma Seçimi başarılı bir şekilde gerçekleşti.');
    }

    public function unSelectListStore()
    {
        session(['selectedCompany' => null]);
        session(['selectedCompany_name' => null]);
        return $this->flashRedirect(route('sgk_company_select'), 'success', 'Firma Seçimi başarılı bir şekilde kaldırılmıştır.');
    }

}
