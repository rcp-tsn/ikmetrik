<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label>Soru İsmi Giriniz</label>
            {!! Form::text('name',isset($job_question) ? $job_question->name : null,['class'=>'form-control selectpicker mt-2','id'=>'SoruName']) !!}
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label >Persoenel Türü Seçiniz</label>
            {!! Form::select('question_type',config('variables.employees.work_type'),isset($job_question) ? $job_question->work_type_id : null,['class'=>'form-control selectpicker mt-2','id'=>'question_type']) !!}
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label >Şube Seçiniz</label>
            {!! Form::select('sgk_company',$sgk_companies,isset($job_question) ? $job_question->sgk_company_id : null,['class'=>'form-control selectpicker mt-2','id'=>'sgk_company']) !!}
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label >Departman Seçiniz</label>
            {!! Form::select('question_grup_id',$departments, isset($job_question) ? $job_question->department_id : null,['class'=>'form-control mt-2 department','id'=>'department']) !!}
        </div>
    </div>

</div>
<input type="hidden" id="id" value="{{isset($id) ? $id : null}}">
@if(isset($questions))
    <div id="kt_repeater_3">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label text-right"></label>
            @foreach($questions as $question)
                <div data-repeater-list="" class="col-lg-12">
                    <div data-repeater-item="" class="form-group row ml-15">
                        <div class="col-lg-10">

                            <div class="input-group">
                                <div class="input-group-prepend">
																				<span class="input-group-text">
																					<i class="la la-phone"></i>
																				</span>
                                </div>
                                <input type="text" class="form-control mt-2" name="questions" placeholder="Sorular" value="{{$question->question}}">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <a href="javascript:;" data-repeater-delete="" class="btn font-weight-bold btn-danger btn-icon">
                                <i class="la la-remove"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="form-group row">
            <div class="col-lg-3"></div>
            <div class="col">
                <div data-repeater-create="" class="btn font-weight-bold btn-primary">
                    <i class="la la-plus"></i>Ekle</div>
            </div>
        </div>
    </div>
@else
    <div id="kt_repeater_3">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label text-right"></label>

            <div data-repeater-list="" class="col-lg-12">
                <div data-repeater-item="" class="form-group row ml-15">
                    <div class="col-lg-10">

                        <div class="input-group">
                            <div class="input-group-prepend">
																				<span class="input-group-text">
																					<i class="la la-phone"></i>
																				</span>
                            </div>
                            <input type="text" class="form-control mt-2" name="questions" placeholder="Sorular" value="">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <a href="javascript:;" data-repeater-delete="" class="btn font-weight-bold btn-danger btn-icon">
                            <i class="la la-remove"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-group row">
            <div class="col-lg-3"></div>
            <div class="col">
                <div data-repeater-create="" class="btn font-weight-bold btn-primary">
                    <i class="la la-plus"></i>SATIR EKLE</div>
            </div>
        </div>
    </div>
@endif



@section('js')
    <script src="/assets/js/pages/crud/forms/widgets/form-repeater.js"></script>
    <script>
        $('#QuestionsCreate').click(function() {  //on click

            var sorular = [];
            $("input[name*='questions']").each(function (index) {
                var input = 0;
                input = $(this).val();
                sorular[index] =  [input]
            });
            var department = $(".department").val();
            var name = $("#SoruName").val();
            var question_type = $("#question_type").val();
            var sgk_company = $("#sgk_company").val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/company_polivalans_question_store',
                data: {
                    name:name,
                    question_type:question_type,
                    sorular:sorular,
                    department:department,
                    sgk_company:sgk_company
                },
                success: function (alert) {
                    $('#exampleModal').modal('toggle');
                    Swal.fire({
                        position: "top-right",
                        icon: alert['type'],
                        title: alert['message'],
                        showConfirmButton: false,
                        timer: 3500
                    });
                    if (alert['type']=='success')
                    {
                        window.location.href = '{{route('company_questions_index')}}';
                    }

                },
            });
        });

    </script>
@endsection

