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
                                    <span class="d-block text-dark font-weight-bolder">Disiplin Soruları</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <div class="form-group" style="text-align: -webkit-right;margin-right: 20px; padding-top: 20px">
                            <a href="{{ route('disciplines.index')}}">
                                <div data-repeater-create=""  class="btn font-weight-bold btn-danger">
                                    <i class="la la-backspace"></i>Geri</div>
                            </a>
                                </div>
                            </div>
                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">

                            <table class="table table-bordered" id="kt_datatable">

                                <thead>
                                <tr>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Soru</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Türü</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">İşlemler</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($questions as $question)
                                    <tr>
                                        <td style="font-weight: bold;font-size: 15px">{{$question->name}}</td>
                                        <td style="font-weight: bold;font-size: 15px">{{ $question->type }}  </td>
                                        <td nowrap="nowrap">


                                            <a href="#"  title="SİL" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area Sil" data-id="{{createHashId($question->id)}}" data-url="{{route('disciplineDelete',createHashId($question->id))}}"><span class="svg-icon svg-icon-primary svg-icon-2x "><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\General\Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span></a>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
            var id = $(this).data('id');
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

                        window.location.href=url;

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

