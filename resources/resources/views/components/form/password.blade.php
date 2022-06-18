<div class="form-group">
    {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    {{ Form::password($name, array_merge(['class' => 'form-control'], $attributes)) }}
</div>