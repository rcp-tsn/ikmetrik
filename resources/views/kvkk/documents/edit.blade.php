@extends('layouts.app')
@section('content')

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
                                    <span class="d-block text-dark font-weight-bolder">Diğer Sayfalar</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::model($sgk_company, ['route' => ['documents.update', createHashId($sgk_company->id)], 'method' => 'PUT', 'class' => 'form', 'id' => 'ezy_standard_form']) !!}
                        <div class="card-body assignment-list-area">
                            <select id="duallistbox_demo1" name="documents[]" multiple="multiple">
                                @foreach($documents as $document)
                                    <option value="{{ $document->id }}" {{ in_array($document->id, $currentAssignments) ? 'selected' : '' }}>{{ $document->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="card-footer">
                            {{ Form::bsUpdate(__('global.buttons.UpdateButtonText')) }}

                            <a href="{{ URL::previous() }}" class="btn btn-light-success font-weight-bold">{{ __('global.buttons.CancelButtonText') }}</a>
                        </div>
                    {!! Form::close() !!}
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
        var demo1 = $('select[name="documents[]"]').bootstrapDualListbox();

    </script>

    <script>
        'use strict';
        $(document).ready(function () {

            $(document).on('click', 'a.page-tour', function () {
                var enjoyhint_instance = new EnjoyHint({});

                enjoyhint_instance.set([
                    {
                        'next .box1 ': 'SGK Şubelerinden ilgili kullanıcıya atanmamışlar listelenmektedir. ',
                    },
                    {
                        'next .moveall ': 'Kullanıcıya tümünü atamak için bu butona, sadece bazılarını atamak için ise Şube adına tek tıklama yeterlidir. ',
                    },
                    {
                        'next .box2 ': 'SGK Şubelerinden ilgili kullanıcıya ataması yapılmışlar listelenmektedir. ',
                    },
                    {
                        'next .removeall ': 'Kullanıcıdan tümünü kaldırmak için bu butona, sadece bazılarını kaldırmak için ise Şube adına tek tıklama yeterlidir. ',
                    },{
                        'next .update-button-area ': 'Güncelle butonuna tıklayarak yapılan değişiklikleri sisteme kaydetmiş olacaksınız. ',
                    }
                ]);
                enjoyhint_instance.run();
            });

        });
    </script>

@endpush

