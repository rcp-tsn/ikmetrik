<!DOCTYPE html>
<html lang="tr">
<head>
    <base href="">
    <meta charset="utf-8" />
    <title>İKMETRİK | Dijital İK İşinize İyi Gelecek</title>
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="_token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="/assets/plugins/global/plugins.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <link href="/assets/css/custom.css" rel="stylesheet" type="text/css"/>
    <link href="/css/enjoyhint.css" rel="stylesheet" type="text/css"/>

</head>
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-row flex-column-fluid page">
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper" style="margin-left: 30px;">
            <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
                @yield('content')
            </div>
        </div>
    </div>
</div>


<script src="/assets/plugins/global/plugins.bundle.js?v=7.0.3"></script>
<script src="/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.3"></script>

<script src="/assets/js/scripts.bundle.js?v=7.0.3"></script>

<script src="/assets/js/pages/widgets.js?v=7.0.3"></script>






@stack('scripts')


</body>

<!--end::Body-->
</html>
