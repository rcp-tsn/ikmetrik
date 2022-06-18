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
			<h2>Özel Nedenlerle İşten Ayrılma Oranı Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/15.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					 İşletme içerisinden işgücü değişikliklerinin zorunlu nedenler ve personel talepleri ile oluşan değişiklikleri ifade eder.4857 sayılı iş kanunundan doğan emeklilik,askerlik ,evlilik ve personel talebi ile oluşan diğer nedenleri ölçmemize sağlar.İşletme bünyesinde 4857 sayılı iş kanunu hükümleri ile tanımlanmış özel çıkşları(askerlik,evlilik,emeklilik) ifade etmeketdir.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Özel neden pasta dağılım grafiği</b><br>
				</p>
			</div>
		</div>
		<?php 
			$sabitler=config('constants');
			$aylar = $sabitler['aylar'];
    		$yillar = $sabitler['yillar']; 
    	?>
		<form method="GET" action="/terminationchartforspecialreasons" style="width: 100%;">
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
					<div class="card-title">
						<h3 class="card-label">Özel Nedenlerle İşten Ayrılma Oranı</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 400px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
				</div>
			</div>
			<!--end::Card-->
		</div>
		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title">
						<h3 class="card-label">Sektör Özel Nedenlerle İşten Ayrılma Oranı</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="sektorchartdiv" style="height: 400px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
						$firma_yuzdesi=100*($firma_evlilikten_ayrilanlar+$firma_askerlikten_ayrilanlar+$firma_emeklilikten_ayrilanlar)/$firma_calisan_sayisi;
						$sektor_yuzdesi=100*($sektor_evlilikten_ayrilanlar+$sektor_askerlikten_ayrilanlar+$sektor_emeklilikten_ayrilanlar)/$sektor_calisan_sayisi;
						$max=$sektor_yuzdesi+$sektor_yuzdesi*15/100; 
						$min=$sektor_yuzdesi-$sektor_yuzdesi*15/100; 
						if ($firma_yuzdesi<=$max AND $firma_yuzdesi>=$min) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
								<div class="alert-icon">
									<i class="fas fa-retweet" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									Değerlendirme oranınız sektör ortalamasının içerisinde olup özel nedenlerden istifa oranınız sektör ortalamasına göre nominal değerdedir.
								</div>
							</div><?php
						}elseif ($firma_yuzdesi<$min) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
								<div class="alert-icon">
									<i class="far fa-check-circle" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									Değerlendirme oranınız sektör ortalama değerinin altında olup ,özel nedenlerden istifa  oranınız olumlu yönde seyretmektedir.
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
							
						}		
					
					 ?>
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
	var firma_emeklilikten_ayrilanlar=<?php echo $firma_emeklilikten_ayrilanlar; ?>;
	var firma_askerlikten_ayrilanlar=<?php  echo $firma_askerlikten_ayrilanlar; ?>;
	var firma_evlilikten_ayrilanlar=<?php  echo $firma_evlilikten_ayrilanlar; ?>;
	
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = [{
		  "baslik": "Evlilikten Dolayı Ayrılanlar",
		  "yuzde": firma_evlilikten_ayrilanlar,
		}, {
		  "baslik": "Askerlikten Dolayı Ayrılanlar",
		  "yuzde": firma_askerlikten_ayrilanlar
		}, {
		  "baslik": "Emeklilikten Dolayı Ayrılanlar",
		  "yuzde": firma_emeklilikten_ayrilanlar
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b")];

	});
</script>
<script type="text/javascript">
	var sektor_emeklilikten_ayrilanlar=<?php echo $sektor_emeklilikten_ayrilanlar; ?>;
	var sektor_askerlikten_ayrilanlar=<?php  echo $sektor_askerlikten_ayrilanlar; ?>;
	var sektor_evlilikten_ayrilanlar=<?php  echo $sektor_evlilikten_ayrilanlar; ?>;
	
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chartsektor = am4core.create("sektorchartdiv", am4charts.PieChart3D);
		chartsektor.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chartsektor.legend = new am4charts.Legend();

		chartsektor.data = [{
		  "baslik": "Evlilikten Dolayı Ayrılanlar",
		  "yuzde": sektor_evlilikten_ayrilanlar,
		}, {
		  "baslik": "Askerlikten Dolayı Ayrılanlar",
		  "yuzde": sektor_askerlikten_ayrilanlar
		}, {
		  "baslik": "Emeklilikten Dolayı Ayrılanlar",
		  "yuzde": sektor_emeklilikten_ayrilanlar
		},];

		var seriessektor = chartsektor.series.push(new am4charts.PieSeries3D());
		seriessektor.dataFields.value = "yuzde";
		seriessektor.dataFields.category = "baslik";
		seriessektor.colors = new am4core.ColorSet();
		seriessektor.colors.list =[am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b")];

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


