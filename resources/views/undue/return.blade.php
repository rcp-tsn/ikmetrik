@extends('layouts.app')
@section('content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class="container-fluid">
            <div class="col-md-12 text-center pt-8">
                <div class="row">
                    <div class="col-md-12">
                        {!! $undue !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@push('scripts')




@endpush

