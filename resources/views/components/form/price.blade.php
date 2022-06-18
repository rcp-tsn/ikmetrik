<div class="form-group">
    @if(isset($attributes['required']) && $attributes['required'] == true)
        {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label required']) }}
    @else
        {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    @endif

    {{ Form::text($name, $value,
    array_merge([
        'class' => 'form-control price',
        'data-buttondown_class' => 'btn btn-secondary',
        'data-buttonup_class' => 'btn btn-secondary',
        'data-min' => 0,
        'data-max' => 9999,
        'data-step' => '0.50',
        'data-decimals' => 2,
        'data-boostat' => 5,
        'data-maxboostedstep' => 10,
        'data-postfix' => '₺',
        ], $attributes)) }}

    @if($help)<p class="help-block">{{ $help }}</p>@endif
</div>
