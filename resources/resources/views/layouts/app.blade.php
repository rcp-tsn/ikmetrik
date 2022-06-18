@include('layouts.partials._top_header')

<!--end::Head-->

<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">

<!--begin::Main-->
@include('layouts.partials._header-mobile')
<div class="d-flex flex-column flex-root">

    <!--begin::Page-->
    <div class="d-flex flex-row flex-column-fluid page">

    @include('layouts.partials._aside')

    <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

        @include('layouts.partials._header')

        <!--begin::Content-->
            <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
                @include('layouts.partials.metrikflow')
                @yield('content')

            </div>

            <!--end::Content-->
            @include('layouts.partials._footer')
        </div>

        <!--end::Wrapper-->
        @include('layouts.partials._sidebar')
    </div>

    <!--end::Page-->
</div>

<!--end::Main-->

@include('layouts.partials.offcanvas._main')

@include('layouts.partials._footer-js')
</body>

<!--end::Body-->
</html>
