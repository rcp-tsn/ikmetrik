<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\WorkTitle;

class WorkTitlesDataTable extends DataTableController
{
    /**
     * @var bool
     */
    protected $hash_id = true;
    /**
     * @var string
     */
    protected $model = WorkTitle::class;

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
       // $query = WorkTitle::query()->select();

        //return $this->applyScopes($query);
    }
}
