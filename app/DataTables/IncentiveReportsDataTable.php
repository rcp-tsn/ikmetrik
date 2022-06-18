<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\Report;

class IncentiveReportsDataTable extends DataTableController
{

    /**
     * @var string
     */
    protected $model = Report::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['id', 'accrual'];
    protected $eager_columns = ['sgk_company' => 'name'];
    protected $additionalOps = 'incentive_reports._additional_ops';
    protected $ops_columns = [];

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
        $query = Report::with('sgk_company')->select('reports.*');

        return $this->applyScopes($query);
    }
}
