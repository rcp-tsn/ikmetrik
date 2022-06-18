<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\Department;

class DepartmentsDataTable extends DataTableController
{
    /**
     * @var bool
     */
    protected $hash_id = true;
    /**
     * @var string
     */
    protected $model = Department::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['id', 'name'];

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Department::query()->select();

        return $this->applyScopes($query);
    }
}
