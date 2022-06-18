
<!-- begin::Notifications Panel-->
<div id="kt_quick_notifications" class="offcanvas offcanvas-left p-10">
@php
    $authUser = Auth::user();
      $appNotifications =  $authUser->notifications()->paginate(10);
      $appNotificationUnreadCount = $authUser->notificationUnreadCount;

@endphp
    <!--begin::Header-->
    <div class="offcanvas-header d-flex align-items-center justify-content-between mb-10">
        <h3 class="font-weight-bold m-0">
            Bildirimler
            @if($appNotificationUnreadCount)
                <small class="text-muted font-size-sm ml-2">{{ $appNotificationUnreadCount }} Yeni</small>
            @endif

        </h3>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_notifications_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>

    <!--end::Header-->

    <!--begin::Content-->
    <div class="offcanvas-content pr-5 mr-n5">

        <!--begin::Nav-->
        <div class="navi navi-icon-circle navi-spacer-x-0">

            @forelse($appNotifications as $notification)
                @include('layouts.notifications.' . snake_case(class_basename($notification->type), '-'))
            @empty
                <p style="margin-left: 20px;margin-top: 5px;">bildirim bulunmamaktadır.</p>
            @endforelse

            <!--begin::Item-->

            <!--end::Item-->
        </div>

        <!--end::Nav-->
    </div>

    <!--end::Content-->
</div>

<!-- end::Notifications Panel-->
