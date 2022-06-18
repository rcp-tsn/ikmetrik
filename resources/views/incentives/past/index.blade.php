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
                                <h3 class="card-label">Teşvik Analizleri</h3>
                            </div>
                            <div class="card-toolbar">
                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                    <li class="nav-item">
                                        <a class="nav-link active"  href="{{ route('declarations.incentives.past', '6111') }}">6111 Teşvik Analizleri</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"  href="{{ route('declarations.incentives.past', '7103') }}">7103 Teşvik Analizleri</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"  href="{{ route('declarations.incentives.past', '7252') }}">7252 Teşvik Analizleri</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="tesvik_6111" role="tabpanel" aria-labelledby="tesvik_6111">
                                    <table class="table table-bordered mb-6">
                                        <thead>
                                        <tr>
                                            <th scope="col">DÖNEMİ</th>
                                            <th scope="col">ÇALIŞAN SAYISI</th>
                                            <th scope="col">YARARLANABİLEN<br/>ÇALIŞAN SAYISI</th>
                                            <th scope="col">TOPLAM TEŞVİK<br/>TUTARI</th>
                                            <th scope="col">YARARLANMIŞ<br/>ÇALIŞAN SAYISI</th>
                                            <th scope="col">YARARLANABİLECEK<br/>ÇALIŞAN SAYISI</th>
                                            <th scope="col">YARARLANABİLECEK<br/>TEŞVİK TUTARI</th>
                                            <th scope="col">YARARLANMIŞ<br/>ÇALIŞAN LİSTELERİ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($sum_incentives as $sum_incentive)
                                            <tr>
                                                <th scope="row">{{ $sum_incentive->accrual->format('m/Y') }}</th>
                                                <td>{{ $sum_incentive->personelCount($sgk_company->id, $law_no) }}</td>
                                                <td>{{ $sum_incentive->yararlanabilen($sgk_company->id, $law_no) }}</td>
                                                <td>{{ number_format($sum_incentive->toplamTesviktutari($sgk_company->id, $law_no), 2, ',', '.'). 'TL'  }}</td>
                                                <td>{{ $sum_incentive->yararlanmis($sgk_company->id, $law_no) }}</td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <a target="_blank" href="{{ route('declarations.incentives.check-incentive', [$sum_incentive->accrual->format('Y-m-d'), 6111]) }}" class="btn btn-success btn-sm mr-3">
                                                        <i class="flaticon-customer"></i>6111</a> &nbsp;
                                                    <a target="_blank" href="{{ route('declarations.incentives.check-incentive', [$sum_incentive->accrual->format('Y-m-d'), 7103]) }}" class="btn btn-primary btn-sm mr-3">
                                                        <i class="flaticon-customer"></i>7103</a>&nbsp;
                                                    <a target="_blank" href="{{ route('declarations.incentives.check-incentive', [$sum_incentive->accrual->format('Y-m-d'), 0]) }}" class="btn btn-danger btn-sm mr-3">
                                                        <i class="flaticon-customer"></i>BOŞ</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="tesvik_7103" role="tabpanel" aria-labelledby="tesvik_7103">Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
                                <div class="tab-pane fade" id="tesvik_7252" role="tabpanel" aria-labelledby="tesvik_7252">Aldus PageMaker including versions lorem Ipsum has been the industry's standard. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
                            </div>
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
