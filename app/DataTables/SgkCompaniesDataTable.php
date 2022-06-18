<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\Company;
use App\Models\SgkCompany;
use Auth;

class   SgkCompaniesDataTable extends DataTableController
{
    protected $hash_id = true;

    /**
     * @var string
     */
    protected $model = SgkCompany::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['name',  'registry_id', 'company_username', 'company_usercode', 'company_syspassword', 'company_password'];
    protected $eager_columns = ['sector' => 'name', 'company' => 'name', 'city' => 'name'];

    /**
     * Common columns
     *
     * @var array
     */

    protected $ops_columns = ['edit', 'show'];
    protected $common_columns = [];

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $company_ids = Company::where('active', 1)->pluck('id');

        if (Auth::user()->company_id == config('app.main_company_id')) {
            if (\Auth::user()->email === 'demo2@ikmetrik.com') {
                $query = SgkCompany::with(['city', 'sector', 'company'])->where('sgk_companies.id', 753)->whereIn('sgk_companies.company_id', $company_ids)->select('sgk_companies.*');
            } else {
                $query = SgkCompany::with(['city', 'sector', 'company'])->whereIn('sgk_companies.company_id', $company_ids)->select('sgk_companies.*');
            }

        } else {
            $query = SgkCompany::with(['city', 'sector', 'company'])->whereIn('sgk_companies.company_id', $company_ids)->where('sgk_companies.company_id', Auth::user()->company_id)->select('sgk_companies.*');
        }



        return $this->applyScopes($query);
    }
}
