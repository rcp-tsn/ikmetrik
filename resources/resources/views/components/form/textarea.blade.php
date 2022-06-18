<div class="form-group">
    {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    {{ Form::textarea($name, $value, array_merge(['class' => 'form-control', 'rows' => 3, 'placeholder' => $label ? $label : $name], $attributes)) }}
</div>