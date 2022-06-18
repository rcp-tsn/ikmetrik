<div class="modal fade modal-info" id="{{ createModalId() }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            {!! Form::open(['url' => $url, 'class' => 'ajax-store-form', 'method' => isset($method) ? $method : 'POST']) !!}
            <div class="modal-body">
                @include('partials.alerts.error')
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
