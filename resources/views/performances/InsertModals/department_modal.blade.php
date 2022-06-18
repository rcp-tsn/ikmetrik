<div class="modal fade" id="DepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <label>Department Giriniz </label>
                    {!! Form::text('name',null,['class'=>'form-control','id'=>'department_name']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                <button type="button" id="deparment_create" class="btn btn-primary font-weight-bold">Kaydet</button>
            </div>
        </div>
    </div>
</div>
<?php
