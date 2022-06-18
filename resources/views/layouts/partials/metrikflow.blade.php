@if(\Illuminate\Support\Facades\Auth::user()->hasRole('Owner'))
<br/><div class="metrikflow">
    <a class="metrikflow__step {!! metricFlowOk(1) !!}" target="_blank" href="{{ route('sgk_companies.index') }}">
        @if(strlen(metricFlowOk(1)) > 0)
        <i class="icon-xl fas fa-check metrikflow-number-white"></i>
        @else
        <i class="icon-xl fas fa-times metrikflow-number-red"></i>
        @endif
            Şube Tanımlayın</a>
    @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Teşvik') || Auth::user()->hasRole('Metrik'))
        <a class="metrikflow__step {!! metricFlowOk(2) !!}" target="_blank" href="{{ route('company_assignments.index') }}">
            @if(strlen(metricFlowOk(2)) > 0)
                <i class="icon-xl fas fa-check metrikflow-number-white"></i>
            @else
                <i class="icon-xl fas fa-times metrikflow-number-red"></i>
                @endif Atama Yapın</a>
    @else
        <a class="metrikflow__step {!! metricFlowOk(2) !!}"  href="javascript:void(0);" title="Erişim yetkiniz bulunmamaktadır.">
            @if(strlen(metricFlowOk(2)) > 0)
                <i class="icon-xl fas fa-check metrikflow-number-white"></i>
            @else
                <i class="icon-xl fas fa-times metrikflow-number-red"></i>
            @endif Atama Yapın</a>
    @endif

    <a class="metrikflow__step {!! metricFlowOk(3) !!}" target="_blank" href="{{ route('sgk_company_select') }}">
        @if(strlen(metricFlowOk(3)) > 0)
            <i class="icon-xl fas fa-check metrikflow-number-white"></i>
        @else
            <i class="icon-xl fas fa-times metrikflow-number-red"></i>
        @endif Şube Seçin</a>


    @if(getSgkCompany())
        @php $sgk_company_tmp = getSgkCompany(); @endphp
        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Teşvik') || Auth::user()->hasRole('Metrik'))
            <a class="metrikflow__step {!! metricFlowOk(4) !!}" target="_blank" href="{{ route('sgk_companies.show', createHashId($sgk_company_tmp->id)) }}">
                @if(strlen(metricFlowOk(4)) > 0)
                    <i class="icon-xl fas fa-check metrikflow-number-white"></i>
                @else
                    <i class="icon-xl fas fa-times metrikflow-number-red"></i>
                @endif Sabitlerinizi Girin</a>
        @else
            <a class="metrikflow__step {!! metricFlowOk(4) !!}"  href="javascript:void(0);" title="Erişim yetkiniz bulunmamaktadır.">
                @if(strlen(metricFlowOk(4)) > 0)
                    <i class="icon-xl fas fa-check metrikflow-number-white"></i>
                @else
                    <i class="icon-xl fas fa-times metrikflow-number-red"></i>
                    @endif Sabitlerinizi Girin</a>
        @endif
    @else
        <a class="metrikflow__step {!! metricFlowOk(4) !!}" target="_blank" href="javascript:void(0);">@if(strlen(metricFlowOk(4)) > 0)
                <i class="icon-xl fas fa-check metrikflow-number-white"></i>
            @else
                <i class="icon-xl fas fa-times metrikflow-number-red"></i>
            @endif  Sabitlerinizi Girin</a>

    @endif
    @if(strlen(metricFlowOk(5)) > 0)
        <a class="metrikflow-do-definitions-area metrikflow__step {!! metricFlowOk(5) !!}" href="#">@if(strlen(metricFlowOk(5)) > 0)
                <i class="icon-xl fas fa-check metrikflow-number-white"></i>
            @else
                <i class="icon-xl fas fa-times metrikflow-number-red"></i>
            @endif Tanımlamaları Yapın</a>
    @else
        <a class="metrikflow__step {!! metricFlowOk(5) !!}"  href="#">@if(strlen(metricFlowOk(5)) > 0)
                <i class="icon-xl fas fa-check metrikflow-number-white"></i>
            @else
                <i class="icon-xl fas fa-times metrikflow-number-red"></i>
            @endif Tanımlamaları Yapın</a>
    @endif
    <a class="metrikflow-show-area metrikflow__step "  href="#">Metrikleri Görüntüleyin</a>
</div>
@endif
