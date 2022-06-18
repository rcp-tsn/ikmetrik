<?php

namespace App\DataTables;

use App\Base\DataTableController;
use App\User;
use DB;

class DemoUsersDataTable extends DataTableController
{
    /**
     * @var string
     */
    protected $model = User::class;

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = ['picture', 'name', 'email'];

    protected $eager_columns = ['company' => 'name'];

    /**
     * Common columns
     *
     * @var array
     */
    protected $common_columns = ['created_at'];

    protected $ops_columns = [];

    /**
     * @var string
     */
    protected $additionalOps = 'users._additional_ops';
    protected $additionalOps2 = 'aaaa';
    protected $hash_id = true;

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {

        $user = \Auth::user();
        if ($user->hasRole('Admin')) {
            $query = User::with('company')->where('is_demo', 1)->select('users.*');
        } else {
            $query = User::with('company')->where('users.company_id', $user->company_id)->select('users.*');
        }

        return $this->applyScopes($query);
    }
}
