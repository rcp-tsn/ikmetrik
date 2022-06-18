<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <h4 class="mb-10  text-dark up-font">{{$control->first_name}} {{$control->last_name}} Çalışanı İçin Ast Bilgileri Girilecek</h4>

        <div class="card card-custom card-transparent">
            <div class="card-body p-0">
                    <div class="card card-custom card-shadowless rounded-top-0">
                        <div class="card-body p-0">
                            <div class="justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                <div class="">
                                    <div class="pb-5" data-wizard-type="step-content">
                                        <!--begin::Section-->
                                        <h4 class="mb-10 text-dark up-font" >Personele Bağlı Kişileri Seçiniz.</h4>
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Section-->
                                        <!--begin::Section-->
                                        <h6 class="font-weight-bolder mb-3">Ast Personelleri Seçiniz:</h6>
                                        <div class="card-body">
                                            <table class="table table-bordered" id="employees_filter">
                                                <thead>
                                                <tr>
                                                    <th class="up-font">#</th>
                                                    <th class="up-font">Avatar</th>
                                                    <th class="up-font">İsim Soyisim</th>
                                                    <th class="up-font">Üst Yönetici</th>
                                                    <th class="up-font">Department</th>
                                                    <th class="up-font">Şube</th>

                                                </tr>

                                                </thead>
                                                <tbody>
                                                @foreach($employees as $employee)

                                                    <tr>
                                                        <td style="text-align: -webkit-center">
                                                            @if(!empty($employee->top_manager_id) and $employee->top_manager_id != $id )
                                                                <button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="top" data-content="{{$employee->full_name}} {{$employee->employee_ust($employee->id,true)}} Bağlı Gözükmektedir Yeni Kişiye Bağlanması İçin Öncelikle {{$employee->employee_ust($employee->id,true)}} Ast Personel Bölümünden  Çıkarılması Gerekmektedir ">
                                                                    <i class="icon-xl la la-info-circle"></i>
                                                                </button>

                                                            @else
                                                            <label class="checkbox checkbox-rounded ">
                                                                <input type="checkbox" <?php if (in_array($employee->id,$selectedEmployeeAst)) { echo "checked"; } ?> name="status[{{$employee->id}}]">
                                                                <span></span></label>
                                                        @endif
                                                        </td>
                                                        <td>
                                                            <div class="symbol symbol-50 symbol-light mt-1">
                                                                <img src="/{{ $employee->avatar }}" class="h-75 align-self-end" alt="">
                                                            </div>
                                                        </td>
                                                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                                        <td><div class="symbol symbol-50 symbol-light mt-1">
                                                                <img src="/{{ $employee->employee_ust($employee->id) }}" title="{{$employee->employee_ust($employee->id,true)}}" class="h-75 align-self-end" alt="">
                                                            </div></td>
                                                        <td>{{ $employee->department->name }}</td>
                                                        <td>{{ $employee->sgk_company->name}}</td>
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Section-->
                                        <!--begin::Section-->
                                        <!--end::Section-->
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
                var table = $('#employees_filter');

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


