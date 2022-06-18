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
                                    <span class="d-block text-dark font-weight-bolder">Çalışan Disiplin Suçları</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                        <!--end:: Card header-->
                        <div class="card-body">
                            {!! Form::open(['route' => 'employee.disciplines', 'files'=>'true', 'class' => 'form', 'id' => 'discipline_form']) !!}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold">Personel Seçiniz</label>
                                        {!! Form::select('employee',$subordinates,null,['class'=>'form-control selectpicker', 'required'=>'required', 'id'=>'employee','data-live-search'=>'true']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold">Dosya</label><br>
                                        {!! Form::file('file',null,['class'=>'form-control ', 'required'=>'required', 'id'=>'']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-weight: bold;font-size: 15px;">Tarih</label>
                                        {!! Form::date('date',null,['class'=>'form-control typeahead-remote', 'required'=>'required','id'=>'discipline_date']) !!}
                                    </div>
                                </div>
                            </div>


                                <div class="">

                                        <!--begin::List Widget 5-->
                                        <div class="card card-custom card-stretch gutter-b">

                                            <div class="row mt-4 mb-4 mr-4">
                                                <div class="col-lg-7 ml-5 mt-5 "><h3 class="card-title font-weight-bolder">Suçlar</h3></div>
                                                <div class="col-lg-4" style="margin-left: 4pc">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group" >
                                                                <label for="search">Suç Arama : </label>
                                                                <input type="text" name="search" id="search" class="form-control search">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group" >
                                                                <label for="search">Suç Filtrele : </label>
                                                                {!! Form::select('type',['İHTAR CEZALARI','İŞTEN ÇIKARMA','ÜCRET KESİNTİSİ'],null,['class'=>'form-control selectpicker','id'=>'disciplines_type']) !!}

                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>
                                            </div>
                                            <!--begin::header-->
                                            <div class="card-header border-0">

                                                <div class="card-toolbar">
                                                </div>
                                            </div>
                                            <!--end::header-->
                                            <!--begin::Body-->
                                            <div id="contaniner" style="text-align: center;font-weight: bold;font-size: large;padding-bottom: 78px" class="counter"></div>
                                            <div class="card-body pt-0  ">
                                                @foreach($company_disciplines as $ciplines)
                                                    <div class="discipline_search ">
                                                <!--begin::Item-->
                                                <div class="d-flex align-items-center mb-6 ">
                                                    <!--begin::Checkbox-->
                                                    <label class="checkbox checkbox-lg checkbox-primary flex-shrink-0 m-0 mr-4 ">
                                                        <input type="checkbox"  name="disciplines[{{$ciplines->id}}]">
                                                        <span></span>
                                                    </label>
                                                    <!--end::Checkbox-->
                                                    <!--begin::Text-->
                                                    <div class="d-flex flex-column flex-grow-1 py-2">
                                                        <a id="value" href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1 ">{{$ciplines->name}}</a>
                                                    </div>
                                                    <hr>
                                                    <!--end::Text-->
                                                    <!--begin::Dropdown-->
                                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="" data-placement="left" data-original-title="Quick actions">
                                                        <a href="#" class="btn btn-clean btn-hover-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="ki ki-bold-more-hor"></i>
                                                        </a>
                                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                                            <!--begin::Navigation-->
                                                            <ul class="navi navi-hover">
                                                                <li class="navi-header font-weight-bold py-4">
                                                                    <span class="font-size-lg">Suç Düzenle:</span>
                                                                    <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right" title="" data-original-title="Click to learn more..."></i>
                                                                </li>
                                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                                <li class="navi-item">
                                                                    <a href="#" class="navi-link">
																		<span class="navi-text">
																			<span class="label label-xl label-inline label-light-success">Düzenle</span>
																		</span>
                                                                    </a>
                                                                </li>
                                                                <li class="navi-item">
                                                                    <a href="#" class="navi-link">
																		<span class="navi-text">
																			<span class="label label-xl label-inline label-light-danger">Sil</span>
																		</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <!--end::Navigation-->
                                                        </div>
                                                    </div>
                                                    <!--end::Dropdown-->
                                                </div>
                                                    </div>
                                                @endforeach
                                                <!--end::Item-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                        <!--end::List Widget 5-->

                                </div>
                                <input type="hidden" name="program_id" id="program_id" value="{{$id}}">
                        </div>
                        <div class="card-body">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                                <button type="submit"  class="btn btn-primary font-weight-bold">Kaydet</button>
                            </div>
                        </div>



                            {!! Form::close() !!}
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


    <!--end::Entry-->
@stop
@section('js')
    <script>

            if ($('#search').val().length == 0)
            {
                $('.discipline_search').hide();
                $(".counter").html("Arama Yapınız Suçlar Listelencek !");
            }
            else
            {
                $(".counter").hide();
            }

        $('#search').keyup(function () {
            if ($('#search').val().length < 2) {
                var tg = $('.discipline_search');
                tg.hide();
                $(".counter").html("Arama Yapınız Suçlar Listelencek !");
                return;
            }
            else
            {
                $(".counter").hide();
            }
            $('.discipline_search').hide();

            var txt = $('#search').val();
            $('.discipline_search').each(function () {
                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {
                    $(this).show();
                }
            });
            var t = $('.discipline_search:visible');

        });
    </script>




@endsection



