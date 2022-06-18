<!--begin::Item-->
<a href="#" class="navi-item">
    <div class="navi-link rounded">
        <div class="symbol symbol-50 symbol-circle mr-3">
            <div class="symbol-label"><i class="flaticon-paper-plane-1 text-success icon-lg"></i></div>
        </div>
        <div class="navi-text">
            <div class="font-weight-bold  font-size-lg">

                {{ $notification->data['name'] }} ({{ $notification->data['firma'] }}) kişisinden yeni {{ $notification->data['type'] }} talebi geldi.

            </div>
            <div class="text-muted">
                {{ $notification->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
</a>

<!--end::Item-->
