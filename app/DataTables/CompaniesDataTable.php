<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\Models\Company;

class CompaniesDataTable extends DataTableController
{

    protected $hash_id = true;
    /**
     * @var string
     */
    protected $model = Company::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['picture', 'name', 'id'];


    /**
     * @var string
     */
    protected $additionalOps = 'companies._additional_ops';
    protected $additionalOps2 = 'aaaa';

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {

        $query = Company::where('active', 1)->select('companies.picture as picture_link', 'companies.id as id', 'companies.*');

        return $this->applyScopes($query);
    }
}
