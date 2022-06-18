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
			<h2>Yaş Dağılımı Grafiği Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/21.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					İşletme bünyesinde hala hazırda çalışan tüm personellerin yaşlarına bakarak sistematik bir inceleme inkanı vermeketdir.Firma bünyesinde yenenek,tecrübe ve gençlik eğrilerinin verimlilik  göstergesi ilişkilendirilmiştir.Yaş dağılımı metriği ile şirketinizin yaş eğrisini bulabilirsiniz.İşletme bünyesinde çalışanların yaş dağılım grafiğini tanımlar.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Yaş dağılım pasta grafiği</b>
				</p>
			</div>
		</div>
		<?php 
			$aylar = array('01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık', );
    		 $yillar = array('2020' =>'2020', '2019' =>'2019', '2018' =>'2018', '2017' =>'2017', '2016' =>'2016', '2015' =>'2015', '2014' =>'2014', ); 
    	?>
    	<form method="GET" action="/agedistributionchart" style="width: 100%;">
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
						<h3 class="card-label">Yaş Dağılımı Grafiği</h3>
					</div>
				</div>
				<div class="card-body">
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
						<h3 class="card-label">Sektör Yaş Dağılımı Grafiği</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="sektorchartdiv" style="height: 350px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
						$firma_toplam=count($gencyasfirma)+count($ortayasfirma)+count($yaslifirma);
						$firma_toplam_yas=array_sum($gencyasfirma)+array_sum($ortayasfirma)+array_sum($yaslifirma);
						if ($firma_toplam==0) {
							$firma_yuzdesi=0;
						}else{
							$firma_yuzdesi=$firma_toplam_yas/$firma_toplam;
						}
						
						$sektor_toplam=count($gencyassektor)+count($ortayassektor)+count($yaslisektor);
						$sektor_toplam_yas=array_sum($gencyassektor)+array_sum($ortayassektor)+array_sum($yaslisektor);
						if ($sektor_toplam==0) {
							$sektor_yuzdesi=0;
						}else{
							$sektor_yuzdesi=$sektor_toplam_yas/$sektor_toplam;
						}
						

						$max=$sektor_yuzdesi+$sektor_yuzdesi*15/100; 
						$min=$sektor_yuzdesi-$sektor_yuzdesi*15/100; 
						if ($firma_yuzdesi<=$max AND $firma_yuzdesi>=$min) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
								<div class="alert-icon">
									<i class="fas fa-retweet" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									Değerlendirme oranınız sektör ortalamasının içerisinde olup yaş dağılım  oranınız sektör ortalamasına göre nominal değerdedir.
								</div>
							</div><?php
						}elseif ($firma_yuzdesi<$min) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #fed403;border-color: #fed403">
								<div class="alert-icon">
									<i class="fas fa-search" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									Değerlendirme oranınız sektör ortalama değerinin altında olup , değerlendirilmelidir.
								</div>
							</div><?php
							
						}elseif ($firma_yuzdesi>$max) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #fed403;border-color: #fed403">
								<div class="alert-icon">
									<i class="fas fa-search" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									Değerlendirme oranınız sektör ortalama değerinin üstünde olup , değerlendirilmelidir.
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
	var gencyasfirma=<?php echo count($gencyasfirma); ?>;
	var ortayasfirma=<?php echo count($ortayasfirma); ?>;
	var yaslifirma=<?php echo count($yaslifirma); ?>;
	
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = [{
		  "baslik": "Genç Yaş Grubunda Olan Personeller",
		  "yuzde": gencyasfirma
		}, {
		  "baslik": "Orta Yaş Grubunda Olan Personeller",
		  "yuzde": ortayasfirma
		}, {
		  "baslik": "Yaşlı Grubunda Olan Personeller",
		  "yuzde": yaslifirma
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b")];

	});
</script>
<script type="text/javascript">
	var gencyassektor=<?php echo count($gencyassektor); ?>;
	var ortayassektor=<?php echo count($ortayassektor); ?>;
	var yaslisektor=<?php echo count($yaslisektor); ?>;
	
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chartsektor = am4core.create("sektorchartdiv", am4charts.PieChart3D);
		chartsektor.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chartsektor.legend = new am4charts.Legend();

		chartsektor.data = [{
		  "baslik": "Genç Yaş Grubunda Olan Personeller",
		  "yuzde": gencyassektor
		}, {
		  "baslik": "Orta Yaş Grubunda Olan Personeller",
		  "yuzde": ortayassektor
		}, {
		  "baslik": "Yaşlı Grubunda Olan Personeller",
		  "yuzde": yaslisektor
		},];


		var seriessektor = chartsektor.series.push(new am4charts.PieSeries3D());
		seriessektor.dataFields.value = "yuzde";
		seriessektor.dataFields.category = "baslik";
		seriessektor.colors = new am4core.ColorSet();
		seriessektor.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b")];

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


