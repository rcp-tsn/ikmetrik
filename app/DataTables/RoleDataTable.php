<?php

namespace App\DataTables;

use App\Base\DataTableController;
use Spatie\Permission\Models\Role;

class RoleDataTable extends DataTableController
{
    /**
     * @var string
     */
    protected $model = Role::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['id', 'name', 'title'];

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
        $query = Role::query()->select();
        return $this->applyScopes($query);
    }
}
