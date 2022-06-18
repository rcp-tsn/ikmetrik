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
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <h3 class="card-title">
                                İZİN DETAY
                            </h3>
                            <div class="card-toolbar"> <span class="display-4 font-weight-boldest mb-8">
                <br/>
                     <a href="{{route('leaves_status')}}" class="btn btn-danger font-weight-bold  py-4"> GERİ DÖN</a>
		</span></div>
                        </div>
                        <style>
                            .font_up
                            {
                                font-size: 15px;
                                font-weight: bold;
                            }
                        </style>
                        <!--end:: Card header-->

                        <!--begin::Card body-->


                        <div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample6">
                            <div class="card">
                                <div class="card-header" id="headingThree6">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree6">
                                        <i class="flaticon2-chart"></i>İzin Dosyaları</div>
                                    </div>
                                <div id="collapseThree6" class="collapse" data-parent="#accordionExample6">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table-bordered table">
                                                <thead>
                                                <tr>
                                                    <th>Dosya Adı</th>
                                                    <th>Dosya İndir/Görüntüle</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($leaveFiles as $file)
                                                <tr>
                                                    <td>{{$file->name}}</td>
                                                    <td>

                                                        <a href="/{{$file->file}}"><button type="button" class="btn btn-primary  btn-icon"><i class="la la-file-text-o"></i></button></a>


                                                    </td>
                                                </tr>
                                                @endforeach
                                                </tbody>


                                            </table>
                                        </div>


                                  </div>
                                </div>
                            </div>

                        </div>
                        <div class="container" style="margin-top: 50px; margin: 10px;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th style="text-align: right">
                                        <div class="btn btn-group">
                                            <a href="{{route('LeaveFormToPdf',createHashId($employeeLeave->id))}}" target="_blank" download="" class="mr-5 ml-5"><button class="btn btn-warning">Formu İndir</button></a>
                                            <button class="btn btn-success" data-toggle="modal" data-target="#leaveModal">Formu Görüntüle</button>
                                        </div>
                                       </th>
                                </tr>
                                <tr>
                                    <th></th>

                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <div class="form-group">
                                    <tr>

                                        <td><label class="font_up" for=""> Adı Soyadı</label></td>
                                        <td><label class="font_up">{{$employeeLeave->employee->first_name}} {{$employeeLeave->employee->last_name}}</label></td>
                                    </tr>
                                </div>

                                <div class="form-group">
                                    <tr>
                                        <td><label class="font_up" for=""> İzin Tipi</label></td>
                                        <td><label class="font_up">{{ config('variables.employees.leave_type')[$employeeLeave->leave_type] }}</label></td>
                                    </tr>

                                </div>

                                <div class="form-group">
                                    <tr>
                                        <td><label class="font_up" for="">İzin Başlangıç Tarihi</label></td>
                                        <td><label class="font_up"> {{ ezey_get_dateformat($employeeLeave->start_date,'toOur')}}</label></td>
                                    </tr>

                                </div>



                                <div class="form-group">
                                    <tr>
                                        <td><label class="font_up" for="">İzin Bitiş Tarihi</label></td>
                                        <td><label class="font_up">{{ ezey_get_dateformat($employeeLeave->job_start_date,'toOur')}}</label></td>
                                    </tr>

                                </div>
                                <div class="form-group">
                                    <tr>
                                        <td><label class="font_up" for="">Ulaşılacak Telefon</label></td>
                                        <td><label class="font_up">{{ !empty($employeeLeave->employee->mobile) ? $employeeLeave->employee->mobile : $employeeLeave->employee->mobile  }}</label></td>
                                    </tr>

                                </div>

                                <div class="form-group">
                                    <tr>
                                        <td><label class="font_up" for="">Açıklama</label></td>
                                        <td><label class="form-control font_up" >{{ $employeeLeave->description }}</td>
                                    </tr>
                                </div>

                                <div class="form-group">
                                    <tr>
                                        <td><label class="font_up" for="">İzin Talep Dosyaları</label></td>
                                        <td>

                                        </td>
                                    </tr>
                                </div>
                                </tbody>
                            </table>
                     </div>





                        <div class="card-body">
                            <style>
                                .step > .content {
                                    position: absolute;
                                    bottom: 40px;
                                    left: 10px;
                                    padding-left: 30px;
                                    line-height: 16px;
                                    padding-bottom: 20px;
                                    width: 100%;
                                }


                                .wzrd {
                                    margin: 105px 0px 141px 0 !important;
                                }


                                .step > .content:after {
                                    position: absolute;
                                    left: 0px;
                                    content: '';
                                    border-left: 4px dotted #a5aeb85c;
                                    height: 80%;
                                    bottom: 20%;
                                }

                                .step > .content:before {
                                    position: absolute;
                                    left: -3px;
                                    content: '';
                                    height: 0px;
                                    bottom: 100%;
                                    border: 5px solid #a5aeb8cc;
                                    border-radius: 100%;
                                }



                                .wzrd > .step:not(:first-child) > a:before {
                                    width: 0px;
                                    height: 0px;
                                    border-top: 20px inset transparent;
                                    border-bottom: 20px inset transparent;
                                    border-left: 20px solid #ffffff;
                                    position: absolute;
                                    content: "";
                                    top: 0;
                                    left: 0;
                                    height: 48px !important;
                                }

                                .step {
                                    position: relative;
                                }

                                .content > .number {
                                    font-size: 27px;
                                    color: #e8164c;
                                    font-weight: bold;
                                    position: absolute;
                                    top: -35px;
                                    left: -8px;
                                }

                                .orange > .content > .number {
                                    color: #fc9700;
                                }

                                .blue > .content > .number {
                                    color: #0080cc;
                                }

                                .green > .content > .number {
                                    color: #00a789;
                                }

                                .step > .content.bottom {
                                    bottom: auto;
                                    top: 40px;
                                    padding-bottom: 0px;
                                    padding-top: 20px;
                                }

                                .step > .content.bottom:after {
                                    left: 0px;
                                    border-left: 4px dotted #a5aeb85c;
                                    height: 80%;
                                    top: 20%;
                                    bottom: auto;
                                }

                                .step > .content.bottom:before {
                                    height: 0px;
                                    bottom: 10%;
                                    top: 100%;
                                }

                                .content.bottom > .number {
                                    top: auto;
                                    bottom: -35px;
                                }


                                .wzrd > .step {
                                    line-height: 48px;
                                    cursor: default;
                                }

                                .wzrd > .step a {
                                    color: white;
                                    font-weight: bold;
                                }

                                .wzrd > .step:not(:first-child) > a {
                                    padding-left: 34px;
                                }

                                .wzrd > .step:not(:first-child) > a:before {
                                    width: 0px;
                                    height: 0px;
                                    border-top: 20px inset transparent;
                                    border-bottom: 20px inset transparent;
                                    border-left: 20px solid #ffffff;
                                    position: absolute;
                                    content: "";
                                    top: 0;
                                    left: 0;
                                }

                                .wzrd > .step:not(:last-child) > a {
                                    margin-right: 6px;
                                    cursor: default;
                                }

                                .wzrd > .step:not(:last-child) > a:after {
                                    width: 0px;
                                    height: 0px;
                                    border-top: 20px inset transparent;
                                    border-bottom: 20px inset transparent;
                                    border-left: 20px solid #eeeeee;
                                    position: absolute;
                                    content: "";
                                    top: 0;
                                    right: -20px;
                                    z-index: 2;
                                }

                                .wzrd > .step:first-child > a {
                                    border-top-left-radius: 4px;
                                    border-bottom-left-radius: 4px;
                                    cursor: default;
                                }

                                .wzrd > .step:last-child > a {
                                    border-top-right-radius: 4px;
                                    border-bottom-right-radius: 4px;
                                    cursor: default;
                                }



                                .wzrd > .step:hover > a:before {
                                    border-right-color: #d5d5d5;
                                }

                                .wzrd > .step:hover > a:after {
                                    border-left-color: #d5d5d5;
                                }

                                .red {
                                    background: #e8164c;
                                }

                                .wzrd > .red a:after {
                                    border-left: 20px solid #e8164c !important;
                                }

                                .orange {
                                    background: #fc9700;
                                }

                                .wzrd > .orange a:after {
                                    border-left: 20px solid #fc9700 !important;
                                }


                                .blue {
                                    background: #0080cc;
                                }

                                .wzrd > .blue a:after {
                                    border-left: 20px solid #0080cc !important;
                                }


                                .green {
                                    background: #00a789;
                                }

                                .wzrd > .green a:after {
                                    border-left: 20px solid #00a789 !important;
                                }


                                .wzrd .disabled .content {
                                    opacity: 0.4 !important;
                                }

                                .wzrd .blue {
                                    background: #0080cc;
                                }

                                .wzrd > .blue a:after {
                                    border-left: 20px solid #0080cc !important;
                                }

                                .wzrd > .blue.disabled a:after {
                                    border-left: 20px solid rgba(0, 128, 204, 0.5) !important;
                                }

                                .wzrd > .blue.disabled {
                                    background: rgba(0, 128, 204, 0.5);
                                }

                                .wzrd > .red.disabled a:after {
                                    border-left: 20px solid rgba(232, 22, 76, 0.5) !important;
                                }

                                .wzrd > .red.disabled {
                                    background: rgba(232, 22, 76, 0.5);
                                }


                                .wzrd > .green.disabled a:after {
                                    border-left: 20px solid rgba(0, 167, 137, 0.5) !important;
                                }

                                .wzrd > .green.disabled {
                                    background: rgba(0, 167, 137, 0.5);
                                }

                                .wzrd > .orange.disabled a:after {
                                    border-left: 20px solid rgba(252, 151, 0, 0.5) !important;
                                }

                                .wzrd > .orange.disabled {
                                    background: rgba(252, 151, 0, 0.5);
                                }

                                .wzrd > .blue.disabled a:after {
                                    border-left: 20px solid rgba(0, 128, 204, 0.5) !important;
                                }

                                .wzrd > .blue.disabled {
                                    background: rgba(0, 128, 204, 0.5);
                                }

                                .reddedildi {
                                    color: red;
                                }

                                .wzrd > .step:not(:last-child) > a:after {
                                    width: 0px;
                                    height: 0px;
                                    border-top: 20px inset transparent;
                                    border-bottom: 20px inset transparent;
                                    border-left: 20px solid #eeeeee;
                                    position: absolute;
                                    content: "";
                                    top: 0;
                                    right: -20px;
                                    z-index: 2;
                                    opacity: 0.4;
                                    height: 48px !important;
                                    opacity: 3.4 !important;
                                }



                                @keyframes hello {
                                    0% {
                                        font-weight: 100;
                                    }
                                    50% {
                                        font-weight: 900;
                                    }
                                    100% {
                                        font-weight: 100;
                                    }
                                }




                                /*@media screen and (max-width: 1355px) {
                            #ccc {
                            display:none !important;
                            }
                            }*/

                                .content2
                                {

                                    height: 48px;
                                }



                                @media screen and (max-width: 700px) {
                                    .content2
                                    {
                                        margin-bottom: 30px;
                                        height: 150px;
                                    }
                                    #ccc
                                    {
                                        color: white!important;
                                    }
                                    .number
                                    {
                                        display: none!important;
                                    }
                                }


                            </style>
                            <div class="form-group row  wzrd">
                                <div class=" content2 col-sm-3 step  red">
                                    <a>
                                        {{$employeeLeave->employee->first_name}} {{$employeeLeave->employee->last_name}} {{ config('veriables.employees.leave_type')[$employeeLeave->leave_type] }} İzin Talebi Yapıldı

                                        <i class="fa fa-check hide"></i>
                                    </a>
                                    <div class="content top">
                                        <div class="number">1.<span>Adım</span></div>
                                        <div id="ccc">
                                            <div>
                                                <strong>İşlem : </strong>
                                                {{ date('d/m/Y', strtotime($employeeLeave->start_date))}} -
                                                {{ date('d/m/Y', strtotime($employeeLeave->job_finish_date))}}
                                                {!! config('variables.employees.leave_type')[$employeeLeave->leave_type] !!} Talep
                                            </div>
                                            <div><strong>İşlem Yapan : </strong> {{ $employeeLeave->user->name }}</div>
                                            <div><strong>İşlem Tarihi : </strong> {{date('d/m/Y', strtotime($employeeLeave->created_at)) }}</div>
                                        </div>
                                    </div>
                                </div>
                                @php $css = ['0'=>'orange','1'=>'green','2'=>'blue'];
                                $css2 = ['0'=>'bottom','1'=>'top','2'=>'bottom']
                                @endphp
                                @for($i = 0; $i<=2; $i++)

                                    <div class=" content2 col-sm-3 step {{$css[$i]}}  ">
                                        <a>
                                            {{$i+1 == 3  ? 'İnsan Kaynakları' : ''}} Yönetici  İşlemi
                                            <i class="fa fa-check hide"></i>
                                        </a>
                                        <div class="content {{$css2[$i]}}">
                                            <div class="number" id="ikirenk">{{$i+2}}. <span>Adım</span></div>
                                            <div id="ccc">
                                                <div>
                                                    <strong>İşlem : </strong>

                                                    @if(isset($leaves[$i]))
                                                        @if($leaves[$i]->type_authority == $i+1)
                                                            @if($leaves[$i]->status == 1)
                                                                Onay Bekleniyor
                                                            @elseif($leaves[$i]->status == 2)
                                                                Onaylandı
                                                            @else
                                                                Reddedildi
                                                            @endif
                                                        @endif
                                                    @else
                                                        Onay Bekleniyor
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>İşlem  Yapacak : </strong>
                                                    @if(isset($leaves[$i]))
                                                        @if($leaves[$i]->type_authority == $i+1)
                                                            {{ $leaves[$i]->user->name }}


                                                        @endif
                                                    @else
                                                        @if(isset($employeeManagers[$i+1]))
                                                            {{ $employeeManagers[$i+1]['name'] }}
                                                        @else
                                                            SİSTEM YÖNETİCİ(YÖNETİCİ YOK)
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class=""><strong>İşlem Tarihi : </strong>
                                                    @if(isset($leaves[$i]))
                                                        @if($leaves[$i]->type_authority == $i+1)
                                                            @if($leaves[$i]->status == 2)

                                                                {{ $leaves[$i]->accept_date }}
                                                            @elseif($leaves[$i]->status==1)
                                                                Bekleniyor
                                                            @else
                                                                {{ $leaves[$i]->decline_date }}
                                                            @endif
                                                        @endif
                                                    @else
                                                        İşlem Yapılmadı
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                            </div>
                        </div>
                        <div class="form-group" align="center" >

                            @if($employeeLeave->status == 2 and $employeeLeave->file_status == 0  )
                                <button type="button" class="btn bg-teal-400 btn-labeled heading-btn btn-xs btn btn-primary m-l-5" data-toggle="modal" data-target="#exampleModal">Onaylı Formu Yükle</button>
                                <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">İmzalı Formu Yükle</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form action="{{route('acceptForm',createHashId($employeeLeave->id))}}"  method="POST" enctype="multipart/form-data">

                                            <div class="modal-body">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="">Onaylı Formu Seç</label>
                                                            <br>
                                                            <input type="file" name="files[]" multiple required>
                                                        </div>
                                                    </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                                                <button type="submit" class="btn btn-primary font-weight-bold">Formu Yükle</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($employeeLeave->status == 2 and $employeeLeave->file_status == 1)
                                <div class="alert alert-success" for="">Onaylanmıştır</div>
                            @elseif($employeeLeave->status == 2 and $employeeLeave->file_status == 0)
                                <label class="alert alert-success" for="">Onaylanmıştır Fakat İmzalı Dosyanız Bekleniyor</label>
                                @elseif($employeeLeave->status == 0)
                                <div class="alert alert-danger" for="">Reddedilmiştir</div>
                                <br>
                                <label style="font-weight: bold ;font-size: 16px;">Nedeni : {{isset($employeeLeave->declineNotification) ? $employeeLeave->declineNotification : '' }} </label>
                            @else
                                <div class="alert alert-success" for="">Onay Bekleniyor</div>
                            @endif
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

@include('leaves.modals.show_form')

@stop

@push('scripts')

@endpush




