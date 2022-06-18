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
			<h2>Engelli Uygunluk Tespiti Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/30.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					Özel sektör işverenlerinin kontenjan dâhilinde veya kontenjan fazlası olarak ya da yükümlü olmadıkları halde engelli çalıştırmaları durumunda işverenlerin bu şekilde çalıştırdıkları her bir engelli için asgari ücret düzeyindeki sosyal güvenlik primi işveren paylarının tamamı Hazinece karşılanması şeklinde değiştirilmiştir.50 ve daha fazla personel çalışrıran işletmelerin toplam personel sayısının %3 oranında engelli istihadamını iafade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Toplam personel sayısı*3%</b><br>
				</p>
			</div>
		</div>
		<?php 
			$aylar = array('01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık', );
    		 $yillar = array('2020' =>'2020', '2019' =>'2019', '2018' =>'2018', '2017' =>'2017', '2016' =>'2016', '2015' =>'2015', '2014' =>'2014', ); 
    	?>
    	<form method="GET" action="/disabilityassessment" style="width: 100%;">
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
					<div class="card-title" style="width: 100%;">
						<h3 class="card-label"style="width: 50%;">
							Firmanızın Engelli Uygunluk Oranı : %{{number_format($firma_yuzdesi,1)}}
						</h3>
						<h3 class="card-label" style="width: 50%;text-align: right;">
							Sektör Engelli Uygunluk Oranı :  %{{number_format($sektor_yuzdesi,1)}}
						</h3>
					</div>
				</div>
				<div class="card-body">
					<span><b>Firmanızın Engelli Personel Sayısı : {{number_format($firma_engelli_sayisi,0,',','.')}} Kişi</b></span><br>
					<span><b>Firmanızın Personel Sayısı : {{number_format($firma_personel_sayisi,0,',','.')}} Kişi</b></span>
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
								<?php if($olmasi_gereken_engelli_sayisi<$firma_engelli_sayisi){
									echo "Yapılan karşılaştırmada engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)."kişi fazladır. ";
								}elseif ($olmasi_gereken_engelli_sayisi>$firma_engelli_sayisi) {
									echo "Yapılan karşılaştırmada engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)."kişi eksiktir. ";
								}else{
									echo "Engelli oranınız uygundur.";
								} ?> Sektöre göre engelli oranınız ise sektör oranına göre nominal orandadır.
							</div>
						</div><?php
					}elseif ($firma_yuzdesi<$min) { ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
							<div class="alert-icon">
								<i class="flaticon-warning" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">	
								<?php if($olmasi_gereken_engelli_sayisi<$firma_engelli_sayisi){
									echo "Yapılan karşılaştırmada engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)."kişi fazladır. ";
								}elseif ($olmasi_gereken_engelli_sayisi>$firma_engelli_sayisi) {
									echo "Yapılan karşılaştırmada engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)."kişi eksiktir. ";
								}else{
									echo "Engelli oranınız uygundur.";
								} ?> Sektöre göre engelli oranınız ise sektör oranının aşağısındadır.
							</div>
						</div><?php
						
					}elseif ($firma_yuzdesi>$max) { ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
							<div class="alert-icon">
								<i class="far fa-check-circle" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								<?php if($olmasi_gereken_engelli_sayisi<$firma_engelli_sayisi){
									echo "Yapılan karşılaştırmada engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)."kişi fazladır. ";
								}elseif ($olmasi_gereken_engelli_sayisi>$firma_engelli_sayisi) {
									echo "Yapılan karşılaştırmada engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)."kişi eksiktir. ";
								}else{
									echo "Engelli oranınız uygundur.";
								} ?> Sektöre göre engelli oranınız ise sektör oranından fazladır.
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
		  "baslik": "Sektör Bazlı Firmanızın Engelli Uygunluk Oranı",
		  "yuzde": firma_yuzdesi
		}, {
		  "baslik": "Sektörün Firmanıza Göre Engelli Uygunluk Oranı",
		  "yuzde": sektor_yuzdesi
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		chart.legend.markers.template.width = 15;
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


