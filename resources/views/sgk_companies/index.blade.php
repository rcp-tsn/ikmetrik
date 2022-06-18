@extends('layouts.app')
@section('content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">SGK Firmalarınız</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">Tarafınıza ataması yapılmış firmalarınız</span>
                                </h3>
                            </div>

                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <table class="table table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>Firma No</th>
                                    <th>Merkez</th>
                                    <th>Sektör</th>
                                    <th>Firma</th>
                                    <th>İşyeri Sicil No</th>
                                    <th>Kullanıcı Adı</th>
                                    <th>Yan Kod</th>
                                    <th>Sistem Şifresi</th>
                                    <th>İşyeri Şifresi</th>
                                    <th>İşlemler</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($sgk_companies as $sgk_company)
                                    <tr>
                                        <td>{{ $sgk_company->id }}</td>
                                        <td>{{ $sgk_company->company->name }}</td>
                                        <td>{{ $sgk_company->sector->name }}</td>
                                        <td>{{ $sgk_company->name }}</td>
                                        <td>{{ $sgk_company->registry_id }}</td>
                                        <td>{{ $sgk_company->company_username }}</td>
                                        <td>{{ $sgk_company->company_usercode }}</td>
                                        <td>{{ $sgk_company->company_syspassword }}</td>
                                        <td>{{ $sgk_company->company_password }}</td>
                                        <td nowrap="nowrap">
                                            <a href="{{ route('sgk_companies.edit', createHashId($sgk_company->id)) }}" title="Düzenle" class="btn btn-icon btn-light btn-hover-primary btn-sm">
                                                <span class="svg-icon svg-icon-2x svg-icon-primary"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                                    </g>
                                                    </svg>
                                                </span>
                                            </a>

                                            <a href="{{ route('sgk_companies.show', createHashId($sgk_company->id)) }}" title="Metrikler İçin Gerekli Bilgiler" class="btn btn-icon btn-light btn-hover-primary btn-sm">
                                                <span class="svg-icon svg-icon-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect x="0" y="0" width="24" height="24"></rect>
																	<path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"></path>
																</g>
															</svg>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                </div>
            </div>

            <!--end::Row-->
            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Entry-->
@stop

@push('scripts')
<script>
    "use strict";
    var KTDatatablesAdvancedColumnRendering = function() {

        var init = function() {
            var table = $('#kt_datatable');

            // begin first table
            table.DataTable({
                responsive: true,
                paging: true
            });

            $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
        };

        return {

            //main function to initiate the module
            init: function() {
                init();
            }
        };
    }();

    jQuery(document).ready(function() {
        KTDatatablesAdvancedColumnRendering.init();
    });
</script>
@endpush

