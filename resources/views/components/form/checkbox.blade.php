{{--<div class="form-group">
    <div class="pull-left margin-right-20">
        {{ Form::checkbox($name, $value, $checked, array_merge(['data-plugin' => 'switchery'], $attributes)) }}
    </div>
    {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    @if($help)<p class="help-block">{{ $help }}</p>@endif
</div>--}}

<div class="form-group">
    {{ Form::checkbox($name, $value, $checked, array_merge([
        'data-plugin' => 'iCheck',
        'data-checkbox-class' => 'icheckbox_flat-blue',
        'class' => 'icheckbox-primary',
        'id' => $name,
        ], $attributes)) }}
    {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    @if($help)<p class="help-block">{{ $help }}</p>@endif
</div>

{{--
<div class="checkbox-custom checkbox-primary">
    {{ Form::checkbox($name, $value, $checked, $attributes) }}
    {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    @if($help)<p class="help-block">{{ $help }}</p>@endif
</div>--}}
