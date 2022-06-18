<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
    <h4 class="modal-title">Kullanıcı Ekle</h4>
</div>
<div class="modal-body">
    {!! Form::open(['route' => ['insert_modals.store', 'user'], 'id' => 'insert-user-form','class' => 'form']) !!}

    <div class="row">
        <div class="col-sm-6">{{ Form::bsText('name', 'İsim Soyisim') }}</div>
        <div class="col-sm-6">{{ Form::bsText('email', 'Email') }}</div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('password', 'Şifre', ['class' => 'control-label']) }}
                <div class="input-group">
                    {{ Form::text('password', null, array_merge(['class' => 'form-control', 'id' => 'pw-generate'])) }}
                    <span class="input-group-btn">
                    <a onclick="pwGenerate(10);" class="btn btn-primary">
                        <i class="icon wb-random" aria-hidden="true"></i> Şifre Üret
                    </a>
                </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">{{ Form::bsText('password_confirmation', 'Şifre tekrar', null, ['id' => 'pw-generate-confirmation']) }}</div>

    </div>


    {!! Form::close() !!}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">KAPAT</button>
    <button type="button" class="btn btn-primary font-weight-bold" onclick="loadInsertModal.store('#insert-user-form')">KAYDET</button>
</div>

<script>
    function pwGenerate (length) {
        var arr = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
            "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
        var randomLetter = "";
        for (i = 0; i < length; i++) {
            randomLetter += arr[Math.floor(arr.length * Math.random())];
        }
        document.getElementById("pw-generate").value = randomLetter;
        document.getElementById("pw-generate-confirmation").value = randomLetter ;
    }
</script>
