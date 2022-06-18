@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
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
                                    <span class="d-block text-dark font-weight-bolder">Çalışan Eğitim Bilgileri</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>

                        <!--end:: Card header-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-9"></div>
                                <div class="col-lg-3">
                                    <div class="form-group" >
                                        <label for="search">Personel Arama : </label>
                                        <input type="text" name="search" id="search" class="form-control search">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($employees as $key => $employee)
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 item_employee">
                                <!--begin::Card-->
                                <div class="card card-custom gutter-b card-stretch">
                                    <!--begin::Body-->
                                    <div class="card-body pt-4">
                                        <!--begin::Toolbar-->
                                        <div class="d-flex justify-content-end">
                                        </div>
                                        <!--end::Toolbar-->
                                        <!--begin::User-->
                                        <div class="d-flex align-items-end mb-7">
                                            <!--begin::Pic-->
                                            <div class="d-flex align-items-center">
                                                <!--begin::Pic-->
                                                <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                                                    <div class="symbol symbol-circle symbol-lg-75">
                                                        <img src="/{{$employee->avatar}}" alt="image">
                                                    </div>
                                                    <div class="symbol symbol-lg-75 symbol-circle symbol-primary d-none">
                                                        <span class="font-size-h3 font-weight-boldest">JM</span>
                                                    </div>
                                                </div>
                                                <!--end::Pic-->
                                                <!--begin::Title-->
                                                <div class="d-flex flex-column">
                                                    <a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">{{$employee->first_name}} {{$employee->last_name}}</a>
                                                    <span class="text-muted font-weight-bold">{{$employee->working_title->name}}</span>
                                                </div>
                                                <!--end::Title-->
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::User-->
                                        <!--begin::Desc-->
                                        <p class="mb-7">
                                            <a href="#" class="text-primary pr-1">{{$employee->email}}</a></p>
                                        <!--end::Desc-->
                                        <!--begin::Info-->
                                        <div class="mb-7">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-dark-75 font-weight-bolder mr-2">Eğitim Düzey:</span>
                                                <div class="text-muted text-hover-primary" id="completed_education_show">{{config('variables.employees.completed_education')[$employee->completed_education($employee->id)]}}</div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-cente my-1">
                                                <span class="text-dark-75 font-weight-bolder mr-2">Okul:</span>
                                                <a href="#" class="text-muted text-hover-primary" id="scholl_show">{{$employee->scholl}}</a>
                                            </div>
                                        </div>
                                        <!--end::Info-->
                                        <a href="#" data-id="{{$employee->id}}" data-name="{{$employee->first_name}}{{$employee->last_name}}"   data-toggle="modal" data-target="#schollModal" class="btn btn-block btn-sm btn-light-warning font-weight-bolder text-uppercase py-4 scholl">Okul Güncelle</a>
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end profil-->
                                @endforeach
                            </div>



                        </div>
                    </div>
                </div>
                <!--begin::Card body-->

                <!--end:: Card body-->
            </div>
            <!--end:: Card-->


            <!--end::Row-->
            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>


    <div class="modal fade" id="schollModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                {!! Form::open(['route' => ['ajax.discipline_store',$id], 'files'=>'true', 'class' => 'form', 'id' => 'discipline_form','readonly']) !!}
                <div class="modal-body">

                    <div class="form-group">
                        <label>Personel Adı Soyadı </label>
                        {!! Form::text('employee',null,['class'=>'form-control selectpicker', 'id'=>'employee','data-live-search'=>'true']) !!}
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Okul İsmi Giriniz</label>
                                {!! Form::text('scholl',null,['class'=>'form-control','id'=>'scholl']) !!}
                            </div>
                        </div>
                        <input type="hidden" name="" id="program_id" value="{{$id}}">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Lisans Türü</label>
                                {!! Form::select('completed_education',config('variables.employees.completed_education'),null,['class'=>'form-control selectpicker','data-live-search'=>'true','id'=>'completed_education']) !!}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                    <button type="button" id="scholl_update" class="btn btn-primary font-weight-bold">Kaydet</button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!--end::Entry-->
@stop

@section('js')
    <script>

    </script>
    <script>
        $(".scholl").click(function ()
        {
            var employee_name =  $(this).data('name');
            $("#employee").val(employee_name);
            var employee_id = $(this).data('id');


        $("#scholl_update").click(function ()
        {

          var scholl =  $("#scholl").val();
          var completed_education =  $("#completed_education").val();
            $('#schollModal').modal('hide');
            $.ajax({
                type: "POST",
                url: '/ajax-employee-scholl-update',
                data: {
                    _token: "{{csrf_token()}}",
                    employee_id:employee_id,
                    scholl:scholl,
                    completed_education:completed_education
                },
                success: function (data) {
                    Swal.fire({
                        position: "top-right",
                        icon: "success",
                        title: 'Güncelleme Başarılı Sayfa Yenilenince Veriler Değişecek',
                        showConfirmButton: false,
                        timer: 3500
                    });

                },

                error: function (error) {
                    alert(error);
                }
            });
        });
     });
    </script>
    <script>
        $('#search').keyup(function () {
            if ($('#search').val().length < 2) {
                var tg = $('.item_employee');
                tg.show();
                $(".counter").html("Toplam <strong>" + tg.length + "</strong> kişi gösteriliyor");
                return;
            }
            $('.item_employee').hide();

            var txt = $('#search').val();
            $('.item_employee').each(function () {
                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {
                    $(this).show();
                }
            });
            var t = $('.item_employee:visible');
            $(".counter").html("Toplam <strong>" + t.length + "</strong> kişi gösteriliyor");
        });
    </script>

@endsection

