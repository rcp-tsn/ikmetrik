
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

<!--end::Subheader-->
