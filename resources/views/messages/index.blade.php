<!--begin::Messages-->
<div class="messages">
    @foreach($messages as $message)
        @if($message->from === Auth::user()->id)
        <!--begin::Message In-->
            <div class="d-flex flex-column mb-5 align-items-start message">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-circle symbol-40 mr-3">
                        <img alt="Pic" src="{{ $message->fromUser->picture }}" />
                    </div>
                    <div>
                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">{{ $message->fromUser->name }}</a>
                        <span class="text-muted font-size-sm">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</span>
                    </div>
                </div>
                <div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">{!! $message->message !!}</div>
            </div>
        <!--end::Message In-->
        @else
        <!--begin::Message Out-->
        <div class="d-flex flex-column mb-5 align-items-end message">
            <div class="d-flex align-items-center">
                <div>
                    <span class="text-muted font-size-sm">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</span>
                    <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">{{ $message->fromUser->name }}</a>
                </div>
                <div class="symbol symbol-circle symbol-40 ml-3">
                    <img alt="Pic" src="{{ $message->fromUser->picture }}" />
                </div>
            </div>
            <div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">{!! $message->message !!}</div>
        </div>
        <!--end::Message Out-->
        @endif
    @endforeach
</div>
