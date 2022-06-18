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
                                    <span class="d-block text-dark font-weight-bolder">Kvkk Eğitimleri</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                        <div class="row">
                            @foreach($educations as $education)
                            <div class="card" style="width: 35rem;">
                                <video controls="" src="{{$education->file}}"
                                       class="bs-card-video"></video>
                                <div class="card-body">
                                    <h5 class="card-title">{{$education->name}}</h5>
                                    <p class="card-text">
                                        {{$education->notification}}
                                    </p>
                                </div>
                            </div>
                            @endforeach
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

