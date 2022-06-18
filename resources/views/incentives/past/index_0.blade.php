@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <div class="card card-custom">
                        <div class="card-header card-header-tabs-line">
                            <div class="card-title">
                                <h3 class="card-label">Herhangibir Kanundan Yararlandırılmayan Çalışan Listesi</h3>
                            </div>
                            <div class="card-toolbar">

                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered mb-6">
                                <thead>
                                <tr>
                                    <th scope="col">DÖNEMİ</th>
                                    <th scope="col">TCK</th>
                                    <th scope="col">İSİM<br/>SOYİSİM</th>
                                    <th scope="col">ÜCRET<br/>TUTARI</th>
                                    <th scope="col">GÜN<br/>EKSİK GÜN</th>
                                    <th scope="col">İŞE BAŞLAMA</th>
                                    <th scope="col">İŞETEN ÇIKIŞ</th>
                                    <th scope="col">MESLEK KOD</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($incentive_services as $incentive_service)
                                    <tr>
                                        <th scope="row">{{ $date }}</th>
                                        <td>{{ $incentive_service->tck }}</td>
                                        <td>{{ $incentive_service->isim.' '.$incentive_service->soyisim  }}</td>
                                        <td>{{ number_format($incentive_service->ucret_tl, 2, ',', '.'). 'TL' }}</td>
                                        <td>{{ $incentive_service->gun.'/'.$incentive_service->eksik_gun }}</td>
                                        <td>{{ $incentive_service->job_start }}</td>
                                        <td>{{ $incentive_service->job_finish }}</td>
                                        <td>{{ $incentive_service->meslek_kod }}</td>
                                        <td>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('modal')

@endpush
@push('scripts')
    <script>

    </script>

@endpush
