{{ Form::bsText('companies[name]', 'Şirket adı', $company->name) }}
{{ Form::bsFile('companies[logo]', 'Şirket logo', [], $company->logo) }}
{{ Form::bsTextarea('companies[address]', 'Şirket adresi', $company->address) }}

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label" for="lat">Enlem</label>
            <input type="text" class="form-control" id="lat" name="companies[lat]" value="{{ $company->lat }}" placeholder="Enlem">
        </div>
    </div>




    <div class="col-md-6">
        <div class="form-group">
            <label for="long">Boylam</label>
            <div class="input-group">
                <input type="text" class="form-control"  id="long" name="companies[long]" value="{{ $company->long }}" placeholder="Boylam">
                <div class="input-group-append">
                    <button type="button" data-target="#getCoordinatesFromMapModal"
                            data-toggle="modal" class="btn btn-default"> <span class="svg-icon svg-icon-primary svg-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>
    </g>
</svg><!--end::Svg Icon--></span></button>
                </div>
            </div>
        </div>
    </div>
</div>


{{ Form::bsText('companies[telephone]', 'Şirket telefonu', $company->telephone) }}
{{ Form::bsSelect('companies[performance_term]', 'Performans Dönemi', config('variables.months'), $company->performance_term) }}
{{ Form::bsSelect('companies[performance_period]', 'Performans Periyodu', config('variables.performance.periods'), $company->performance_period) }}


@if($subCompany)
    <input type="hidden" name="companies[company_id]" value="{{ $subCompany }}">
    {{ Form::bsSelect('companies[type]', 'Şirket tipi', ['location' => 'Lokasyon', 'sub_company' => 'Alt şirket'], $company->type) }}
@endif
