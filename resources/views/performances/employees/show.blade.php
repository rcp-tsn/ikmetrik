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
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Çalışanlar</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>


                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            @include('partials.alerts.error')

                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container-fluid">
                                    <!--begin::Profile Account Information-->
                                    <div class="d-flex flex-row">
                                        <!--begin::Aside-->
                                        <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
                                            <!--begin::Profile Card-->
                                            <div class="card card-custom card-stretch">
                                                <!--begin::Body-->
                                                <div class="card-body pt-4">
                                                    <!--begin::User-->
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                                                            <div class="symbol-label"></div>
                                                        </div>
                                                        <div>
                                                            <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{$employee->first_name.' '.$employee->last_name}}</a>
                                                        </div>
                                                    </div>
                                                    <!--end::User-->
                                                    <!--begin::Contact-->
                                                    <div class="py-9">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="font-weight-bold mr-2">Email:</span>
                                                            <span class="font-weight-bold mr-2"> {{$employee->email}}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="font-weight-bold mr-2">Telefon:</span>
                                                            <span class="font-weight-bold mr-2"> {{$employee->mobile}}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <span class="font-weight-bold mr-2">Adres:</span>
                                                            <span class="font-weight-bold mr-2"> {{$employee->address}}</span>
                                                        </div>
                                                    </div>
                                                    <!--end::Contact-->
                                                    <!--begin::Nav-->
                                                    <div class="navi navi-bold navi-hover nav-pills navi-link-rounded" id="myTab3" role="Tablist">
                                                        <ul class="nav nav-sucess nav-pills">
                                                            <li class="navi-item mb-2">
                                                                <a href="#personel" id="home-tab-personel" class="navi-link py-4 "  data-toggle="tab" >
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<polygon points="0 0 24 0 24 24 0 24" />
																			<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
																			<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                                                                    <span class="navi-text font-size-lg col-lg-12">Kişisel Bilgiler</span>
                                                                </a>
                                                            </li>
                                                            <li class="navi-item mb-2">
                                                                <a href="#iletisim" id="home-tab-iletisim" class="navi-link py-4 "  data-toggle="tab" >
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<polygon points="0 0 24 0 24 24 0 24" />
																			<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
																			<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                                                                    <span class="navi-text font-size-lg col-lg-12">İletişim Bilgileri</span>
                                                                </a>
                                                            </li>
                                                            <li class="navi-item mb-2">
                                                                <a href="#aile" id="home-tab-aile" class="navi-link py-4 "  data-toggle="tab" >
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<polygon points="0 0 24 0 24 24 0 24" />
																			<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
																			<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                                                                    <span class="navi-text font-size-lg col-lg-12">Aile Bilgileri</span>
                                                                </a>
                                                            </li>
                                                            <li class="navi-item mb-2">
                                                                <a href="#yonetici" id="home-tab-yonetici" class="navi-link py-4"  data-toggle="tab" >
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<polygon points="0 0 24 0 24 24 0 24" />
																			<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																			<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                                                                    <span class="navi-text font-size-lg col-lg-12">Yönetici Bilgileri </span>
                                                                </a>
                                                            </li>
                                                            <li class="navi-item mb-2">
                                                                <a href="#maas" id="home-tab-maas" class="navi-link py-4"  data-toggle="tab" >
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<polygon points="0 0 24 0 24 24 0 24" />
																			<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																			<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                                                                    <span class="navi-text font-size-lg col-lg-12">Maaş Bilgileri </span>
                                                                </a>
                                                            </li>
                                                            <li class="navi-item mb-2">
                                                                <a href="#mesai" id="home-tab-mesai" class="navi-link py-4"  data-toggle="tab" >
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<polygon points="0 0 24 0 24 24 0 24" />
																			<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																			<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                                                                    <span class="navi-text font-size-lg col-lg-12">Mesai Bilgileri </span>
                                                                </a>
                                                            </li>
                                                            <li class="navi-item mb-2">
                                                                <a href="#izin" id="home-tab-izin" class="navi-link py-4"  data-toggle="tab" >
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<polygon points="0 0 24 0 24 24 0 24" />
																			<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																			<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                                                                    <span class="navi-text font-size-lg col-lg-12">İzin Bilgileri </span>
                                                                </a>
                                                            </li>
                                                        </ul>

                                                    </div>
                                                    <!--end::Nav-->
                                                </div>
                                                <!--end::Body-->
                                            </div>
                                            <!--end::Profile Card-->
                                        </div>
                                        <!--end::Aside-->
                                        <!--begin::Content-->
                                        <div class="tab-content mt-5" id="MyTabContent3">

                                            <div class="flex-row-fluid ml-lg-8 tab-pane fade active show" id="personel" >
                                                <!--begin::Card-->
                                                <div class="card card-custom col-lg-12 ">
                                                    <!--begin::Header-->
                                                    <div class="card-header py-3">
                                                        <div class="card-title align-items-start flex-column">
                                                            <h3 class="card-label font-weight-bolder text-dark">Kişisel Bilgiler</h3>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="reset" class="btn btn-success mr-2">Kaydet</button>
                                                            <button type="reset" class="btn btn-secondary">İptal</button>
                                                        </div>
                                                    </div>
                                                    <!--end::Header-->
                                                    <!--begin::Form-->
                                                    <form class="form">
                                                        <div class="card-body">
                                                            <!--begin::Heading-->
                                                            <div class="row">
                                                                <label class="col-xl-3"></label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <h5 class="font-weight-bold mb-6"></h5>
                                                                </div>
                                                            </div>
                                                            <!--begin::Form Group-->
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Ad</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="first_name" value="{{$employee->first_name}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Soyad</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="last_name" value="{{$employee->last_name}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">TC Kimlik</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="identity_number" value="{{$employee->employee_personel->identity_number}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Cinsiyet</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="gender" value="{{config('variables.employees.gender')[$employee->employee_personel->gender]}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Doğum Tarihi</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="birth_date" value="{{date("d-m-Y", strtotime(substr($employee->employee_personel->birth_date,0,10)))}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Kan Grubu</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="blood_group" value="{{config('variables.employees.blood_group')[$employee->employee_personel->blood_group]}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Eğitim Durumu</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="educational_status" value="{{config('variables.employees.educational_status')[$employee->employee_personel->educational_status]}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Eğitim Seviyesi</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="educational_status" value="{{config('variables.employees.completed_education')[$employee->employee_personel->completed_education]}}" />
                                                                </div>
                                                            </div>
                                                            <!--begin::Form Group-->

                                                            <!--begin::Form Group-->
                                                            <div class="separator separator-dashed my-5"></div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Personel Adına Giriş Yap</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <button type="button" class="btn btn-light-primary font-weight-bold btn-sm">Giriş Yap</button>
                                                                    <p class="form-text text-muted pt-2">After you log in, you will be asked for additional information to confirm your identity and protect your account from being compromised.</p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                                <!--end::Card-->
                                            </div>

                                            <div class="flex-row-fluid ml-lg-8 tab-pane fade" id="iletisim" >
                                                <!--begin::Card-->
                                                <div class="card card-custom">
                                                    <!--begin::Header-->
                                                    <div class="card-header py-3">
                                                        <div class="card-title align-items-start flex-column">
                                                            <h3 class="card-label font-weight-bolder text-dark">İletişim Bilgileri</h3>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="reset" class="btn btn-success mr-2">Kaydet</button>
                                                            <button type="reset" class="btn btn-secondary">İptal</button>
                                                        </div>
                                                    </div>
                                                    <!--end::Header-->
                                                    <!--begin::Form-->
                                                    <form class="form">
                                                        <div class="card-body">
                                                            <!--begin::Heading-->
                                                            <div class="row">
                                                                <label class="col-xl-3"></label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <h5 class="font-weight-bold mb-6"></h5>
                                                                </div>
                                                            </div>
                                                            <!--begin::Form Group-->
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <div class="input-group input-group-lg input-group-solid">
                                                                        <div class="input-group-prepend">
																	<span class="input-group-text">
																		<i class="la la-at"></i>
																	</span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-lg form-control-solid" readonly name="email" value="{{$employee->email}}" placeholder="Email" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Telefon</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <input class="form-control form-control-lg form-control" type="text" name="mobile" value="{{$employee->mobile}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Adres</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <input class="form-control form-control-lg form-control" type="text" name="address" value="{{$employee->address}}" />
                                                                    <p class="form-text text-muted pt-2">After you log in, you will be asked for additional information to confirm your identity and protect your account from being compromised.</p>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                                <!--end::Card-->
                                            </div>

                                            <div class="flex-row-fluid ml-lg-8 tab-pane fade" id="aile" >
                                                <!--begin::Card-->
                                                <div class="card card-custom">
                                                    <!--begin::Header-->
                                                    <div class="card-header py-3">
                                                        <div class="card-title align-items-start flex-column">
                                                            <h3 class="card-label font-weight-bolder text-dark">Aile Bilgileri</h3>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="reset" class="btn btn-success mr-2">Kaydet</button>
                                                            <button type="reset" class="btn btn-secondary">İptal</button>
                                                        </div>
                                                    </div>
                                                    <!--end::Header-->
                                                    <!--begin::Form-->
                                                    <form class="form">
                                                        <div class="card-body">
                                                            <!--begin::Heading-->
                                                            <div class="row">
                                                                <label class="col-xl-3"></label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <h5 class="font-weight-bold mb-6"></h5>
                                                                </div>
                                                            </div>
                                                            <!--begin::Form Group-->
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Medeni Hali</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="marital_status" value="{{config('variables.employees.marital_status')[$employee->employee_personel->marital_status]}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Çocuk Sayısı</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" name="last_name" value="{{$employee->employee_personel->children_number}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Engelli Birey Sayısı</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <input class="form-control form-control-lg form-control" type="text" name="disability_level" value="{{config('variables.employees.disability_level')[$employee->employee_personel->disability_level]}}" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Ev Kira mı?</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <input class="form-control form-control-lg form-control" type="text" name="mobile" value="{{config('variables.employees.home')[$employee->employee_personel->home]}}" />
                                                                    <p class="form-text text-muted pt-2">After you log in, you will be asked for additional information to confirm your identity and protect your account from being compromised.</p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                                <!--end::Card-->
                                            </div>

                                            <div class="flex-row-fluid ml-lg-8 tab-pane fade" id="yonetici" >
                                                <!--begin::Card-->
                                                <div class="card card-custom">
                                                    <!--begin::Header-->
                                                    <div class="card-header py-3">
                                                        <div class="card-title align-items-start flex-column">
                                                            <h3 class="card-label font-weight-bolder text-dark">Yönetici Bilgileri</h3>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="reset" class="btn btn-success mr-2">Kaydet</button>
                                                            <button type="reset" class="btn btn-secondary">İptal</button>
                                                        </div>
                                                    </div>
                                                    <!--end::Header-->
                                                    <!--begin::Form-->
                                                    <form class="form">
                                                        <div class="card-body">
                                                            <!--begin::Heading-->
                                                            <div class="row">
                                                                <label class="col-xl-3"></label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <h5 class="font-weight-bold mb-6"></h5>
                                                                </div>
                                                            </div>
                                                            <!--begin::Form Group-->
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Yöneticisi</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <img src="/.'{{$employee->employee_ust($employee->id,false)}}'" alt="" title="{{$employee->employee_ust($employee->id,true)}}" width="50px" height="50px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Ast Personelleri</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    @foreach($employee->employee_subordinate($employee->id) as $employee_ast)
                                                                        <img src="/{{$employee_ast->avatar}}" alt="" title="{{$employee_ast->first_name.' '.$employee_ast->last_name}}" width="50px" height="50px">
                                                                    @endforeach
                                                                </div>
                                                                <p class="form-text text-muted pt-2">After you log in, you will be asked for additional information to confirm your identity and protect your account from being compromised.</p>

                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                                <!--end::Card-->
                                            </div>

                                            <div class="flex-row-fluid ml-lg-8 tab-pane fade" id="maas" >
                                                <!--begin::Card-->
                                                <div class="card card-custom">
                                                    <!--begin::Header-->
                                                    <div class="card-header py-3">
                                                        <div class="card-title align-items-start flex-column">
                                                            <h3 class="card-label font-weight-bolder text-dark">Maaş Bilgileri</h3>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="reset" class="btn btn-success mr-2">Kaydet</button>
                                                            <button type="reset" class="btn btn-secondary">İptal</button>
                                                        </div>
                                                    </div>
                                                    <!--end::Header-->
                                                    <!--begin::Form-->
                                                    <form class="form">
                                                        <div class="card-body">
                                                            <!--begin::Heading-->
                                                            <div class="row">
                                                                <label class="col-xl-3"></label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <h5 class="font-weight-bold mb-6"></h5>
                                                                </div>
                                                            </div>
                                                            <!--begin::Form Group-->
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Maaşı</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" name="salary" type="text" value="{{$employee->employeeSalary->salary}} TL" />
                                                                    <p class="form-text text-muted pt-2">After you log in, you will be asked for additional information to confirm your identity and protect your account from being compromised.</p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                                <!--end::Card-->
                                            </div>

                                            <div class="flex-row-fluid ml-lg-8 tab-pane fade" id="mesai" >
                                                <!--begin::Card-->
                                                <div class="card card-custom">
                                                    <!--begin::Header-->
                                                    <div class="card-header py-3">
                                                        <div class="card-title align-items-start flex-column">
                                                            <h3 class="card-label font-weight-bolder text-dark">Mesai Bilgileri</h3>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="reset" class="btn btn-success mr-2">Kaydet</button>
                                                            <button type="reset" class="btn btn-secondary">İptal</button>
                                                        </div>
                                                    </div>
                                                    <!--end::Header-->
                                                    <!--begin::Form-->
                                                    <form class="form">
                                                        <div class="card-body">
                                                            <!--begin::Heading-->
                                                            <div class="row">
                                                                <label class="col-xl-3"></label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <h5 class="font-weight-bold mb-6"></h5>
                                                                </div>
                                                            </div>
                                                            <!--begin::Form Group-->
                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Mesai</label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                        <input class="form-control form-control-lg form-control" type="text" value="" />
                                                                    <p class="form-text text-muted pt-2">After you log in, you will be asked for additional information to confirm your identity and protect your account from being compromised.</p>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                                <!--end::Card-->
                                            </div>


                                            <div class="flex-row-fluid ml-lg-8 tab-pane fade" id="izin" >
                                                <!--begin::Card-->
                                                <div class="card card-custom col-lg-12">
                                                    <!--begin::Header-->
                                                    <div class="card-header py-3">
                                                        <div class="card-title align-items-start flex-column">
                                                            <h3 class="card-label font-weight-bolder text-dark">Mesai Bilgileri</h3>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="reset" class="btn btn-success mr-2">Kaydet</button>
                                                            <button type="reset" class="btn btn-secondary">İptal</button>
                                                        </div>
                                                    </div>
                                                    <!--end::Header-->
                                                    <!--begin::Form-->
                                                    <form class="form">
                                                        <div class="card-body">
                                                            <!--begin::Heading-->
                                                            <div class="row">
                                                                <label class="col-xl-3"></label>
                                                                <div class="col-lg-9 col-xl-6">
                                                                    <h5 class="font-weight-bold mb-6"></h5>
                                                                </div>
                                                            </div>
                                                            <!--begin::Form Group-->
                                                            <div class="form-group row">
                                                                @if(isset($employee))
                                                                    <div style="height: 300px;">
                                                                        <table  width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Yıl</th>
                                                                                <th>Devreden</th>
                                                                                <th>Hakedilen İzin</th>
                                                                                <th>Kullanılan
                                                                                    <br/>İzin</th>
                                                                                <th>Kalan İzin</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>


                                                                            @php $i = 0; $kalan = 0; $devreden = 0; @endphp
                                                                            @foreach($employee->leaveYears() as $key => $year)
                                                                                @php
                                                                                    $seniority = $employee->getSeniorityData();

                                                                                         $leave = $seniority['year_by_leave'];
                                                                                        $hak_edilen = isset($leave[$i - 1]) ? $leave[$i-1] : 0;
                                                                                        $kullanilan = $employee->getLeaveInfo($year);
                                                                                        $kalan = ($hak_edilen - $kullanilan) + $devreden;

                                                                                @endphp
                                                                                <tr>
                                                                                    <td>{{ $year }}</td>
                                                                                    <td>{{ $devreden }}</td>
                                                                                    <td>{{ $hak_edilen }}</td>
                                                                                    <td>{{ $kullanilan }}</td>
                                                                                    <td>{{ $kalan  }}</td>
                                                                                </tr>
                                                                                @php
                                                                                    //deneme
                                                                                        $devreden = $kalan;
                                                                                        $i++;
                                                                                @endphp
                                                                            @endforeach

                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                @endif
                                                            </div>

                                                        </div>
                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                                <!--end::Card-->
                                            </div>



                                        </div>

                                        <!--end::Content-->
                                    </div>
                                    <!--end::Profile Account Information-->
                                </div>
                                <!--end::Container-->
                            </div>



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
    <!-- Modal-->
    <div class="modal fade" id="employeeAnyCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Çalışanları Excel İle Yükle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                    <br>
                    <a href="{{route('excell-sablon')}}" download=""><button class="btn btn-success">Excel Format İndir</button></a>
                </div>

                {!! Form::open(['route' => 'employee_any_store', 'files'=>'true', 'class' => 'form','enctype'=>'multipart/form-data']) !!}
                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Excel Dosyası Yükleyiniz</label>
                                <br>
                                {!! Form::file('excel',null,['class'=>'form-control','id'=>'excel_file']) !!}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Kaydet</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script>
        "use strict";
        var KTDatatablesAdvancedColumnRendering = function() {

            var init = function() {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    paging: true
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
@endpush

