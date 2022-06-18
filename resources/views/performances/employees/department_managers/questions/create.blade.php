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
                                SORULARI EKLE
                            </h3>






                            <div class="card-toolbar">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#BlueExampleQuestions">Mavi Yaka Örnek Soru</button>
                                <button class="btn btn-default m-l-5" data-toggle="modal" data-target="#WhiteExampleQuestions">Beyaz Yaka Örnek Soru</button>
                                <!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            -->
                            </div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            @include('partials.alerts.error')
                            @include('performances.employees.department_managers.questions.form')
                        </div>
                        <div class="card-footer">
                            <button type="button" id="QuestionsCreate" class="btn btn-primary mr-2">KAYDET</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
                        </div>
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

    <div class="modal fade" id="BlueExampleQuestions" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="BlueExampleQuestions">Mavi Yaka Örnek Soruları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Ana Kriterler</th>
                            <th></th>
                            <th>Detay Kriterleri</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($a = 1; $a<=20;$a++)
                            <tr>
                                <td></td>
                                <td>{{$a}}</td>
                                <td  style="font-weight: bold;font-size: 15px">{{config('variables.blueExampleQuestions')[$a]}}</td>
                            </tr>
                        @endfor
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="WhiteExampleQuestions" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Beyaz Yaka Soruları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                        <tr>

                            <th></th>
                            <th>Detay Kriterleri</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($a = 1; $a<=15;$a++)
                            <tr>

                                <td>{{$a}}</td>
                                <td style="font-weight: bold;font-size: 15px">{{config('variables.WhiteExampleQuestions')[$a]}}</td>
                            </tr>
                        @endfor
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>


@stop






