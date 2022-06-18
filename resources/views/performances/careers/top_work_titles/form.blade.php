<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <h4 class="mb-10  text-dark up-font">{{$work_title->name}} Ünvanı İçin Üst Bilgileri Girilecek</h4>

        <div class="card card-custom card-transparent">
            <div class="card-body p-0">
                    <div class="card card-custom card-shadowless rounded-top-0">
                        <div class="card-body p-0">
                            <div class="justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                <div class="">
                                    <div class="pb-5" data-wizard-type="step-content">
                                        <!--begin::Section-->
                                        <h4 class="mb-10 text-dark up-font" >Personel Üst Alt Kişilerini Giriniz.</h4>
                                        <h6 class="font-weight-bolder mb-3">Üst Ünvan Seçiniz:</h6>
                                        <div class="form-group">
                                            <label class="up-font" >Üst Ünvan Seçiniz</label>
                                            {!! Form::select('top_work_title', $work_titles,null, ['class'=>'form-control selectpicker','data-live-search'=>'true'] )  !!}

                                        </div>
                                        <div class="separator separator-dashed my-5"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end: Wizard Bpdy-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>


@section('js')
    <script>

        $("#phone_work").mask("(999) 999-9999");
        $("#ephone").mask("(999) 999-9999");
        $('#birth_date').mask('99/99/9999',{placeholder:"mm/dd/yyyy"});

    </script>
    <script>
        "use strict";
        var KTDatatablesAdvancedColumnRendering = function() {

            var init = function() {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    paging: false
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

        $( "#btn-filterr" ).click(function() {

            var  sgk_company_id =   $('select[name=sgk_company_filter] option').filter(':selected').val()
            var department =  $('select[name=department_filter] option').filter(':selected').val()



            var sayi = $(".type_applicant").length;

            if(sayi > 0 )
            {
                $(".type_applicant").append().remove();
            }

            $.ajax({
                type: "GET",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/employee-sgkCompany-department-filterr/'+ sgk_company_id + '/' +department,
                success: function (datas) {
                    var items = '';
                    $.each(datas, function (i, item) {
                        $('#employees_filter tbody').append(item);
                    });
                },
            });
        });

    </script>
@endsection


