@extends('layouts.app')
@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('kvkk'))
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    @endif


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
                    <div class="box box-danger">
                        <div class="box-header with-border" style="margin-top: 15px;">
                            <div style="margin: 10px; background-color: #ffffff">
                                @if(Session::has('error'))
                                    <div class=" alert alert-danger">
                                        {{session('error')}}
                                    </div>
                                @elseif(Session::has('success'))
                                    <div class=" alert alert-success">
                                        {{session('success')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
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

                    <a href="{{ route('request.controller') }}" class="btn btn-primary font-weight-bold btn-sm create-button-area">
    <span class="svg-icon svg-icon-light svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span> Talep/Şikayet Oluştur
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
                                    <span class="d-block text-dark font-weight-bolder">Kvkk Talep ve Şikayetler</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                        <div class="row">

                            <div class="card-body">
                                <table class="table table-bordered" id="kt_datatable">
                                    <thead>
                                    <tr>
                                        <th>Adı Soyadı</th>
                                        <th>Firma ile İlişkisi</th>
                                        <th>Telefon</th>
                                        <th>Adres</th>
                                        <th>Başvuru Formu</th>
                                        @if(Auth::user()->hasAnyRole('Admin','kvkk'))
                                            <th>İşlemler</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>{{$request->name}}</td>
                                            <td>{{$request->company_contact_type}}</td>
                                            <td>{{$request->phone}}</td>
                                            <td>{{$request->address}}</td>
                                            <td>
                                                    <a href="/{{$request->file}}" download="talep_şikayet_formu.docx"><button class="btn btn-outline-primary btn-icon-left m-b-10">  <i class="fa fa-file-word-o"></i><span>Başvuruyu İndir</span></button></a>
                                            </td>
                                            <td>
                                                @if(Auth::user()->hasAnyRole('kvkk') and $request->status == 0 )
                                                   <button data-id="{{$request->name}}" aria-label="" class="btn btn-info btn-icon-left m-b-10 m-l-5 kisi_name " data-toggle="modal" data-target="#addNewAppModal2" type="button" ><i class="pg-icon"></i><span class="">Cevap Ver</span></button><br>
                                                @endif
                                                <div>
                                                    @if($request->status==1)
                                                        <div>
                                                            <button class="btn btn-success companyRequest">Cevap Verilmiştir</button>
                                                            <a href="/{{$request->company_request_file}}" download=""><button data-id="{{$request->company_request}}" class="btn btn-outline-primary btn-icon-left m-b-10">Cevabı İndir</button></a>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <div>
                                                                <label for="">Cevap Verilmemiştir</label>
                                                                <div >
                                                                    <h1 id="counter{{$request->id}}" class="" style="font-size: 20px"></h1>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <?php
                                                                $nowDate  = $request->date;
                                                                $newDate = strtotime('+31 day',strtotime($nowDate));
                                                                $newDate = date('Y-m-d' ,$newDate );
                                                                ?>
                                                                <script>
                                                                    var countDownDate = new Date("{{$newDate}}").getTime();
                                                                    // Update the count down every 1 second
                                                                    var x = setInterval(function() {
                                                                        var now = new Date().getTime();
                                                                        // Find the distance between now an the count down date
                                                                        var distance = countDownDate - now;
                                                                        // Time calculations for days, hours, minutes and seconds
                                                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                                        // Output the result in an element with id="counter"11
                                                                        document.getElementById("counter{{$request->id}}").innerHTML = days + " Gün " + hours + ":" +
                                                                            minutes + ":" + seconds ;
                                                                        // If the count down is over, write some text
                                                                        if (distance < 0) {
                                                                            clearInterval(x);
                                                                            document.getElementById("counter{{$request->id}}").innerHTML = "EXPIRED";
                                                                        }
                                                                    }, 1000);
                                                                </script>

                                                            </div>
                                                        </div>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>


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

    <div class="modal fade stick-up" id="addNewAppModal2" tabindex="-1" role="dialog" aria-labelledby="addNewAppModal2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clearfix ">
                    <button aria-label="" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-icon">X</i>
                    </button>
                    <h4 class="p-b-5"><span class="semi-bold">Cevap Yazın </span></h4>
                </div>
                <form  action="{{route('request.return')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p class="small-text">Cevap Hazırlayın</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <input class="form-control" id="name" value="" readonly type="text">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>Açıklama</label>
                                    <input  type="text" class="form-control" id="notification"  name="notification">
                                </div>
                            </div>
                        </div>
                        @if(isset($request))
                            <input type="hidden" name="id" value="{{$request->id}}">
                            <input type="hidden" name="company_id" value="{{$request->company_id}}">
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button aria-label="" id="add-app" type="submit" class="btn btn-primary  btn-cons">Ekle</button>
                        <button aria-label="" type="button" class="btn btn-cons" data-dismiss="modal">Vazgeç</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
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
        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>
    <script>

        $('.kisi_name').click(function () {

            var id = $(this).data('id');
            $("#name").val(id);
            console.log(id);
        });

    </script>


@endpush

