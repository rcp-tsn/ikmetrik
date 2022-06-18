<div class="card-body">
    <div class="row">
        <div class="col-lg-6">
            <label>İletişim  Türü * </label>
            <div class="form-group  required">
                {!! Form::select('type',config('variables.crm.contack_types') ,isset($notification->type) ? $notification->type : null,['class'=>'form-control selectpicker file_types','required','data-lice-search'=>'true']) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group required">
                <label>Resim Ekle * </label>
                <br>
                {!! Form::file('image',null,['class'=>'form-control','required']) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Mesaj</label>
                <textarea name="message" class="form-control " required id="" cols="30" rows="10">
                    {{isset($notification->message) ? $notification->message : ' '}}
                </textarea>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group required">
                <label>Başlık *</label>
                {!! Form::text('title',isset($notification->title) ? $notification->title : null,['class'=>'form-control','required']) !!}
            </div>
        </div>
    </div>
    <div class="row">
        @if(isset($notifications))
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
    @if(isset($sgk_companies))
        <div class="container-fluid container mt-10">
            <div class="row">
                <div class="col-lg-5">

                    <div class="form-group">

                        <label>Şube Seçiniz</label>
                        {!! Form::select('sgk_company_id',$sgk_companies,null,['class'=>'form-control selectpicker','data-live-search'=>'true','id'=>'sgk_company']) !!}
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="form-group">
                        <label>Departman Seçiniz</label>
                        {!! Form::select('department',$departments,null,['class'=>'form-control selectpicker','data-live-search'=>'true','id'=>'department']) !!}
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <button class="btn btn-success mt-8" type="button" id="btn-filter">Filtrele</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive" style="height: 40%">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-lg-1">
                            <div class="form-group">
                                <label>listeleme</label>
                                <select  class="custom-select custom-select-sm form-control form-control-sm" name="" id="type">
                                    <option value="">4</option>
                                    <option value="">20</option>

                                    <option value="">40</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-9"></div>
                        <div class="col-lg-2">
                            <div class="form-group" >
                                <label for="search">Personel Arama : </label>
                                <input type="text" name="search" id="search" class="form-control search">
                            </div>
                        </div>
                    </div>
                    <div style="max-height: 500px">
                        <table class="table table-bordered applicant_employee" id="applicant_employee">

                            <thead>
                            <tr>
                                <th>
                                    <label class="checkbox checkbox-rounded" style="display:initial">
                                        <input id="allSelect"  type="checkbox">
                                        <span></span></label>
                                </th>
                                <th>Adı Soyadı</th>
                                <th>Departman</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif


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
