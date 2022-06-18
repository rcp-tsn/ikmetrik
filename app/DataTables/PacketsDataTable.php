<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\Packet;
use App\Models\Sector;

class PacketsDataTable extends DataTableController
{

    protected $hash_id = true;
    /**
     * @var string
     */
    protected $model = Packet::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['title', 'max_user_number', 'price'];

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
        $query = Packet::query()->select();

        return $this->applyScopes($query);
    }
}
