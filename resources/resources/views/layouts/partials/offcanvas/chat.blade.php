
<!--begin::Chat Panel-->
<div class="modal modal-sticky modal-sticky-bottom-right" id="kt_chat_modal" role="dialog" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!--begin::Card-->
            <div class="card card-custom " id="support-form">

                <!--begin::Header-->
                <div class="card-header align-items-center px-4 py-3">
                    <div class="text-left flex-grow-1">

                        <!--begin::Dropdown Menu-->
                        <div class="dropdown dropdown-inline">
								<span class="svg-icon svg-icon-lg">

									<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24" />
											<path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
											<path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
										</g>
									</svg>

                                    <!--end::Svg Icon--></span>
                        </div>

                        <!--end::Dropdown Menu-->
                    </div>
                    <div class="text-center flex-grow-1">
                        <div class="text-dark-75 font-weight-bold font-size-h5">İK METRİK MÜŞTERİ HİZMETLERİ</div>
                    </div>
                    <div class="text-right flex-grow-1">
                        <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-dismiss="modal">
                            <i class="ki ki-close icon-1x"></i>
                        </button>
                    </div>
                </div>

                <!--end::Header-->

                <!--begin::Body-->
                {!! Form::open(['route' => 'crm-support-ajax-store', 'novalidate'=>'novalidate', 'class' => 'form', 'id' => 'support_form']) !!}
                <div class="card-body">
                    <div class="form-group">
                        <div id="result" style="display: none;"></div>
                    </div>

                    <div class="form-group">
                        <label for="contact_by">İletişim Kurmak İstediğiniz Birimi Seçin</label>
                        <select class="form-control form-control-lg" id="contact_by" name="contact_by" required="required">
                            <option value="DESTEK">TEKNİK DESTEK BİRİMİ</option>
                            <option value="SATIS">SATIŞ VE PAZARLAMA BİRİMİ</option>
                            <option value="MUHASEBE">MUHASEBE VE FİNANS BİRİMİ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">İsim Soyisim</label>
                        <input class="form-control form-control-lg" type="text" value="" id="name" name="name" required="required">
                    </div>
                    <div class="form-group">
                        <label for="email">E-posta Adresiniz</label>
                        <input class="form-control form-control-lg" type="email" value="" id="email" name="email" required="required">
                    </div>

                    <div class="form-group">
                        <label for="email">Telefon Numaranız</label>
                        <input class="form-control form-control-lg" type="text" value="" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="email">Mesajınız</label>
                        <textarea class="form-control p-0" rows="2"  id="message" required="required" name="message"></textarea>
                    </div>

                </div>

                <!--end::Body-->

                <!--begin::Footer-->
                <div class="card-footer align-items-center">

                    <!--begin::Compose-->

                    <div class="d-flex align-items-center justify-content-between mt-5">
                        <div class="mr-3">
                            <a href="#" class="btn btn-clean btn-icon btn-md mr-1"><i class="flaticon2-photograph icon-lg"></i></a>
                            <a href="#" class="btn btn-clean btn-icon btn-md"><i class="flaticon2-photo-camera  icon-lg"></i></a>
                        </div>
                        <div>

                            <button class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6" type="button" id="btnSubmit">
                                <span id="sendBtnLabel">GÖNDER</span>
                                <span id="sendBtnSpinner" style="display:none;">
                                    <span  class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                KAYIT OLUŞTURULUYOR...
                                </span>

                            </button>
                        </div>
                    </div>

                    <!--begin::Compose-->
                </div>
            {!! Form::close() !!}
                <!--end::Footer-->
            </div>

            <!--end::Card-->
        </div>
    </div>
</div>
<div class="modal modal-sticky modal-sticky-bottom-right" id="live_support_modal" role="dialog" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!--begin::Card-->
            <div class="card card-custom " id="live-support-form">

                <!--begin::Header-->
                <div class="card-header align-items-center px-4 py-3">
                    <div class="text-left flex-grow-1">

                        <!--begin::Dropdown Menu-->
                        <div class="dropdown dropdown-inline">
								<span class="svg-icon svg-icon-lg">

									<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24" />
											<path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
											<path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
										</g>
									</svg>

                                    <!--end::Svg Icon--></span>
                        </div>

                        <!--end::Dropdown Menu-->
                    </div>
                    <div class="text-center flex-grow-1">
                        <div class="text-dark-75 font-weight-bold font-size-h5">İK METRİK MÜŞTERİ HİZMETLERİ</div>
                    </div>
                    <div class="text-right flex-grow-1">
                        <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-dismiss="modal">
                            <i class="ki ki-close icon-1x"></i>
                        </button>
                    </div>
                </div>

                <!--end::Header-->

                <!--begin::Body-->

                <div class="card-body" id="kt_chat_aside">
                    <!--begin::Scroll-->
                    <div class="scroll scroll-pull" data-height="375" data-mobile-height="300">
                        <!--begin::Messages aaa-->
                        <div id="messages">
                        </div>
                        <!--end::Messages-->
                    </div>
                    <!--end::Scroll-->

                </div>

                <!--end::Body-->

                <!--begin::Footer-->
                <div class="card-footer align-items-center">

                    <div class="input-text">
                        <input type="text" name="message" class="form-control border-1 p-3 submit" placeholder="Type a message">

                        <div class="d-flex align-items-center justify-content-between mt-5">
                            <div>
                                <button type="button" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send2 py-2 px-6">Gönder</button>
                            </div>
                        </div>
                    </div>
                </div>

            <!--end::Footer-->
            </div>

            <!--end::Card-->
        </div>
    </div>
</div>
@push('scripts')

@endpush
