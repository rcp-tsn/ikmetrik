<style>
    .font-up
    {
        font-size: 16px;
        font-weight: bold;
        color: black;
    }
</style>
<div class="card-body">


    <div class="row">
        <div class="col-lg-2">
            <div class="form-group">

                <label style="font-size: 15px;font-weight: bold" class="font-up" >Başvuru Tarihi :  {{date('d/m/Y')}}</label>
            </div>

        </div>
        <div class="col-lg-2">
            <div class="form-group">
                <label style="font-size: 15px;font-weight: bold" >İsim Soyisim :  {{isset($employee) ? $employee->full_name : ''}}</label>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label style="font-size: 15px;font-weight: bold">Telefon/Email :  {{isset($employee) ? $employee->mobile : ''}} / {{isset($employee) ? $employee->email : ''}}</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label style="font-size: 15px;font-weight: bold" for="">Şikayetçinin Talebi</label>
                {!! Form::select('request_types', config('variables.requests.request_types'),null,['class'=>'form-control selectpicker','required'=>'required','data-live-search'=>'true']) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label style="font-size: 15px;font-weight: bold" for="">Şirketle Olan İlişkiniz</label>
                {!! Form::select('company_contact_type', config('variables.requests.company_contact_type'),null,['class'=>'form-control selectpicker','required'=>'required','data-live-search'=>'true']) !!}
            </div>
        </div>

    </div>

    <div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label style="font-size: 15px;font-weight: bold" for="">Şirketle İlişkiniz Devam Ediyor mu ? </label>
            {!! Form::select('company_contact_status', ['0'=>'HAYIR','1'=>'EVET'],null,['class'=>'form-control selectpicker','required'=>'required','data-live-search'=>'true']) !!}
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label style="font-size: 15px;font-weight: bold" for="">Talep ve Şikayet Açıklamasını Yapınız</label>
            {!! Form::text('customer_request',null,['class'=>'form-control selectpicker','required'=>'required','data-live-search'=>'true']) !!}
        </div>
    </div>
    </div>
</div>



