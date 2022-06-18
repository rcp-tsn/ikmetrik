@include('layouts.partials._top_header')

<!--end::Head-->
<style type="text/css">
    .card-header{
        border-bottom: 2px solid #ccced0 !important;
    }
    .formulgoster{
        font-weight: 700;
        margin-right: 5px;
        float: left;
        cursor: pointer;
    }
    .formul{
        float: left;
        display: none;
    }
    .metric-define-div{
        width: 60%;float: left;font-size: 15px;padding-top: 10px;margin-bottom: 20px;
    }
    .metric-image-div{
        width: 33%;min-height: 200px;float: left;padding: 5px 10px;
    }
    .date-part-main{
        height: 50px;
    }
    .date-part{
        float: left;
    }
    .date-part-second{
        width: 17%;margin-right: 10px;float: left;
    }
    .date-part-third{
        width: 24%;margin-right: 10px;float: left;
    }
    .date-part-label{
        float: left;font-size: 17px;font-weight: 700;margin-right: 4px;line-height: 40px;
    }
    .date-year, .date-month{
        width: 150px;margin-right: 10px;float: left;
    }
    .date-form{
        width: 100%;
    }
    @media(max-width: 700px){
        .metric-image-div{
            width: 100%;min-height: auto;padding-left: 0px;
        }
        .metric-define-div{
            width: 100%;
        }
        .date-form{
            height: 300px;
        }
        .date-part-main{
            height: 150px;
        }
        .date-part-label{
            width: 100%;
        }
        .date-part-second{
            width: 100%;
        }
        .date-part-third{
            width: 100%;
            margin-bottom: 20px;
        } 
        .card.card-custom > .card-body {
            padding: 2rem 0rem;
        }
    }
    @media(min-width: 701px) and (max-width: 850px){
        .date-form{
            height: 140px;
        }
        .date-part-main{
            height: 150px;
        }
        .date-part-label{
            width: 100%;
        }
    }
</style>
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

                @yield('content')

            </div>

            <!--end::Content-->
            @include('layouts.partials._footer')
        </div>

        <!--end::Wrapper-->
    </div>

    <!--end::Page-->
</div>

<!--end::Main-->

@include('layouts.partials.offcanvas._main')

@include('layouts.partials._footer-js')
</body>

<!--end::Body-->
</html>
