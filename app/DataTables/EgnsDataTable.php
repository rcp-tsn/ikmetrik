<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\Egn;

class EgnsDataTable extends DataTableController
{
    protected $hash_id = true;

    /**
     * @var string
     */
    protected $model = Egn::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['id', 'name'];

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
        $query = Egn::query()->select();

        return $this->applyScopes($query);
    }
}
