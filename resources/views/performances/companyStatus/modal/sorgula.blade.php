<style>
    label
    {
        font-size: 15px;
        font-weight: bold;
    }
    #minMaas
    {
        font-size: 15px;
        font-weight: bold;
    }
    #maxMaas
    {
        font-size: 15px;
        font-weight: bold;
    }
    #toplamPuan
    {
        font-size: 15px;
        font-weight: bold;
    }
</style>
<div class="modal fade" id="sorgulaModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Personel Maaş Sorgula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                  <div class="col-lg-6">
                      <div class="form-group">
                          <label style="font-weight: bold;font-size: 15px;">Personel</label>
                          {!! Form::select('employee',$employees, null,['class'=>'form-control selectpicker','data-live-search'=>'true','id'=>'employee_id']) !!}
                      </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="form-group">
                          <label style="font-weight: bold;font-size: 15px;">Pozisyon</label>
                          {!! Form::text('pozisyon',null,['class'=>'form-control','readonly','id'=>'pozisyon']) !!}
                      </div>
                  </div>
              </div>
                <br>
                <div class="Sonuc" style="display: none">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label style="font-weight: bold;font-size: 15px;">PERFORMANS PUAN</label>
                                {!! Form::text('performance', null,['class'=>'form-control ','readonly','id'=>'performance']) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label style="font-weight: bold;font-size: 15px;">EĞİTİM PUANI</label>
                                {!! Form::text('education',null,['class'=>'form-control','readonly','id'=>'education']) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label style="font-weight: bold;font-size: 15px;">DİL PUANI</label>
                                {!! Form::text('language',null,['class'=>'form-control','readonly','id'=>'language']) !!}
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group" style="text-align: right">
                                <label style="font-size: 20px;font-weight: bold">En Az Maaş :<label style="font-size: 20px;font-weight: bold" id="minMaas"></label></label><label style="font-size: 20px;font-weight: bold"
                                >₺</label>
                            </div>
                        </div>
                        <div class="col-lg-6"style="text-align: left">
                            <div class="form-group">
                                <label style="font-size: 20px;font-weight: bold">En Fazla Maaş :<label style="font-size: 20px;font-weight: bold" id="maxMaas"></label></label><label style="font-size: 20px;font-weight: bold"
                                    >₺</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div style="text-align: center;font-size: 20px;font-weight: bold;background-color: orange">
                    <label  for="">PERSONEL SONUÇLAR</label>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label style="font-weight: bold;font-size: 15px;">PERSONEL PERFORMANS PUAN</label>
                                {!! Form::text('performance', null,['class'=>'form-control ','readonly','id'=>'personelPerformance']) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label style="font-weight: bold;font-size: 15px;"> PERSONELEĞİTİM PUANI</label>
                                {!! Form::text('education',null,['class'=>'form-control','readonly','id'=>'personelEducation']) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label style="font-weight: bold;font-size: 15px;"> PERSONEL DİL PUANI</label>
                                {!! Form::text('language',null,['class'=>'form-control','readonly','id'=>'personelLanguage']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div style="font-size: 15px;font-weight: bold" class="form-group">
                            <label style="font-size: 20px;font-weight: bold">Toplam Puan :<label style="font-size: 20px;font-weight: bold"  id="toplamPuan"></label></label>
                        </div>
                    </div>
                    <label for="">Sonuc</label>
                    <br>
                    <div style="text-align: center;font-size: 20px;font-weight: bold;background-color:green;color: white">
                    <label style="font-size: 20px;font-weight: bold;" >ALMASI GEREKEN ÜCRET: <label style="font-size: 20px;font-weight: bold;" id="Sonuc"></label></label>₺
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold sorgula">SORGULA</button>
            </div>
        </div>
    </div>
</div>
