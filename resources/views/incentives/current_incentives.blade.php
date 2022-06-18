@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Callout-->
                    <div class="card card-custom wave wave-animate-slow wave-primary mb-8 mb-lg-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center p-5">
                                <!--begin::Icon-->
                                <div class="col-lg-0 mr-6">
													<span class="svg-icon svg-icon-primary svg-icon-4x">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"/>
        <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"/>
        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
        <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"/>
    </g>
</svg>
													</span>
                                </div>
                                <!--end::Icon-->
                                <!--begin::Content-->
                                <div class="col-lg-4 d-flex flex-column">
                                    <a href="#" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Cari Dönem Teşvikleri</a>
                                    <div class="text-dark-75">Seçili Şube İçin Cari Dönem Teşvik Listesi</div>
                                </div>
                                <div class="col-lg-8 d-flex align-items-center justify-content-lg-end">

                                </div>
                                <!--end::Content-->
                            </div>
                        </div>
                    </div>
                    <!--end::Callout-->
                </div>
            </div>
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-body">
                            @include('incentives.current_incentives_table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

