@if($type == 'overtime')
{!! Form::open(['route' => ['modal.store-value', [createHashId($sgk_company->id), $type]], 'class' => 'ajax-store-form' , 'method' => 'POST']) !!}
<div class="modal-header">
    <h5 class="modal-title"><i class="flaticon-plus"></i> Fazla Mesai Süresi Ekle</h5>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="overtime_date" class="control-label">Fazla Mesai Dönemi</label>
        <input type="text" class="form-control" id="overtime_date" name="overtime_date"  placeholder="Dönem Seçiniz" />
    </div>

    {{ Form::bsText('value', 'Fazla Mesai Süresi', null, ['required' => true]) }}
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
@endif


@if($type == 'education')
    {!! Form::open(['route' => ['modal.store-value', [createHashId($sgk_company->id), $type]], 'class' => 'ajax-store-form' , 'method' => 'POST']) !!}
    <div class="modal-header">
        <h5 class="modal-title"><i class="flaticon-plus"></i> Eğitim Maliyetleri</h5>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="overtime_date" class="control-label">Yıl</label>
            <input type="text" class="form-control" id="value_year" name="value_year"  placeholder="Yıl giriniz" />
            <p class="help-block">Örnek: 2020</p>
        </div>

        {{ Form::bsText('value', 'Eğitim Maliyeti', null, ['required' => true]) }}
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary font-weight-bold">KAYDET</button>
    </div>
    {!! Form::close() !!}
@endif


@if($type == 'time')
    {!! Form::open(['route' => ['modal.store-value', [createHashId($sgk_company->id), $type]], 'class' => 'ajax-store-form' , 'method' => 'POST']) !!}
    <div class="modal-header">
        <h5 class="modal-title"><i class="flaticon-plus"></i> Eğitime Ayrılan Süreler</h5>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="overtime_date" class="control-label">Yıl</label>
            <input type="text" class="form-control" id="value_year" name="value_year" placeholder="Yıl giriniz" />
            <p class="help-block">Örnek: 2020</p>
        </div>

        {{ Form::bsText('value', 'Eğitime Ayrılan Süre', null, ['required' => true]) }}
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary font-weight-bold">KAYDET</button>
    </div>
    {!! Form::close() !!}
@endif


@if($type == 'cost')
    {!! Form::open(['route' => ['modal.store-value', [createHashId($sgk_company->id), $type]], 'class' => 'ajax-store-form' , 'method' => 'POST']) !!}
    <div class="modal-header">
        <h5 class="modal-title"><i class="flaticon-plus"></i> Cirolar</h5>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="overtime_date" class="control-label">Yıl</label>
            <input type="text" class="form-control" id="value_year" name="value_year"  placeholder="Yıl giriniz" />
            <p class="help-block">Örnek: 2020</p>
        </div>

        {{ Form::bsText('value', 'Ciro', null, ['required' => true]) }}
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary font-weight-bold">KAYDET</button>
    </div>
    {!! Form::close() !!}
@endif
