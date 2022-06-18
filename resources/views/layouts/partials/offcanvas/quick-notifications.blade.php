
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
            Duyurular
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


<!-- begin::Crm Notifications Panel-->
<div id="kt_quick_crm_notifications" class="offcanvas offcanvas-left p-10">
@php
            $authUser = Auth::user();

             $CrmAppNotifications =  $authUser->crmnotifications(1);
             $CrmAppNotificationUnreadCount = $authUser->notificationUnreadCount;




@endphp
<!--begin::Header-->
    <div class="offcanvas-header d-flex align-items-center justify-content-between mb-10">
        @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole('Owner','Admin'))

        @endif
        <h3 class="font-weight-bold m-0">
            Duyurular
            @if($CrmAppNotificationUnreadCount)
                <small class="text-muted font-size-sm ml-2">{{ $CrmAppNotificationUnreadCount }} Yeni</small>
            @endif

        </h3>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_crm_notifications_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>

    <!--end::Header-->

    <!--begin::Content-->
    <div class="offcanvas-content pr-5 mr-n5">

        <!--begin::Nav-->
        <div class="navi navi-icon-circle navi-spacer-x-0">

            @forelse($CrmAppNotifications as $notification)
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label"><i class="flaticon-paper-plane-1 text-success icon-lg"></i></div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold  font-size-lg">

                                {{ $notification->message }}

                            </div>
                            <div class="text-muted">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </a>

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

<!-- end::Crm Notifications Panel-->
