<div class="form-group">
    {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    {{ Form::email($name, $value, array_merge(['class' => 'form-control', 'placeholder' => $label ? $label : $name], $attributes)) }}
</div>