<div class="modal fade" id="workingTitleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            {!! Form::open(['route' => ['working_title'], 'files'=>'true',  'class' => 'form', 'id' => 'title_form']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <label>Ünvan Giriniz </label>
                    {!! Form::text('name',null,['class'=>'form-control','id'=>'working_title_name']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                <button type="button" id="working_title_create" class="btn btn-primary font-weight-bold">Kaydet</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
