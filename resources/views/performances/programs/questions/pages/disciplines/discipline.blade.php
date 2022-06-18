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

                       <div class="form-group" style="text-align: -webkit-right;margin-right: 20px;">
                           <a href="{{ URL::previous() }}">
                               <div data-repeater-create=""  class="btn font-weight-bold btn-danger">
                                   <i class="la la-backspace"></i>Geri</div>
                           </a>
                           <a href="{{ route('question_evalation', [ 'type' => 'discipline_create' , 'id' => createHashId($id),'page'=> isset($page) ? $page : 1 ]) }}">
                           <div data-repeater-create=""  class="btn font-weight-bold btn-warning">
                               <i class="la la-plus"></i>Disiplin Suçu Ekle</div>
                           </a>


                               <div  data-toggle="modal" data-target="#disiplinModal"  class="btn font-weight-bold btn-success">
                                   <i class="la la-plus"></i>Excel İle Yükleme</div>

                       </div>
                    @include('partials.alerts.error')
                        <!--end:: Card header-->
                         <div class="card-body">

                                    <table class="table table-bordered" id="kt_datatable">

                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Çalışan Adı</th>
                                            <th>Tarih</th>
                                            <th>Evrak</th>
                                            <th>Durum</th>
                                        </tr>

                                        </thead>
                                        <tbody>
                                        @foreach($disciplines as $discipline)
                                            <tr>
                                                <td> <div class="symbol symbol-50 symbol-light mt-1">
                                                    <img src="/{{ $discipline->employee->avatar }}" class="h-75 align-self-end" alt="">
                                                </div></td>
                                                <td>{{$discipline->employee->first_name}} {{$discipline->employee->first_name}}</td>
                                                <td>{{ $discipline->discipline_date->format('d/m/Y') }} </td>
                                                <td><a href="/{{$discipline->file}}"><button class="btn btn-success">Tutanak İndir</button></a></td>
                                                <td style="text-align:center">


                                                    <a href="{{route('discipline_delete',['id'=>createHashId($discipline->id)])}}" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area" title="Sil"><span class="svg-icon svg-icon- svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\General\Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
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



@include('performances.programs.questions.pages.disciplines.insertModal')
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
        $(".aktif").click(function ()
        {
           var page = $(this).html();
        });
    </script>

    <script>
        $(".excelUpload").click(function ()
        {
            $("#disiplinModal").modal('hide');
            $("#LoadingModal").modal('show');
            $("#excelUpload").submit();
        })
    </script>

@endpush



