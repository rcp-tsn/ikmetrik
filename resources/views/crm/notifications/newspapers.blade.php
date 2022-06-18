@php
    $authUser = \Illuminate\Support\Facades\Auth::user();
    $newspapers = $authUser->crmnotifications(2);
    $sira = 0;

    @endphp
<style>

    .carousel-inner {
        height: 40%;
        background: #000;
    }

    .carousel-caption{padding-bottom:80px;}

    h2{font-size: 60px;}
    p{padding:10px}

    /* Background images are set within the HTML using inline CSS, not here */

    .fill {
        width: 100%;
        height: 100%;
        background-position: center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
        opacity:0.6;
    }




    /**
     * Button
     */
    .btn-transparent {
        background: transparent;
        color: #fff;
        border: 2px solid #fff;
    }
    .btn-transparent:hover {
        background-color: #fff;
    }

    .btn-rounded {
        border-radius: 70px;
    }

    .btn-large {
        padding: 11px 45px;
        font-size: 18px;
    }

    /**
     * Change animation duration
     */
    .animated {
        -webkit-animation-duration: 1.5s;
        animation-duration: 1.5s;
    }

    @-webkit-keyframes fadeInRight {
        from {
            opacity: 0;
            -webkit-transform: translate3d(100px, 0, 0);
            transform: translate3d(100px, 0, 0);
        }

        to {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            -webkit-transform: translate3d(100px, 0, 0);
            transform: translate3d(100px, 0, 0);
        }

        to {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
        }
    }

    .fadeInRight {
        -webkit-animation-name: fadeInRight;
        animation-name: fadeInRight;
    }



</style>
<div id="carouselExampleIndicators" style="background: white!important;"  class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @forelse($newspapers as $newspaper)
        <li data-target="#carouselExampleIndicators" data-slide-to="{{$sira}}" class="{{$sira == 0 ? 'active' : ''}}"></li>
            @php $sira ++; @endphp
        @empty
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"  ></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1" ></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2" ></li>
         @endforelse
    </ol>
    @php $sira2=0; @endphp
    <div class="carousel-inner">
        @forelse($newspapers as $newspaper)
        <div class="carousel-item {{$sira2 == 0 ?'active':''}}">
            <a href="{{route('newspaper.detail',createHashId($newspaper->id))}}"><img class="d-block w-100 " style="height: 450px"   src="{{$newspaper->image}}"></a>
            <div class="carousel-caption">
                <h1 style="color: black;font-weight: bold">{{$newspaper->title}}</h1>
                <p style="color: black;font-weight: bold;font-size: 15px">{{mb_strimwidth(strip_tags($newspaper->message),0,50,'...')}}</p>
            </div>
        </div>
            {{$sira2++}}
        @empty
            <style>
                @media (min-width:500px) {
                    .img
                    {
                        height: 450px;
                    }
                }
                @media (max-width:500px) {
                    .img
                    {
                        font-weight: bold!important;
                    }
                }
            </style>

            <div class="carousel-item active">
                <img class="d-block w-100 img "    src="\assets\media\bg\performansslider.jpg" alt="First slide">

            </div>
            <div class="carousel-item">
                <img class="d-block w-100 img "    src="\assets\media\bg\ebordroslider.jpg" alt="First slide">

            </div>
            <div class="carousel-item">
                <img class="d-block w-100 img "    src="\assets\media\bg\kvkkslider.jpg" alt="First slide">

            </div>
        @endforelse
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
     <span class="svg-icon btn-light btn-hover-primary svg-icon-primary svg-icon-3x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Navigation\Angle-left.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-12.000003, -11.999999) "/>
    </g>
</svg><!--end::Svg Icon--></span>
        <span class="sr-only">Geri</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
       <span class="svg-icon btn-light btn-hover-primary svg-icon-primary svg-icon-3x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Navigation\Angle-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-270.000000) translate(-12.000003, -11.999999) "/>
    </g>
</svg><!--end::Svg Icon--></span>
        <span class="sr-only">İleri</span>
    </a>
</div>
