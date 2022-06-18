@extends('layouts.app')
@section('content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class="container-fluid">
            <div class="col-md-12 text-center pt-8">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <form action="{{ route('undue.post') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <img src="{{ route('captcha', $cookieKey) }}">
                            </div>
                            <div class="form-group">
                                <input type="text" name="captcha" class="form-control" placeholder="Capcha giriniz">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="form-control button" value="GİRİŞ">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@push('scripts')




@endpush

