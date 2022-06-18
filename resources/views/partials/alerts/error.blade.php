@if (count($errors) > 0)
    <div class="alert alert-danger" role="alert">
        <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('message'))
    <div class="alert alert-success">
        <strong>{!! session('message')!!}</strong>
    </div>
@endif
@if(strlen(session()->get('danger'))>0)
    <div class="alert alert-danger">
        {!! session()->get('danger') !!}

    </div>
@endif

@if(strlen(session()->get('success'))>0)
    <div class="alert alert-success">
        {!! session()->get('success') !!}
    </div>
@endif
@if(strlen(session()->get('warning'))>0)
    <div class="alert alert-warning">
        {!! session()->get('warning') !!}
    </div>
@endif


