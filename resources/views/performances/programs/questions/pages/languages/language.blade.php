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
                                    <span class="d-block text-dark font-weight-bolder">Çalışan Dil Bilgileri</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>
                            <div class="form-group" style="text-align: -webkit-right;margin-right: 20px; padding-top: 20px">
                                <a href="{{ URL::previous() }}">
                                    <div data-repeater-create=""  class="btn font-weight-bold btn-danger">
                                        <i class="la la-backspace"></i>Geri</div>
                                </a>
                            </div>
                        </div>

                        <!--end:: Card header-->
                        <div class="card-body">

                                <table class="table table-bordered" id="kt_datatable">
                                    <thead>
                                    <th>#</th>
                                    <th>Ad Soyad</th>
                                    <th>Ünvan</th>
                                    <th>Bilinen Yabancı Dil Sayısı</th>
                                    <th>İşlemler</th>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $key => $employee)
                                        <tr>
                                            <td> <div class="symbol symbol-circle symbol-lg-75">
                                                    <img src="/{{$employee->avatar}}" alt="image">
                                                </div></td>
                                            <td>{{$employee->first_name}} {{$employee->last_name}}</td>
                                            <td>{{$employee->working_title->name}}</td>
                                            <td>{{isset( $language_count[$employee->id]) ?  $language_count[$employee->id] : '0' }}</td>
                                            <td> <a href="#" data-id="{{$employee->id}}" id="show_discont"  data-toggle="modal" data-target="#DiscontModal{{$employee->id}}" class="btn btn-block btn-sm btn-light-warning font-weight-bolder text-uppercase py-4 scholl">Bildiği Yabancı Diller</a>
                                                <a href="#" data-id="{{$employee->id}}" data-name="{{$employee->first_name}}{{$employee->last_name}}"   data-toggle="modal" data-target="#schollModal" class="btn btn-block btn-sm btn-light-warning font-weight-bolder text-uppercase py-4 scholl">Dil Ekle</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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
                                <label>Bildiği Dil</label>
                                {!! Form::select('language',config('variables.employees.languages'),null,['class'=>'form-control selectpicker','id'=>'language','data-live-search'=>'true']) !!}
                            </div>
                        </div>
                        <input type="hidden" name="" id="program_id" value="{{$id}}">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Düzey</label>
                                {!! Form::select('level',config('variables.employees.level'),null,['class'=>'form-control selectpicker','id'=>'level' , 'data-live-search'=>'true']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                    <button type="button" id="discon_create" class="btn btn-primary font-weight-bold">Kaydet</button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>


    @for($i = 0; $i <= $employees_count;$i++)

        @php
            if (isset($languages_employee[$i]))
            {
                 $keys = array_keys($languages_employee[$i]); $key = $keys[0];
            }

        @endphp

        @if(isset($key) and isset($languages_employee[$i]))
            <div class="modal fade" id="DiscontModal{{isset($key) ? $key : '5000000' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Çalışan Dil Bilgisi</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-2">
                                <!--begin::Table-->
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">

                                    @foreach($languages_employee[$i][$key] as  $language)
                                        <!--begin::Item-->


                                            <tr>
                                                <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40 symbol-light-success">
																		<span class="symbol-label">
																			<span class="svg-icon svg-icon-lg svg-icon-success">
																				<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Cart3.svg-->
																				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																						<rect x="0" y="0" width="24" height="24"></rect>
																						<path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
																						<path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000"></path>
																					</g>
																				</svg>
                                                                                <!--end::Svg Icon-->
																			</span>
																		</span>
                                                    </div>
                                                    <!--end::Symbol-->
                                                </td>
                                                <td class="font-size-lg font-weight-bolder text-dark-75 align-middle w-150px pb-6">{{config('variables.employees.languages')[$language['language']]}}</td>
                                                <td class="font-weight-bold text-muted text-right btn-light-warning align-middle pb-6">{{config('variables.employees.level')[$language['level']]}}</td>
                                            </tr>
                                            <!--end::Item-->
                                            @endforeach
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
    @endfor

    <!--end::Entry-->
@stop

@section('js')
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
        $(".scholl").on('click',function ()
        {
            var employee_name =  $(this).data('name');
            $("#employee").val(employee_name);
            var employee_id = $(this).data('id');


            $("#discon_create").click(function ()
            {

                var language =  $("#language").val();
                var level =  $("#level").val();
                $('#schollModal').modal('hide');
                $.ajax({
                    type: "POST",
                    url: '/ajax-employee-language-create',
                    data: {
                        _token: "{{csrf_token()}}",
                        employee_id:employee_id,
                        language:language,
                        level:level,
                        program_id:{{$id}}
                    },
                    success: function (data) {
                        Swal.fire({
                            position: "top-right",
                            icon: "success",
                            title: 'Kayıt Başarılı Sayfa Yenilenince Veriler Değişecek',
                            showConfirmButton: false,
                            timer: 3500
                        });
                        window.setTimeout(function(){
                            window.location.reload();
                        }, 2000);

                    },

                    error: function (error) {
                        alert(error);
                    }
                });
            });
        });
    </script>


@endsection

