@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
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
                                    <span class="d-block text-dark font-weight-bolder">Diğer Sayfalar</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            {!! Form::open(['route'=>['save-demo-users'],'id'=>'frm','name'=>'frm'])!!}
                            <button type="button" onclick="applicationOperation('confirm')" class="btn btn-success btn-lg btn-block">SEÇİLİ KAYITLARA DEMO HESABI OLUŞTUR</button>
                            <table class="table table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll" name="checkAll"></th>
                                    <th>Şirket</th>
                                    <th>Yetkili</th>
                                    <th>Cep</th>
                                    <th>Telefon</th>
                                    <th>E-posta</th>
                                    <th>E-posta Durumu</th>
                                    <th>#</th>
                                </tr>

                                </thead>
                                <tbody id="applicationList">
                                @foreach($customer_emails as $customer_email)
                                    <tr>

                                        <td><input type="checkbox" id="demousers" name="demousers[]" value="{{$customer_email->id}}" ></td>
                                        <td>{{ $customer_email->customer->name }}</td>
                                        <td>{{ $customer_email->customer_official }}</td>
                                        <td>{{ $customer_email->phone }}</td>
                                        <td>{{ $customer_email->mobile }}</td>
                                        <td>{{ $customer_email->email }}</td>
                                        <td>{!! $customer_email->hasEmail() !!}</td>
                                        <td>
                                            <a href="{{ route('users.edit-demo', $customer_email->id) }}" title="Kaydı düzenle" class="btn btn-icon btn-light btn-hover-primary btn-sm">
                                <span class="svg-icon svg-icon-2x svg-icon-primary"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span>                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! Form::close() !!}
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

<script>
    $(document).ready(function () {
        $('#checkAll').on('click', function () {
            $(this).closest('table').find('tbody :checkbox')
                .prop('checked', this.checked)
                .closest('tr').toggleClass('selected', this.checked);
        });

        $('tbody :checkbox').on('click', function () {
            $(this).closest('tr').toggleClass('selected', this.checked);

            $(this).closest('table').find('#checkAll').prop('checked', ($(this).closest('table').find('tbody :checkbox:checked').length == $(this).closest('table').find('tbody :checkbox').length));
        });
    });

    function applicationOperation(operation) {
        if (operation == 'confirm') {
            var checked = $('#applicationList').find('input[name="demousers[]"]:checked').length;
            if (!checked)
                bootbox.alert("İşlem yapmak için en az bir kayıt <u>seçilmelidir!</u>");
            //$('#uyari_modal').modal('show');
            else {
                bootbox.confirm("Bu kayıtlar için <b>DEMO TANIMLAMAK</b> istediğinize emin misiniz? ", function (result) {
                    if (result) {
                        document.forms['frm'].submit();
                    }
                });
            }
        }
    }
    </script>
@endpush

