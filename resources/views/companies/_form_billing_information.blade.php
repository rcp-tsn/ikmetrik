{{ Form::hidden('billing[id]', $company->billingInformation->id) }}

<div class="form-group">
    <label>Ticari İsim</label>
    {!! Form::text("billing[trade_name]",$company->billingInformation->trade_name,['class'=>'form-control form-control-solid','id'=>'trade_name']) !!}
</div>

<div class="form-group">
    <label>Mersis No</label>
    {!! Form::text("billing[mersis_no]",$company->billingInformation->mersis_no,['class'=>'form-control form-control-solid','id'=>'mersis_no']) !!}
</div>


<div class="form-group">
    <label>Vergi Dairesi</label>
    {!! Form::text("billing[tax_office]",$company->billingInformation->tax_office,['class'=>'form-control form-control-solid','id'=>'tax_office']) !!}
</div>


<div class="form-group">
    <label>Vergi No</label>
    {!! Form::text("billing[tax_number]",$company->billingInformation->tax_number,['class'=>'form-control form-control-solid','id'=>'tax_number']) !!}
</div>
