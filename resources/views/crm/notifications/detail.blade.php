@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->
            <!--begin::Row-->
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <h3 class="card-title">
                                {{$newspaper->title}}
                            </h3>
                            <div class="card-toolbar">
                           </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="d-flex flex-column-fluid">
                            <div class="container-fluid">
                                    <img class="d-block w-100" style="height: 600px" src="{{$newspaper->image}}"  alt="HABERLER">
                            </div>

                        </div>

                            <label style="font-size: 15px;font-weight: bold;color: red" for="">Yaynlanma Tarihi :  {{ $newspaper->created_at->diffForHumans() }}</label>


                        <div class="card-body">
                            <br>
                            <div class="form-group">
                               @php echo $newspaper->message; @endphp
                            </div>
                        </div>

                    <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->


            <!--end::Row-->
            <!--end::Dashboard-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@stop





