@if(isset($additions['addButton']))

    @php $additions['addButton']['authorization'] = isset($additions['addButton']['authorization'])
    ? $additions['addButton']['authorization']
    : ['role' => null, 'permissions' => null] @endphp

        @php $idKey = md5($additions['addButton']['type'] . ' - ' . time()); @endphp
        <div class="form-group">
            @isset($additions['blankSelect']) @php $list = array_replace([null => 'Seçiniz'], $list) @endphp @endisset

            @if(isset($attributes['required']) && $attributes['required'] == true)
                {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label required']) }}
            @else
                {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
            @endif

            <div class="input-group">
                {{ Form::select($name, $list, $selected, array_merge(['class' => 'select2', 'multiple' => 'multiple', 'data-live-search' => 'true', 'style' => 'width: 100%'], $selectAttributes), $attributes) }}

                @ability($additions['addButton']['authorization']['role'], $additions['addButton']['authorization']['permissions'])
                <span class="input-group-btn">
                    <button id="{{ $idKey }}" data-url="{{ route('insert_modals.create', $additions['addButton']['type']) }}"
                            onclick="loadInsertModal.create('{{ $idKey }}')"
                            @if(isset($additions['addButton']['selectorId'])) data-selector-id="{{ $additions['addButton']['selectorId'] }}" @endif
                            type="button" class="btn btn-success">
                        {{ $additions['addButton']['text'] }}
                    </button>
                </span>
                @push('modals')
                    <div class="modal fade modal-info" id="modal-{{ $idKey }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                @include('insert_modals.' . $additions['addButton']['type'])
                            </div>
                        </div>
                    </div>
                @endpush
                @endability

            </div>
            @isset($additions['help'])<p class="help-block">{{ $additions['help'] }}</p>@endif
        </div>
@else
    <div class="form-group">
        @isset($additions['blankSelect']) @php $list = array_replace([null => 'Seçiniz'], $list) @endphp @endisset

        @if(isset($attributes['required']) && $attributes['required'] == true)
            {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label required']) }}
        @else
            {{ Form::label($name, $label ? $label : $name, ['class' => 'control-label']) }}
        @endif

        {{ Form::select($name, $list, $selected, array_merge(['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'multiple' => 'multiple'], $selectAttributes), $attributes) }}
        @isset($additions['help'])<p class="help-block">{{ $additions['help'] }}</p>@endif
    </div>
@endif
