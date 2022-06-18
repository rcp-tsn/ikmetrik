<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <h4 class="mb-10  text-dark up-font">{{$work_title->name}} Ünvanı İçin Performans Bilgileri Girilecek</h4>

        <div class="card card-custom card-transparent">
            <div class="card-body p-0">
                    <div class="card card-custom card-shadowless rounded-top-0">
                        <div class="card-body p-0">
                            <div class="justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                <div class="">
                                    <div class="pb-5" data-wizard-type="step-content">
                                        <!--begin::Section-->
                                        <h4 class="mb-10 text-dark up-font" >Ünvan Performans Bilgilerini Girniz.</h4>
                                        <div class="form-group">
                                            <label class="up-font"> Ünvan Performans Bilgilerini Seçiniz</label>
                                            {!! Form::number('performance_puan',null, ['class'=>'form-control ','required'] )  !!}

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
@endsection


