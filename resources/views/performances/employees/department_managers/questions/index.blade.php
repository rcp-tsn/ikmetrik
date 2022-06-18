@extends('layouts.app')
@section('content')
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

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
                                    <a href="{{ route('department-question-managers.create') }}" class="btn btn-primary font-weight-bold btn-sm create-button-area">
    <span class="svg-icon svg-icon-light svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span> YENİ SORU EKLE
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class=" container-fluid ">
                            <!--begin::Dashboard-->

                            <!--begin::Row-->
                            <div class="row mt-0 mt-lg-8">
                                <div class="col-xl-12">
                                    <!--begin::Card-->
                                    <div class="card card-custom gutter-b ">

                                        <!--begin::Card header-->
                                        <div class="card-header h-auto border-0">
                                            <div class="card-title py-5">
                                                <h3 class="card-label">
                                                    <span class="d-block text-dark font-weight-bolder">{{isset($department->department) ? $department->department->name.' ' :null}}POLİVALANS DEĞERLENDİRME SORULARI</span>
                                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                                </h3>
                                            </div>

                                        </div>
                                        <div class="card-body">
                                            @include('partials.alerts.error')
                                            <table class="table table-bordered " id="kt_datatable">

                                                <thead>
                                                <tr>
                                                    <th>Adı</th>
                                                    <th>Department</th>
                                                    <th>Türü</th>
                                                    <th>İşlemler</th>
                                                </tr>

                                                </thead>
                                                <tbody>
                                                @foreach($job_questions as $job_question)
                                                    <tr>
                                                        <td>{{$job_question->name}}</td>
                                                        <td>{{isset($job_question->department->name) ? $job_question->department->name :null }}</td>
                                                        <td>{{ config('variables.employees.work_type')[$job_question->work_type_id] }} </td>
                                                        <td nowrap="nowrap">
                                                            <a href="{{ route('department-question-managers.edit', createHashId($job_question->id)) }}" title="Düzenle" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
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
                                                            </a>
                                                            <a href="{{ route('company_polivalans_question_delete', createHashId($job_question->id)) }}" title="Sil" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
																<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\General\Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                                            </a>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                </div>
            </div>

            <!--end::Row-->
            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Entry-->
@stop

@push('scripts')
    <script>
        "use strict";
        var KTDatatablesAdvancedColumnRendering = function() {

            var init = function() {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    paging: true
                });

                $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
            };

            return {

                //main function to initiate the module
                init: function() {
                    init();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTDatatablesAdvancedColumnRendering.init();
        });
    </script>
@endpush

