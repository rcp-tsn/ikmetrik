<div class="card-body">

        <div class="row">

            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-weight: bold;font-size: 15px;">PERFORMANS AĞIRLIK PUANI GİRİNİZ</label>
                    {!! Form::number('performance',isset($companyStatus) ? $companyStatus->performance_puan : null,['class'=>'form-control','placholder'=>'AĞIRLIK PUANI','min'=>'0','max'=>'100','required']) !!}
                    <span>Girilecek değer 0-100 Arasında Olmalıdır!</span>
                </div>
                <hr style="border: revert">

            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-weight: bold;font-size: 15px;">EĞİTİM AĞIRLIK PUANI GİRİNİZ</label>
                    {!! Form::number('education',isset($companyStatus->taban_maas) ? $companyStatus->education_puan : null ,['class'=>'form-control','placholder'=>'AĞIRLIK PUANI','min'=>'0','max'=>'100','required']) !!}
                    <span>Girilecek değer 0-100 Arasında Olmalıdır!</span>
                </div>
                <hr style="border: revert">

            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label style="font-size: 15px;font-weight: bold;">DİL BİLGİSİ AĞIRLIK PUANI GİRİNİZ</label>
                    <br>
                    {!! Form::number('language',isset($companyStatus->taban_maas) ? $companyStatus->language_puan : null ,['class'=>'form-control','placholder'=>'AĞIRLIK PUANI','min'=>'0','max'=>'100','required']) !!}
                    <span>Girilecek değer 0-100 Arasında Olmalıdır!</span>

                </div>
                <hr style="border: revert">

            </div>
        </div>


    <br>
    <span>NOT:GİRİLEN AĞIRLIK PAUANLAR TOPLAMI 100 OLMALIDIR!</span>

</div>
@push('js')

@endpush
