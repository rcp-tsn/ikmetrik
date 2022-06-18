
<div class="card-body">
                           <div class="row">
                                   <div class="col-lg-6">
                                       <div class="form-group form-group-default form-group-default-select2  ">
                                           <label>Personel Seçiniz * </label>
                                           {!! Form::select('employee[]',$employees,isset($selectedEmployee) ? $selectedEmployee : null,['class'=>'form-control selectpicker','multiple'=>'true']) !!}

                                       </div>

                                   </div>
                               <div class="col-lg-6">
                                   <div class="form-group required">
                                       <label>Evrak İsmi * </label>
                                       {!! Form::text('name',isset($notification->name) ? $notification->name : null,['class'=>'form-control','required']) !!}
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
                                        <label>Açıklama *</label>
                                        {!! Form::text('notification',isset($notification->notification) ? $$notification->notification : null,['class'=>'form-control','required']) !!}
                                    </div>
                                </div>
                                <div class="form-check primary">
                                    <input type="checkbox" name="pdf_parse" id="checkColorOpt2">
                                    <label for="checkColorOpt2">
                                        Pdf Parçalama Yapılacak
                                    </label>
                                    <br>
                                    <span>Pdf Sayfası Birden Fazla Olmalıdır Seçilen Personel Sayısı İle Pdf Sayfası Eşit Olmalıdır!!</span>
                                </div>
                            </div>
                        </div>


