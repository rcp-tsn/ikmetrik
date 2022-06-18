<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\Company;
use App\Models\CompanyAssignment;
use App\Models\SgkCompany;
use Auth;

class SgkCompanySelectsDataTable extends DataTableController
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
    protected $columns = ['id', 'name',  'registry_id'];
    protected $ops_columns = [];
    protected $eager_columns = ['company' => 'name'];
    protected $additionalOps = 'main_incentives._additional_ops';
    /**
     * Common columns
     *
     * @var array
     */
    protected $common_columns = [];

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $company_ids = Company::where('active', 1)->pluck('id');

        $currentAssignments = CompanyAssignment::where('user_id', Auth::user()->id)->pluck('sgk_company_id');
        $query = SgkCompany::query()->with(['company' => function ($query2) {
            $query2->where('active', 1);
        }])->whereIn('id', $currentAssignments)->whereIn('sgk_companies.company_id', $company_ids)->select();

        return $this->applyScopes($query);
    }
}
