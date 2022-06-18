<div class="form-group">
    <label>{{ __($routeParts[0].'.fields.name') }}</label>
    {!! Form::text("name",null,['class'=>'form-control form-control-solid','id'=>'name','required'=>'required']) !!}
</div>
<div class="form-group">
    <label>{{ __($routeParts[0].'.fields.title') }}</label>
    {!! Form::text("title",null,['class'=>'form-control form-control-solid','id'=>'title']) !!}
</div>
<div class="form-group">
    <label>{{ __($routeParts[0].'.permissions_for_roles') }}:</label>
    <div class="checkbox-list">
        @foreach($permissions as $permission)
        <label class="checkbox">
            {{ Form::checkbox('permissions[]', $permission->id, in_array($permission->id, $selectPermissions) ? true : false) }} {{ $permission->name }}
            <span></span>
        </label>
        @endforeach
    </div>
</div>


