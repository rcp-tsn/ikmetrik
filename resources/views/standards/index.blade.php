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
                        <div class="card-heade h-auto border-0">
                            <div class="card-title py-5">
                                @include('partials.alerts.error')
                            </div>

                        </div>
                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            @include('partials.datatable_top')
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

@include('partials.datatable_bottom')



@push('scripts')
    <script>
        'use strict';
        $(document).ready(function () {

            $(document).on('click', 'a.page-tour', function () {
                var enjoyhint_instance = new EnjoyHint({});

                enjoyhint_instance.set([
                    {
                        'next .create-button-area': 'Sisteme yeni kayıt eklemek için tıklayınız. ',
                    },{
                        'next .edit-button-area': 'İlgili kaydı güncelleştirmek için bu butonu kullanabilirsiniz.',
                    },{
                        'next .show-button-area': 'Şubeye ait metrik sabitleri için bu bölümü kullanabilirsiniz.',
                    }
                ]);
                enjoyhint_instance.run();
            });

        });
    </script>

@endpush
