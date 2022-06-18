
<!--begin::Header-->
<div id="kt_header" class="header  header-fixed ">

    <!--begin::Header Wrapper-->
    <div class="header-wrapper rounded-top-xl d-flex flex-grow-1 align-items-center">

        <!--begin::Container-->
        <div class=" container-fluid  d-flex align-items-center justify-content-end justify-content-lg-between flex-wrap">

            <!--begin::Menu Wrapper-->
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">

                <!--begin::Menu-->
                <div id="kt_header_menu" class="header-menu header-menu-mobile  header-menu-layout-default ">

                    <!--begin::Nav-->
                    <ul class="menu-nav ">
                        <li class="menu-item" data-menu-toggle="click" aria-haspopup="true"><a href="{{ route('home') }}" class="menu-link menu-toggle"><span class="menu-text"><img src="/assets/media/logos/logo.png"></span></a>

                        <li class="menu-item  {!! Request::is('/', 'home') ? 'menu-item-here' : null !!}  menu-item-rel" data-menu-toggle="click" aria-haspopup="true"><a href="{{ route('home') }}" class="menu-link "><span class="menu-text">ANASAYFA</span></a>
                        </li>
                        <li class="menu-item  {!! Request::is('permissions', 'permissions/*','crm_supports', 'crm_supports/*',  'company_assignments', 'company_assignments/*', 'roles', 'roles/*', 'users', 'users/*', 'companies', 'companies/*', 'sectors', 'sectors/*', 'departments', 'departments/*', 'jobs', 'jobs/*', 'work_titles', 'work_titles/*', 'sgk_companies', 'sgk_companies/*') ? 'menu-item-here' : null !!} menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-text">TANIMLAMALAR</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                            @hasanyrole('Admin|Owner|Teşvik|Metrik')
                            <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                <ul class="menu-subnav">

                                    @role('Admin')
                                    <li class="menu-item {!! Request::is('companies', 'companies/*') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                        <a href="{{ route('companies.index') }}" class="menu-link ">
                                            <span class="svg-icon menu-icon">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>
                                            </span>
                                            <span class="menu-text">Firma İşlemleri</span>
                                        </a>
                                    </li>
                                    @endrole

                                    <li class="menu-item {!! Request::is('sgk_companies', 'sgk_companies/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a  href="{{ route('sgk_companies.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                </span><span class="menu-text">SGK Firmaları</span></a>
                                    </li>
                                    @hasanyrole('Admin|Owner')

                                    <li class="menu-item {!! Request::is('users', 'users/*') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                        <a  href="{{ route('users.index') }}" class="menu-link ">
                                            <span class="svg-icon menu-icon">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>
                                            </span>
                                            <span class="menu-text">Kullanıcı İşlemleri</span>
                                        </a>
                                    </li>
                                    @endhasanyrole
                                    <li class="menu-item {!! Request::is('company_assignments', 'company_assignments/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a  href="{{ route('company_assignments.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                </span><span class="menu-text">Firma Atamaları</span></a>
                                    </li>


                                    @role('Admin')
                                    <li class="menu-item {!! Request::is('permissions', 'permissions/*') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                        <a  href="{{ route('permissions.index') }}" class="menu-link ">
                                            <span class="svg-icon menu-icon">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>
                                            </span>
                                            <span class="menu-text">Yetkiler</span>
                                        </a>
                                    </li>
                                    <li class="menu-item {!! Request::is('roles', 'roles/*') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                        <a  href="{{ route('roles.index') }}" class="menu-link ">
                                            <span class="svg-icon menu-icon">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>
                                            </span>
                                            <span class="menu-text">Roller</span>
                                        </a>
                                    </li>

                                    <li class="menu-item {!! Request::is('packets', 'packets/*') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                        <a  href="{{ route('packets.index') }}" class="menu-link ">
                                            <span class="svg-icon menu-icon">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>
                                            </span>
                                            <span class="menu-text">Paket İşlemleri</span>
                                        </a>
                                    </li>
                                    <li class="menu-item {!! Request::is('sectors', 'sectors/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a  href="{{ route('sectors.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                </span><span class="menu-text">Sektör İşlemleri</span></a>
                                    </li>
                                    <li class="menu-item {!! Request::is('egns', 'egns/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a  href="{{ route('egns.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                </span><span class="menu-text">Eksik Gün Nedenleri</span></a>
                                    </li>

                                    <li class="menu-item {!! Request::is('work_titles', 'work_titles/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a  href="{{ route('work_titles.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                </span><span class="menu-text">İş Ünvanı İşlemleri</span></a>
                                    </li>
                                    <li class="menu-item {!! Request::is('jobs', 'jobs/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a  href="{{ route('jobs.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                </span><span class="menu-text">Meslek İşlemleri</span></a>
                                    </li>
                                    <li class="menu-item {!! Request::is('departments', 'departments/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a  href="{{ route('departments.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                </span><span class="menu-text">Departman İşlemleri</span></a>
                                    </li>

                                    <li class="menu-item {!! Request::is('crm_supports', 'crm_supports/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a  href="{{ route('crm_supports.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                </span><span class="menu-text">Destek Talepleri</span></a>
                                    </li>
                                    @endrole


                                </ul>
                            </div>
                            @endhasanyrole
                        </li>
                        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Metrik') || Auth::user()->modulePermit(2))
                            <li class="menu-item  {!! Request::is('declarations/incentives/metrik-start', 'declarations/incentives/metrik-start/*','declarations/incentives/is-kazasi', 'declarations/incentives/is-kazasi/*',  'declarations/incentives/calisan-viziteleri', 'declarations/incentives/calisan-viziteleri/*', 'declarations/incentives/giris-cikis-bildirgesi-start', 'declarations/incentives/giris-cikis-bildirgesi-start/*') ? 'menu-item-here' : null !!} menu-item-submenu menu-item-rel"
                                data-menu-toggle="click" aria-haspopup="true">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <span class="menu-text">METRİK TANIMLAMALARI</span>
                                    <span class="menu-desc"></span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {!! Request::is('declarations/incentives/metrik-start') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                            <a href="{{ route('declarations.incentives.metrik') }}" class="menu-link ">
                                                <span class="svg-icon menu-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                                        </g>
												    </svg>
                                                </span>
                                                <span class="menu-text">Tahakkuk Listeleri</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {!! Request::is('declarations/incentives/is-kazasi') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                            <a href="{{ route('declarations.incentives.is-kazasi')}}" class="menu-link ">
                                                <span class="svg-icon menu-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                                        </g>
												    </svg>
                                                </span>
                                                <span class="menu-text">İş Kazaları</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {!! Request::is('declarations/incentives/calisan-viziteleri') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                            <a href="{{ route('declarations.incentives.calisan-viziteleri') }}" class="menu-link ">
                                                <span class="svg-icon menu-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                                        </g>
												    </svg>
                                                </span>
                                                <span class="menu-text">Çalışan Viziteleri</span>
                                            </a>
                                        </li>
                                        <li class="menu-item {!! Request::is('declarations/incentives/giris-cikis-bildirgesi-start') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                            <a href="{{ route('declarations.incentives.giris-cikis-bildirgesi-start') }}" class="menu-link ">
                                                <span class="svg-icon menu-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                                        </g>
												    </svg>
                                                </span>
                                                <span class="menu-text">Giriş Çıkış Bildirgesi</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Metrik') || Auth::user()->modulePermit(2))
                            <li class="menu-item  {!! Request::is('sub-metrics/*', 'metrics/EPRQ8kqzNdb0V1BoxdYv', 'metrics/EPRQ8kqzNdb0V1BoxdYv/*','metrics/36oVw5d8ZP8Z4pP21zra', 'metrics/36oVw5d8ZP8Z4pP21zra/*',  'metrics/2VMkL1djOYoZJrP5mn4X', 'metrics/2VMkL1djOYoZJrP5mn4X/*') ? 'menu-item-here' : null !!} menu-item-submenu menu-item-rel"
                                data-menu-toggle="click" aria-haspopup="true">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <span class="menu-text">METRİKLER</span>
                                    <span class="menu-desc"></span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                    <ul class="menu-subnav">
                                        @foreach(getMetricGroups() as $metricGroup)
                                            <li class="menu-item {!! Request::is('metrics/'.createHashId($metricGroup->id), 'metrics/'.createHashId($metricGroup->id).'/*') ? 'menu-item-here' : null !!}" aria-haspopup="true">
                                                <a target="_blank" href="{{ route('metrics', createHashId($metricGroup->id)) }}" class="menu-link ">
                                            <span class="svg-icon menu-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                       fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20
    C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20
    L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3
    12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21
    C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                                              fill="#000000" />
                                                        <rect fill="#000000" opacity="0.3"
                                                              transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858"
                                                              width="3" height="18" rx="1" />
                                                    </g>
                                                </svg>
                                            </span>
                                                    <span class="menu-text">{{ $metricGroup->name }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>

                        @endif
                        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Teşvik')|| Auth::user()->modulePermit(1))
                            <li class="menu-item  {!! Request::is('declarations/incentives/incentives-start', 'declarations/incentives/incentives-start/*', 'declarations/incentives/incentives-start-v2', 'declarations/incentives/incentives-start-v2/*', 'declarations/incentives/gain-start', 'declarations/incentives/gain-start/*', 'declarations/incentives/current_incentives', 'declarations/incentives/current_incentives/*', 'declarations/incentives/gain_incentives', 'declarations/incentives/gain_incentives/*', 'declarations/incentives/pdf-incentive-reports', 'declarations/incentives/pdf-incentive-reports/*') ? 'menu-item-here' : null !!} menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="menu-link menu-toggle"><span class="menu-text">TEŞVİK İŞLEMLERİ</span><span class="menu-desc"></span><i class="menu-arrow"></i></a>
                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                    <ul class="menu-subnav">

                                        @hasanyrole('Admin|Teşvik')
                                        <li class="menu-item {!! Request::is('declarations/incentives/incentives-start') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a href="{{ route('declarations.incentives.main') }}" class="menu-link "><span class="svg-icon menu-icon">

												<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                    <!--end::Svg Icon--></span><span class="menu-text">Excel İle Teşvikleri Çek</span></a>
                                        </li>
                                        <li class="menu-item {!! Request::is('declarations/incentives/incentives-start-v2') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a href="{{ route('declarations.incentives.main-v2') }}" class="menu-link "><span class="svg-icon menu-icon">

												<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                    <!--end::Svg Icon--></span><span class="menu-text">V2 İle Teşvikleri Çek</span></a>
                                        </li>
                                        <li class="menu-item {!! Request::is('declarations/incentives/gain-start') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a href="{{ route('declarations.incentives.gain-start') }}" class="menu-link "><span class="svg-icon menu-icon">

												<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                    <!--end::Svg Icon--></span><span class="menu-text">Hakedişleri Çek</span></a>
                                        </li>
                                        <li class="menu-item {!! Request::is('declarations/incentives/current_incentives') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a href="{{ route('declarations.incentives.current_incentives') }}" class="menu-link "><span class="svg-icon menu-icon">

												<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                    <!--end::Svg Icon--></span><span class="menu-text">Cari Dönem Teşvikleri</span></a>
                                        </li>
                                        <li class="menu-item {!! Request::is('declarations/incentives/gain_incentives') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a href="{{ route('declarations.incentives.gain_incentives') }}" class="menu-link "><span class="svg-icon menu-icon">

												<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                    <!--end::Svg Icon--></span><span class="menu-text">Hakedişler</span></a>
                                        </li>

                                        <li class="menu-item {!! Request::is('incentive_reports', 'incentive_reports/*') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a href="{{ route('incentive_reports.index') }}" class="menu-link "><span class="svg-icon menu-icon">

												<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
														<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
													</g>
												</svg>

                                                    <!--end::Svg Icon--></span><span class="menu-text">Teşvik Raporları</span></a>
                                        </li>
                                        @endhasanyrole
                                        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Teşvik') || Auth::user()->modulePermit(1))
                                            <li class="menu-item {!! Request::is('declarations/incentives/pdf-incentive-reports') ? 'menu-item-here' : null !!}" aria-haspopup="true"><a href="{{ route('declarations.incentives.pdf-incentive-reports') }}" class="menu-link ">
                                            <span class="svg-icon menu-icon">
                                               <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                                    </g>
                                                </svg>
                                            </span>
                                                    <span class="menu-text">Teşvik Raporları</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li class="menu-item  {!! Request::is('faq', 'faq/*') ? 'menu-item-here' : null !!}  menu-item-rel" data-menu-toggle="click" aria-haspopup="true"><a href="{{ route('faq') }}" class="menu-link "><span class="menu-text">S.S.S</span></a>
                        </li>

                    </ul>
                    <!--end::Nav-->
                </div>

                <!--end::Menu-->
            </div>

            <!--end::Menu Wrapper-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center py-3 py-lg-2">


            </div>

            <!--end::Toolbar-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Header Wrapper-->
</div>

<!--end::Header-->
