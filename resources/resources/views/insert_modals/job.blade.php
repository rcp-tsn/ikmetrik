<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
    <h4 class="modal-title">Meslek Ekle</h4>
</div>
<div class="modal-body">
    {!! Form::open(['route' => ['insert_modals.store', 'job'], 'id' => 'insert-job-form']) !!}
        {{ Form::bsText('name', 'Meslek Adı') }}
    {!! Form::close() !!}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">KAPAT</button>
    <button type="button" class="btn btn-primary font-weight-bold" onclick="loadInsertModal.store('#insert-job-form')">KAYDET</button>
</div>
