@extends('layouts.application')

@section('content')
    <div class="panel panel-bordered">
        <div class="panel-heading">

        </div>
        <div class="panel-body">
            <p>Toplam {{ $notifications->total() }} adet bildiriminiz bulunmaktadır.</p>
            @forelse($notifications as $notification)
                @include('layouts.notifications.' . snake_case(class_basename($notification->type), '-'))
            @empty
                @component('components.alert.warning')
                    Kayıtlı bildirim bulunmamaktadır.
                @endcomponent
            @endforelse
            <div class="data-table-toolbar">
                {!! $notifications->links() !!}
            </div>
        </div>
    </div>
@endsection

