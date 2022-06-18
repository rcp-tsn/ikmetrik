{{--
<label>
    {{ Form::radio($name, $value, $checked, array_merge(['class' => 'minimal form-control'], $attributes)) }}
    {{ $label ? $label : $name }}
</label>--}}
<div style="margin-bottom: 7px;">
    {{ Form::radio($name, $value, $checked, array_merge([
        'data-plugin' => 'iCheck',
        'data-radio-class' => 'iradio_flat-blue',
        'class' => 'icheckbox-primary',
        'id' => $name . $value,
        ], $attributes)) }}
    {{ Form::label($name . $value, $label ? $label : $name) }}
</div>

{{--
<input type="radio" class="icheckbox-primary" id="inputRadiosUnchecked" name="inputRadios"
       data-plugin="iCheck" data-radio-class="iradio_flat-blue" />
<label for="inputRadiosUnchecked">Unchecked</label>--}}
