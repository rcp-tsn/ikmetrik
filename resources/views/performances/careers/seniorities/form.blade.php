<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <h4 class="mb-10  text-dark up-font">{{$work_title->name}} Ünvanı İçin Kıdem Bilgileri Girilecek</h4>

        <div class="card card-custom card-transparent">
            <div class="card-body p-0">
                    <div class="card card-custom card-shadowless rounded-top-0">
                        <div class="card-body p-0">
                            <div class="justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                <div class="">
                                    <div class="pb-5" data-wizard-type="step-content">
                                        <!--begin::Section-->
                                        <h4 class="mb-10 text-dark up-font" >Ünvan Kıdem Bilgilerini Girniz.</h4>
                                        <div class="form-group">
                                            <label class="up-font"> Ünvan Kıdem Bilgilerini Seçiniz</label>
                                            {!! Form::select('senority_year', ['1'=>'1 Yıl','3'=>'3 Yıl','5'=>'5 Yıl','8'=>'8 Yıl','10'=>'10 Yıl','15'=>'15 Yıl','20'=>'20 Yıl'],null, ['class'=>'form-control selectpicker','data-live-search'=>'true'] )  !!}

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


