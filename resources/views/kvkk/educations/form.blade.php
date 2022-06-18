<div class="card-body">

    <div class="row">
        <div class="col-lg-6">
            <label>Eğitim Başlık * </label>
            <div class="form-group  required">
                {!! Form::text('name',isset($education->name) ? $education->name : null,['class'=>'form-control','required']) !!}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group required">
                <label>Açıklama (Eğitim Konusu) *</label>
                {!! Form::text('notification',isset($education->notification) ? $education->notification : null,['class'=>'form-control','required']) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group required">
                <label>Dosya (VİDEO(mp4) FORMATINDA) * </label>
                <br>
                {!! Form::file('file',null,['class'=>'form-control','required']) !!}
            </div>
        </div>


        <div class="col-lg-6">
            <div class="form-group required">
                <label>Personel Seçiniz</label>
                <br>
                {!! Form::select('status',[0=>'AKTİF',1=>'PASİF'],null,['class'=>'form-control selectpicker','required']) !!}
            </div>
        </div>
    </div>
</div>



@section('js')


@endsection
