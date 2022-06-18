@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent

    <style>

    </style>


    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <h3 class="card-title up-font">
                               AST DEĞERLENDİRME
                            </h3>
                            <h3 class="card-title" style="font-weight: bold;font-size: 15px;" >Soruların Tamamını Değerlendirdikten Sonra Kaydet Butonu Aktif Olacak.</h3>
                            <div class="card-toolbar">
                                <div class="form-group" style="text-align: -webkit-right;margin-right: 20px; padding-top: 20px">
                                    <a href="{{ URL::previous() }}">
                                        <div data-repeater-create=""  class="btn font-weight-bold btn-danger">
                                            <i class="la la-backspace"></i>Geri</div>
                                    </a>
                                </div>
                                <!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::open(['route' => [ 'question_evalation_store','id' => createHashId($id),'top_manager' =>'top_manager','type' => 'subordinate']]) !!}
                        <div class="mt-15">
                            @include('partials.alerts.error')

                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container-fluid">
                                    <div class="card card-custom card-transparent">
                                        <div class="">
                                            <div class="row" >
                                                <div class="col-lg-4">
                                                    <div class="">
                                                        <div class="card card-custom card-transparent flex-wrap bannerr" id="bannerr" style="" >
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold">Ast Personel Seçiniz</h3>
                                                            </div>
                                                            <div class="form-group mb-4 m-5">
                                                                <div class="alert alert-custom alert-default" role="alert">
                                                                    <div class="alert-icon">
										                           	<span class="svg-icon svg-icon-primary svg-icon-xl">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24"></rect>
																			<path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
																			<path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
																		</g>
																	</svg>
                                                <!--end::Svg Icon-->
																</span>
                                                                    </div>
                                                                    <div class="alert-text up-font">LİSTEDE PERSONEL YOKSA TÜM DEĞERLENDİRMELER YAPILMIŞTIR</div>
                                                                </div>

                                                            </div>
                                                            <div class="form-group" style="margin: 5px">
                                                                <label class="ml-5 mt-5" style="font-weight: bold">AST PERSONEL</label>
                                                                    <div class="mt-5">
                                                                        {!! Form::select('subordinate',$subordinates, isset($selectedSubordinate) ? $selectedSubordinate: null,['class'=>'form-control selectpicker m-r-2','data-live-search'=>'true']) !!}
                                                                    </div>
                                                            </div>



                                                            <div class="card-body">
                                                                <div class="form-group">


                                                                <label style="font-weight: bold;font-size: 15px;">Test Durumu</label>
                                                                <div class="progress mt-10" style="font-size: 15px;font-weight: bold;height: 25px;">
                                                                    <div  class="progress-bar progress_class" role="progressbar" style="width: 1%;background-color: orange;height: 25px;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">0%</div>
                                                                </div>
                                                                </div>
                                                                <br>
                                                                <div class="form-group">
                                                                    <label>Toplam Puan (100 Üzerinden)</label>
                                                                    <input class="form-control" readonly type="text" value="" id="toplamPuan">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="">
                                                        <div class="card card-custom card-transparent table-responsive" id="sayfa" style="height: 500px">
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold">Yetkinlik Sorularınız</h3>

                                                            </div>
                                                            <div class="card-body">
                                                                @php $soru = 1; @endphp
                                                                @foreach($questions as $key =>  $question)
                                                                    <div class="form-group" >
                                                                        <h4>Soru:    {{$soru++}}</h4>
                                                                    </div>
                                                                    <div class="question_count" id="" ></div>
                                                                    <div class="form-group " id="next{{$key}}">
                                                                        <label style="font-weight: bold;font-size: 15px"  class="control-label">{{$question->question}} ? </label>
                                                                        <div class="form-group" style="margin-left: 15px">
                                                                            <div class="radio-list mt-5">
                                                                                <label class="radio">
                                                                                    <input id="" data-id="{{$key}}" type="radio" data-puan="{{isset($puanlar[1]) ? $puanlar[1] : 1  }}" value="{{isset($puanlar[1]) ? $puanlar[1] : 1  }}" class="question"  name="radios[{{$question->id}}]">
                                                                                    <span></span>Yetersiz</label>
                                                                                <label class="radio ">
                                                                                    <input  type="radio" data-id="{{$key}}" data-puan="{{isset($puanlar[2]) ? $puanlar[2] : 2  }}" value="{{isset($puanlar[2]) ? $puanlar[2] : 2  }}" class="question"   name="radios[{{$question->id}}]">
                                                                                    <span></span>Orta</label>
                                                                                <label class="radio">
                                                                                    <input type="radio" data-id="{{$key}}" data-puan="{{isset($puanlar[3]) ? $puanlar[3] : 3  }}" value="{{isset($puanlar[3]) ? $puanlar[3] : 3  }}" class="question"   name="radios[{{$question->id}}]">
                                                                                    <span></span>İyi</label>
                                                                                <label class="radio">
                                                                                    <input type="radio" data-id="{{$key}}" data-puan="{{$top_puan}}" value="{{$top_puan}}" class="question"  name="radios[{{$question->id}}]">
                                                                                    <span></span>Mükemmel</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer" style="display: none">
                            <button type="submit" class="btn btn-primary mr-2">{{ __('global.buttons.SaveButtonText') }}</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
                        </div>
                    {!! Form::close() !!}
                    <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                </div>
            </div>
            <!--end::Row-->
            <!--end::Dashboard-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@endsection

@section('js')
    <script>

        $('.question').click(function ()
        {
            var id = $(this).data('id');
            console.log(id);
            var next = Number(id + 1) ;
            $("#sayfa").scrollTo('#next'+id,1000);
        });



    </script>
    <script>

        var puans=[];
        $("#toplamPuan").val(0);
        $('.question').click(function(i, obj) {
            var  sum = 0;
            puans[$(this).data('id')] = $(this).data('puan');
            $(puans).each(function(index, element) {
                sum += element;
            });
            $("#toplamPuan").val(sum);
        });




    </script>

    <script>
            var count = $(".question_count").length;
            var click = 0;
            var key = [];
            $('.question').click(function ()
            {

                 if (jQuery.inArray($(this).data('id'), key) !== -1)
                 {
                     var deger = 0;
                     deger  = (click * 100)/count;
                     $(".progress_class").css("width",deger+"%");
                     if (deger == 100)
                     {
                         $(".card-footer").css({display:"unset"});

                     }
                 }
                 else
                 {

                     key.push($(this).data('id'));
                     click = click + 1;
                     var deger = 0;
                     deger  = (click * 100)/count;
                     $(".progress_class").css("width",deger+"%");
                     $(".progress_class").html(deger+"%");
                     if (deger == 100)
                     {
                         $(".card-footer").css({display:"unset"});

                     }
                 }


            });
    </script>
@endsection

