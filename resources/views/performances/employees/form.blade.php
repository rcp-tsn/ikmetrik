<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom card-transparent">
            <div class="card-body p-0">
                <!--begin: Wizard-->
                <div class="wizard wizard-4" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="true">
                    <!--begin: Wizard Nav-->
                    <div class="wizard-nav">
                        <div class="wizard-steps">
                            <!--begin::Wizard Step 1 Nav-->
                            <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                                <div class="wizard-wrapper">
                                    <div class="wizard-number">1</div>
                                    <div class="wizard-label">
                                        <div class="wizard-title">Genel Bilgiler</div>
                                        <div class="wizard-desc">Çalışana Ait Genel Bilgiler</div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Wizard Step 1 Nav-->
                            <!--begin::Wizard Step 2 Nav-->
                            <div class="wizard-step" data-wizard-type="step">
                                <div class="wizard-wrapper">
                                    <div class="wizard-number">2</div>
                                    <div class="wizard-label">
                                        <div class="wizard-title">Kişisel Bilgiler</div>
                                        <div class="wizard-desc">Çalışanın Kişisel Bilgileri</div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Wizard Step 2 Nav-->
                            <!--begin::Wizard Step 3 Nav-->
                            <div class="wizard-step" data-wizard-type="step">
                                <div class="wizard-wrapper">
                                    <div class="wizard-number">3</div>
                                    <div class="wizard-label">
                                        <div class="wizard-title">Ücret Bilgileri</div>
                                        <div class="wizard-desc">Çalışanın Ücret Bilgileri</div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Wizard Step 3 Nav-->
                            <!--end::Wizard Step 4 Nav-->
                        </div>
                    </div>
                    <!--end: Wizard Nav-->
                    <!--begin: Wizard Body-->
                    <div class="card card-custom card-shadowless rounded-top-0">
                        <div class="card-body p-0">
                            <div class="justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                <div class="">
                                    <!--begin: Wizard Form-->

                                    <!--begin: Wizard Step 1-->
                                    <div class="" data-wizard-type="step-content" data-wizard-state="current">
                                        <h3 class="mb-10 font-weight-bold text-dark"></h3>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Şirket Seçiniz</label> <span style="color: red">*</span>
                                                    {!! Form::select("sgk_company_id",$sgk_companies , isset($selectedSgkCompany) ? $selectedSgkCompany : null ,['class'=>'form-control selectpicker sgk_company_idd ','id'=>'sgk_company_idd','required'=>'required','data-live-search'=>'true']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Adress</label> <span style="color: red">*</span>

                                                    {!! Form::text("address", isset($employee->address) ? $employee->address : null ,['class'=>'form-control','id'=>'first_name','required'=>'required']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>İsim</label> <span style="color: red">*</span>
                                                    {!! Form::text("first_name",!empty($employee->first_name) ? $employee->first_name : null ,['class'=>'form-control first_name','id'=>'first_name','required'=>'required']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Soyisim</label> <span style="color: red">*</span>
                                                    {!! Form::text("last_name",!empty($employee->last_name) ? $employee->last_name : null ,['class'=>'form-control last_name ','id'=>'email','required'=>'required']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>E-posta(Kişisel)</label> <span style="color: red">*</span>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">@</span>
                                                        </div>
                                                        {!! Form::text("email",!empty($employee->email) ? $employee->email : null ,['class'=>'form-control email ','id'=>'email','required'=>'required']) !!}
                                                    </div>
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Telefon(kişisel)</label> <span style="color: red">*</span>
                                                    {!! Form::text("mobile",!empty($employee->mobile) ? $employee->mobile : null ,['class'=>'form-control mobile ','id'=>'ephone', 'maxlength'=>'10','required'=>'required']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>İşe Başlama Tarihi</label> <span style="color: red">*</span>
                                                    {!! Form::date("job_start_date",!empty($employee->job_start_date) ? $employee->job_start_date : null ,['class'=>'form-control','id'=>'example-date-input job_start_date','required'=>'required']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Çalışan Türü</label> <span style="color: red">*</span>
                                                    {!! Form::select("work_type", config('variables.employees.work_type') , !empty($employee->work_type) ? $employee->work_type : null , ['class'=>'form-control selectpicker','id'=>'status','required'=>'required']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label>Ünvan</label> <span style="color: red">*</span>
                                                <div class="form-group input-group">

                                                    {!! Form::select("work_title", $working_titles , !empty($employee->work_title_id) ? $employee->work_title_id : null ,['class'=>'form-control work_title ','id' => 'working_title_id ','required'=>'required']) !!}
                                                    <button  type="button"  data-toggle="modal" data-target="#workingTitleModal" class="btn btn-success ml-3">Ünvan Ekle</button>

                                                </div>

                                            </div>
                                            <div class="col-lg-6">
                                                <label>Departman</label> <span style="color: red">*</span>
                                                <div class="form-group input-group">

                                                    {!! Form::select("department", $departments, !empty($employee->department_id) ? $employee->department_id : null ,['class'=>'form-control deparment ','id'=>'department','required'=>'required']) !!}
                                                    <button  type="button"  data-toggle="modal" data-target="#DepartmentModal" class="btn btn-success ml-3">Department Ekle</button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Durumu</label> <span style="color: red">*</span>
                                                    {!! Form::select("status", config('variables.employee_status') , !empty($employee->status) ? $employee->status : null , ['class'=>'form-control selectpicker','id'=>'status','required'=>'required']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Resim</label>
                                                    <p></p>
                                                    {!! Form::file("avatar",  null , ['class'=>'form-control','id'=>'avatar']) !!}
                                                    <span>Personel resmi yok ise sistem kendi resmini oluşturacak</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Pozisyon</label> <span style="color: red">*</span>
                                                    {!! Form::select("pozisyon", $pozisyon , !empty($employee->pozisyon_id) ? $employee->pozisyon_id : null , ['class'=>'form-control selectpicker','id'=>'status','required'=>'required','data-live-search'=>'true']) !!}
                                                    <span>Zorunlu Alan</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--end: Wizard Step 1-->
                                    <!--begin: Wizard Step 2-->
                                    <div class="pb-5" data-wizard-type="step-content">
                                        <div class="mb-10 font-weight-bold text-dark">Kişisel Bilgiler</div>
                                        <!--begin::Input-->
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class=" control-label">Doğum Tarihi</label> <span style="color: red">*</span>
                                                    <div class="">

                                                        <div class="form-group has-feedback has-feedback-right">
                                                            {!! Form::text("birth_date",isset($employee->employee_personel->birth_date) && strlen($employee->employee_personel->birth_date) >= 10 ? $employee->employee_personel->birth_date->format('d/m/Y') : '',['class'=>'form-control input-xs birth_date','id'=>'birth_date']) !!}

                                                            <div class="form-control-feedback">
                                                                <i class="icon-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" control-label">Kimlik Numarası(Tc Kimlik)</label> <span style="color: red">*</span>
                                                    <div class="">
                                                        {!! Form::text("identity_number",isset($employee->employee_personel)  ? $employee->employee_personel->identity_number : null,['class'=>'form-control input-xs identity_number','id'=>'identity_number','required','maxlength'=>'11']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Medeni Hal</label>
                                                    <div class="">
                                                        {!! Form::select('marital_status', config('variables.employees.marital_status'),isset($employee->employee_personel)  ? $employee->employee_personel->marital_status : null, ['class'=>'form-control selectpicker select-size-xs','id'=>'marital_status']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class=" control-label">Cinsiyet</label>
                                                    <div class="">
                                                        {!! Form::select('gender', config('variables.employees.gender'),isset($employee->employee_personel)  ? $employee->employee_personel->gender : null, ['class'=> 'form-control selectpicker select-size-xs','id'=>'gender']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class=" control-label">Engelli Birey Varmı</label>
                                                    <div class="">
                                                        {!! Form::select('disability_level', config('variables.employees.disability_level'),isset($employee->employee_personel)  ? $employee->employee_personel->disability_level : null, ['class'=>' form-control selectpicker select-size-xs','id'=>'disability_level']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class=" control-label">Ev Kira mı</label>
                                                    <div class="">
                                                        {!! Form::select('home',  config('variables.employees.home'),isset($employee->employee_personel)  ? $employee->employee_personel->home : null, ['class'=>' form-control selectpicker select-size-xs','id'=>'disability_level']) !!}
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="control-label">Çocuk Sayısı</label>
                                                    <div class="">
                                                        {!! Form::text("children_number", isset($employee->employee_personel)  ? $employee->employee_personel->children_number : null,['class'=>'form-control input-xs','id'=>'children_number']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class=" control-label">Kan Grubu</label>
                                                    <div class="">
                                                        {!! Form::select('blood_group', config('variables.employees.blood_group'),isset($employee->employee_personel)  ? $employee->employee_personel->blood_group : null, ['class'=>' form-control  selectpicker select-size-xs','id'=>'blood_group']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class=" control-label">Eğitim Durumu</label>
                                                    <div class="">
                                                        {!! Form::select('educational_status', config('variables.employees.educational_status'),isset($employee->employee_personel)  ? $employee->employee_personel->educational_status : null, ['class'=>' form-control selectpicker select-size-xs','id'=>'educational_status']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class=" control-label">Tamamlanan En Yüksek Eğitim Seviyesi</label>
                                                    <div class="">
                                                        {!! Form::select('completed_education', config('variables.employees.completed_education'),isset($employee->employee_personel)  ? $employee->employee_personel->completed_education : null, ['class'=>'form-control selectpicker select-size-xs','id'=>'completed_education']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class=" control-label">Üniv.Okuyan Çocuk Var mı</label>
                                                    <div class="">
                                                        {!! Form::select('university',  config('variables.employees.University'),isset($employee->employee_personel)  ? $employee->employee_personel->university : null, ['class'=>' form-control selectpicker select-size-xs','id'=>'disability_level']) !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Wizard Step 2-->
                                    <!--begin: Wizard Step 3-->
                                    <div class="pb-5" data-wizard-type="step-content">
                                        <div class="mb-10 font-weight-bold text-dark">Çalışan Ücret Bilgileri</div>

                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label class=" control-label">Maaş</label><span style="color: red">*</span>
                                                    <div class="col-md-9">
                                                        {!! Form::text("salary",isset($employee->employeeSalary)  ? str_replace('.', ',', $employee->employeeSalary->salary) : null,['class'=>'form-control input-xs salary','id'=>'salary']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Birim</label>
                                                    <div class="col-md-9">
                                                        {!! Form::select('salary_unit', config('variables.employees.salary_unit'),isset($employee->employeeSalary)  ? $employee->employeeSalary->salary_unit : null, ['class'=>' form-control select-size-xs selectpicker','id'=>'salary_unit','data-live-search'=>'true']) !!}
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Maaş Periyodu</label>
                                                    <div class="col-md-9">
                                                        {!! Form::select('salary_period', config('variables.employees.salary_period'),isset($employee->employeeSalary)  ? $employee->employeeSalary->salary_period : null, ['class'=>' form-control select-size-xs selectpicker','id'=>'salary_period','data-live-search'=>'true']) !!}
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Maaş Tipi</label>
                                                    <div class="col-md-9">
                                                        {!! Form::select('salary_type', config('variables.employees.salary_type'),isset($employee->employeeSalary)  ? $employee->employeeSalary->salary_type : null, ['class'=>' form-control select-size-xs selectpicker','id'=>'salary_type','data-live-search'=>'true']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group" style="display: none;" id="agi_field">
                                                    <label class="col-md-2 control-label">AGİ?</label>
                                                    <div class="col-md-9">
                                                        {!! Form::select('include_agi', config('variables.employees.include_agi'),isset($employee->employeeSalary)  ? $employee->employeeSalary->include_agi : null, ['class'=>' form-control select-size-xs selectpicker','id'=>'include_agi','data-live-search'=>'true']) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Durumu</label>
                                                    <div class="col-md-9">
                                                        {!! Form::select("salary_active", ['1' => 'AKTİF', '0' => 'PASİF'], isset($employee->employeeSalary)  ? $employee->employeeSalary->active : null, ['class'=>' form-control select-size-xs selectpicker','id'=>'salary_active','data-live-search'=>'true']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Wizard Step 3-->

                                    <!--end: Wizard Step 4-->
                                    <!--begin: Wizard Actions-->
                                    <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                        <div class="mr-2">
                                            <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">Geri</button>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4 save" data-wizard-type="action-submit">Kaydet</button>
                                            <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next">İleri</button>
                                        </div>
                                    </div>
                                    <!--end: Wizard Actions-->

                                    <!--end: Wizard Form-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end: Wizard Bpdy-->
                </div>
                <!--end: Wizard-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>

@include('performances.InsertModals.working_title_modal')
@include('performances.InsertModals.department_modal')
@section('js')
    <script>

        $("#phone_work").mask("(999) 999-9999");

        $('#birth_date').mask('99/99/9999',{placeholder:"mm/dd/yyyy"});

    </script>
    <script>
        $(document).ready(function ()
        {
            $("#working_title_create").click(function ()
            {
                var name = $("#working_title_name").val();
                var sgk_company_id = $( ".sgk_company_idd option:selected" ).val();
                console.log(sgk_company_id);
                $.ajax({
                    type: "POST",
                    url: '/ajax-company-working-title-create',
                    data: {
                        _token: "{{csrf_token()}}",
                        name:name,
                        sgk_company_id:sgk_company_id,
                    },

                    success: function (response) {
                        alert(response['addValue']);
                        $('#workingTitleModal').modal('hide');
                        $(".work_title").append(response['addValue']);


                    },

                    error: function (error) {
                        if( error.status === 422 ) {
                            $errors = error.responseJSON['errors'];
                            alert($errors['name']);
                            if (! form.find('.form-errors').length) {
                                form.prepend('<div class="form-errors"></div>');
                            }

                            errorsHtml = '<div class="alert alert-danger"><ul>';
                            $.each($errors, function(key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            errorsHtml += '</ul></di>';
                            form.find('.form-errors').html(errorsHtml);
                        }
                    }
                });

            });
        });

    </script>
    <script>
        $(document).ready(function ()
        {
            $("#deparment_create").click(function ()
            {
                var name = $("#department_name").val();
                var sgk_company_id = $( ".sgk_company_idd option:selected" ).val();
                console.log(sgk_company_id);
                $.ajax({
                    type: "POST",
                    url: '/ajax-company-deparment-create',
                    data: {
                        _token: "{{csrf_token()}}",
                        name:name,
                        sgk_company_id:sgk_company_id,
                    },

                    success: function (response) {
                        alert(response['addValue']);
                        $('#DepartmentModal').modal('hide');
                        $(".deparment").append(response['addValue']);


                    },

                    error: function (error) {
                        if( error.status === 422 ) {
                            $errors = error.responseJSON['errors'];
                            alert($errors['name']);
                            if (! form.find('.form-errors').length) {
                                form.prepend('<div class="form-errors"></div>');
                            }

                            errorsHtml = '<div class="alert alert-danger"><ul>';
                            $.each($errors, function(key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            errorsHtml += '</ul></di>';
                            form.find('.form-errors').html(errorsHtml);
                        }
                    }
                });

            });
        });

    </script>

    <script>
        // Class definition
        var KTFormControls = function () {
            var say = 0;
            // Private functions
            var _initDemo1 = function () {
                FormValidation.formValidation(
                    document.getElementById('ezy_standard_form'),
                    {
                        fields: {
                            first_name: {
                                validators: {
                                    notEmpty: {
                                        message: 'İsim Alanı  gerekli'
                                    }
                                }
                            },
                            last_name: {
                                validators: {
                                    notEmpty: {
                                        message: 'Soyisim Alanı  gerekli'
                                    }
                                }
                            },
                            mobile: {
                                validators: {
                                    notEmpty: {
                                        message: 'Soyisim Alanı  gerekli'
                                    }
                                }
                            },
                            email: {
                                validators: {
                                    notEmpty: {
                                        message: 'Mail Alanı  gerekli'
                                    }
                                }
                            },
                            email: {
                                validators: {
                                    emailAddress: {
                                        message: 'Mail Formatı Hatalı'
                                    }
                                }
                            },
                            address: {
                                validators: {
                                    notEmpty: {
                                        message: 'Adres Alanı  gerekli'
                                    }
                                }
                            },
                            job_start_date: {
                                validators: {
                                    notEmpty: {
                                        message: 'İşe Giriş  Alanı gerekli'
                                    }
                                }
                            },
                            birth_date: {
                                validators: {
                                    notEmpty: {
                                        message: 'Doğum Tarihi  Alanı gerekli'
                                    }
                                }
                            },
                            identity_number: {
                                validators: {
                                    notEmpty: {
                                        message: 'Tc Kimlik No Alanı gerekli'
                                    }
                                }
                            },
                            salary: {
                                validators: {
                                    notEmpty: {
                                        message: 'Maaş Alanı gerekli'
                                    }
                                }
                            },
                        },

                        plugins: { //Learn more: https://formvalidation.io/guide/plugins
                            trigger: new FormValidation.plugins.Trigger(),
                            // Bootstrap Framework Integration
                            bootstrap: new FormValidation.plugins.Bootstrap(),
                            // Validate fields when clicking the Submit button
                            submitButton: new FormValidation.plugins.SubmitButton(),
                            // Submit the form when all fields are valid
                            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                        }
                    }
                );
            }


            return {
                // public functions
                init: function() {
                    _initDemo1();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTFormControls.init();
        });

    </script>
    <script >

        $(".save").on('click',function ()
        {

            var say = 0;
            var first_name = $(".first_name").val();
            var last_name = $(".last_name").val();
            var address = $(".address").val();
            var email = $(".email").val();
            var mobile = $(".mobile").val();
            var job_start_date = $(".job_start_date").val();
            var birth_date = $(".birth_date").val();
            var identity_number = $(".identity_number").val();
            var salary = $(".salary").val();
            console.log(first_name);
            if (first_name == "" || last_name == "" || address == "" || email == "" || job_start_date == "" || birth_date == "" || identity_number == "" || salary == "" || mobile == "")
            {
                swal("ÜZGÜNÜZ!", "Doldurulması Zorunlu Alanlar Doldurulmadı!", "error");

            }
        })
    </script>






@endsection

