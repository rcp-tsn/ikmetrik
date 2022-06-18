<?php

namespace App\Providers;

use Form;
use Illuminate\Support\ServiceProvider;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Form components
         */
        Form::component('bsText', 'components.form.text', ['name', 'label' => null, 'value' => null, 'attributes' => [], 'help' => null]);
        Form::component('bsPrice', 'components.form.price', ['name', 'label' => null, 'value' => null, 'attributes' => [], 'help' => null]);
        Form::component('bsNumber', 'components.form.number', ['name', 'label' => null, 'value' => null, 'attributes' => [], 'help' => null]);
        Form::component('bsEmail', 'components.form.email', ['name', 'label' => null, 'value' => null, 'attributes' => []]);
        Form::component('bsPassword', 'components.form.password', ['name', 'label' => null, 'attributes' => []]);
        Form::component('bsTextarea', 'components.form.textarea', ['name', 'label' => null, 'value' => null, 'attributes' => []]);
        Form::component('bsTextEditor', 'components.form.text_editor', ['name', 'label' => null, 'value' => null, 'attributes' => []]);
        Form::component('bsSelect', 'components.form.select', ['name', 'label' => null, 'list', 'selected' => null, 'selectAttributes' => [], 'attributes' => [], 'additions' => []]);
        Form::component('bsMultiSelect2', 'components.form.multi_select2', ['name', 'label' => null, 'list', 'selected' => null, 'selectAttributes' => [], 'attributes' => [], 'additions' => []]);
        Form::component('bsMultiSelect', 'components.form.multi_select', ['name', 'label' => null, 'list', 'selected' => null, 'selectAttributes' => [], 'attributes' => []]);
        Form::component('bsCheckbox', 'components.form.checkbox', ['name', 'label' => null, 'value' => null, 'checked' => null, 'attributes' => [], 'help' => null]);
        Form::component('bsRadio', 'components.form.radio', ['name', 'label' => null, 'value' => null, 'checked' => null, 'attributes' => []]);
        Form::component('bsFile', 'components.form.file', ['name', 'label' => null, 'attributes' => [], 'link' => null, 'labelShow' => true]);
        Form::component('bsDate', 'components.form.date', ['name', 'label' => null, 'selected' => null]);
        Form::component('bsSubmit', 'components.form.submit', ['name', 'attributes' => []]);
        Form::component('bsCancel', 'components.form.cancel', ['name', 'attributes' => []]);
        Form::component('bsUpdate', 'components.form.update', ['name', 'attributes' => []]);
        Form::component('bsSave', 'components.form.save', ['name', 'attributes' => []]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
