{!! Form::open(['route' => [$route], 'id' => $formId]) !!}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">{{ $title }}</h4>
</div>
<div class="modal-body">
    <div id="form-errors"></div>
    {{ $slot }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kapat</button>
    {{ Form::bsSubmit('Kaydet') }}
</div>
{!! Form::close() !!}