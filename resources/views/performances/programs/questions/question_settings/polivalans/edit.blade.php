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
                                SORULARI DÜZENLE
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            @include('partials.alerts.error')
                            @include('performances.programs.questions.question_settings.polivalans.form')
                        </div>
                        <div class="card-footer">
                            <button type="button" id="QuestionsEdit" class="btn btn-primary mr-2 QuestionsEdit " >KAYDET</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
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
        $(document).ready(function ()
        {
            $('.QuestionsEdit').click(function() {  //on click
                var sorular = [];
                $("input[name*='questions']").each(function (index) {
                    var input = 0;
                    input = $(this).val();
                    sorular[index] =  [input]
                });
                var id = $("#id").val();
                var department = $(".department").val();
                var name = $("#SoruName").val();
                var question_type = $("#question_type").val();
                var sgk_company = $("#sgk_company").val();
                $.ajax({
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '/company_polivalans_question_update',
                    data: {
                        id:id,
                        name:name,
                        question_type:question_type,
                        sorular:sorular,
                        department:department,
                        sgk_company:sgk_company
                    },
                    success: function (alert) {
                        $('#exampleModal').modal('toggle');
                        Swal.fire({
                            position: "top-right",
                            icon: alert['type'],
                            title: alert['message'],
                            showConfirmButton: false,
                            timer: 3500
                        });
                        if (alert['type']=='success')
                        {
                            window.location.href = '{{route('company_questions_index')}}';
                        }

                    },
                });
            });

        });

    </script>
@endpush




