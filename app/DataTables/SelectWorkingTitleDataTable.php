<?php

namespace App\DataTables;

use App\Models\WorkTitle;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class SelectWorkingTitleDataTable extends DataTable
{

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action',function ($query) {
                return  '<a href="'.route('worktitleSelect',createHashId($query->id)).'"><button class="btn btn-success">Ünvan Seç</button></a';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\WorkTitle $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $data =  WorkTitle::where('sgk_company_id',\Auth::user()->sgk_company_id)->select('work_titles.*');
       // $query =  $model->where('sgk_company_id',\Auth::user()->sgk_company_id)->get()->pluck('name','id');

        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('work_titles-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);


    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
           Column::make('name')->title('Ünvan Adı'),Column::make('action')->title('İşlemler')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'WorkTitle_' . date('YmdHis');
    }
}
