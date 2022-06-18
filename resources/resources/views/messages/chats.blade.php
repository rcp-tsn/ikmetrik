@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <!--begin::Chat-->
            <div class="d-flex flex-row">
                <!--begin::Aside-->
                <div class="flex-row-auto offcanvas-mobile w-350px w-xl-400px" id="kt_chat_aside">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin:Users-->
                            <div class="mt-7 scroll scroll-pull users">
                            @foreach($users as $user)
                                <!--begin:User-->
                                    <div class="d-flex align-items-center justify-content-between mb-5 user" id="{{ $user->id }}">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50 mr-3">
                                                <img alt="Pic" src="{{ $user->picture }}" />
                                            </div>
                                            <div class="d-flex flex-column">
                                                <p class="text-dark-75 text-hover-primary font-weight-bold font-size-lg name">{{ $user->name }}</p>
                                                <p class="text-muted font-weight-bold font-size-sm email">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="text-muted font-weight-bold font-size-sm">35 mins</span>
                                            @if($user->unread)
                                                <span class="label label-sm label-success pending">{{ $user->unread }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!--end:User-->
                                @endforeach
                            </div>
                            <!--end:Users-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Aside-->
                <!--begin::Content-->
                <div class="flex-row-fluid ml-lg-8" id="kt_chat_content">
                    <!--begin::Card-->
                    <div class="card card-custom message-wrapper">
                        <!--begin::Header-->
                        <div class="card-header align-items-center px-4 py-3">
                            <div class="text-left flex-grow-1">
                                <!--begin::Aside Mobile Toggle-->
                                <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md d-lg-none" id="kt_app_chat_toggle">
														<span class="svg-icon svg-icon-lg">
															<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Communication/Adress-book2.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect x="0" y="0" width="24" height="24" />
																	<path d="M18,2 L20,2 C21.6568542,2 23,3.34314575 23,5 L23,19 C23,20.6568542 21.6568542,22 20,22 L18,22 L18,2 Z" fill="#000000" opacity="0.3" />
																	<path d="M5,2 L17,2 C18.6568542,2 20,3.34314575 20,5 L20,19 C20,20.6568542 18.6568542,22 17,22 L5,22 C4.44771525,22 4,21.5522847 4,21 L4,3 C4,2.44771525 4.44771525,2 5,2 Z M12,11 C13.1045695,11 14,10.1045695 14,9 C14,7.8954305 13.1045695,7 12,7 C10.8954305,7 10,7.8954305 10,9 C10,10.1045695 10.8954305,11 12,11 Z M7.00036205,16.4995035 C6.98863236,16.6619875 7.26484009,17 7.4041679,17 C11.463736,17 14.5228466,17 16.5815,17 C16.9988413,17 17.0053266,16.6221713 16.9988413,16.5 C16.8360465,13.4332455 14.6506758,12 11.9907452,12 C9.36772908,12 7.21569918,13.5165724 7.00036205,16.4995035 Z" fill="#000000" />
																</g>
															</svg>
                                                            <!--end::Svg Icon-->
														</span>
                                </button>
                            </div>
                            <div class="text-center flex-grow-1">
                                <div class="text-dark-75 font-weight-bold font-size-h5">Matt Pears</div>
                                <div>
                                    <span class="label label-sm label-dot label-success"></span>
                                    <span class="font-weight-bold text-muted font-size-sm">Active</span>
                                </div>
                            </div>
                            <div class="text-right flex-grow-1">
                                <!--begin::Dropdown Menu-->
                                <div class="dropdown dropdown-inline">
                                    <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<span class="svg-icon svg-icon-lg">
																<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Communication/Add-user.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<polygon points="0 0 24 0 24 24 0 24" />
																		<path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																		<path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
																	</g>
																</svg>
                                                                <!--end::Svg Icon-->
															</span>
                                    </button>
                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-md">
                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover py-5">
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
																		<span class="navi-icon">
																			<i class="flaticon2-drop"></i>
																		</span>
                                                    <span class="navi-text">New Group</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
																		<span class="navi-icon">
																			<i class="flaticon2-list-3"></i>
																		</span>
                                                    <span class="navi-text">Contacts</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
																		<span class="navi-icon">
																			<i class="flaticon2-rocket-1"></i>
																		</span>
                                                    <span class="navi-text">Groups</span>
                                                    <span class="navi-link-badge">
																			<span class="label label-light-primary label-inline font-weight-bold">new</span>
																		</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
																		<span class="navi-icon">
																			<i class="flaticon2-bell-2"></i>
																		</span>
                                                    <span class="navi-text">Calls</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
																		<span class="navi-icon">
																			<i class="flaticon2-gear"></i>
																		</span>
                                                    <span class="navi-text">Settings</span>
                                                </a>
                                            </li>
                                            <li class="navi-separator my-3"></li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
																		<span class="navi-icon">
																			<i class="flaticon2-magnifier-tool"></i>
																		</span>
                                                    <span class="navi-text">Help</span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
																		<span class="navi-icon">
																			<i class="flaticon2-bell-2"></i>
																		</span>
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
                                <!--end::Dropdown Menu-->
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Scroll-->
                            <div class="scroll scroll-pull" data-mobile-height="350">
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
                            <!--begin::Compose-->
                            <div class="input-text">
                                <input type="text" name="message" class="form-control border-1 p-3 submit" placeholder="Type a message">

                                <div class="d-flex align-items-center justify-content-between mt-5">
                                    <div>
                                        <button type="button" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send2 py-2 px-6">Gönder</button>
                                    </div>
                                </div>
                            </div>
                            <!--begin::Compose-->
                        </div>
                        <!--end::Footer-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Chat-->
        </div>
    </div>

@stop

@push('scripts')
<script>
    var receiver_id = '';
    var my_id = '{{ Auth::user()->id }}';

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('23d21e64435bd25dd63d', {
            cluster: 'eu',
            forceTLS: true
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            //alert(JSON.stringify(data));
            if (my_id == data.from) {
                $('#' + data.to).click();
            } else if (my_id == data.to) {
                if (receiver_id == data.from) {
                    $('#' + data.from).click();
                } else {
                    var pending = parseInt($('#' + data.from).find('.pending').html());
                    if (pending) {
                        $('#' + data.from).find('.pending').html(pending + 1);
                    } else {
                        $('#' + data.from).append('<span class="label label-sm label-success pending">1</span>');
                    }
                }
            }
        });
        $('.user').click(function () {
            $('.user').removeClass('active');
            $(this).addClass('active');
            $(this).find('.pending').remove();
            receiver_id = $(this).attr('id');
            $.ajax({
                type: 'get',
                url:'get-message/'+ receiver_id,
                data: "",
                cache: false,
                success: function(data) {
                    //alert(data);
                    $('#messages').html(data);
                }
            })
        });
        $(document).on('keyup', '.input-text input', function (e) {
            var message = $(this).val();

            if (e.keyCode == 13 && message != '' && receiver_id != '') {
                $(this).val('');
                var datastr = "receiver_id=" + receiver_id + "&message=" + message;
                $.ajax({
                    type: "post",
                    url: "set-messages",
                    data : datastr,
                    cache: false,
                    success: function(data) {

                    },
                    error: function (jqXHR, status, err) {

                    },
                    complete: function () {

                    }
                })
            }
        });

        $('.chat-send2').click(function () {
            var message = $('.input-text input').val();

            if (message != '') {
                $('.input-text input').val('');
                var datastr = "receiver_id=" + receiver_id + "&message=" + message;
                $.ajax({
                    type: "post",
                    url: "set-messages",
                    data : datastr,
                    cache: false,
                    success: function(data) {

                    },
                    error: function (jqXHR, status, err) {

                    },
                    complete: function () {

                    }
                })
            }
        });

    });



    "use strict";

    // Class definition
    var KTAppChat = function () {
        var _chatAsideEl;
        var _chatAsideOffcanvasObj;
        var _chatContentEl;

        // Private functions
        var _initAside = function () {
            // Mobile offcanvas for mobile mode
            _chatAsideOffcanvasObj = new KTOffcanvas(_chatAsideEl, {
                overlay: true,
                baseClass: 'offcanvas-mobile',
                //closeBy: 'kt_chat_aside_close',
                toggleBy: 'kt_app_chat_toggle'
            });

            // User listing
            var cardScrollEl = KTUtil.find(_chatAsideEl, '.scroll');
            var cardBodyEl = KTUtil.find(_chatAsideEl, '.card-body');
            var searchEl = KTUtil.find(_chatAsideEl, '.input-group');

            if (cardScrollEl) {
                // Initialize perfect scrollbar(see:  https://github.com/utatti/perfect-scrollbar)
                KTUtil.scrollInit(cardScrollEl, {
                    mobileNativeScroll: true,  // Enable native scroll for mobile
                    desktopNativeScroll: false, // Disable native scroll and use custom scroll for desktop
                    resetHeightOnDestroy: true,  // Reset css height on scroll feature destroyed
                    handleWindowResize: true, // Recalculate hight on window resize
                    rememberPosition: true, // Remember scroll position in cookie
                    height: function() {  // Calculate height
                        var height;

                        if (KTUtil.isBreakpointUp('lg')) {
                            height = KTLayoutContent.getHeight();
                        } else {
                            height = KTUtil.getViewPort().height;
                        }

                        if (_chatAsideEl) {
                            height = height - parseInt(KTUtil.css(_chatAsideEl, 'margin-top')) - parseInt(KTUtil.css(_chatAsideEl, 'margin-bottom'));
                            height = height - parseInt(KTUtil.css(_chatAsideEl, 'padding-top')) - parseInt(KTUtil.css(_chatAsideEl, 'padding-bottom'));
                        }

                        if (cardScrollEl) {
                            height = height - parseInt(KTUtil.css(cardScrollEl, 'margin-top')) - parseInt(KTUtil.css(cardScrollEl, 'margin-bottom'));
                        }

                        if (cardBodyEl) {
                            height = height - parseInt(KTUtil.css(cardBodyEl, 'padding-top')) - parseInt(KTUtil.css(cardBodyEl, 'padding-bottom'));
                        }

                        if (searchEl) {
                            height = height - parseInt(KTUtil.css(searchEl, 'height'));
                            height = height - parseInt(KTUtil.css(searchEl, 'margin-top')) - parseInt(KTUtil.css(searchEl, 'margin-bottom'));
                        }

                        // Remove additional space
                        height = height - 2;

                        return height;
                    }
                });
            }
        }

        return {
            // Public functions
            init: function() {
                // Elements
                _chatAsideEl = KTUtil.getById('kt_chat_aside');
                _chatContentEl = KTUtil.getById('kt_chat_content');

                // Init aside and user list
                _initAside();

                // Init inline chat example
                KTLayoutChat.setup(KTUtil.getById('kt_chat_content'));

                // Trigger click to show popup modal chat on page load
                if (KTUtil.getById('kt_app_chat_toggle')) {
                    setTimeout(function() {
                        KTUtil.getById('kt_app_chat_toggle').click();
                    }, 1000);
                }
            }
        };
    }();

    jQuery(document).ready(function() {
        KTAppChat.init();
    });

</script>

@endpush

