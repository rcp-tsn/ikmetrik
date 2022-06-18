<nav aria-label="breadcrumb" class="ml-5 mr-5">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" style="font-size: 14px;"><a href="{{ route('root') }}">Anasayfa</a></li>
        <li class="breadcrumb-item" style="font-size: 14px;"><a href="{{ route('metrics', createHashId($metric_report->metric_report_group_id)) }}">{{ $metric_report->metric_report_group->name }}</a></li>
        <li class="breadcrumb-item active" style="font-weight: bolder" aria-current="page">{{ $metric_report->name }}</li>
    </ol>
</nav>

