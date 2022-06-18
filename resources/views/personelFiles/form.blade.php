<div class="card-body">

<div class="row">
    <div class="col-lg-6">
        <label>Evrak Türü * </label>
        <div class="form-group input-group required">
            {!! Form::select('file_type_id',$file_types ,isset($personelFile->file_type_id) ? $personelFile->file_type_id : null,['class'=>'form-control selectpicker file_types','required','data-lice-search'=>'true']) !!}
            <button  type="button"  data-toggle="modal" data-target="#FileTypeModal" class="btn btn-success ml-3">Evrak Türü Ekle</button>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group required">
            <label>Açıklama *</label>
            {!! Form::text('notification',isset($personelFile->notification) ? $personelFile->notification : null,['class'=>'form-control','required']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group required">
            <label>Dosya (PDF FORMATINDA) * </label>
            <br>
            {!! Form::file('file',null,['class'=>'form-control','required']) !!}
        </div>
    </div>


    <div class="col-lg-6">
        <div class="form-group required">
            <label>Personel Seçiniz</label>
            <br>
            {!! Form::select('employee',$employees,null,['class'=>'form-control selectpicker','required','data-live-search'=>'true']) !!}
        </div>
    </div>



    @if(isset($personelFile))
    <div class="col-lg-6" style="margin-top: 15px">
        <div class="form-group">
            <div class="form-check primary">
                <input type="checkbox" name="sms" id="checkColorOpt2">
                <label for="checkColorOpt2" style="font-weight: bold;font-size: 15px;">
                    Sms İle Bilgilendirme Yap
                </label>
            </div>
        </div>
    </div>
    @endif
</div>
</div>



@section('js')
<script>
    $("#file_type_create").click(function ()
    {
        var name = $("#file_name").val();
        $.ajax({
            type: "POST",
            url: '/ajax/employee/FileType/create',
            data: {
                _token: "{{csrf_token()}}",
                name:name,
            },

            success: function (response) {

                $('#FileTypeModal').modal('hide');
                $(".file_types").append(response['addValue']);
                $(".file_types").selectpicker("refresh");



            },

            error: function (error) {
                if( error.status === 422 ) {
                    $errors = error.responseJSON['errors'];
                    alert(error['name']);
                    if (! form.find('.form-errors').length) {
                        form.prepend('<div class="form-errors"></div>');
                    }

                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    $.each($errors, function(key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></di>';
                    form.find('.form-errors').html(errorsHtml);
                }
            }
        });

    });
</script>

@endsection
