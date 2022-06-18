<div class="card-body">
    @foreach($performance_program_types as $key =>  $type )

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">

                <label style="font-weight: bold;font-size: 15px;padding-top: 30px;padding-left: 50px;">{{$type->performance_type()}}</label>
                {!! Form::hidden('performance_type',$type->performance_type3(),null,['class'=>'form-control ']) !!}
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label style="font-weight: bold;font-size: 15px;">Puan</label>
                {!! Form::text('target',$type->puan,['class'=>'form-control','placholder'=>'Min Zam','readonly']) !!}
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label style="font-size: 15px;font-weight: bold;">Hedef Puan</label>
                <br>
                {!! Form::text('targets['. $type->id.'][]',isset($target_services[$key]) ? $target_services[$key]['target_puan'] : null,['class'=>'form-control','min'=>'1','max'=>"$type->puan"]) !!}
            </div>
        </div>
    </div>
        <hr style="border: revert">
    @endforeach
</div>
