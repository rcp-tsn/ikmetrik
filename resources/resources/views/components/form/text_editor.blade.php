<div class="form-group">
    {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    {{ Form::textarea($name, $value, array_merge(['class' => 'form-control', 'data-plugin' => 'summernote', 'id' => 'summernote'], $attributes)) }}
</div>