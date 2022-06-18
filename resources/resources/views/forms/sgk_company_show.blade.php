<div class="row">
    <div class="col-lg-6">
        <div class="card card-custom card-border" style="height:350px;">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon-time-1 text-primary"></i>
                    </span>
                    <h3 class="card-label">Fazla Mesai Süreleri</h3>
                </div>

                <div class="card-toolbar">
                    <a href="javascript:void(0);" class="btn btn-sm btn-success font-weight-bold ajax-open-modal add-new-constants-button-area"
                       data-url="{{ route('modal.open-value', [createHashId($sgk_company->id), 'overtime']) }}">
                        <i class="flaticon-plus"></i>YENİ EKLE
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 200px">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-6">
                            <thead>
                            <tr>
                                <th scope="col">Dönem</th>
                                <th scope="col">Fazla Mesai Süresi</th>
                                <th scope="col">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($metrik_overtimes) > 0)
                                @foreach($metrik_overtimes as $metrik_overtime)
                                    <tr>
                                        <td>{{ getFullMonthName($metrik_overtime->value_month) }} / {{ $metrik_overtime->value_year }}</td>
                                        <td>{{ $metrik_overtime->value }} saat</td>
                                        <td>
                                            <a href="javascript:void(0);" class="ajax-open-modal btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-new-constants-button-area" data-url="{{ route('modal.edit-value', createHashId($metrik_overtime->id)) }}">
                                                <i class="flaticon2-pen icon-md text-success"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="return confirmOpsDelete('{{ createHashId($metrik_overtime->id) }}')" class="btn btn-icon btn-light btn-hover-primary btn-sm delete-new-constants-button-area" >
                                                <i class="flaticon2-rubbish-bin icon-md text-danger"></i>
                                            </a>

                                            {{ Form::open([
                                                    'method' => 'DELETE',
                                                    'url' => route('modal.destroy-value', createHashId($metrik_overtime->id)),
                                                    'id' => 'ops-delete-form-' . createHashId($metrik_overtime->id)
                                                    ]) }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3"> Kayıt bulunamamıştır...</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-custom card-border" style="height:350px;">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon-time-1 text-primary"></i>
                    </span>
                    <h3 class="card-label">Eğitim Maliyetleri</h3>
                </div>

                <div class="card-toolbar">
                    <a href="javascript:void(0);" class="btn btn-sm btn-success font-weight-bold ajax-open-modal"
                       data-url="{{ route('modal.open-value', [createHashId($sgk_company->id), 'education']) }}">
                        <i class="flaticon-plus"></i>YENİ EKLE
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 200px">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-6">
                            <thead>
                            <tr>
                                <th scope="col">Dönem</th>
                                <th scope="col">Eğitim Maliyeti</th>
                                <th scope="col">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($metrik_educations) > 0)
                                @foreach($metrik_educations as $metrik_education)
                                    <tr>
                                        <td>{{ $metrik_education->value_year }}</td>
                                        <td>{!! number_format($metrik_education->value, 2, ',', '.') !!} Tl</td>
                                        <td>
                                            <a href="javascript:void(0);" class="ajax-open-modal btn btn-icon btn-light btn-hover-primary btn-sm mx-3" data-url="{{ route('modal.edit-value', createHashId($metrik_education->id)) }}">
                                                <i class="flaticon2-pen icon-md text-success"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="return confirmOpsDelete('{{ createHashId($metrik_education->id) }}')" class="btn btn-icon btn-light btn-hover-primary btn-sm" >
                                                <i class="flaticon2-rubbish-bin icon-md text-danger"></i>
                                            </a>

                                            {{ Form::open([
                                                    'method' => 'DELETE',
                                                    'url' => route('modal.destroy-value', createHashId($metrik_education->id)),
                                                    'id' => 'ops-delete-form-' . createHashId($metrik_education->id)
                                                    ]) }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3"> Kayıt bulunamamıştır...</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-lg-6">
        <div class="card card-custom card-border" style="height:350px;">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon-time-1 text-primary"></i>
                    </span>
                    <h3 class="card-label">Eğitime Ayrılan Süreler</h3>
                </div>

                <div class="card-toolbar">
                    <a href="javascript:void(0);" class="btn btn-sm btn-success font-weight-bold ajax-open-modal"
                       data-url="{{ route('modal.open-value', [createHashId($sgk_company->id), 'time']) }}">
                        <i class="flaticon-plus"></i>YENİ EKLE
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 200px">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-6">
                            <thead>
                            <tr>
                                <th scope="col">Dönem</th>
                                <th scope="col">Eğitime Ayrılan Süre</th>
                                <th scope="col">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($metrik_times) > 0)
                                @foreach($metrik_times as $metrik_time)
                                    <tr>
                                        <td>{{ $metrik_time->value_year }} </td>
                                        <td>{{ $metrik_time->value }} saat </td>
                                        <td>
                                            <a href="javascript:void(0);" class="ajax-open-modal btn btn-icon btn-light btn-hover-primary btn-sm mx-3" data-url="{{ route('modal.edit-value', createHashId($metrik_time->id)) }}">
                                                <i class="flaticon2-pen icon-md text-success"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="return confirmOpsDelete('{{ createHashId($metrik_time->id) }}')" class="btn btn-icon btn-light btn-hover-primary btn-sm" >
                                                <i class="flaticon2-rubbish-bin icon-md text-danger"></i>
                                            </a>

                                            {{ Form::open([
                                                    'method' => 'DELETE',
                                                    'url' => route('modal.destroy-value', createHashId($metrik_time->id)),
                                                    'id' => 'ops-delete-form-' . createHashId($metrik_time->id)
                                                    ]) }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3"> Kayıt bulunamamıştır...</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-custom card-border" style="height:350px;">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon-time-1 text-primary"></i>
                    </span>
                    <h3 class="card-label">Cirolar</h3>
                </div>

                <div class="card-toolbar">
                    <a href="javascript:void(0);" class="btn btn-sm btn-success font-weight-bold ajax-open-modal"
                       data-url="{{ route('modal.open-value', [createHashId($sgk_company->id), 'cost']) }}">
                        <i class="flaticon-plus"></i>YENİ EKLE
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 200px">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-6">
                            <thead>
                            <tr>
                                <th scope="col">Dönem</th>
                                <th scope="col">Ciro</th>
                                <th scope="col">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($metrik_costs) > 0)
                                @foreach($metrik_costs as $metrik_cost)
                                    <tr>
                                        <td>{{ $metrik_cost->value_year }}</td>
                                        <td>{!! number_format($metrik_cost->value, 2, ',', '.') !!} Tl </td>
                                        <td>
                                            <a href="javascript:void(0);" class="ajax-open-modal btn btn-icon btn-light btn-hover-primary btn-sm mx-3" data-url="{{ route('modal.edit-value', createHashId($metrik_cost->id)) }}">
                                                <i class="flaticon2-pen icon-md text-success"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="return confirmOpsDelete('{{ createHashId($metrik_cost->id) }}')" class="btn btn-icon btn-light btn-hover-primary btn-sm" >
                                                <i class="flaticon2-rubbish-bin icon-md text-danger"></i>
                                            </a>

                                            {{ Form::open([
                                                    'method' => 'DELETE',
                                                    'url' => route('modal.destroy-value', createHashId($metrik_cost->id)),
                                                    'id' => 'ops-delete-form-' . createHashId($metrik_cost->id)
                                                    ]) }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3"> Kayıt bulunamamıştır...</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        function confirmOpsDelete(id) {
            bootbox.dialog({
                message: "{{ __('admin.ops.delete_confirmation') }}",
                title: "<h5 style='text-align: left;'>{{ __('admin.ops.delete_title') }}</h5>",
                buttons: {
                    danger: {
                        label: "{{ __('admin.ops.delete') }}",
                        className: "btn-danger",
                        callback: function() {
                            document.getElementById('ops-delete-form-' + id).submit();
                        }
                    },
                    main: {
                        label: "{{ __('admin.ops.cancel') }}",
                        className: "btn-primary",
                        callback: function() {}
                    }
                }
            });
        }
    </script>

    <script>
        'use strict';
        $(document).ready(function () {

            $(document).on('click', 'a.page-tour', function () {
                var enjoyhint_instance = new EnjoyHint({});

                enjoyhint_instance.set([
                    {
                        'next .add-new-constants-button-area': 'Sisteme yeni sabit eklemek için tıklayınız. ',
                    },{
                        'next .edit-new-constants-button-area': 'İlgili kaydı güncelleştirmek için bu butonu kullanabilirsiniz.',
                    },{
                        'next .delete-new-constants-button-area': 'İlgili kaydı silmek için bu butonu kullanabilirsiniz.',
                    }
                ]);
                enjoyhint_instance.run();
            });

        });
    </script>
@endpush
