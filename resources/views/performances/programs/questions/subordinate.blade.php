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
                            <h3 class="card-title">
                               AST DEĞERLENDİRME
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::open(['route' => [ 'question_evalation_store','id' => createHashId($id),'top_manager' =>' ','type' => 'subordinate'], 'files'=>'true', 'class' => 'form', 'id' => 'kt_form']) !!}
                        <div class="card-body">
                            @include('partials.alerts.error')

                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container-fluid">
                                    <div class="card card-custom card-transparent">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="container-fluid">
                                                        <div class="card card-custom card-transparent flex-wrap">
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold">Ast Personel Seçiniz</h3>
                                                            </div>
                                                            <div class="form-group mb-4 mt-5">
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
                                                                    <div class="alert-text">LİSTEDE PERSONEL YOKSA TÜM DEĞERLENDİRMELER YAPILMIŞTIR</div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="ml-5 mt-5" style="font-weight: bold">AST PERSONEL</label>
                                                                    <div class="mt-5">
                                                                        {!! Form::select('subordinate',$subordinates, isset($selectedSubordinate) ? $selectedSubordinate: null,['class'=>'form-control selectpicker','data-live-search'=>'true']) !!}
                                                                    </div>
                                                            </div>



                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center w-25 flex-fill float-right mt-lg-12 mt-8">
                                                                    <span class="font-weight-bold text-dark-75">Progress</span>
                                                                    <div class="progress progress-xs mx-3 w-100">
                                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 63%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                    <span class="font-weight-bolder text-dark">78%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="container-fluid">
                                                        <div class="card card-custom card-transparent">
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold">Yetkinlik Sorularınız</h3>

                                                            </div>
                                                            <div class="card-body">
                                                                @php $soru = 1; @endphp
                                                                @foreach($questions as $key =>  $question)
                                                                    <div class="form-group">
                                                                        <h4>Soru:    {{$soru++}}</h4>
                                                                    </div>

                                                                    <div class="form-group" id="next{{$key}}">
                                                                        <label  class="control-label">{{$question->question}} ? </label>
                                                                        <div class="form-group" style="margin-left: 15px">
                                                                            <div class="radio-list mt-5">
                                                                                <label class="radio">
                                                                                    <input required id="" data-id="{{$key}}" type="radio" class="question" value="1" name="radio[{{$question->id}}][1]">
                                                                                    <span></span>Yetersiz</label>
                                                                                <label class="radio ">
                                                                                    <input required type="radio" data-id="{{$key}}" class="question" value="2"  name="radio[{{$question->id}}][2]">
                                                                                    <span></span>Orta</label>
                                                                                <label class="radio">
                                                                                    <input required type="radio" data-id="{{$key}}" class="question" value="3"  name="radio[{{$question->id}}][3]">
                                                                                    <span></span>İyi</label>
                                                                                <label class="radio">
                                                                                    <input required type="radio" data-id="{{$key}}" class="question" value="4"  name="radio[{{$question->id}}][4]">
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
                        <div class="card-footer">
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
            $(document).scrollTo('#next'+id,1000);
        });



    </script>
@endsection

