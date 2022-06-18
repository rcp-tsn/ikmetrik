<?php

namespace App\DataTables;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class EmployeeDataTable extends DataTable
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
            ->addColumn('action2',function ($query)  {
                if (!empty($query->employee_ust($query->id)))
                {
                    $top_manager = ' <div class="symbol symbol-50 symbol-light-white ">
                    <img src="/'.$query->employee_ust($query->id).'" title="'.$query->employee_ust($query->id,true).'"  class="h-75 align-self-end" alt="ssss">

                                                                                </div> ';
                }
                else
                {
                    $top_manager = 'Yönetici Tanımlı Değildir';
                }


                return $top_manager;

            })
            ->addColumn('subordinate',function ($query){

                if(count($query->employee_subordinate($query->id)) > 0)
                {
                    $a = '';

                    foreach($query->employee_subordinate($query->id) as $value => $employee_ast)
                    {
                        $a =$a.'<div class="symbol symbol-40 symbol-light-white mr-5"><div class="symbol-label">
                      <img src="/'.$employee_ast->avatar.'" title="'.$employee_ast->first_name.' '.$employee_ast->last_name.'"  class="h-75 align-self-end" alt="">
                                                                                    </div>
                                                                                </div>';
                    }



                }
                else
                {
                    $a = 'Tanımlı Personel Yoktur';
                }
                return $a;
            })

            ->addColumn('action',function ($query) {
                $btn = '';
                if (Auth::user()->hasAnyRole('e-bordro','Performance','Admin'))
                {
                    $btn =   '  <a href="'. route("employee.show", createHashId($query->id)) .'" title="Düzenle" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
																<span class="svg-icon svg-icon-md svg-icon-primary">
																	<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Communication/Write.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24"></rect>
																			<path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)"></path>
																			<path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
                                            </a>';
                }

                if (Auth::user()->hasRole('Performance') and Auth::user()->packetModule(9))
                {
                    $btn = $btn.'<a href="'.route("employee.profile", createHashId($query->id)).'" title="Personeli Göster" class="btn btn-icon btn-light btn-shadow-hover btn-sm mx-3">
																<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Design\Substract.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                            </a>';

                    $btn = $btn.'<a href="'.route("employee_settings_index", createHashId($query->id)).'" title="Yönetici Atama İşlemleri" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3">
																<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Design\Substract.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                            </a>';
                }
                if (Auth::user()->hasAnyRole('Owner')) {
                    $btn = $btn . '<a href="' . route("employee.delete", createHashId($query->id)) . '" title="Sil" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
																<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\General\Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"></rect>
        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>
        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>
    </g>
</svg><!--end::Svg Icon--></span>
                                            </a>';
                }
                if (Auth::user()->packetModule(8) or Auth::user()->packetModule(10)) {
                    if (Auth::user()->hasAnyRole('e-bordro', 'Employee', 'kvkk')) {
                        $btn = $btn . '<a href="' . route("personelFiles.show", createHashId($query->id)) . '" title="Özlük Dosyaları." class="btn btn-icon btn-light btn-hover-primary btn-sm">
																<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\Group-folders.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M4.5,21 L21.5,21 C22.3284271,21 23,20.3284271 23,19.5 L23,8.5 C23,7.67157288 22.3284271,7 21.5,7 L11,7 L8.43933983,4.43933983 C8.15803526,4.15803526 7.77650439,4 7.37867966,4 L4.5,4 C3.67157288,4 3,4.67157288 3,5.5 L3,19.5 C3,20.3284271 3.67157288,21 4.5,21 Z" fill="#000000" opacity="0.3"/>
        <path d="M2.5,19 L19.5,19 C20.3284271,19 21,18.3284271 21,17.5 L21,6.5 C21,5.67157288 20.3284271,5 19.5,5 L9,5 L6.43933983,2.43933983 C6.15803526,2.15803526 5.77650439,2 5.37867966,2 L2.5,2 C1.67157288,2 1,2.67157288 1,3.5 L1,17.5 C1,18.3284271 1.67157288,19 2.5,19 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                            </a>';
                    }
                }

                return $btn;
            })
            ->addColumn('status',function ($query){
                   if ($query->status == 0)
                   {
                       $sonuc = '<div class="alert alert-danger">Pasif</div>';
                   }
                   else
                   {
                       $sonuc = '<div class="alert alert-success">Aktif</div>';
                   }

                   return $sonuc;
            })
            ->rawColumns(['action2','action','subordinate','status']);



    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Employee $model)
    {
        $sgk_company_id = session()->get('selectedCompany')['id'];
        if (Auth::user()->hasAnyRole('e-bordro','Admin','company_owner','Performance','kvkk','Discipline'))
        {
            $employees = Employee::where('company_id',Auth::user()->company_id)
                ->where('sgk_company_id',$sgk_company_id)
                ->orderBy('first_name')
                ->select('employees.*');
        }
        elseif(Auth::user()->hasRole('Employee'))
        {
            $employees = Employee::where('company_id',Auth::user()->company_id)
                ->where('id',Auth::user()->employee_id)
                ->orderBy('first_name')
                ->select('employees.*');
        }




        return $this->applyScopes($employees);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->responsive(true)

            ->setTableId('kt_datatable')
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
            Column::make('first_name')->title('İsim'),
            Column::make('last_name')->title('Soyisim'),
            Column::make('email')->title('E-posta'),
            Column::make('action2')->title('Üst Yönetici'),
            Column::make('subordinate')->title('Ast Personel'),
            Column::make('status')->title('Durum'),
            Column::make('action')->title('İşlemler'),


        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Employee_' . date('YmdHis');
    }
}
