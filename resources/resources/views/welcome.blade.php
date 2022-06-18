@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Detay sayfası'])
    @endcomponent
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Charts Widget 5-->

                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Recent Orders</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">More than 500+ new orders</span>
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-dark-75" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link py-2 px-4" data-toggle="tab" href="#kt_charts_widget_2_chart_tab_1">
                                            <span class="nav-text font-size-sm">Month</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-2 px-4" data-toggle="tab" href="#kt_charts_widget_2_chart_tab_2">
                                            <span class="nav-text font-size-sm">Week</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-2 px-4 active" data-toggle="tab" href="#kt_charts_widget_2_chart_tab_3">
                                            <span class="nav-text font-size-sm">Day</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div id="kt_charts_widget_5_chart"></div>
                                </div>
                                <div class="col-4 d-flex flex-column">
                                    <!--begin::Engage Widget 2-->
                                    <div class="flex-grow-1 bg-danger p-8 rounded-xl flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + 0.5rem) bottom; background-size: auto 70%; background-image: url(assets/media/svg/humans/custom-3.svg)">
                                        <h4 class="text-inverse-danger mt-2 font-weight-bolder">User Confidence</h4>
                                        <p class="text-inverse-danger my-6">
                                            Boost marketing & sales<br />through product confidence.
                                        </p>
                                        <a href="#" class="btn btn-warning font-weight-bold py-2 px-6">Learn</a>
                                    </div>
                                    <!--end::Engage Widget 2-->
                                </div>
                            </div>
                        </div>
                        <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                    <!--end:: Charts Widget 5-->
                </div>
            </div>

            <!--end::Row-->

            <!--begin::Row-->
            <div class="row">
                <div class="col-xl-5">
                    <div class="card-stretch gutter-b">

                        <!--begin::Stats Widget 11-->
                        <div class="card card-custom gutter-b">

                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
								<span class="symbol  symbol-50 symbol-light-success mr-2">
									<span class="symbol-label">
										<span class="svg-icon svg-icon-xl svg-icon-success">

											<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
													<path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>

                                            <!--end::Svg Icon--></span> </span>
								</span>
                                    <div class="d-flex flex-column text-right">
                                        <span class="text-dark-75 font-weight-bolder font-size-h3">750$</span>
                                        <span class="text-muted font-weight-bold mt-2">Weekly Income</span>
                                    </div>
                                </div>
                                <div id="kt_stats_widget_11_chart" class="card-rounded-bottom" data-color="success" style="height: 150px"></div>
                            </div>

                            <!--end::Body-->
                        </div>

                        <!--begin::Stats Widget 10-->
                        <div class="card card-custom ">

                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
								<span class="symbol  symbol-50 symbol-light-info mr-2">
									<span class="symbol-label">
										<span class="svg-icon svg-icon-xl svg-icon-info">

											<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Cart3.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000" />
												</g>
											</svg>

                                            <!--end::Svg Icon--></span> </span>
								</span>
                                    <div class="d-flex flex-column text-right">
                                        <span class="text-dark-75 font-weight-bolder font-size-h3">+259</span>
                                        <span class="text-muted font-weight-bold mt-2">Sales Change</span>
                                    </div>
                                </div>
                                <div id="kt_stats_widget_10_chart" class="card-rounded-bottom" data-color="info" style="height: 150px"></div>
                            </div>

                            <!--end::Body-->
                        </div>

                        <!--end::Stats Widget 10-->
                    </div>
                </div>
                <div class="col-xl-7">


                    <!--begin::Base Table Widget 5-->
                    <div class="card card-custom card-stretch gutter-b">

                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">New Arrivals</span>
                                <span class="text-muted mt-3 font-weight-bold font-size-sm">More than 400+ new members</span>
                            </h3>
                            <div class="card-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-dark-75">
                                    <li class="nav-item">
                                        <a class="nav-link py-2 px-4" data-toggle="tab" href="#kt_tab_pane_4_1">Month</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-2 px-4" data-toggle="tab" href="#kt_tab_pane_4_2">Week</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-2 px-4 active" data-toggle="tab" href="#kt_tab_pane_4_3">Day</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-2 pb-0">

                            <!--begin::Table-->
                            <div class="table-responsive">
                                <table class="table table-borderless table-vertical-center">
                                    <thead>
                                    <tr>
                                        <th class="p-0" style="width: 50px"></th>
                                        <th class="p-0" style="min-width: 150px"></th>
                                        <th class="p-0" style="min-width: 140px"></th>
                                        <th class="p-0" style="min-width: 110px"></th>
                                        <th class="p-0" style="min-width: 50px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="py-5 pl-0">
                                            <div class="symbol symbol-50 symbol-light mr-2">
												<span class="symbol-label">
													<img src="/assets/media/svg/misc/006-plurk.svg" class="h-50 align-self-center" alt="" />
												</span>
                                            </div>
                                        </td>
                                        <td class="pl-0">
                                            <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Top Authors</a>
                                            <span class="text-muted font-weight-bold d-block">Successful Fellas</span>
                                        </td>
                                        <td class="text-right">
											<span class="text-muted font-weight-500">
												ReactJs, HTML
											</span>
                                        </td>
                                        <td class="text-right">
                                            <span class="label label-lg label-light-primary label-inline">Approved</span>
                                        </td>
                                        <td class="text-right pr-0">
                                            <a href="#" class="btn btn-icon btn-light btn-sm">
												<span class="svg-icon svg-icon-md svg-icon-success">

													<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<polygon points="0 0 24 0 24 24 0 24" />
															<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1" />
															<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) " />
														</g>
													</svg>

                                                    <!--end::Svg Icon--></span> </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0 py-5">
                                            <div class="symbol symbol-50 symbol-light mr-2">
												<span class="symbol-label">
													<img src="/assets/media/svg/misc/015-telegram.svg" class="h-50 align-self-center" alt="" />
												</span>
                                            </div>
                                        </td>
                                        <td class="pl-0">
                                            <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Popular Authors</a>
                                            <span class="text-muted font-weight-bold d-block">Most Successful</span>
                                        </td>
                                        <td class="text-right">
											<span class="text-muted font-weight-500">
												Python, MySQL
											</span>
                                        </td>
                                        <td class="text-right">
                                            <span class="label label-lg label-light-warning label-inline">In Progress</span>
                                        </td>
                                        <td class="text-right pr-0">
                                            <a href="#" class="btn btn-icon btn-light btn-sm">
												<span class="svg-icon svg-icon-md svg-icon-success">

													<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<polygon points="0 0 24 0 24 24 0 24" />
															<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1" />
															<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) " />
														</g>
													</svg>

                                                    <!--end::Svg Icon--></span> </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-5 pl-0">
                                            <div class="symbol symbol-50 symbol-light mr-2">
												<span class="symbol-label">
													<img src="/assets/media/svg/misc/003-puzzle.svg" class="h-50 align-self-center" alt="" />
												</span>
                                            </div>
                                        </td>
                                        <td class="pl-0">
                                            <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">New Users</a>
                                            <span class="text-muted font-weight-bold d-block">Awesome Users</span>
                                        </td>
                                        <td class="text-right">
											<span class="text-muted font-weight-500">
												Laravel,Metronic
											</span>
                                        </td>
                                        <td class="text-right">
                                            <span class="label label-lg label-light-success label-inline">Success</span>
                                        </td>
                                        <td class="text-right pr-0">
                                            <a href="#" class="btn btn-icon btn-light btn-sm">
												<span class="svg-icon svg-icon-md svg-icon-success">

													<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<polygon points="0 0 24 0 24 24 0 24" />
															<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1" />
															<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) " />
														</g>
													</svg>

                                                    <!--end::Svg Icon--></span> </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-5 pl-0">
                                            <div class="symbol symbol-50 symbol-light mr-2">
												<span class="symbol-label">
													<img src="/assets/media/svg/misc/005-bebo.svg" class="h-50 align-self-center" alt="" />
												</span>
                                            </div>
                                        </td>
                                        <td class="pl-0">
                                            <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Active Customers</a>
                                            <span class="text-muted font-weight-bold d-block">Best Customers</span>
                                        </td>
                                        <td class="text-right">
											<span class="text-muted font-weight-500">
												AngularJS, C#
											</span>
                                        </td>
                                        <td class="text-right">
                                            <span class="label label-lg label-light-danger label-inline">Rejected</span>
                                        </td>
                                        <td class="text-right pr-0">
                                            <a href="#" class="btn btn-icon btn-light btn-sm">
												<span class="svg-icon svg-icon-md svg-icon-success">

													<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<polygon points="0 0 24 0 24 24 0 24" />
															<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1" />
															<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) " />
														</g>
													</svg>

                                                    <!--end::Svg Icon--></span> </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-5 pl-0">
                                            <div class="symbol symbol-50 symbol-light mr-2">
												<span class="symbol-label">
													<img src="/assets/media/svg/misc/014-kickstarter.svg" class="h-50 align-self-center" alt="" />
												</span>
                                            </div>
                                        </td>
                                        <td class="pl-0">
                                            <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Bestseller Theme</a>
                                            <span class="text-muted font-weight-bold d-block">Amazing Templates</span>
                                        </td>
                                        <td class="text-right">
											<span class="text-muted font-weight-500">
												ReactJS, Ruby
											</span>
                                        </td>
                                        <td class="text-right">
                                            <span class="label label-lg label-light-warning label-inline">In Progress</span>
                                        </td>
                                        <td class="text-right pr-0">
                                            <a href="#" class="btn btn-icon btn-light btn-sm">
												<span class="svg-icon svg-icon-md svg-icon-success">

													<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<polygon points="0 0 24 0 24 24 0 24" />
															<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1" />
															<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) " />
														</g>
													</svg>

                                                    <!--end::Svg Icon--></span> </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!--end::Tablet-->
                        </div>

                        <!--end::Body-->
                    </div>

                    <!--end::Base Table Widget 5-->
                </div>
            </div>

            <!--end::Row-->

            <!--begin::Row-->
            <div class="row">
                <div class="col-xl-5">

                    <!--begin::List Widget 4-->
                    <div class="card card-custom card-stretch gutter-b">

                        <!--begin::Header-->
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bolder text-dark">Todo</h3>
                            <div class="card-toolbar">
                                <div class="dropdown dropdown-inline">
                                    <a href="#" class="btn btn-light btn-sm font-size-sm font-weight-bolder dropdown-toggle text-dark-75" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Create
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">


                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover">
                                            <li class="navi-header pb-1">
                                                <span class="text-primary text-uppercase font-weight-bold font-size-sm">Add new:</span>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-shopping-cart-1"></i></span>
                                                    <span class="navi-text">Order</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-calendar-8"></i></span>
                                                    <span class="navi-text">Event</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-graph-1"></i></span>
                                                    <span class="navi-text">Report</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-rocket-1"></i></span>
                                                    <span class="navi-text">Post</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-writing"></i></span>
                                                    <span class="navi-text">File</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!--end::Navigation-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-2">

                            <!--begin::Item-->
                            <div class="d-flex align-items-center">

                                <!--begin::Bullet-->
                                <span class="bullet bullet-bar bg-success align-self-stretch"></span>

                                <!--end::Bullet-->

                                <!--begin::Checkbox-->
                                <label class="checkbox checkbox-lg checkbox-light-success checkbox-single flex-shrink-0 m-0 mx-4">
                                    <input type="checkbox" name="select" value="1" />
                                    <span></span>
                                </label>

                                <!--end::Checkbox-->

                                <!--begin::Text-->
                                <div class="d-flex flex-column flex-grow-1">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">
                                        Create FireStone Logo
                                    </a>
                                    <span class="text-muted font-weight-bold">
									Due in 2 Days
								</span>
                                </div>

                                <!--end::Text-->

                                <!--begin::Dropdown-->
                                <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                    <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>
                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">

                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover">
                                            <li class="navi-header font-weight-bold py-4">
                                                <span class="font-size-lg">Choose Label:</span>
                                                <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right" title="Click to learn more..."></i>
                                            </li>
                                            <li class="navi-separator mb-3 opacity-70"></li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-success">Customer</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-danger">Partner</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-warning">Suplier</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-primary">Member</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-dark">Staff</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-separator mt-3 opacity-70"></li>
                                            <li class="navi-footer py-4">
                                                <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                    <i class="ki ki-plus icon-sm"></i>
                                                    Add new
                                                </a>
                                            </li>
                                        </ul>

                                        <!--end::Navigation-->

                                    </div>
                                </div>

                                <!--end::Dropdown-->
                            </div>

                            <!--end:Item-->

                            <!--begin::Item-->
                            <div class="d-flex align-items-center mt-10">

                                <!--begin::Bullet-->
                                <span class="bullet bullet-bar bg-primary align-self-stretch"></span>

                                <!--end::Bullet-->

                                <!--begin::Checkbox-->
                                <label class="checkbox checkbox-lg checkbox-light-primary checkbox-single flex-shrink-0 m-0 mx-4">
                                    <input type="checkbox" value="1" />
                                    <span></span>
                                </label>

                                <!--end::Checkbox-->

                                <!--begin::Text-->
                                <div class="d-flex flex-column flex-grow-1">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">
                                        Stakeholder Meeting
                                    </a>
                                    <span class="text-muted font-weight-bold">
									Due in 3 Days
								</span>
                                </div>

                                <!--end::Text-->

                                <!--begin::Dropdown-->
                                <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                    <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>
                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">

                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover">
                                            <li class="navi-header font-weight-bold py-4">
                                                <span class="font-size-lg">Choose Label:</span>
                                                <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right" title="Click to learn more..."></i>
                                            </li>
                                            <li class="navi-separator mb-3 opacity-70"></li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-success">Customer</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-danger">Partner</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-warning">Suplier</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-primary">Member</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-dark">Staff</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-separator mt-3 opacity-70"></li>
                                            <li class="navi-footer py-4">
                                                <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                    <i class="ki ki-plus icon-sm"></i>
                                                    Add new
                                                </a>
                                            </li>
                                        </ul>

                                        <!--end::Navigation-->

                                    </div>
                                </div>

                                <!--end::Dropdown-->
                            </div>

                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex align-items-center mt-10">

                                <!--begin::Bullet-->
                                <span class="bullet bullet-bar bg-warning align-self-stretch"></span>

                                <!--end::Bullet-->

                                <!--begin::Checkbox-->
                                <label class="checkbox checkbox-lg checkbox-light-warning checkbox-single flex-shrink-0 m-0 mx-4">
                                    <input type="checkbox" value="1" />
                                    <span></span>
                                </label>

                                <!--end::Checkbox-->

                                <!--begin::Text-->
                                <div class="d-flex flex-column flex-grow-1">
                                    <a href="#" class="text-dark-75 text-hover-primary font-size-sm font-weight-bold font-size-lg mb-1">
                                        Scoping & Estimations
                                    </a>
                                    <span class="text-muted font-weight-bold">
									Due in 5 Days
								</span>
                                </div>

                                <!--end::Text-->

                                <!--begin: Dropdown-->
                                <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                    <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>
                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">

                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover">
                                            <li class="navi-header font-weight-bold py-4">
                                                <span class="font-size-lg">Choose Label:</span>
                                                <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right" title="Click to learn more..."></i>
                                            </li>
                                            <li class="navi-separator mb-3 opacity-70"></li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-success">Customer</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-danger">Partner</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-warning">Suplier</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-primary">Member</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-dark">Staff</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-separator mt-3 opacity-70"></li>
                                            <li class="navi-footer py-4">
                                                <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                    <i class="ki ki-plus icon-sm"></i>
                                                    Add new
                                                </a>
                                            </li>
                                        </ul>

                                        <!--end::Navigation-->
                                    </div>
                                </div>

                                <!--end::Dropdown-->
                            </div>

                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex align-items-center mt-10">

                                <!--begin::Bullet-->
                                <span class="bullet bullet-bar bg-info align-self-stretch"></span>

                                <!--end::Bullet-->

                                <!--begin::Checkbox-->
                                <label class="checkbox checkbox-lg checkbox-light-info checkbox-single flex-shrink-0 m-0 mx-4">
                                    <input type="checkbox" value="1" />
                                    <span></span>
                                </label>

                                <!--end::Checkbox-->

                                <!--begin::Text-->
                                <div class="d-flex flex-column flex-grow-1">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">
                                        Sprint Showcase
                                    </a>
                                    <span class="text-muted font-weight-bold">
									Due in 1 Day
								</span>
                                </div>

                                <!--end::Text-->

                                <!--begin::Dropdown-->
                                <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                    <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>
                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">

                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover py-5">
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-drop"></i></span>
                                                    <span class="navi-text">New Group</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-list-3"></i></span>
                                                    <span class="navi-text">Contacts</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-rocket-1"></i></span>
                                                    <span class="navi-text">Groups</span>
                                                    <span class="navi-link-badge">
													<span class="label label-light-primary label-inline font-weight-bold">new</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-bell-2"></i></span>
                                                    <span class="navi-text">Calls</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-gear"></i></span>
                                                    <span class="navi-text">Settings</span>
                                                </a>
                                            </li>
                                            <li class="navi-separator my-3"></li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-magnifier-tool"></i></span>
                                                    <span class="navi-text">Help</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-bell-2"></i></span>
                                                    <span class="navi-text">Privacy</span>
                                                    <span class="navi-link-badge">
													<span class="label label-light-danger label-rounded font-weight-bold">5</span>
												</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!--end::Navigation-->
                                    </div>
                                </div>

                                <!--end::Dropdown-->
                            </div>

                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex align-items-center mt-10">

                                <!--begin::Bullet-->
                                <span class="bullet bullet-bar bg-danger align-self-stretch"></span>

                                <!--end::Bullet-->

                                <!--begin::Checkbox-->
                                <label class="checkbox checkbox-lg checkbox-light-danger checkbox-single flex-shrink-0 m-0 mx-4">
                                    <input type="checkbox" value="1" />
                                    <span></span>
                                </label>

                                <!--end::Checkbox:-->

                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1">
                                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">
                                        Project Retro
                                    </a>
                                    <span class="text-muted font-weight-bold">
									Due in 12 Days
								</span>
                                </div>

                                <!--end::Text-->

                                <!--begin: Dropdown-->
                                <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                    <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>
                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">

                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover">
                                            <li class="navi-header font-weight-bold py-4">
                                                <span class="font-size-lg">Choose Label:</span>
                                                <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right" title="Click to learn more..."></i>
                                            </li>
                                            <li class="navi-separator mb-3 opacity-70"></li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-success">Customer</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-danger">Partner</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-warning">Suplier</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-primary">Member</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
												<span class="navi-text">
													<span class="label label-xl label-inline label-light-dark">Staff</span>
												</span>
                                                </a>
                                            </li>
                                            <li class="navi-separator mt-3 opacity-70"></li>
                                            <li class="navi-footer py-4">
                                                <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                    <i class="ki ki-plus icon-sm"></i>
                                                    Add new
                                                </a>
                                            </li>
                                        </ul>

                                        <!--end::Navigation-->
                                    </div>
                                </div>

                                <!--end::Dropdown-->
                            </div>

                            <!--end::Item-->
                        </div>

                        <!--end::Body-->
                    </div>

                    <!--end:List Widget 4-->

                </div>
                <div class="col-xl-7">

                    <!--begin::List Widget 12-->
                    <div class="card card-custom card-stretch">

                        <!--begin::Header-->
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bolder text-dark">Latest Media</h3>
                            <div class="card-toolbar">
                                <div class="dropdown dropdown-inline">
                                    <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-ver"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">


                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover">
                                            <li class="navi-header pb-1">
                                                <span class="text-primary text-uppercase font-weight-bold font-size-sm">Add new:</span>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-shopping-cart-1"></i></span>
                                                    <span class="navi-text">Order</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-calendar-8"></i></span>
                                                    <span class="navi-text">Event</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-graph-1"></i></span>
                                                    <span class="navi-text">Report</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-rocket-1"></i></span>
                                                    <span class="navi-text">Post</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-icon"><i class="flaticon2-writing"></i></span>
                                                    <span class="navi-text">File</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!--end::Navigation-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-2">

                            <!--begin::Item-->
                            <div class="d-flex flex-wrap align-items-center mb-10">

                                <!--begin::Symbol-->
                                <div class="symbol symbol-60 symbol-2by3 flex-shrink-0">
                                    <div class="symbol-label" style="background-image: url('/assets/media/stock-600x400/img-20.jpg')"></div>
                                </div>

                                <!--end::Symbol-->

                                <!--begin::Title-->
                                <div class="d-flex flex-column ml-4 flex-grow-1 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Cup & Green</a>
                                    <span class="text-muted font-weight-bold">Size: 87KB</span>
                                </div>

                                <!--end::Title-->

                                <!--begin::btn-->
                                <span class="label label-lg label-light-primary label-inline mt-lg-0 mb-lg-0 my-2 font-weight-bold py-4">Approved</span>

                                <!--end::Btn-->
                            </div>

                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex flex-wrap align-items-center mb-10">

                                <!--begin::Symbol-->
                                <div class="symbol symbol-60 symbol-2by3 flex-shrink-0">
                                    <div class="symbol-label" style="background-image: url('assets/media/stock-600x400/img-19.jpg')"></div>
                                </div>

                                <!--end::Symbol-->

                                <!--begin::Title-->
                                <div class="d-flex flex-column ml-4 flex-grow-1 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Yellow Background</a>
                                    <span class="text-muted font-weight-bold">Size: 1.2MB</span>
                                </div>

                                <!--end::Title-->

                                <!--begin::btn-->
                                <span class="label label-lg label-light-warning label-inline mt-lg-0 mb-lg-0 my-2 font-weight-bold py-4">In Progress</span>

                                <!--end::Btn-->
                            </div>

                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex flex-wrap align-items-center mb-10">

                                <!--begin::Symbol-->
                                <div class="symbol symbol-60 symbol-2by3 flex-shrink-0">
                                    <div class="symbol-label" style="background-image: url('assets/media/stock-600x400/img-25.jpg')"></div>
                                </div>

                                <!--end::Symbol-->

                                <!--begin::Title-->
                                <div class="d-flex flex-column ml-4 flex-grow-1 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Nike & Blue</a>
                                    <span class="text-muted font-weight-bold">Size: 87KB</span>
                                </div>

                                <!--end::Title-->

                                <!--begin::btn-->
                                <span class="label label-lg label-light-success label-inline mt-lg-0 mb-lg-0 my-2 font-weight-bold py-4">Success</span>

                                <!--end::btn-->
                            </div>

                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex flex-wrap align-items-center">

                                <!--begin::Symbol-->
                                <div class="symbol symbol-60 symbol-2by3 flex-shrink-0">
                                    <div class="symbol-label" style="background-image: url('assets/media/stock-600x400/img-24.jpg')"></div>
                                </div>

                                <!--end::Symbol-->

                                <!--begin::Title-->
                                <div class="d-flex flex-column ml-4 flex-grow-1 mr-2">
                                    <a href="#" class="text-dark-75 font-weight-bold font-size-lg text-hover-primary mb-1">Red Boots</a>
                                    <span class="text-muted font-weight-bold">Size: 345KB</span>
                                </div>

                                <!--end::Title-->

                                <!--begin::btn-->
                                <span class="label label-lg label-light-danger label-inline mt-lg-0 mb-lg-0 my-2 font-weight-bold py-4">Rejected</span>

                                <!--end::btn-->
                            </div>

                            <!--end::Item-->
                        </div>

                        <!--end::Body-->
                    </div>

                    <!--end::List Widget 12-->
                </div>
            </div>

            <!--end::Row-->

            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Entry-->
@endsection
