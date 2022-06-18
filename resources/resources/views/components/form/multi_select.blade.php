<div class="form-group">
    {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
    {{ Form::select($name, $list, $selected, array_merge(['class' => 'form-control selectpicker'], $selectAttributes), $attributes) }}
    <p class="help-block success">Eklemek istediklerinizi sol kutudan seçiniz.</p>
</div>
