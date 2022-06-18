@include('layouts.partials._top_header')
<!--end::Head-->

<!--begin::Body-->
<body  id="kt_body"  class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading"  >

<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Error-->
    <div class="d-flex flex-row-fluid bgi-size-cover bgi-position-center" style="background-image: url(/assets/media/error/505.jpg);">
        <!--begin::Content-->
        <div class="d-flex flex-row-fluid flex-column justify-content-end align-items-center text-center text-white pb-40">
            <h1 class="display-1 font-weight-bold">OOPS!</h1>
            <span class="display-4 font-weight-boldest mb-8">
			{!! $exception->getMessage() !!}
                <br/>
                <br/>
                <a href="{{ URL::previous() }}" class="btn btn-danger font-weight-bold font-size-h3 px-12 py-5"> GERİ DÖN</a>
		</span>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Error-->
</div>
<!--end::Main-->

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
<!--end::Global Config-->

<!--begin::Global Theme Bundle(used by all pages)-->
<script src="/assets/plugins/global/plugins.bundle.js?v=7.0.4"></script>
<script src="/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.4"></script>
<script src="/assets/js/scripts.bundle.js?v=7.0.4"></script>
<!--end::Global Theme Bundle-->


</body>
<!--end::Body-->
</html>