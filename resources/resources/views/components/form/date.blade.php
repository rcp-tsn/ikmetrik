<div class="form-group">
    <label>{{ $label ? $label : $name }}:</label>
    <div class="input-group date">
        <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
        </div>
        <input type="text" name="{{ $name }}" value="{{ $selected }}" class="form-control bs-input-datepicker pull-right">
    </div>
    <!-- /.input group -->
</div>