@extends('layouts.app')
@section('content')
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid mt-10">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Callout-->
                    <div class="card card-custom wave wave-animate-slow wave-primary mb-8 mb-lg-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center p-5">
                                <!--begin::Icon-->
                                <div class="col-lg-0 mr-6">
													<span class="svg-icon svg-icon-primary svg-icon-4x">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"/>
        <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"/>
        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
        <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"/>
    </g>
</svg>
													</span>
                                </div>
                                <!--end::Icon-->
                                <!--begin::Content-->
                                <div class="col-lg-4 d-flex flex-column">
                                    <a href="#" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Teşvik Raporları</a>
                                    <div class="text-dark-75">Seçili Şube İçin Ay Bazlı Teşvik Raporları</div>
                                </div>
                                <div class="col-lg-8 d-flex align-items-center justify-content-lg-end">
                                    <a href="#" data-toggle="modal" data-target="#tesvikHakedisleriMdl" class="btn btn-icon btn-link pulse pulse-danger mr-8">
                                        <i class="flaticon-info font-weight-bolder" style="font-size: 2.6rem !important;"></i>
                                        <span class="pulse-ring"></span>
                                    </a>
                                </div>
                                <!--end::Content-->
                            </div>
                        </div>
                    </div>
                    <!--end::Callout-->
                </div>
            </div>
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">
                        <div class="card-body">
                            <table class="table table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>Teşvik Dönemi</th>
                                    <th>Rapor Görüntüle</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($gains as $gain)
                                    <tr>
                                        <td>{{ $gain->accrual->format('d/m/Y') }}</td>
                                        <td nowrap="nowrap">
                                            <a href="{{ route('declarations.incentives.gain_incentives') }}?date={{ $gain->accrual->format('Y-m-d') }}" class="btn btn-success btn-sm mr-3">
                                                <i class="flaticon2-pie-chart"></i>Hakediş Raporu</a>
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
    <!--begin::Modal-->
    <div class="modal fade" id="tesvikHakedisleriMdl" tabindex="-1" role="dialog" aria-labelledby="tesvikHakedisleriMdl" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tesvikHakedisleriMdlLabel">Teşvik Raporları Bilgilendirme</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-custom alert-white alert-shadow gutter-b" role="alert">
                        <div class="alert-icon alert-icon-top">
										<span class="svg-icon svg-icon-3x svg-icon-primary mt-4">
											<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Tools/Tools.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
        <path d="M12,16 C12.5522847,16 13,16.4477153 13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 C11,16.4477153 11.4477153,16 12,16 Z M10.591,14.868 L10.591,13.209 L11.851,13.209 C13.447,13.209 14.602,11.991 14.602,10.395 C14.602,8.799 13.447,7.581 11.851,7.581 C10.234,7.581 9.121,8.799 9.121,10.395 L7.336,10.395 C7.336,7.875 9.31,5.922 11.851,5.922 C14.392,5.922 16.387,7.875 16.387,10.395 C16.387,12.915 14.392,14.868 11.851,14.868 L10.591,14.868 Z" fill="#000000"/>
    </g>
</svg>
                                            <!--end::Svg Icon-->
										</span>
                        </div>
                        <div class="alert-text">
                            <p>Bu bölümde seçili şubenize ait <code>01/01/2020</code> tarihinden itibaren hakedilen teşvik raporlarınızı <code>ay bazında</code> gurupladık.</p>
                            <p>
                                <span class="label label-inline label-pill label-danger label-rounded mr-2">NOT:</span>İlgili aya ait raporu satırın sonunda yer alan <a href="#" class="btn btn-success btn-sm mr-3">
                                    <i class="flaticon2-pie-chart"></i>Hakediş Raporu</a> butonuna tıklayarak görüntüleyebilirsiniz.</p>
                        </div>
                    </div>

                    <div class="alert alert-custom alert-white alert-shadow gutter-b" role="alert">
                        <div class="alert-icon alert-icon-top">
										<span class="svg-icon svg-icon-3x svg-icon-primary mt-4">
											<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Tools/Tools.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
        <path d="M12,16 C12.5522847,16 13,16.4477153 13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 C11,16.4477153 11.4477153,16 12,16 Z M10.591,14.868 L10.591,13.209 L11.851,13.209 C13.447,13.209 14.602,11.991 14.602,10.395 C14.602,8.799 13.447,7.581 11.851,7.581 C10.234,7.581 9.121,8.799 9.121,10.395 L7.336,10.395 C7.336,7.875 9.31,5.922 11.851,5.922 C14.392,5.922 16.387,7.875 16.387,10.395 C16.387,12.915 14.392,14.868 11.851,14.868 L10.591,14.868 Z" fill="#000000"/>
    </g>
</svg>
                                            <!--end::Svg Icon-->
										</span>
                        </div>
                        <div class="alert-text">
                            <p>Firmanızın tüm şubeleri için ortak rapora erişmek için sağ bölümdeki <code>Toplu Hakediş Raporu</code> bağlantısını tıklayabilirsiniz.</p>
                            <p>
                                <span class="label label-inline label-pill label-danger label-rounded mr-2">NOT:</span>Tüm raporlarımızı görüntülenmesinin ardından PDF olarak bilgisayarınızada indirebilirsiniz.</p>
                        </div>
                    </div>

                     </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">TAMAM</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
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

