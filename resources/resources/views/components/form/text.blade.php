<div class="form-group">
    @if((isset($attributes['label']) && $attributes['label'] !== false) || !isset($attributes['label']))
        @if(isset($attributes['required']) && $attributes['required'] == true)
            {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label required']) }}
        @else
            {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
        @endif
    @endif
    {{ Form::text($name, $value, array_merge(['class' => 'form-control', 'placeholder' => $label ? $label : $name], $attributes)) }}
    @if($help)<span class="form-text text-muted">{{ $help }}</span>@endif
</div>
