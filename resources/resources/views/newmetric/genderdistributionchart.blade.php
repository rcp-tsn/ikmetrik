@extends('layouts.metric')
@section('content')
    @include('layouts.metric_breadcrumb')
<style type="text/css">
    .p5{
        padding: 5px 15px;
    }
</style>
<div class="container" style="padding: 30px;">
	<div class="row">
		<div class="col-lg-12">
			<h2>Cinsiyet Dağılım Grafiği Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/22.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p>
					İşgücüne katılım oranındaki değişimler cinsiyet ekseninde ele alındığında göze çarpan olgulardan birisi cinsiyet-konum grupları arasındaki ayrışmadır. Dolayısıyla  işletmede  çalışan  kadınların diğer cinsiyet-konum gruplarından ayrışarak olumsuz makroekonomik koşullara karşı direnç göstermesi söz konusudur.Firmada  çalışan   kadınlar iş gücü ilişkisinde takip edilmelidir.İşletme bünyesinde çalışanların cinsiyet dağılım grafiğini tanımlar.<br><br>
					<b>Hesaplama Formülü:Cinsiyet dağılım pasta grafiği</b>
				</p>
			</div>
		</div>
		<?php
			$aylar = array('01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık', );
    		 $yillar = array('2020' =>'2020', '2019' =>'2019', '2018' =>'2018', '2017' =>'2017', '2016' =>'2016', '2015' =>'2015', '2014' =>'2014', );
    	?>
    	<form method="GET" action="/genderdistributionchart" style="width: 100%;">
			<div style="width: 17%;margin-right: 10px;float: left;">
				<label style="float: left;font-size: 17px;font-weight: 700;margin-right: 4px;line-height: 40px;">Ay</label>
				<select class="form-control" name="month" style="width: 80%;margin-right: 10px;float: left;">
					@foreach($aylar as $key => $ay)
					<option value="{{$key}}" <?php if($month==$key){echo "selected";} ?>>{{$ay}}</option>
					@endforeach
				</select>
			</div>
			<div style="width: 24%;margin-right: 10px;float: left;">
				<label style="float: left;font-size: 17px;font-weight: 700;margin-right: 4px;line-height: 40px;">Yıl</label>
				<select class="form-control" name="year" style="width: 58%;margin-right: 10px;float: left;">
					@foreach($yillar as $key => $yil)
					<option value="{{$key}}" <?php if($year==$key){echo "selected";} ?>>{{$yil}}</option>
					@endforeach
				</select>
				<button type="submit" class="btn btn-primary mr-2">Getir</button>
			</div>

		</form>
		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title">
						<h3 class="card-label">Cinsiyet Dağılım Grafiği</h3>
					</div>
				</div>
				<div class="card-body">
					<span><b>Firmanızın Erkek Personel Sayısı : {{$firma_erkek_sayisi}} Kişi</b></span><br>
					<span><b>Firmanızın Kadın Personel Sayısı : {{$firma_kadin_sayisi}} Kişi</b></span>
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 300px;margin-bottom: 20px;"></div>
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
						<h3 class="card-label">Sektör Cinsiyet Dağılım Grafiği</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="sektorchartdiv" style="height: 300px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
					for ($i=0; $i <2 ; $i++) {
						if ($i==0) {
								$baslik="KADIN";
								$firma_yuzdesi=$firma_kadin_sayisi*100/($firma_kadin_sayisi+$firma_erkek_sayisi);
								$sektor_yuzdesi=$sektor_kadin_sayisi*100/($sektor_kadin_sayisi+$sektor_erkek_sayisi);
							}else{
								$baslik="ERKEK";
								$firma_yuzdesi=$firma_erkek_sayisi*100/($firma_kadin_sayisi+$firma_erkek_sayisi);
								$sektor_yuzdesi=$sektor_erkek_sayisi*100/($sektor_kadin_sayisi+$sektor_erkek_sayisi);
							}

						$max=$sektor_yuzdesi+$sektor_yuzdesi*15/100;
						$min=$sektor_yuzdesi-$sektor_yuzdesi*15/100;
						if ($firma_yuzdesi<=$max AND $firma_yuzdesi>=$min) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
								<div class="alert-icon">
									<i class="fas fa-retweet" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									<strong>{{$baslik}} PERSONEL DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalamasının içerisinde olup Cinsiyet dağılım   oranınız sektör ortalamasına göre nominal değerdedir.
								</div>
							</div><?php
						}elseif ($firma_yuzdesi<$min) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #fed403;border-color: #fed403">
								<div class="alert-icon">
									<i class="fas fa-search" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									<strong>{{$baslik}} PERSONEL DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalama değerinin altında olup , değerlendirilmelidir.
								</div>
							</div><?php

						}elseif ($firma_yuzdesi>$max) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #fed403;border-color: #fed403">
								<div class="alert-icon">
									<i class="fas fa-search" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									<strong>{{$baslik}} PERSONEL DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalama değerinin üstünde olup , değerlendirilmelidir.
								</div>
							</div><?php

						}
					}?>
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
	var firma_erkek_sayisi=<?php echo $firma_erkek_sayisi; ?>;
	var firma_kadin_sayisi=<?php  echo $firma_kadin_sayisi; ?>;

	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = [{
		  "baslik": "Firmanızın Erkek Personel Sayısı Oranı",
		  "yuzde": firma_erkek_sayisi
		}, {
		  "baslik": "Firmanızın Kadın Personel Sayısı Oranı",
		  "yuzde": firma_kadin_sayisi
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330")];

	});
</script>
<script type="text/javascript">
	var sektor_erkek_sayisi=<?php echo $sektor_erkek_sayisi; ?>;
	var sektor_kadin_sayisi=<?php  echo $sektor_kadin_sayisi; ?>;

	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chartsektor = am4core.create("sektorchartdiv", am4charts.PieChart3D);
		chartsektor.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chartsektor.legend = new am4charts.Legend();

		chartsektor.data = [{
		  "baslik": "Sektörün Erkek Personel Sayısı Oranı",
		  "yuzde": sektor_erkek_sayisi
		}, {
		  "baslik": "Sektörün Kadın Personel Sayısı Oranı",
		  "yuzde": sektor_kadin_sayisi
		},];

		var seriessektor = chartsektor.series.push(new am4charts.PieSeries3D());
		seriessektor.dataFields.value = "yuzde";
		seriessektor.dataFields.category = "baslik";
		seriessektor.colors = new am4core.ColorSet();
		seriessektor.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330")];

	});
</script>
@stop


