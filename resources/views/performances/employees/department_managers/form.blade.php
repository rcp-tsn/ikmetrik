<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <h4 class="mb-10  text-dark up-font">{!!  $department->name !!} İçin Yönetici Seçilecek</h4>

        <div class="card card-custom card-transparent">
            <div class="card-body p-0">
                <div class="card card-custom card-shadowless rounded-top-0">
                    <div class="card-body p-0">
                        <div class="justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                            <div class="">
                                <div class="pb-5" data-wizard-type="step-content">
                                    <!--begin::Section-->
                                    <h4 class="mb-10 text-dark up-font" >Departman Yönetici Seçiniz.</h4>
                                    <h6 class="font-weight-bolder mb-3">Yönetici Seçiniz:</h6>
                                    <div class="form-group">
                                        <label class="up-font" style="font-weight: bold;font-size: 15px" >Çalışanlar</label>
                                        {!! Form::select('employees', $employees, !empty($selectedDepartment) ? $selectedDepartment->employee_id : null,['class'=>'form-control selectpicker','data-live-search'=>'true'] )  !!}
                                    </div>
                                    <div class="separator separator-dashed my-5"></div>
                                    <!--end::Section-->
                                    <!--begin::Section-->
                                    <h6 class="font-weight-bolder mb-3">Department Yöneticisi:</h6>
                                    <label style="font-size: 15px;font-weight: bold" class="mt-4" for="">{!! $department->department_manager() !!}</label>
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
@endsection


