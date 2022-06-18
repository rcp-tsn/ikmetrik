<div class="form-group">
    <label>Paket</label>
    {!! Form::select("packet[]",  $packets, isset($selectPackets) ? $selectPackets : null, ['class'=>'form-control selectpicker input-xs','id'=>'packet_2','multiple']) !!}
</div>

<div class="form-group">
    <label>Paket Ücret Şekli</label>
    {!! Form::select("companies[packet_price_type]",  ['AYLIK' => 'AYLIK OLARAK ÖDEME', 'YILLIK' => 'YILLIK OLARAK ÖDEME'], isset($company->packet_price_type) ? $company->packet_price_type : null, ['class'=>'form-control input-xs','id'=>'packet_price_type']) !!}
</div>

{{ Form::bsText('companies[start_date]', 'Faturalandırma  Başlangıç Tarihi', (strlen($company->start_date) >0 ) ? $company->start_date->format('d/m/Y') : '', ['placeholder'=>'gg/aa/yyyy'], 'Sadece metrik firmaları için fatura başlangıç tarihi gereklidir. Diğerleri için boş geçilebilir.') }}
