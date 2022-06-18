<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="soruName">Soru İsmi Giriniz</label>
            <input id="soruName" required type="text" name="name" class="form-control">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="soruName">Soru Türü Seçiniz</label>
        {!! Form::select('question_type',config('variables.question_type'),null,['class'=>'form-control selectpicker mt-2','id'=>'question_type']) !!}
        </div>
    </div>

</div>

<div id="kt_repeater_3">
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-right"></label>

        <div data-repeater-list="" class="col-lg-12">
            @foreach(config('variables.target_questions') as  $question)

                <div data-repeater-item="" class="form-group row ml-10">
                    <div class="col-lg-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
																				<span class="input-group-text">
																					<i class="la la-phone"></i>
																				</span>
                            </div>
                            <input type="text" class="form-control" name="questions" placeholder="Sorular" value="{{$question}}">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        {!! Form::select('question_grup_id',config('variables.question_grub_type'),null,['class'=>'form-control mt-2 QuestionGrub','id'=>'QuestionGrub']) !!}
                    </div>
                    <div class="col-lg-2">
                        <a href="javascript:;" data-repeater-delete="" class="btn font-weight-bold btn-danger btn-icon">
                            <i class="la la-remove"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    <div class="form-group row">
        <div class="col-lg-3"></div>
        <div class="col">
            <div data-repeater-create="" class="btn font-weight-bold btn-primary">
                <i class="la la-plus"></i>Add</div>
        </div>
    </div>
</div>
@section('js')
    <script src="/assets/js/pages/crud/forms/widgets/form-repeater.js"></script>


    <script>
        $('#QuestionsCreate').click(function(event) {  //on click
            var questions = [];
            var sorular = [];
            $("input[name*='questions']").each(function (index) {
                var input = 0;
                input = $(this).val();
                sorular[index] =  [input]
            });
            $('.QuestionGrub option:selected').each(function(info) {

                var   department = $(this).val();
                questions[info] =  [department]

            });
            var name = $("#soruName").val();
            var question_type = $("#question_type").val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/company_question_store',
                data: {
                    name:name,
                    question_type:question_type,
                    sorular:sorular,
                    questions:questions
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
