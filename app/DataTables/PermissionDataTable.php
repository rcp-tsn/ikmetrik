<?php

namespace App\DataTables;

use App\Base\DataTableController;
use Spatie\Permission\Models\Permission;

class PermissionDataTable extends DataTableController
{
    /**
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['name'];

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
        $query = Permission::query()->select();
        return $this->applyScopes($query);
    }
}
