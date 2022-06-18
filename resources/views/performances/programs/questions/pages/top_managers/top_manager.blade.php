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
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight: bold;font-size: 15px;">
                                Üst Yönetici Değerlendirilmesi
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
                        {!! Form::open(['route' => ['question_evalation_store','id' => createHashId($id),'top_manager' => createHashId($top_manager->id),'type' => 'top_manager'], 'files'=>'true', 'class' => 'form top_manager_form', 'id' => 'top_manager_form']) !!}
                        <div class="">
                            @include('partials.alerts.error')

                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container-fluid">
                                    <div class="card card-custom card-transparent">
                                        <div class="">
                                            <div class="row">
                                                <div class="col-lg-4 ">
                                                    <div class="">
                                                        <div class="card card-custom card-transparent bannerr m-t-15 " id="bannerr" style="position: fixed;width: 22%" >
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold;font-size: 15px;">Üst Yöneticiniz : {{$top_manager->first_name}} {{$top_manager->last_name}}</h3>

                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label style="font-weight: bold">Test Durumu</label>
                                                                    <div class="progress" style="font-size: 15px;height: 25px;">
                                                                        <div class="progress-bar progress_class" role="progressbar" style="width: 1%;background-color: orange;height: 25px;font-size: 15px;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">75%</div>
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
                                                <div class="col-lg-8 m-t-5">
                                                    <div class="container-fluid">
                                                        <div class="card card-custom card-transparent" style="margin-top: 15px">
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold">Yetkinlik Sorularınız</h3>

                                                            </div>
                                                            <div class="card-body table-responsive" id="sayfa" style="height: 500px">
                                                    @php $soru = 1; @endphp
                                                    @foreach($questions as $key =>  $question)
                                                        <div class="question_count">
                                                        <div class="form-group">
                                                            <h4>Soru:    {{$soru++}}</h4>
                                                        </div>

                                                        <div class="form-group" id="next{{$key}}">
                                                            <label style="font-weight: bold;font-size: 15px"  class="control-label">{{$question->question}} ? </label>
                                                            <div class="form-group" style="margin-left: 15px">
                                                                <div class="radio-list mt-5">
                                                                    <label class="radio">
                                                                        <input id="" data-id="{{$key}}" type="radio" class="question" data-puan="{{isset($puanlar[1]) ? $puanlar[1] : 1  }}" value="{{isset($puanlar[1]) ? $puanlar[1] : 1  }}" name="radio[{{$question->id}}]">
                                                                        <span></span>Yetersiz</label>
                                                                    <label class="radio ">
                                                                        <input type="radio" data-id="{{$key}}" class="question" data-puan="{{isset($puanlar[2]) ? $puanlar[2] : 2  }}" value="{{isset($puanlar[2]) ? $puanlar[2] : 2  }}"  name="radio[{{$question->id}}]">
                                                                        <span></span>Orta</label>
                                                                    <label class="radio">
                                                                        <input type="radio" data-id="{{$key}}" class="question" data-puan="{{isset($puanlar[3]) ? $puanlar[3] : 3  }}" value="{{isset($puanlar[3]) ? $puanlar[3] : 3  }}"  name="radio[{{$question->id}}]">
                                                                        <span></span>İyi</label>
                                                                    <label class="radio">
                                                                        <input type="radio" data-id="{{$key}}" class="question" data-puan="{{$top_puan}}" value="{{$top_puan}}"  name="radio[{{$question->id}}]">
                                                                        <span></span>Mükemmel</label>
                                                                </div>
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
                            <button type="submit" id="submit" class="btn btn-primary mr-2">{{ __('global.buttons.SaveButtonText') }}</button>
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
@stop

@section('js')
    <script>

        $('.question').click(function ()
        {
            var id = $(this).data('id');
            var next = Number(id + 1) ;
            $("#sayfa").scrollTo('#next'+id,1000);
        });



    </script>
    <script>
            var toplam = 0;
            var key =[];
            var puans=[];

            $("#toplamPuan").val(0);
            $('.question').click(function(i, obj) {
               var  sum = 0;
                puans[$(this).data('id')] = $(this).data('puan');
                $(puans).each(function(index, element) {
                    sum += element;
                });
                $("#toplamPuan").val(sum);
                // alert(puans);
                // puans.forEach(function(puans){sum+=parseFloat(puans) || 0;});


                // if (jQuery.inArray($(this).data('id'), key) !== -1) {
                //     var puan = puans[key];
                //
                //     var a =  $("#toplamPuan").val();
                //     var sonuc = a - puan;
                //     $("#toplamPuan").val(sonuc);
                //     toplam = parseInt($("#toplamPuan").val()) + parseInt($(this).data('puan'));
                // }
                // else
                // {
                //
                //     key.push($(this).data('id'));
                //     toplam = parseInt($("#toplamPuan").val()) + parseInt($(this).data('puan'));
                //     $("#toplamPuan").val(toplam);
                // }

            });




    </script>

    <script>
        var count = $(".question_count").length;
        var click = 0;
        var key = [];
        var puan = [];
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
    <script>
        $(document).read(function ()
        {
            $("#submit").click(function ()
            {
                var deger = $(".progress-bar").html();
                if (deger == '100%')
                {


                }
                else
                {
                    Swal.fire({
                        position: "top-right",
                        icon: "error",
                        title: 'Tüm Soruları Cevaplamanız Gerekmektedir',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
            });
        });

    </script>
@endsection

