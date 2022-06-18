<ul class="sticky-toolbar nav flex-column pl-2 pr-2 pt-3 pb-3 mt-4 ">
    <!--begin::Item-->
    <li class="nav-item mb-2 faq-area" data-toggle="tooltip" title="" data-placement="left" data-original-title="Yardım Klavuzu">
        <a class="btn btn-sm btn-icon btn-bg-light btn-icon-warning btn-hover-warning" href="{{ route('faq') }}" target="_blank">
            <i class="flaticon2-telegram-logo font-size-h1"></i>
        </a>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="nav-item send-message-area"  id="kt_sticky_toolbar_chat_toggler" data-toggle="tooltip" title="" data-placement="left" data-original-title="Mesaj Gönder">
        <a class="btn btn-sm btn-icon btn-bg-light btn-icon-danger btn-hover-danger" href="#" data-toggle="modal" data-target="#kt_chat_modal">
            <i class="flaticon-multimedia-2 font-size-h1"></i>
        </a>
    </li>
    <!--
    <li class="nav-item mb-2 mt-2" id="kt_sticky_toolbar_chat_toggler" data-toggle="tooltip" title="" data-placement="left" data-original-title="Canlı Destek">
        <a class="btn btn-sm btn-icon btn-bg-light btn-icon-danger btn-hover-danger btn-open-modal-chat" href="#" data-toggle="modal" data-target="#live_support_modal">
            <i class="flaticon2-chat-1 font-size-h1"></i>
        </a>
    </li>
    -->
    <!--end::Item-->
</ul>
<script>
    var HOST_URL = "http://keenthemes.com/metronic/tools/preview";
</script>


<!--begin::Global Config(global config for global JS scripts)-->
<script>
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1200
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#663259",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#F3F6F9",
                    "dark": "#212121"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#F4E1F0",
                    "secondary": "#ECF0F3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#212121",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#ECF0F3",
                "gray-300": "#E5EAEE",
                "gray-400": "#D6D6E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#80808F",
                "gray-700": "#464E5F",
                "gray-800": "#1B283F",
                "gray-900": "#212121"
            }
        },
        "font-family": "Poppins"
    };
</script>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="/assets/plugins/global/plugins.bundle.js?v=7.0.3"></script>
<script src="/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.3"></script>
<script src="/assets/plugins/custom/bootbox/bootbox.min.js"></script>
<script src="/assets/js/scripts.bundle.js?v=7.0.3"></script>




<script src="/assets/js/pages/custom/wizard/wizard-4.js?v=7.0.3"></script>

<script src="/assets/js/pages/crud/forms/widgets/bootstrap-switch.js"></script>
<script src="/assets/js/pages/crud/forms/widgets/select2.js"></script>
<!--end::Global Theme Bundle-->
<script src="/assets/js/pages/widgets.js?v=7.0.3"></script>
<script src="/assets/js/pages/features/miscellaneous/sweetalert2.js"></script>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script src="/assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.3"></script>
@include('partials.alerts.toastr')

<script src="/assets/js/pages/my-script.js"></script>
<script src="/js/axios.min.js"></script>
<script src="/js/upload_app.js"></script>
<script src="/js/uploader_bootstrap.js"></script>

<script src="/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="/assets/js/pages/crud/forms/editors/ckeditor-classic.js"></script>

<script src="/assets/amcharts/amcharts.js?v=7.0.5"></script>
<script src="/assets/amcharts/serial.js?v=7.0.5"></script>
<script src="/assets/amcharts/radar.js?v=7.0.5"></script>
<script src="/assets/amcharts/pie.js?v=7.0.5"></script>
<script src="/assets/amcharts/polarScatter.min.js?v=7.0.5"></script>
<script src="/assets/amcharts/animate.min.js?v=7.0.5"></script>
<script src="/assets/amcharts/export.min.js?v=7.0.5"></script>
<script src="/assets/amcharts/light.js?v=7.0.5"></script>
<script type="text/javascript" src="/assets/js/html2canvas.js"></script>
<script type="text/javascript" src="/assets/js/jquery.bootstrap-duallistbox.js"></script>
<script type="text/javascript" src="/js/enjoyhint.min.js"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!--  A polyfill for browsers that don't support ligatures: remove liga.js if not needed-->
@yield('js')
<script>
    $(document).ready(function ()
    {
        $("#_performance").hide();
        $("._money").hide();
        $(".performance_program_div").click(function ()
        {
            $("#_performance").toggle(400);
        })
        $(".money_management").click(function ()
        {
            $("._money").toggle(400);
        })
    })
</script>
<script>
    'use strict';
    $(document).ready(function () {

        $(document).on('click', 'a.metrikflow-do-definitions-area', function () {
            var enjoyhint_instance = new EnjoyHint({});

            enjoyhint_instance.set([
                {
                    'next .metrik-definitions-menu-area': 'Bu bölümden metrik tanımlamalarınızın yapılması gereklidir.',
                }
            ]);
            enjoyhint_instance.run();
        });

    });
</script>

<script>
    'use strict';
    $(document).ready(function () {

        $(document).on('click', 'a.metrikflow-show-area', function () {
            var enjoyhint_instance = new EnjoyHint({});

            enjoyhint_instance.set([
                {
                    'next .metrik-show-menu-area': 'Bu bölümden metriklerinizi görüntüleyebilirsiniz.',
                }
            ]);
            enjoyhint_instance.run();
        });

    });
</script>

<style>
    #loading {
        position: fixed;
        display: block;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        text-align: center;
        opacity: 0.7;
        background-color: #fff;
        z-index: 99;
    }

    #loading-image {
        position: absolute;
        top: 100px;
        left: 240px;
        z-index: 100;
    }
</style>





<script>

    $(window).on('load', function(){

        $("#loading").hide();
    });

</script>
<script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.8/firebase-app.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.6.8/firebase-analytics.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey: "AIzaSyAbz4ncsM1IB_KpaIkqHtMM5EzcFftmMGw",
        authDomain: "ikmetrik-4fe64.firebaseapp.com",
        projectId: "ikmetrik-4fe64",
        storageBucket: "ikmetrik-4fe64.appspot.com",
        messagingSenderId: "41493451875",
        appId: "1:41493451875:web:ef2b73d44c5a6abf3b5456",
        measurementId: "G-P7FE2KF7RW"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);

</script>

<div id="loading">
    <img id="loading-image" src="" alt="Yükleniyor..." />
</div>

<div class="modal fade" id="LoadingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Onaylama işleminiz yapılıyor</h5>
            </div>
            <div class="modal-body">
                <h3>Lütfen Bekleyiniz</h3>
                <div class="loader-container">
                    <div class="loader">
                        <div class="square one"></div>
                        <div class="square two"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-default" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Title</h4>
            </div>
            <div class="modal-body">
                <p>Body</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
<div id="hr-modals-here">
    @stack('modals')
</div>



@stack('modal')


@stack('scripts')

