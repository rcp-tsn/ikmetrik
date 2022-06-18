@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            {!! Form::open(['route' => 'declarations.incentives.options.store', 'class' => 'form', 'id' => 'ezy_standard_form']) !!}
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Taraması Yapılacak Kanunları Belirleyiniz</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Kanunlar:</label>
                                <div class="row">
                                    <div class="col-lg-3 options-div">
                                        <label class="option options-bolumu">
                                            <span class="option-control">
                                                <span class="radio">
                                                    <input type="checkbox" name="options[]" value="6111" checked="checked">
                                                    <span></span>
                                                </span>
                                            </span>
                                            <span class="option-label">
                                                <span class="option-head">
                                                    <span class="option-title">10.Madde</span>
                                                    <span class="option-focus">6111</span>
                                                </span>
                                                <span class="option-body">6111 / 4447 SAYILI KANUN GEÇİCİ 10.MADDE</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 options-div">
                                        <label class="option options-bolumu">
                                            <span class="option-control">
                                                <span class="radio">
                                                    <input type="checkbox" name="options[]" value="17103" checked="checked">
                                                    <span></span>
                                                </span>
                                            </span>
                                            <span class="option-label">
                                                <span class="option-head">
                                                    <span class="option-title">19.Madde</span>
                                                    <span class="option-focus">17103 - 27103</span>
                                                </span>
                                                <span class="option-body">17103 / 4447 SAYILI KANUN GEÇİCİ 19.MADDE</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 options-div">
                                        <label class="option options-bolumu">
                                            <span class="option-control">
                                                <span class="radio">
                                                    <input type="checkbox" name="options[]" value="7252" checked="checked">
                                                    <span></span>
                                                </span>
                                            </span>
                                            <span class="option-label">
                                                <span class="option-head">
                                                    <span class="option-title">26.Madde</span>
                                                    <span class="option-focus">7252</span>
                                                </span>
                                                <span class="option-body">7252 / 4447/GEÇİCİ 26.MADDE (KÇÖ-NÜD YARARLANANLAR)</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-lg-3 options-div">
                                        <label class="option options-bolumu">
                                            <span class="option-control">
                                                <span class="radio">
                                                    <input type="checkbox" name="options[]" value="27256" checked="checked">
                                                    <span></span>
                                                </span>
                                            </span>
                                            <span class="option-label">
                                                <span class="option-head">
                                                    <span class="option-title">28.Madde</span>
                                                    <span class="option-focus">27256</span>
                                                </span>
                                                <span class="option-body">27256 / 4447/GEÇİCİ 28.MADDE (İLAVE İSTİHDAM)</span>
                                            </span>
                                        </label>
                                    </div>

                                </div>
                                <label>Tarama Bölümleri:</label>
                                <div class="row">
                                    <div class="col-lg-6 options-div">
                                        <label class="option  tarama-bolumu">
                                            <span class="option-control">
                                                <span class="radio">
                                                    <input type="radio" name="start" value="EXCEL" checked="checked">
                                                    <span></span>
                                                </span>
                                            </span>
                                            <span class="option-label">
                                                <span class="option-head">
                                                    <span class="option-title">TEŞVİK TARAMASI</span>
                                                    <span class="option-focus">EXCEL</span>
                                                </span>
                                                <span class="option-body">EXCEL İLE TEŞVİK TARAMASI</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-lg-6 options-div">
                                        <label class="option  tarama-bolumu">
                                            <span class="option-control">
                                                <span class="radio">
                                                    <input type="radio" name="start" value="HAKEDIS">
                                                    <span></span>
                                                </span>
                                            </span>
                                            <span class="option-label">
                                                <span class="option-head">
                                                    <span class="option-title">E-BildirgeV2 ve İşveren Sistemi</span>
                                                    <span class="option-focus">V2</span>
                                                </span>
                                                <span class="option-body">E-BildirgeV2 SİSTEMİNDEN ONAYLI BİLDİRGELERİ ÇEK VE İŞVEREN İLE DEVAM ET</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="button" class="btn btn-success btn-lg btn-block start-btn">SEÇİLİ KANUNLARDA TARAMAYA SEÇİLİ BÖLÜM İLE BAŞLA</button>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
@push('modal')

@endpush
@push('scripts')

<script>
    $('.start-btn').click(function ()
    {
        var selected = new Array();
        $(".options-div input[type=checkbox]:checked").each(function () {

            selected.push(this.value);

        });
        if (selected.length <= 0) {
            alert('Taranmasını istediğiniz kanunlardan en az bir tanesinin seçilmesi gerekmektedir.')
        } else {
                $('#ezy_standard_form').submit();
        }
    });

    $('.options-div').click(function ()
    {
        $(this).find('input[type=checkbox]').prop("checked", !$(this).find('input[type=checkbox]').prop("checked"));
    });
</script>
@endpush
