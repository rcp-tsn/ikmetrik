<div class="card-body">
    @foreach($departments as $key =>  $department )

    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">

                <label style="font-weight: bold;font-size: 15px;"></label>
                <label style="font-weight: bold;font-size: 15px;padding-top: 30px;padding-left: 50px">{{$department->name}}</label>
                {!! Form::hidden('department['. $department->id.']',$department->name,['class'=>'form-control','readonly','type'=>'hidden']) !!}

            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label style="font-weight: bold;font-size: 15px;">Hedef Türü</label>
                {!! Form::text('name['. $department->id.']',isset($targetSelected[$department->id]) ? $targetSelected[$department->id]['name'] : null,['class'=>'form-control','placholder'=>'Hedef Türü']) !!}
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label style="font-weight: bold;font-size: 15px;">Hedef</label>
                {!! Form::number('target['. $department->id .']',isset($targetSelected[$department->id]) ? $targetSelected[$department->id]['target'] : null,['class'=>'form-control','placholder'=>'Hedef']) !!}
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label style="font-size: 15px;font-weight: bold;">Yapılan</label>
                <br>
                {!! Form::number('targets['. $department->id.']',isset($targetSelected[$department->id]) ? $targetSelected[$department->id]['happening'] : null,['class'=>'form-control']) !!}
            </div>
        </div>
    </div>
        <hr style="border: revert">
    @endforeach
</div>
