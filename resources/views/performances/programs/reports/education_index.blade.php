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
                                    <span class="d-block text-dark font-weight-bolder">Performans Eğitim Sorgulama</span>
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
                                    {!! Form::select('educations[]',config('variables.question_grub_type'),null,['class'=>'form-control selectpicker educations','multiple']) !!}
                                </div>
                            </div>
                                <input type="hidden" name="program_id" value="{{$performance_program->id}}">
                                <div class="col-md-4 ">
                                    <div class="form-group p-5">
                                        <button type="button" id="btn-filter" class="btn btn-success btn-filter">Filtrele</button>
                                        <button type="submit" id="excel" class="btn btn-warning ml-3">Excel Rapor Al</button>
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
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Alması Greken Eğitimler</th>

                                </tr>

                                </thead>
                                <tbody>

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
    <!--end::Entry-->
@stop

@push('scripts')
    <script>


            $("#excel").hide();
            $( ".btn-filter" ).click(function() {
                $('#excel').show();
            });

    </script>
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

        $( ".btn-filter" ).click(function() {
            var  id =  {{$performance_program->id}};
            var SecilenKategoriler = [];
            $('.educations :selected').each(function (i, selected) {
                SecilenKategoriler[i] = $(selected).text();
            });

            var sayi = $(".type_applicant").length;

            if(sayi > 0 )
            {
                $(".type_applicant").append().remove();
            }

            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/employee-educations-filter',
                data:{
                    'secilenler':SecilenKategoriler,
                    'program_id':id
                },
                success: function (datas) {
                    var items = '';
                    $.each(datas, function (i, item) {
                        $('#kt_datatable tbody').append(item);
                    });
                },
            });
        });

    </script>
    <script>
        /*
        $("#excel").click(function ()
        {
            var  id =  {{$performance_program->id}};
            var SecilenKategoriler = [];
            $('.educations :selected').each(function (i, selected) {
                SecilenKategoriler[i] = $(selected).text();
            });

            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/employee-educations-excel-report',
                data:{
                    'secilenler':SecilenKategoriler,
                    'program_id':id
                },
                success: function (datas) {
                   alert('başarılı');
                },
            });

        });
*/
    </script>
@endpush

