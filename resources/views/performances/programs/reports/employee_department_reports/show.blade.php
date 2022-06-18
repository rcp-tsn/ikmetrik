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
                                    <span class="d-block text-dark font-weight-bolder">Katıldığınız Performanslar</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">

                            <table class="table table-bordered" id="kt_datatable">

                                <thead>
                                <tr>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Adı Soyadı</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Aldığı Toplam Puan</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Puan Karşılığı</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td @if($employee->id == $employee_id) style="font-weight: bold;font-size: 15px;background-color: red;color: white" @else style="font-weight: bold;font-size: 15px" @endif>{{$employee->id == $employee_id ? $employee->full_name : '***************'}} </td>
                                        <td @if($employee->id == $employee_id) style="font-weight: bold;font-size: 15px;background-color: red;color: white" @else style="font-weight: bold;font-size: 15px" @endif>{{ number_format(array_sum($employee_toplam_puan[$employee->id]),2,',','.') }}</td>
                                        <td  @if($employee->id == $employee_id) style="font-weight: bold;font-size: 15px;background-color: red;color: white" @else style="font-weight: bold;font-size: 15px" @endif>{{$employee->id == $employee_id ? $sonuc : '****************' }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="font-weight: bold;font-size: 15px">Ortalama Puan : {{isset($ortalama) ? number_format($ortalama,2,',','.') : 0}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <table class="table mt-10" style="border: 1px;width: 30%;" >
                                <thead>

                                </thead>
                                <tbody>
                                <tr>
                                    <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px">Puan Aralığı</td>
                                    <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px">Tanım</td>
                                    <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px"></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;font-weight: bold">0-50 arası</td>
                                    <td  style="font-size: 15px;font-weight: bold">Beklentinin Altında</td>
                                    <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: red;text-align: center;"></div></td>
                                </tr>
                                <tr>
                                    <td  style="font-size: 15px;font-weight: bold">50-69</td>
                                    <td  style="font-size: 15px;font-weight: bold">Beklenen Seviyeye Yakın</td>
                                    <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: yellow;text-align: center;"></div></td>
                                </tr>
                                <tr>
                                    <td  style="font-size: 15px;font-weight: bold">69-80 arasında</td>
                                    <td  style="font-size: 15px;font-weight: bold">Beklenen seviyede</td>
                                    <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: blue;text-align: center;"></div></td>
                                </tr>
                                <tr>
                                    <td  style="font-size: 15px;font-weight: bold">80-100 arasında</td>
                                    <td  style="font-size: 15px;font-weight: bold">Beklenen seviyenin üstünde</td>
                                    <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: #90ff90;text-align: center;"></div></td>
                                </tr>
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

@endpush

