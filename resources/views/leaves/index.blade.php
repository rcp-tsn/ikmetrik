@extends('layouts.app')
@section('content')
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
                <a href="{{ route('leaves.create') }}" class="btn btn-primary font-weight-bold btn-sm create-button-area">
    <span class="svg-icon svg-icon-light svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span> İZİN TALEP ET
                </a>
            </div>
        </div>
    </div>
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

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">İzinlerim</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            @if(isset($employee))
                                <div style="height: 300px;" class="table-responsive">
                                    <table class="table table-bordered " border="2">
                                        <thead>
                                        <tr>
                                            <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">Yıl</th>
                                            <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">Devreden</th>
                                            <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">Hakedilen İzin</th>
                                            <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">Kullanılan
                                                <br/>İzin</th>
                                            <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">Kalan İzin</th>
                                        </tr>
                                        </thead>
                                        <tbody>


                                        @php $i = 0; $kalan = 0; $devreden = 0; @endphp
                                        @foreach($employee->leaveYears() as $key => $year)
                                            @php
                                                $seniority = $employee->getSeniorityData();

                                                     $leave = $seniority['year_by_leave'];
                                                    $hak_edilen = isset($leave[$i - 1]) ? $leave[$i-1] : 0;
                                                    $kullanilan = $employee->getLeaveInfo($year);
                                                    $kalan = ($hak_edilen - $kullanilan) + $devreden;

                                            @endphp
                                            <tr>
                                                <td style="font-size: 15px;font-weight: bold">{{ $year }}</td>
                                                <td style="font-size: 15px;font-weight: bold">{{ $devreden }}</td>
                                                <td style="font-size: 15px;font-weight: bold">{{ $hak_edilen }}</td>
                                                <td style="font-size: 15px;font-weight: bold">{{ $kullanilan }}</td>
                                                <td style="font-size: 15px;font-weight: bold">{{ $kalan  }}</td>
                                            </tr>
                                            @php
                                                //deneme
                                                    $devreden = $kalan;
                                                    $i++;
                                            @endphp
                                        @endforeach

                                        <tr>

                                            <td style="font-size: 15px;font-weight: bold" colspan="3">Toplam Kalan İzin</td>
                                            <td style="font-size: 15px;font-weight: bold">{{ $kalan  }}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                            @endif


                            <div class="table-responsive">
                                <table class="table table-bordered " id="kt_datatable">

                                    <thead>
                                    <tr>
                                        <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">Adı Soyadı</th>
                                        <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">Departman</th>
                                        <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">İzin Başlangıç</th>
                                        <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">İzin Bitiş</th>
                                        <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">İzin Süre</th>
                                        <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">Durum</th>
                                        <th style="background-color: darkorange;font-weight: bold;font-size: 12px;color: white">İşlemler</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    @foreach($employeeLeaves as $leave)

                                        <tr>
                                            <td style="font-weight: bold;font-size: 15px">{{$leave->employee->full_name}}</td>
                                            <td style="font-weight: bold;font-size: 15px">{{$leave->department()}}</td>
                                            <td style="font-weight: bold;font-size: 15px">{{ $leave->start_date->format('d/m/Y') }} - {{$leave->start_time}} </td>
                                            <td style="font-weight: bold;font-size: 15px">{{ $leave->job_start_date->format('d/m/Y') }} - {{$leave->job_start_time}}</td>
                                            <td style="font-weight: bold;font-size: 15px">{{\Illuminate\Support\Carbon::parse($leave->start_date->format('Y-m-d').' '.$leave->start_time)->diffForHumans(\Illuminate\Support\Carbon::parse($leave->job_start_date->format('Y-m-d').' '.$leave->job_start_time)) }}
                                            <td><?php  echo isset(config('variables.employees.leave_durum')[$leave->status]) ? config('variables.employees.leave_durum')[$leave->status] : '' ; ?></td>

                                            <td nowrap="nowrap">

                                                <a href="{{ route('leaves.show', createHashId($leave->id)) }}" title="İzin Detayları" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
																<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Design\Substract.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                                </a>
                                                @if($leave->status !=2)
                                                <a href="#"  title="SİL" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area Sil" data-url="{{route('leave_delete',createHashId($leave->id))}}"><span class="svg-icon svg-icon-primary svg-icon-2x "><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\General\Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span></a>
@endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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
    <script>
        $("body").on('click','.Sil',function ()
        {
            var url = $(this).data('url');


            swal({
                title: "Emin misiniz?",
                text: "Silme İşlemi Geri Alınamaz! Yapılan Tüm Süreç Silinecek!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {

                        window.location.href= url;

                        swal("Silme İşlemi Başarılı", {
                            icon: "success",
                        });
                    } else {
                        swal("Silme İşlemi Başarısız");
                    }
                });
        });
    </script>
@endpush

