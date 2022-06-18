<div class="form-group">
    @if((isset($attributes['label']) && $attributes['label'] !== false) || !isset($attributes['label']))
        @if(isset($attributes['required']) && $attributes['required'] == true)
            {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label required']) }}
        @else
            {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
        @endif
    @endif

        {{ Form::text($name, $value,
        array_merge([

            'class' => 'form-control number',
            'data-buttondown_class' => 'btn btn-secondary',
            'data-buttonup_class' => 'btn btn-secondary',
            'data-min' => 0,
            'data-max' => 9999,
            'data-step' => '1',
            'data-decimals' => 0,
            'data-boostat' => 5,
            'data-maxboostedstep' => 10,
            ], $attributes)) }}

    @if($help)<p class="help-block">{{ $help }}</p>@endif
</div>
