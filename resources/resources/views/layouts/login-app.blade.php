<!DOCTYPE html>
<html lang="tr">

<!--begin::Head-->
<head>
    <base href="">
    <meta charset="utf-8" />
    <title>İKMETRİK | Dijital İK İşinize İyi Gelecek</title>
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

    <!--end::Fonts-->

    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />

    <!--end::Page Vendors Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="/assets/plugins/global/plugins.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <link href="/assets/css/pages/login/login-1.css?v=7.0.3" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes(used by all pages)-->

    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="/assets/media/logos/favicon.png" />
</head>

<!--end::Head-->

<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">

<!--begin::Main-->
<div class="d-flex flex-column flex-root">

    @yield('login')
</div>

<!--end::Main-->


@include('layouts.partials._footer-js')
<script src="/assets/js/pages/custom/login/login-general.js"></script>
</body>

<!--end::Body-->
</html>
