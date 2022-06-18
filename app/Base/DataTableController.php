<?php

namespace App\Base;

use App\Models\Packet;
use App\Models\PacketCompany;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

abstract class DataTableController extends DataTable
{
    /**
     * Model that is used to generate this DataTable
     *
     * @var string
     */
    protected $model = '';

    /**
     * Columns to show
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Columns to search
     *
     * Example:
     *
     * protected $search_columns = [
            'title' => [
            'column' => 0,
            'select_type' => 'text'
            ],
            'difficulty' => [
                'column' => 1,
                'select_type' => 'select2'
            ],
            'test_category.title' => [
                'column' => 2,
                'select_type' => 'select2'
            ]
        ]
     *
     * @var array
     */
    protected $search_columns = [];


    /**
     * Image columns to show the value wrapped in img tag, in other words, show image instead of a column value
     *
     * @var array
     */
    protected $image_columns = [];

    /**
     * Boolean columns for translation, show meaningful text instead of 1/true or 0/false
     *
     * @var array
     */
    protected $boolean_columns = [];

    /**
     * Relations count columns, show the number of related models. Ordering is not allowed.
     *
     * Example:
     *
     * protected $count_columns = ['books'];
     *
     * $languages = Language::with('books');
     * return $this->applyScopes($languages);
     *
     * @var array
     */
    protected $count_columns = [];

    /**
     * Laravel DataTables escapes all output by default.
     *
     * Define all the fields that should not be escaped but ops as ops is already included if set true like above
     *
     * @var array
     */
    protected $raw_columns = [];

    /**
     * Properties of the relationships that are loaded via eager loading
     * For instance let Article has a Category and we want to show the Category title within the Article Datatable
     * You can load the article that belongs to category within query function like:
     *
     * public function query()
     * {
     *      $articles = Article::with('category');
     *      return $this->applyScopes($articles);
     * }
     *
     * $eager_columns = ['category' => 'title'];
     *
     * Another example, if there are two relationships loaded via eager loading
     *
     * public function query()
     * {
     *      $comments = Comment::with('category', 'article');
     *      return $this->applyScopes($articles);
     * }
     *
     * $eager_columns = ['category' => 'title', 'article' => 'title'];
     *
     * @var array
     */
    protected $eager_columns = [];

    /**
     * Show the action buttons, show, edit and delete.
     *
     * Of course, you need to set this to false, if there exists a model with datatable but is not a resource.
     * In other words, set this to false, if the model does not have show/delete/edit routes.
     *
     * @var bool
     */
    protected $ops = true;

    /**
     * Select columns to show.
     *
     * @var array
     */
    protected $ops_columns = ['edit', 'delete'];

    /**
     * Select columns permission.
     *
     * @var array
     */
    protected $column_permission = ['create' => null, 'edit' => null, 'delete' => null];

    /**
     * Additional ops
     *
     * @var null|string
     */
    protected $additionalOps = null;
    protected $additionalOps2 = null;

    /**
     * Common columns such that used by more than one class, so that translation belongs to root, not to any model
     * specially, for instance, every model has `created_at` and `updated_at` attribute, hence translation of those
     * properties are single, instead of defining for each model, like `admin.fields.published_at` and so on.
     *
     * @var array
     */
    protected $common_columns = ['created_at', 'updated_at'];

    /**
     * Are you encrypted for id?
     *
     * @var bool
     */
    protected $hash_id = true;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $ops_columns = $this->ops_columns;
        $column_permission = $this->column_permission;
        $additionalOps = $this->additionalOps;
        $additionalOps2 = $this->additionalOps2;
        $resource = str_plural($this->getModelName());
        $hashId = $this->hash_id;
        $dataTable = new EloquentDataTable($query);
        if ($additionalOps2 == 'aaaa') {
            return $dataTable->addColumn('picture', function ($model) {
                $url= $model->picture;
                return '<img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" />';
            })->addColumn('id', function ($model) {

                $company_packet_ids = PacketCompany::where('company_id', $model->id)->pluck('packet_id');

                $packets = Packet::whereIn('id', $company_packet_ids)->get();
                $text = "";
                foreach($packets as $packet) {
                    $text .= $packet->title.',';
                }
                $text = remove_last_string($text, ',');
                return $text;
            })->addColumn('action', function($model) use ($ops_columns, $column_permission, $additionalOps, $resource, $hashId) {
                return view('partials.ops', [
                    'resource' => $resource,
                    'id' => $model->id,
                    'ops_columns' => $ops_columns,
                    'column_permission' => $column_permission,
                    'additionalOps' => $additionalOps,
                    'hashId' => $hashId,
                ]);
            })->rawColumns(['picture', 'action']);
        } else {
            return $dataTable->addColumn('action', function($model) use ($ops_columns, $column_permission, $additionalOps, $resource, $hashId) {
                return view('partials.ops', [
                    'resource' => $resource,
                    'id' => $model->id,
                    'ops_columns' => $ops_columns,
                    'column_permission' => $column_permission,
                    'additionalOps' => $additionalOps,
                    'hashId' => $hashId,
                ]);
            });
        }

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax('')
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * @return array
     */
    protected function getAjaxParameters()
    {
        return [$this->getModelName(), $this->datatables->eloquent($this->query())];
    }

    /**
     * Optional builder parameters
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        $parameters = [
            'order'   => [[0, 'desc']],
            'oLanguage' => __('admin.datatables'),
            'buttons' => [
                'excel',
                'print'
            ],
            'responsive' => true
        ];

        if (count($this->search_columns)) {

            $addParameters = [];
            foreach ($this->search_columns as $searchColumn) {

                switch ($searchColumn['select_type']) {
                    case 'text':
                        $addParameters[] = [
                            'column_number' => $searchColumn['column'],
                            'filter_type' => 'text',
                            'filter_delay' => 500,
                            'filter_default_label' => 'Aranacak kelime',
                        ];
                        break;
                    case 'select2':
                        $addParameters[] = [
                            'column_number' => $searchColumn['column'],
                            'select_type' => 'select2',
                            'style_class' => 'select2',
                            'filter_default_label' => 'Seçiniz',
                            'select_type_options' => [
                                'theme' => 'bootstrap',
                                'width' => '150px',
                                'placeholder' => 'Seçiniz',
                            ],
                            'data' => $searchColumn['data']
                        ];
                        break;
                }
            }

            $parameters = array_merge($parameters, [
                'columnFilters' => $addParameters
            ]);
        }

        return $parameters;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(array $result = [])
    {
        list($columns, $countColumnsPosition, $model, $table) = $this->getColumnParameters();

        collect($columns)->each(function ($column, $key) use ($model, $table, $countColumnsPosition, &$result) {
            $orderAndSearch = $key < $countColumnsPosition;
            $this->pushColumns($result, [
                'data' => $column,
                'name' => implode([$table, $column], '.'),
                'title' => trans(snake_case(str_plural($model)) . '.fields.' . $column)
            ], $orderAndSearch, $orderAndSearch);
        })->recollect($this->eager_columns)->each(function ($column, $key) use ($model, &$result) {
            $string = implode([$key, $column], '.');
            $this->pushColumns($result, [
                'data' => $string,
                'name' => $string,
                'title' => trans(snake_case(str_plural($model)) . '.fields.' . $string),
            ]);
        })->recollect($this->count_join_columns)->each(function ($column) use (&$result, $model) {
            $this->pushColumns($result, [
                'data' => $column,
                'name' => $column,
                'title' => trans('admin.fields.' . implode([$model, $column], '.')),
            ], true, false);
        })->recollect($this->common_columns)->each(function ($column) use ($table, &$result) {
            $string = implode([$table, $column], '.');
            $this->pushColumns($result, [
                'data' => $column,
                'name' => $string,
                'title' => trans('admin.fields.' . $column),
            ]);
        });
        return $this->pushOps($result);
    }

    /**
     * @param array $result
     * @param mixed $data
     * @param bool  $order
     * @param bool  $search
     *
     * @return array
     */
    protected function pushColumns(&$result, $data, $order = true, $search = true)
    {
        $searchColumns = $this->search_columns;
        if (count($searchColumns)) {
            if (isset($searchColumns[$data['data']]) && $searchColumns[$data['data']]['select_type'] == 'select2') {
                $order = false;
                $search = true;
            }
        }

        $result[] = array_merge($data, [
            'orderable'  => $order,
            'searchable' => $search
        ]);
        return $result;
    }

    /**
     * Get the columns that are being translated, model and table
     *
     * @return array
     */
    protected function getColumnParameters()
    {
        $columns = array_merge($this->image_columns, $this->columns, $this->boolean_columns, $this->count_columns);
        return [
            $columns,
            count($columns) - count($this->count_columns),
            $this->getModelName(),
            $this->getTableName()
        ];
    }

    /**
     * @param $datatables
     *
     * @return mixed
     */
    protected function pushOps($datatables)
    {
        if ($this->ops === true) {
            $this->pushColumns($datatables, [
                'data' => 'action',
                'name' => 'action',
                'title' => trans('admin.ops.name')
            ], false, false);
        }

        return $datatables;
    }

    /**
     * Get the table name of the model
     *
     * @return string
     */
    protected function getTableName()
    {
        return (new $this->model)->getTable();
    }

    /**
     * Show image instead of url for the image columns
     *
     * @param $model
     * @param $image_column
     *
     * @return string
     */
    protected function wrapImage($model, $image_column)
    {
        $url = asset($model->$image_column);
        return "<a target='_blank' href='{$url}'>
                    <img style='max-height:50px' class='img-responsive' src='{$url}'/>
               </a>";
    }

    /**
     * Get model name
     *
     * @return string
     */
    protected function getModelName()
    {
        return snake_case(class_basename($this->model));
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return $this->getModelName() . '_datatable_' . time();
    }

    /**
     * @param $datatables
     *
     * @return mixed
     */
    protected function setRawColumns($datatables)
    {
        return $datatables->rawColumns(array_merge($this->raw_columns, $this->image_columns));
    }
}
