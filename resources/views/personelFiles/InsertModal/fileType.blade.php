<div class="modal fade" id="FileTypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Evrak Türü <span>İş başvuru,İş giriş vb</span> </label>
                    {!! Form::text('name',null,['class'=>'form-control','id'=>'file_name']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                <button type="button" id="file_type_create" class="btn btn-primary font-weight-bold">Kaydet</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="fileUpload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form action="{{route('personelFiles.fileUpload')}}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="" style="font-size: 15px;font-weight: bold;">Dosya (PDF FORMATINDA)</label>
                    <br>
                    {!! Form::file('file',null,['class'=>'form-control','id'=>'file']) !!}
                </div>
            </div>
                <input type="hidden" class="fileUpload" name="id" value="">
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                <button type="submit" id="file_type_create" class="btn btn-primary font-weight-bold">Kaydet</button>
            </div>
            </form>
        </div>
    </div>
</div>


{{--//Tebligat itiraz formu--}}
<div class="modal fade slide-up disable-scroll" id="protest" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog ">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header clearfix text-left">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                    <h5><span class="semi-bold">Tebligat İtiraz</span></h5>
                    <p class="p-b-10">Aşşağıdaki Formu Doldurunuz</p>
                </div>
                <div class="modal-body">
                    <form role="form" id="protestForm" method="Post" action="{{route('employee_file_protest')}}">
                        @csrf
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">İtiraz Nedeni Yazınız</label>
                            <textarea class="form-control" name="notification" id="exampleFormControlTextarea1" required rows="5"></textarea>
                        </div>

                        <input type="hidden" name="file_id" class="notification_id3" value="">
                        <input type="hidden" name="employee_id" class="working_id3" value="">


                    </form>
                    <div class="row">
                        <div class="col-md-4 m-t-10 sm-m-t-10">
                            <button aria-label="" type="button" class="btn btn-primary protest_submit btn-block m-t-5">Gönder</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
{{--//Tebligat Onay Formu--}}

<div class="modal fade fill-in" id="NotificationSms" tabindex="-1" role="dialog" style="display: none; padding-right: 17px;" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-left p-b-5"><span class="semi-bold">Telefonuza Gönderilen Onay Kodunu </span> Giriniz</h5>
            </div>
            <div class="modal-body">
                <form id="notificaiton_accept_employee" action="{{route('employee_file_accept')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 ">
                            <input type="text" placeholder="Onay Kodu"  required class="form-control onay_code"  name="code">
                        </div>
                        <input type="hidden" name="file_id" class="payroll_i2" value="">
                        <input type="hidden" name="employee_id" class="working_i2" value="">
                        <div class="col-lg-3 no-padding sm-m-t-10 sm-text-center">
                            <button aria-label="" type="button" class="btn btn-primary btn-lg code">Onayla</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade slide-up disable-scroll" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Yeni Bordro Tek Sayfa Olacak Şekilde Pdf Formatında Yüklenmelidir Kişiye Ait Bordro Yüklendiğinden Emin Olun</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form  method="post" action="{{route('bordrolama.edit')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label>Bordro</label>
                                <input type="file" name="pdf" class="form-control" required>
                                <input type="hidden" name="payroll_id" value="" class="editPayrollId">
                                <input type="hidden" name="employee_id" value="" class="editEmployeeId">
                                <input type="hidden" name="page_id" value="" class="editPageId">
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group ">
                                <label>Açıklama Ekleyin</label>
                                <input type="text" name="notification" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-check primary">
                        <input type="checkbox" name="sms" id="checkColorOpt2">
                        <label for="checkColorOpt2">
                            Sms İle Bilgilendirme Yap
                        </label>
                    </div>


                    <div class="row">
                        <div class="col-md-4 m-t-10 sm-m-t-10">
                            <button aria-label="" type="submit" class="btn btn-primary btn-block m-t-5">Güncelle</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- itiraz formları -->
@if(isset($protests))
    @foreach($protests as $protest)

        <div class="modal fade fill-in" id="protestForms{{$protest->id}}" tabindex="-1" role="dialog" style="display: none; padding-right: 17px;" aria-modal="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                        <h5 class="text-left p-b-5"><span class="semi-bold">Bordro İtiraz Formu </span> <p class="p-b-10">{{isset($protest->employee) ? $protest->employee->full_name:null}}</p></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">İtiraz Nedeni Yazınız</label>
                            <textarea class="form-control" style="color: black;font-size: 13px;font-weight: bold" readonly name="notification" id="exampleFormControlTextarea1"   rows="6">
                                            {{$protest->notification}}
                                        </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endforeach
@endif
