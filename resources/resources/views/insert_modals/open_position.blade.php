<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Açık Pozisyon Oluşturun</h4>
</div>
<div class="modal-body">
    {!! Form::open(['route' => ['insert_modals.store', 'open_position'], 'id' => 'insert-open_position-form']) !!}
        {{ Form::bsText('title', 'Pozisyon Adı', null, ['required' => true], 'Örn: Kalite Kontrol Uzmanı, Satış Sorumlusu') }}
        {{ Form::bsSelect('department_id', 'Departman', $appDepartments, null, [], [], [
        'addButton' => [
            'text' => 'Yeni',
            'type' => 'department',
            'authorization' => ['role' => ['admin'], 'permissions' => ''] ]
        ]) }}
        {{ Form::bsMultiSelect2('competence[]', 'Yetkinlik Seç', $appCompetences, null, [], ['required' => true], [
        'addButton' => [
                'text' => 'Yeni',
                'type' => 'competence',
                'authorization' => ['role' => ['owner', 'human_resources', 'admin'], 'permissions' => '']
            ]]) }}
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('start-end', 'Pozisyon geçerlilik süresini belirleyin', ['class' => 'control-label']) }}
                    {{ Form::bsCheckbox('indefinite', 'Süresiz', 0, false) }}
                    <div class="input-daterange" id="date-input-wrapper">
                        <div class="input-group">
                                  <span class="input-group-addon">
                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                  </span>
                            <input type="text" value="" class="form-control datepicker" name="validity_start_date" />
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">arasında</span>
                            <input type="text" value="" class="form-control datepicker" name="validity_end_date" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kapat</button>
    <button type="button" onclick="loadInsertModal.store('#insert-open_position-form')" class="btn btn-primary">Kaydet</button>
</div>