<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\CrmSupport;
use DB;

class CrmSupportsDataTable extends DataTableController
{
    protected $hash_id = true;

    /**
     * @var string
     */
    protected $model = CrmSupport::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['contact_by', 'company', 'name', 'email', 'phone', 'status', 'created_at2', 'ip'];

    /**
     * Common columns
     *
     * @var array
     */
    protected $common_columns = [];
    protected $ops_columns = ['edit', 'delete'];
    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = CrmSupport::query()->select(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y %H:%i") as created_at2'), 'crm_supports.*');

        return $this->applyScopes($query);
    }
}
