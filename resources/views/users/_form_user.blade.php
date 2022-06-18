<div class="form-group">
    <label>Ad Soyad</label>
    {!! Form::text("name",$user->name,['class'=>'form-control form-control-solid','id'=>'name','required'=>'required']) !!}
</div>
<div class="form-group">
    <label>Email</label>
    {!! Form::text("email",$user->email,['class'=>'form-control form-control-solid','id'=>'email','required'=>'required']) !!}
</div>
