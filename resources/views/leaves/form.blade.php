<div class="card-body">

    <style>
        .font-ozel
        {
            font-weight: bold;
            font-size: 15px;
        }
    </style>

    <div class="row">

        <div class="col-lg-12">
            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Leaves'))
            <label class="font-ozel">Personel Seçiniz</label>
            @endif
            <div class="form-group">
                {!! Form::select('employee',$employees,isset($leave->employee_id) ? $leave->employee_id : null,['class'=>'form-control selectpicker','data-live-search'=>'true','required'=>'true']) !!}
            </div>
        </div>

    </div>




    <div class="row">
        <div class="col-lg-6">
            <label class="font-ozel">İzin Türü</label>
            <div class="form-group  required">
                {!! Form::select('leave_type',config('variables.employees.leave_type'), null,['class'=>'form-control selectpicker file_types','required','data-lice-search'=>'true']) !!}

            </div>
        </div>
        <div class="col-lg-6">
            <label class="font-ozel">İzin Başlangıç Tarihi</label>
            <div class="form-group input-group required">

                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Leaves'))
                    {!! Form::text("start_date", null,['class'=>'form-control datepicker','id'=>'start_date', 'required'=>'required', 'autocomplete' => 'off']) !!}
               @else

                    {!! Form::text("start_date", null,['class'=>'form-control date-personel ','id'=>'start_date' ,'required'=>'required' ,  'autocomplete' => 'off']) !!}
               @endif

                <div class=" date" id="startTime">
                    {!! Form::time("start_time", '09:00',['class'=>'form-control','id'=>'start_time', 'autocomplete' => 'off']) !!}
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group required">
                <label class="font-ozel">Kullanılacak Gün (Saatlik İzinler İçin 0 Bırakınız) * </label>
                {!! Form::text("days",isset($leave->day) ? $leave->day :  null,['class'=>'form-control','id'=>'days','required'=>'required']) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <label class="font-ozel">İzin Bitiş Tarihi ve Saati</label>
            <div class="form-group input-group required">

                        {!! Form::text("job_start_date", null,['class'=>'form-control ','id'=>'jobStartDate','readonly','value'=>'']) !!}


                        {!! Form::time("job_start_time", '09:00',['class'=>'form-control','id'=>'job_start_time', 'autocomplete' => 'off']) !!}
                 </div>
        </div>

    </div>

        <div class="form-group">
            <label style="font-weight: bold;font-size: 15px;" class="font-ozel">Açıklama</label>
            {!! Form::text("description", null,['class'=>'form-control','id'=>'description','required'=>'required']) !!}
        </div>

    <div class="form-group">

        <label style="font-weight: bold;font-size: 15px;" class="font-ozel">Açıklama</label>
        <br>
        <input type="file" name="files[]" multiple>

    </div>

</div>



@section('js')

    <script>
        var leave_type = 0;
        $("select").change(function() {
            $("select[name=leave_type] option:selected").each(function () {
                leave_type = $(this).attr("value");
            });
        });
        console.log(leave_type);
        function addDays(date, days) {
            var result = new Date(date);
            var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];

            result.setDate(result.getDate() + days);
            return result;
        }
        function formatDate(date) {
            if (date.getMonth() < 9)
            {
                return date.getDate() + '/' + "0"+(date.getMonth() + 1) + '/' + date.getFullYear();
            }
            else
            {
                return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
            }

        }



        $("#days").change(function() {
            var dateString = $('#start_date').val();

            var add_day = Number($(this).val());

            var dateString = dateString.split("/");//27/11/1977

            var formated_date = Number(dateString[2]) + "-" + dateString[1] + "-" + Number(dateString[0]);

            var date = new Date(formated_date);
            console.log(date);
            var leave_type = 0;

                $(".file_types option:selected").each(function () {
                    leave_type = $(this).attr("value");
                });

            console.log(leave_type);

            if (leave_type == '12' || leave_type == '4' || leave_type == '13')
            {
                if (add_day > 0)
                {
                    for (var ab = 0; ab <= add_day ; ab++) {
                        var a = Number(ab);
                        var b = addDays(date, a);
                        var days = ['Pazar', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                        var date2 = new Date(b);
                        if (days[date2.getDay()] == 'Pazar')
                        {
                            add_day +=1;
                        }
                    }
                }


            }


            if (add_day >0)
            {
                var correctDate = formatDate(addDays(date, add_day));

                document.getElementById("jobStartDate").value = correctDate;
                $('#jobStartDate').val(correctDate);

            }
            else
            {
                var correctDate = formatDate(date);
                document.getElementById("jobStartDate").value = correctDate;
                $('#jobStartDate').val(correctDate);
            }




        });

        $('.datepicker').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,

        }).on('changeDate', function (ev) {
            $(this).datepicker('hide');

        });



        $('#leave_type').select2({
            dropdownParent: $('#modal-default')
        });


        $('#btnSaveFinish').click(function() {
            if ($("#frmModalDetail").valid()) {
                $( '#frmModalDetail' ).submit();
            } else {
                return false;
            }
        });




        $('#reset').on('click', function() {
            validator.resetForm();
        });


    </script>


    <script >
        $('.date-personel').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            startDate: new Date(),

        }).on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });

    </script>

@endsection
