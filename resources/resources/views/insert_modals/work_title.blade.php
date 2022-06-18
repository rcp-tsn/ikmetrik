<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Ünvan Ekle</h4>
</div>
<div class="modal-body">
    {!! Form::open(['route' => ['insert_modals.store', 'work_title'], 'id' => 'insert-work_title-form']) !!}
        {{ Form::bsText('title', 'Ünvan Adı') }}
    {!! Form::close() !!}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kapat</button>
    <button type="button" onclick="loadInsertModal.store('#insert-work_title-form')" class="btn btn-primary">Kaydet</button>
</div>