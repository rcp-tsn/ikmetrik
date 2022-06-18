<div class="form-group">
    @if($labelShow)
        {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    @endif
        <div class="custom-file">
            {{ Form::file($name, array_merge(['class' => 'custom-file-input'], $attributes)) }}
            <label class="custom-file-label" for="customFile">YÜKLE</label>
        </div>
</div>
