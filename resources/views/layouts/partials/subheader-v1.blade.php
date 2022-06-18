
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4  subheader-transparent" id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

        <!--begin::Info-->

        <div class="d-flex align-items-center flex-wrap mr-1">

            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline mr-5">

                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold my-2 mr-5">
                     </h5>

                <!--end::Page Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="" class="text-muted">
                           </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="" class="text-muted">
                           </a>
                    </li>
                </ul>

                <!--end::Breadcrumb-->
            </div>

            <!--end::Page Heading-->
        </div>

        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            @php
                $route = Route::currentRouteName();

                $getName = ($route != 'root') ? $route : 'admin.root';


            @endphp
            @if (strpos($route, 'index') !== false && Route::has($createNew = substr($route, 0, strrpos($route, '.') + 1) . 'create'))


                <a href="{{ route($createNew) }}" class="btn btn-primary font-weight-bold btn-sm create-button-area">
    <span class="svg-icon svg-icon-light svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span> {{ trans(str_replace('-', '_',$createNew)) }}
                </a>



            @elseif(isset($customCreateNew))
                <a href="{{ $customCreateNew }}" class="btn btn-primary font-weight-bold btn-sm">
    <span class="svg-icon svg-icon-light svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span> YENİ ŞUBE EKLE
                </a>


            @endif
            @if(!empty(session()->get('any_excel')) and strpos($route, 'index') !== false && Route::has($createNew = substr($route, 0, strrpos($route, '.') + 1) . 'create') )
                <a href="#" data-toggle="modal" data-target="#employeeAnyCreate"  class="btn btn-primary font-weight-bold btn-sm create-button-area " style="margin-left: 15px">
                 <span class="svg-icon svg-icon-light svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\Upload-folder.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"/>
        <path d="M8.54301601,14.4923287 L10.6661,14.4923287 L10.6661,16.5 C10.6661,16.7761424 10.8899576,17 11.1661,17 L12.33392,17 C12.6100624,17 12.83392,16.7761424 12.83392,16.5 L12.83392,14.4923287 L14.9570039,14.4923287 C15.2331463,14.4923287 15.4570039,14.2684711 15.4570039,13.9923287 C15.4570039,13.8681299 15.41078,13.7483766 15.3273331,13.6563877 L12.1203391,10.1211145 C11.934804,9.91658739 11.6185961,9.90119131 11.414069,10.0867264 C11.4020553,10.0976245 11.390579,10.1091008 11.3796809,10.1211145 L8.1726869,13.6563877 C7.98715181,13.8609148 8.00254789,14.1771227 8.20707501,14.3626578 C8.29906387,14.4461047 8.41881721,14.4923287 8.54301601,14.4923287 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span> Excel İle Yükle
                </a>
       @endif
            @if(isset($pdf_export))

                <a id="exportPdfHref" target="_blank" href="#" data-url="{{ $pdf_export }}" class="btn btn-success btn-sm mr-3"><i class="flaticon2-pie-chart"></i> RAPORU PDF OLARAK AKTAR</a>

            @endif
            <!--end::Actions-->

            <!--begin::Dropdown-->


            <!--end::Dropdown-->
        </div>

        <!--end::Toolbar-->
    </div>
</div>
<div class="modal fade" id="employeeAnyCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Çalışanları Excel İle Yükle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
                <a href="/sablon/employee.xlsx" download="sablon.xlsx"><button class="btn btn-success">Excel Format İndir</button></a>
            </div>

            {!! Form::open(['route' => 'employee_any_store', 'files'=>'true', 'class' => 'form','enctype'=>'multipart/form-data']) !!}
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Excel Dosyası Yükleyiniz</label>
                            <br>
                            {!! Form::file('excel',null,['class'=>'form-control','id'=>'excel_file']) !!}
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Kaydet</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!--end::Subheader-->
