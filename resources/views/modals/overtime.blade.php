{!! Form::open(['route' => ['modal.store-overtime', createHashId($sgk_company->id)], 'class' => 'ajax-store-form' , 'method' => 'POST']) !!}
<div class="modal-header">
    <h5 class="modal-title"><i class="flaticon-plus"></i> Fazla Mesai Süresi Ekle</h5>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="overtime_date" class="control-label">Fazla Mesai Dönemi</label>
        <input type="text" class="form-control" id="overtime_date" name="overtime_date" readonly="readonly" placeholder="Dönem Seçiniz" />
    </div>

    {{ Form::bsText('overtime', 'Fazla Mesai Süresi', $metrik_overtime->overtime, ['required' => true]) }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Kapat</button>
    <button type="submit" class="btn btn-primary font-weight-bold">KAYDET</button>
</div>
<script>
    $('#overtime_date').datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        locale:'tr'
    });
</script>
{!! Form::close() !!}
