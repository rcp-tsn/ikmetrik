<div class="panel panel-bordered panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            Filtrele
        </h3>
    </div>
    <div class="panel-body">
        {!! Form::open(['route' => $route, 'method' => 'get']) !!}
        <div class="row">
            @if($filterType === 'group')
                <div class="col-sm-3">
                    {{ Form::bsSelect('work_title', 'Ünvan', $work_titles, isset($requestData['work_title']) ? $requestData['work_title'] : '', [], [], ['blankSelect' => true]) }}
                </div>
            @else
                <div class="col-sm-3">
                    {{ Form::bsSelect('group', 'Grup', $groups, isset($requestData['group']) ? $requestData['group'] : '', [], [], ['blankSelect' => true]) }}
                </div>
            @endif
            <div class="col-sm-3">
                {{ Form::bsSelect('department', 'Departman', $departments, isset($requestData['department']) ? $requestData['department'] : '', [], [], ['blankSelect' => true]) }}
            </div>
            <div class="col-sm-4">
                {{ Form::bsText('search', 'Arama', isset($requestData['search']) ? $requestData['search'] : '', ['placeholder' => 'Aranacak Kelime']) }}
            </div>
            <div class="col-md-2">
                <button type="submit" style="margin-top: 26px;" class="btn btn-block btn-primary">Ara</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>