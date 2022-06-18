@extends('layouts.app')
@section('content')
    <style>
        #sayfalama
        {
            padding: 0px 25px 25px 25px;
        }
        #sayfalama a
        {
            color: #FFFFFF; background-color: #0AA699;padding: 3px 7px; margin-right: 6px; text-decoration: none;

        }
        #sayfalama a.aktif
        {
            background: red;
        }
    </style>
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
                                    <span class="d-block text-dark font-weight-bolder">Performans Polivalans Sorgulama</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>
                            @include('partials.alerts.error')
                        </div>


                        <!--end:: Card header-->
                        <form action="{{route('educations_excel_report')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 p-5 ">
                                    <div class="form-group m-l-5 " style="padding-left: 25px">
                                        {!! Form::select('department',$departments,$selectedDepartment,['class'=>'form-control selectpicker department']) !!}
                                    </div>
                                </div>
                                <input type="hidden" name="program_id" value="">
                                <div class="col-md-4 ">
                                    <div class="form-group p-5">
                                        <button type="button" id="btn-filter" class="btn btn-success btn-filter">Filtrele</button>
                                        <button type="button" id="excel" class="btn btn-warning ml-3 btn-excel">Excel Rapor Al</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--begin::Card body-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-1">
                                    <div class="form-group">
                                        <label>listeleme</label>
                                        <select  class="custom-select custom-select-sm form-control form-control-sm" name="" id="type">
                                            <option selected value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="200">200</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-9"></div>
                                <div class="col-lg-2">
                                    <div class="form-group" >
                                        <label for="search">Personel Arama : </label>
                                        <input type="text" name="search" id="search" class="form-control search">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered" id="kt_datatable">

                                <thead>
                                <tr>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Ad Soyad</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Performans Programı</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Puan</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Rapor</th>

                                </tr>

                                </thead>
                                <tbody>
                                @foreach($employees as $employee)
                                    <tr  class="education_employee">
                                        <td>{{$employee->full_name}}</td>
                                        <td>{{$performance_program->name}}</td>
                                        <td>{{!empty($puans[$employee->id]) ? array_sum($puans[$employee->id]) : 0 }}</td>
                                        <td><button data-id="{{$employee->id}}" data-toogle="modal" class="btn btn-success modalButton">Polivalans Raporu Göster</button></td>
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
    @foreach($employees as $employee)
        @if(isset($values[$employee->id]))
    <div class="modal fade" id="poivalans{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-6 mb-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Çalışan Polivalans Tablosu</span>
                        </h3>
                        <div style="-webkit-align-self: center">
                            <form action="{{route('polivalans_report_excel')}}" method="Post">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{createHashId($employee->id)}}">
                                <button type="submit" class="btn btn-success">Excel Aktar</button>
                            </form>

                        </div>
                    </div>
                    <!--end::Header-->

                    <!--begin::Body-->
                    <div class="card-body pt-2">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <th style="background-color: orange" colspan="4">POLİVALANS EĞİTİM İHTİYAÇ ANALİZ</th>
                                </thead>
                                <tbody>
                                @if(count($values[$employee->id]) > 0)
                                    @php $sira = 1; @endphp
                                    @foreach($values[$employee->id] as $education)
                                    <tr class="">
                                        <td>{{$sira ++}}</td>
                                        <td>{{$education['question']}}</td>
                                        <td>{{$education['puan']}}</td>
                                        <td>{{$education['durum']}}</td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>

                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Body-->
                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endif
    @endforeach
    <!--end::Container-->
    <!--end::Entry-->
@stop

@push('scripts')

    <script>

        $('#search').keyup(function () {

            var tg = $('.education_employee').length;
            $("tr.education_employee tr:gt(" + tg +  ")").show();
            $('.education_employee').hide();
            var txt = $('#search').val();
            $('.education_employee').each(function () {
                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {
                    $(this).show();
                }
            });
            var t = $('.education_employee:visible');
            $(".counter").html("Toplam <strong>" + t.length + "</strong> kişi gösteriliyor");
        });
    </script>
    <script>
        $(".modalButton").click(function ()
        {
            var id = $(this).data('id');
            if($('#poivalans'+id).length > 0)
            {
                $("#poivalans"+id).modal('show');
            }
            else
            {
                alert('Bu Personelin Polivalans Raporu Yoktur');
            }

        })
    </script>

    <script>

        $( ".btn-filter" ).click(function() {
            var  id =  '{{createHashId($performance_program->id)}}';
            var SecilenKategoriler = 0;
            $('.department :selected').each(function (i, selected) {
                SecilenKategoriler = $(selected).val();
            });
            window.location.href = 'https://ik.ikmetrik.com/performance/program/polivalans_report/'+ id +'/'+SecilenKategoriler
        });

    </script>

    <script>

        $( ".btn-excel" ).click(function() {
            var  id =  {{$performance_program->id}};
            var SecilenKategoriler={{$selectedDepartment}};
            if (SecilenKategoriler.length <=0)
            {
                   Swal.fire('Hiçbir Departman Seçilmedi')

            }
            else
            {
                window.location.href = 'https://ik.ikmetrik.com/polivalans/excel/all/'+ SecilenKategoriler + '/' + id
            }


        });

    </script>
@endpush

