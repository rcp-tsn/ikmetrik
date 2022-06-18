
<!--begin::Aside Secondary-->
<div class="sidebar sidebar-left d-flex flex-row-auto flex-column " id="kt_sidebar">


    <!--end::Aside Secondary Header-->

    <!--begin::Aside Secondary Content-->
    <div class="sidebar-content flex-column-fluid pb-10 pt-9 px-5 px-lg-10">


            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 225px; background-color: #663259; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/media/svg/patterns/taieri.svg)">

                <!--begin::Body-->

                <div class="card-body company-select-area d-flex align-items-center">
                    <div>
                        <h4 class="text-white font-weight-bolder line-height-lg mb-5">AKTİF FİRMA: {{ Auth::user()->company->name }}</h4>
                        @if(session()->has('selectedCompany'))
                        <h4 class="text-white font-weight-bolder line-height-lg mb-5">SEÇİLİ FİRMA: {{ session()->get('selectedCompany')['name'] }}
                            <span>Sicil No : {{ session()->get('selectedCompany')['registry_id'] }} </span>
                        </h4>

                            <a href="{!! route('sgk_company_un_select.store') !!}" class="btn font-weight-bolder text-uppercase btn-success py-4 px-6">SEÇİMİ KALDIR</a>
                        @else
                            <a href="{{ route('sgk_company_select') }}" class="btn font-weight-bolder text-uppercase btn-success py-4 px-6">FİRMA SEÇİN</a>
                        @endif
                        <br/>
                        <br/>
                        <br/>
                    </div>

                </div>
                <!--end::Body-->
            </div>





        <!--begin::List Widget 1-->
        <div class="card card-custom card-shadowless incentives-details-area bg-white gutter-b">

            <!--begin::Header-->
            <div class="card-header border-0 pt-5 ">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">KISAYOL İŞLEMLERİ</span>
                </h3>

                @if(!empty(Auth::user()->employee_id) and !empty(session()->get('performance_types')) and \Illuminate\Support\Facades\Auth::user()->packetModule(9))
                @if(!empty(session()->get('performance_types')))
                <hr>
                <label style="font-weight: bold;font-size: 15px;">Toplam Puan: {{session()->get('toplam_puan')}}</label>
                <hr>
                @endif
                    @endif
            </div>



            <!--end::Header-->
            @if((Auth::user()->hasRole('Performance') || Auth::user()->hasRole('Employee')) and \Illuminate\Support\Facades\Auth::user()->packetModule(9) and !empty(session()->get('performance_types')) )

            <!--Performans Değerlendirme Bölümü-->

            <div class="card card-custom gutter-b card-stretch item_employee table-responsive" style="height: 680px;">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Section-->
                @if(!empty(Auth::user()->employee_id) and !empty(session()->get('performance_types')))

                    @foreach(session()->get('performance_types') as $type)

                        <div class="col-xl-12">
                            <!--begin::Stats Widget 4-->
                            <div class="card card-custom card-stretch gutter-b"  >
                                <!--begin::Body-->
                                <div class="card-body d-flex align-items-center py-0 mt-8">
                                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-5">
                                        <label class="card-title font-weight-bolder text-dark-20 font-size-h7 mb-2 text-hover-primary">{{ $type->performance_type() }}</label>
                                        <span class="" style="font-weight: bold;font-size: 15px;color: black"><span></span>{{$type->performance_type_puan(Auth::user()->employee_id,$type->performance_program_id,$type->performance_type_id)}} / {{$type->type_puan($type->performance_program_id,$type->performance_type_id)}}</span>puan
                                    </div>
                                    <img src="/icon/{{ $type->icon() }}" alt="" class="align-self-end h-50px" style="margin-bottom: 20px">
                                </div>

                                <!--end::Body-->
                            </div>
                            <!--end::Stats Widget 4-->
                        </div>
                    @endforeach
                    @endif

                </div>
            </div>
            @endif
            <!--end Performans-->
            <!--begin::Body-->
            <div class="card-body pt-1">
            @if(config('app.main_company_id') == Auth::user()->company_id and \Illuminate\Support\Facades\Auth::user()->packetModule(2) )
                <!--begin::Item-->
                <div class="d-flex align-items-center mb-10">

                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-success mr-5">
						<span class="symbol-label">
							<i class="icon-xl la la-file-excel"></i> </span>
                    </div>

                    <!--end::Symbol-->

                    <!--begin::Text-->
                    <div class="d-flex flex-column font-weight-bold">
                        <a href="{{ route('declarations.incentives.excel-export') }}" class="text-dark text-hover-primary mb-1 font-size-lg">Taslak Teşvik Raporu</a>
                        <span class="text-muted">HTML ve excel formatında</span>
                    </div>

                    <!--end::Text-->
                </div>

                <!--end::Item-->

                <!--begin::Item-->
                <div class="d-flex align-items-center mb-10">

                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-danger mr-5">
						<span class="symbol-label">
							<i class="icon-xl la la-file-pdf"></i> </span>
                    </div>

                    <!--end::Symbol-->

                    <!--begin::Text-->
                    <div class="d-flex flex-column font-weight-bold">
                        <a href="#" class="text-dark-75 text-hover-primary mb-1 font-size-lg">Detaylı Teşvik Raporu</a>
                        <span class="text-muted">HTML ve PDF formatında</span>
                    </div>

                    <!--end::Text-->
                </div>

                <div class="d-flex align-items-center mb-10">

                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-warning mr-5">
						<span class="symbol-label">
							<i class="icon-xl la la-file-pdf"></i> </span>
                    </div>

                    <!--end::Symbol-->

                    <!--begin::Text-->
                    <div class="d-flex flex-column font-weight-bold">
                        <a href="{{ route('declarations.incentives.gain_incentives') }}" class="text-dark-75 text-hover-primary mb-1 font-size-lg">Hakediş Raporu</a>
                        <span class="text-muted">HTML ve PDF formatında</span>
                    </div>

                    <!--end::Text-->
                </div>
            @endif
            @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Teşvik') || Auth::user()->modulePermit(1))
                <div class="d-flex align-items-center mb-10">

                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-warning mr-5">
						<span class="symbol-label">
							<i class="icon-xl la la-file-pdf"></i> </span>
                    </div>

                    <!--end::Symbol-->

                    <!--begin::Text-->

                    <div class="d-flex flex-column font-weight-bold">
                        <a href="{{ route('declarations.incentives.all_gain_incentives') }}" class="text-dark-75 text-hover-primary mb-1 font-size-lg">Toplu Hakediş Raporu</a>
                        <span class="text-muted">HTML ve PDF formatında</span>
                    </div>

                    <!--end::Text-->
                </div>
            @endif
            </div>

            <!--end::Body-->
        </div>

        <!--end::List Widget 1-->

        <!--begin::List Widget 9-->


        <!--end: Card-->

        <!--end: List Widget 9-->
    </div>

    <!--end::Aside Secondary Content-->
</div>

<!--end::Aside Secondary-->
