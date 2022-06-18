<div class="col-md-6" style="margin-bottom: 20px;">
    <div class="panel" style="height: 100%;">
        <div class="panel-heading">

            <h3 class="panel-title">
                {{ $title }}
            </h3>
            <div class="panel-actions">
                @if(isset($new_url))
                <a class="btn btn-outline btn-success btn-xs" title="Yeni Oluştur" href="{{ $new_url }}">Yeni</a>
                @endif
                <a class="btn btn-outline btn-primary btn-xs" title="Tümünü göster" href="{{ $url }}">Tümü</a>
            </div>
        </div>
        <div class="panel-body">
            {{ $slot }}
        </div>
    </div>
</div>