<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <h4 class="mb-10  text-dark up-font">{{$control->first_name}} {{$control->last_name}} Çalışanı İçin Üst/Ast Bilgileri Girilecek</h4>

        <div class="card card-custom card-transparent">
            <div class="card-body p-0">
                    <div class="card card-custom card-shadowless rounded-top-0">
                        <div class="card-body p-0">
                            <div class="justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                <div class="">
                                    <div class="pb-5" data-wizard-type="step-content">
                                        <!--begin::Section-->
                                        <h4 class="mb-10 text-dark up-font" >Personel Üst Alt Kişilerini Giriniz.</h4>
                                        <h6 class="font-weight-bolder mb-3">Yönetici Seçiniz:</h6>
                                        <div class="form-group">
                                            <label class="up-font" >1.Üst Personel Seçiniz</label>
                                            {!! Form::select('top_manager_id', $employees2, isset($managers['oneManager']) ? $managers['oneManager'] : null,['class'=>'form-control selectpicker','data-live-search'=>'true'] )  !!}
                                            <div class="form-group mt-4">
                                                <label class="checkbox up-font">1.Üst Yönetici Yok İse Tıklayınız
                                                <input type="checkbox" name="top_manager_disabled">
                                                <span></span></label>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed my-5"></div>

                                        <div class="form-group">
                                            <label class="up-font" >2.Üst Personel Seçiniz</label>
                                            {!! Form::select('top_manager_id2', $employees2, isset($managers['twoManager']) ? $managers['twoManager'] : null,['class'=>'form-control selectpicker','data-live-search'=>'true'] )  !!}
                                            <div class="form-group mt-4">
                                                <label class="checkbox up-font">2.Üst Yönetici Yok İse Tıklayınız
                                                    <input type="checkbox" name="top_manager_disabled2">
                                                    <span></span></label>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed my-5"></div>
                                        <!--end::Section-->

                                        <div class="form-group">
                                            <label class="up-font" >3.Üst Personel Seçiniz</label>
                                            {!! Form::select('top_manager_id3', $employees2, isset($managers['thereeManager']) ? $managers['thereeManager'] : null,['class'=>'form-control selectpicker','data-live-search'=>'true'] )  !!}
                                            <div class="form-group mt-4">
                                                <label class="checkbox up-font">3.Üst Yönetici Yok İse Tıklayınız
                                                    <input type="checkbox" name="top_manager_disabled3">
                                                    <span></span></label>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed my-5"></div>
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


