@extends('layouts.app')
@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('kvkk'))
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    @endif
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
                                    <span class="d-block text-dark font-weight-bolder">Kvkk Talep / Şikayet Oluştur</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="col-md-12">

                        <div class="col-lg-6">


                            <form class="" action="{{route('request.controll')}}" method="POST" role="form">
                                @csrf
                                <div class="form-group form-group-default required ">
                                    <label>Adı Soyadı</label>
                                    <input type="text" class="form-control" name="name" required="">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default required">
                                            <label>Tc Kimlik No </label>
                                            <input type="text" minlength="10"  class="form-control" maxlength="11" name="no" required="">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group  form-group-default required">
                                    <label>E-Posta</label>
                                    <input type="email" class="form-control" placeholder="ex: some@example.com" required="">
                                </div>

                                <div class="col-md-12 ">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Gönder</button>
                                    </div>
                                </div>
                            </form>

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


@endpush

