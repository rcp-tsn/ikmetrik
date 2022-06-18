<div class="card-body">
   @foreach($steps as $step)
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-weight: bold;font-size: 15px;">{{$step}} BASAMAK</label>
                    {!! Form::select('status['.$step.'][]',$status,isset($companyStatus) ? $companyStatus->status_id : null,['class'=>'form-control selectpicker','data-live-search'=>'true','multiple']) !!}
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-weight: bold;font-size: 15px;">TABAN MAAŞ</label>
                    {!! Form::number('taban['.$step.'][]',isset($companyStatus->taban_maas) ? $companyStatus->taban_maas : null ,['class'=>'form-control','placholder'=>'En Az Ücret']) !!}
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-size: 15px;font-weight: bold;">TAVAN MAAŞ</label>
                    <br>
                    {!! Form::number('tavan['.$step.'][]',isset($companyStatus->taban_maas) ? $companyStatus->taban_maas : null ,['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
        <hr style="border: revert">
    @endforeach
</div>
@push('js')

@endpush
