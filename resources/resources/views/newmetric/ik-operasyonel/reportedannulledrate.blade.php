@extends('layouts.metric')
@section('content')

<style type="text/css">
    .p5{
        padding: 5px 15px;
    }
</style>
<div class="container" style="padding: 30px;">
	<div class="row">
		<div class="col-lg-12">
			<h2>Bildirimli Fesih Oranı Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/10.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					Feshi, genel olarak taraflardan birinin tek taraflı irade beyanıyla sözleşmeye son vermesidir.bu irade beyanı karşı tarafa ulaştığı zaman, diğer taraf sözleşmeyi fesheden tarafın iş ilişkisini sona erdirmek istediğini anlaması şartı aranmaktadır. Bildirimli fesih işleminde karşılık,süreli fesihle sona erdirmek isteyen işverenin geçerli sebep gösterme zorunluluğu bulunmaktadır.
					İşverence tazminat ödenerek 04 Bildirimli fesih yolu ile yapılan iş sözleşmesi fesihlerini ifade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Bildirimli fesih yapılan personel sayısı/Ortalama personel sayısı</b><br><br>
				</p>
			</div>
		</div>
		<?php 
			$sabitler=config('constants');
			$aylar = $sabitler['aylar'];
    		$yillar = $sabitler['yillar']; 
    	?>
		<form method="GET" action="/reportedannulledrate" style="width: 100%;">
			<div class="row">
				<div class="col-md-6" style="height: 50px;">
					<div style="float: left;">
						<label style="float: left;font-size: 17px;font-weight: 700;margin-right: 4px;line-height: 40px;">Başlangıç Tarihi </label>
						<select class="form-control" name="minmonth" style="width: 150px;margin-right: 10px;float: left;">
							@foreach($aylar as $key => $ay)
							<option value="{{$key}}" <?php if($minmonth==$key){echo "selected";} ?>>{{$ay}}</option>
							@endforeach		
						</select>
					</div>
					<div style="float: left;">
						<label style="float: left;font-size: 17px;font-weight: 700;margin-right: 4px;line-height: 40px;"></label>
						<select class="form-control" name="minyear" style="width: 150px;margin-right: 10px;float: left;">
							@foreach($yillar as $key => $yil)
							<option value="{{$key}}" <?php if($minyear==$key){echo "selected";} ?>>{{$yil}}</option>
							@endforeach		
						</select>
					</div>
				</div>
				<div class="col-md-6" style="height: 50px;">
					<div style="float: left;">
						<label style="float: left;font-size: 17px;font-weight: 700;margin-right: 4px;line-height: 40px;">Bitiş Tarihi </label>
						<select class="form-control" name="maxmonth" style="width: 150px;margin-right: 10px;float: left;">
							@foreach($aylar as $key => $ay)
							<option value="{{$key}}" <?php if($maxmonth==$key){echo "selected";} ?>>{{$ay}}</option>
							@endforeach		
						</select>
					</div>
					<div style="float: left;">
						<label style="float: left;font-size: 17px;font-weight: 700;margin-right: 4px;line-height: 40px;"></label>
						<select class="form-control" name="maxyear" style="width: 150px;margin-right: 10px;float: left;">
							@foreach($yillar as $key => $yil)
							<option value="{{$key}}" <?php if($maxyear==$key){echo "selected";} ?>>{{$yil}}</option>
							@endforeach		
						</select>
						<button type="submit" class="btn btn-primary mr-2">Getir</button>
					</div>
				</div>
			</div>
		</form>
		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title" style="width: 100%;">
						<h3 class="card-label"style="width: 50%;">
							Firmanızın Bildirimli Fesih Oranı : %{{number_format($firma_yuzdesi,1)}}
						</h3>
						<h3 class="card-label" style="width: 50%;text-align: right;">
							Sektör Bildirimli Fesih Oranı :  %{{number_format($sektor_yuzdesi,1)}}
						</h3>
					</div>
				</div>
				<div class="card-body">
					<span><b>Firmanızın Biildirimli Fesih Yapılan Personel Sayısı : {{number_format($firma_bildirimli_fesih_sayisi,0,',','.')}} Kişi</b></span><br>
					<span><b>Firmanızın Ortalama Çalışan Sayısı : {{number_format($firma_calisan_sayisi,0,',','.')}} Kişi</b></span>
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 300px;margin-bottom: 20px;"></div>
					<?php
					$max=$sektor_yuzdesi+$sektor_yuzdesi*15/100; 
					$min=$sektor_yuzdesi-$sektor_yuzdesi*15/100; 
					if ($firma_yuzdesi<=$max AND $firma_yuzdesi>=$min) { ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
							<div class="alert-icon">
								<i class="fas fa-retweet" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız sektör ortalamasının içerisinde olup bildirimli fesih oranınız sektör ortalamasına göre nominal değerdedir.
							</div>
						</div><?php
					}elseif ($firma_yuzdesi<$min) { ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
							<div class="alert-icon">
								<i class="far fa-check-circle" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız sektör ortalama değerinin altında olup ,bildirimli fesih oranınız   olumlu yönde seyretmektedir.
							</div>
						</div><?php
						
					}elseif ($firma_yuzdesi>$max) { ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
							<div class="alert-icon">
								<i class="flaticon-warning" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız sektör ortalama değerinin üzerinde olup, artış durumu neticesinde iş gücü kayıpları,iş kazası ,birim maliyet  ve eğitim oranlarında artış görülmesi muhtemeldir.
							</div>
						</div><?php
						
					} ?>
				</div>
			</div>
			<!--end::Card-->
		</div>
	</div>
</div>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script type="text/javascript">
	var firma_yuzdesi=<?php echo $firma_yuzdesi; ?>;
	var sektor_yuzdesi=<?php  echo $sektor_yuzdesi; ?>;
	
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = [{
		  "baslik": "Sektör Bazlı Firmanızın Bildirimli Fesih Oranı",
		  "yuzde": firma_yuzdesi
		}, {
		  "baslik": "Sektörün Firmanıza Göre Bildirimli Fesih Oranı",
		  "yuzde": sektor_yuzdesi
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330")];

	});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script type="text/javascript">
	$('.formulgoster').click(function(){
		$('.formul').slideToggle("slow");
		var x = document.getElementById("formulgoster");
	  if (x.innerHTML === "Formülü Göster") {
	    x.innerHTML = "Formülü Gizle";
	  } else {
	    x.innerHTML = "Formülü Göster";
	  }
	});
</script>
@stop


