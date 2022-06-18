<div class="card-body">

        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-weight: bold;font-size: 15px;">{{$companyStatus->step}} BASAMAK</label>
                    {!! Form::select('status[]',$status,isset($companyStatus) ? $companyStatus->status_id : null,['class'=>'form-control selectpicker','data-live-search'=>'true']) !!}
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-weight: bold;font-size: 15px;">TABAN MAAŞ</label>
                    {!! Form::text('taban[]',$companyStatus->taban_maas,['class'=>'form-control','placholder'=>'En Az Ücret']) !!}
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-size: 15px;font-weight: bold;">TAVAN MAAŞ</label>
                    <br>
                    {!! Form::number('tavan[]',$companyStatus->taban_maas,['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
        <hr style="border: revert">
</div>
