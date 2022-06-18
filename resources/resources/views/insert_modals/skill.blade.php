<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Yetenek Ekle</h4>
</div>
<div class="modal-body">
    {!! Form::open(['route' => ['insert_modals.store', 'skill'], 'id' => 'insert-skill-form']) !!}
        {{ Form::bsText('title', 'Yetenek adı') }}
    {!! Form::close() !!}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kapat</button>
    <button type="button" onclick="loadInsertModal.store('#insert-skill-form')" class="btn btn-primary">Kaydet</button>
</div>