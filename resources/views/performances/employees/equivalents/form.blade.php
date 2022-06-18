<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <h4 class="mb-10 text-dark" style="font-size: 15px;font-weight: bold">{{$control->first_name}} {{$control->last_name}} Çalışanı İçin Eş Değer Bilgileri Girilecek</h4>

        <div class="card card-custom card-transparent">
            <div class="card-body p-0">
                    <div class="card card-custom card-shadowless rounded-top-0">
                        <div class="card-body p-0">
                            <div class="justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                <div class="">
                                    <div class="pb-5" data-wizard-type="step-content">
                                        <!--begin::Section-->
                                        <h4 class="mb-10 font-weight-bold text-dark">Personel Eş Değer Giriniz.</h4>
                                        <div class="card-body">
                                            <table class="table table-bordered" id="kt_datatable">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Avatar</th>
                                                    <th>İsim Soyisim</th>
                                                    <th>Departman</th>
                                                    <th>Ünvan</th>
                                                </tr>

                                                </thead>
                                                <tbody>

                                                @foreach($employees as $employee)
                                                    <tr>
                                                        <td style="text-align: -webkit-center">
                                                            <label class="checkbox checkbox-rounded ">
                                                                <input type="checkbox"   <?php if (in_array($employee->id,$selectedEmployeeAst)) { echo "checked"; } ?> name="status[{{$employee->id}}]">
                                                                <span></span></label></td>
                                                        <td>
                                                            <div class="symbol symbol-50 symbol-light mt-1">
                                                                <img src="/{{ $employee->avatar }}" class="h-75 align-self-end" alt="">
                                                            </div>
                                                        </td>
                                                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                                        <td>{{ $employee->department->name }}</td>
                                                        <td>{{ $employee->working_title->name}}</td>
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
@endsection


