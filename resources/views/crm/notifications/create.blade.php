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
                                İLETİŞİM KUR
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::open(['route' => 'crm_notifications.store', 'files'=>'true', 'class' => 'form', 'id' => 'kt_form']) !!}
                        <div class="">
                            @include('partials.alerts.error')
                            @include('crm.notifications.form')
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">{{ __('global.buttons.SaveButtonText') }}</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
                        </div>

                    {!! Form::close() !!}
                    @include('personelFiles.InsertModal.fileType')
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

        $( "#btn-filter" ).click(function() {
            var  id =  $("#sgk_company").val();
            var department_id = $("#department").val();
            var sayi = $(".type_applicant").length;

            if(sayi > 0 )
            {
                $(".type_applicant").append().remove();
            }

            $.ajax({
                type: "GET",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/ajax/employee/filter/'+ id +'/'+department_id,
                success: function (datas) {
                    var items = '';
                    $.each(datas['data'], function (i, item) {
                        $('#applicant_employee tbody').append(item);
                    });
                },
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            $('#allSelect').click(function(event) {  //on click
                if(this.checked) { // check select status
                    $(":checkbox").attr("checked", true);
                }else{
                    $(":checkbox").attr("checked", false);
                }
            });

        });
    </script>

@endpush




