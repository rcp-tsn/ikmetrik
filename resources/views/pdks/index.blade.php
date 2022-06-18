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
                                    <span class="d-block text-dark font-weight-bolder">PUANTAJLARIM </span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            @php $sira = 1; @endphp
                            <table class="table table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tarih</th>
                                    <th>Dönem</th>
                                    @if(Auth::user()->hasAnyRole('Admin','e-bordro'))
                                        <th>Çalışan Sayısı</th>
                                        <th>Gönder</th>
                                        <th>İşlemler</th>
                                    @endif
                                    <th>Puantajlar</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $sira = 1; @endphp
                                @foreach($pdks as $pdk)
                                    <tr>
                                        <td>{{$sira ++}}</td>
                                        <td>{{!empty($pdk->date) ? $pdk->date->format('d/m/Y') : null}}</td>
                                        <td>{{isset($period[$pdk->id]) ? $period[$pdk->id] : ' '}}</td>

                                        @if(Auth::user()->hasAnyRole('Admin','e-bordro'))
                                            <td>{{!empty($pdk->employee_count) ? $pdk->employee_count : null}}</td>
                                            <td>
                                                @if($pdk->sms_status==1)
                                                    <span class="label label-lg label-light-success label-inline font-weight-bold py-4 ">SMS GÖNDERİLDİ</span>
                                                @else
                                                    <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">GÖNDERİM BEKLİYOR</span>
                                                    <br>
                                                    <a style="margin-top: 10px" title="TÜM PERSONELLERE SMS GÖNDER"  data-id="{{$pdk->id}}" class="btn btn-outline-success btn-sm mr-3 sms">
                                                        <i class="flaticon2-chat-1"></i>Gönder</a>

                                                @endif
                                            </td>
                                            <td>
                                                @if($pdk->sms_status == 0)

                                                    <form  id="pdkdelete{{ $pdk->id }}" action="{{route('pdk.delete',['id' => createHashId($pdk->id)])}}" method="POST">
                                                        @csrf
                                                        <a href="#" type="button" data-id="{{$pdk->id}}" class="btn btn-outline-danger btn-sm mr-3 pdkDelete">
                                                            <i class="flaticon2-trash"></i>Sil</a>
                                                    </form>
                                                @else
                                                    <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Gönderilen Puantajlar Silinemez</span>

                                                @endif
                                            </td>
                                        @endif
                                        <td>

                                            <div class="btn-group">
                                                <a href="{{route('employee_pdk_show',['id'=>createHashId($pdk->id)])}}" class="btn btn-success btn-sm mr-3">
                                                    <i class="flaticon2-pie-chart"></i>Puantajları Gör</a>
                                                @if(Auth::user()->hasAnyRole('Admin','e-bordro'))
                                                    <a href="{{route('pdks_not',createHashId($pdk->id))}}" class="btn btn-warning btn-sm mr-3">
                                                        <i class="flaticon2-pie-chart"></i>Eşleşmeyenler</a>

                                            </div>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                            <div class="modal fade" id="LoadingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">PUANTAJLAR GÖNDERİLİYOR</h5>
                                        </div>
                                        <div class="modal-body">
                                            <h3>Lütfen Bekleyiniz</h3>
                                            <div class="loader-container">
                                                <div class="loader">

                                                    <div class="square one"></div>
                                                    <div class="square two"></div>
                                                </div>
                                            </div>
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


    <script>
        $('.sms').click(function () {
            var id = $(this).data('id');
            swal({
                title: "Eminmisiniz?",
                text: "Bu İşlem Geri alınamaz Bordrolar Kontrol Edildikten Sonra Yapınız!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $('#LoadingModal').modal('show');
                        window.location.href = '{{env('APP_URL')}}/employee/pdks/sms/'+id;
                    } else {
                        swal("Silme İşlemi İptal Edildi");
                        $(".page").show();
                    }
                });
        });

    </script>

    <script>

        $('.pdkDelete').click(function () {
            var id = $(this).data('id');
            console.log(id);
            swal({
                title: "Eminmisiniz?",
                text: "Bu İşlem Geri Alınamaz Silme İşlemi İçin Onaylayın!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $("#pdkdelete"+id).submit();
                        swal("Silme İşlemi Başarılı", {
                            icon: "success",
                        });
                    } else {
                        swal("Gönderim İşlemi İptal Edildi");
                    }
                });
        });

    </script>
@endpush

