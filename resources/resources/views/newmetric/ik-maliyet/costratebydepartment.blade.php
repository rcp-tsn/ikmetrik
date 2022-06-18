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
			<h2>Departman Bazında Maliyet Oranı Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/6.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					Birçok işletmede iş gücü maliyetleri temel veya ikinci masraf kalemi olarak tanımlanmıştır.İşletme maliyet yönetimi ekseninde birim maliyet oranı diğer kalemler ile birlikte(sgk+vergi) stratejik önem arzetmektedir. İşletmelerde bildirge üzerinden olışan departman tanımlarının maliyet ekseninde oranını belirler.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Departman toplam maliyet(ücret+ikramiye toplamı)/Genel toplam maliyet(meslek kodları)</b><br>
				</p>
			</div>
		</div>
		<?php 
			$aylar = array('01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık', );
    		 $yillar = array('2020' =>'2020', '2019' =>'2019', '2018' =>'2018', '2017' =>'2017', '2016' =>'2016', '2015' =>'2015', '2014' =>'2014', ); 
    	?>
    	<form method="GET" action="/costratebydepartment" style="width: 100%;">
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
							Firmanızın Departman Bazında Maliyet Oranı
						</h3>
					
					</div>
				</div>
				<div class="card-body">
					<span><b>Firmanızın Toplam Maliyeti : {{number_format($firma_toplam_maliyet,2,',','.')}} ₺</b></span><br>
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 500px;margin-bottom: 20px;"></div>

				</div>
			</div>
			<!--end::Card-->
		</div>
		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title">
						<h3 class="card-label">Sektör Departman Bazında Maliyet Oranı</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="sektorchartdiv" style="height: 500px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
					for ($i=0; $i <10 ; $i++) { 
						//$indis=array_search($firmadata[$i]['kod'], array_column($sectordata, 'kod'));
						$indis=-1;
						foreach ($sectordata as $key => $value) {
							if ($value['kod']==$firmadata[$i]['kod']) {
								$indis=$key;
							}
						}
						if ($indis!=-1) {
							$firma_yuzdesi=100*(122.5*($firmadata[$i]['ucret_toplam']+$firmadata[$i]['ikramiye_toplam'])/100)/$firma_toplam_maliyet;
							$sektor_departman_maliyeti=100*(122.5*($sectordata[$indis]['ucret_toplam']+$sectordata[$indis]['ikramiye_toplam'])/100)/$sektor_toplam_maliyet;
							$max=$sektor_departman_maliyeti+$sektor_departman_maliyeti*15/100; 
							$min=$sektor_departman_maliyeti-$sektor_departman_maliyeti*15/100; 
							if ($firma_yuzdesi<=$max AND $firma_yuzdesi>=$min) { ?>
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
									<div class="alert-icon">
										<i class="fas fa-retweet" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['departman_adi']}} DEPARTMANININ DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalamasının içerisinde olup departman maliyet oranınız sektör ortalamasına göre nominal değerdedir.
									</div>
								</div><?php
							}elseif ($firma_yuzdesi<$min) { ?>
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
									<div class="alert-icon">
										<i class="far fa-check-circle" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['departman_adi']}} DEPARTMANININ DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalama değerinin altında olup ,departman maliyet oranı durumunuz olumlu yönde seyretmektedir.
									</div>
								</div><?php
								
							}elseif ($firma_yuzdesi>$max) { ?>
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
									<div class="alert-icon">
										<i class="flaticon-warning" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['departman_adi']}} DEPARTMANININ DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalama değerinin üzerinde olup, artış durumu neticesinde rakabet ve satış süreçlerinde zorluklar yaşanması muhtemeldir.
									</div>
								</div><?php
								
							}
						}
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
	var firma_departman=new Array();
	var departman_yuzde=0;
	var firmatoplammaliyet = <?php echo $firma_toplam_maliyet; ?>;
	var arrayfirma=<?php echo json_encode($firmadata); ?>;
	for (var i =0 ; i < arrayfirma.length; i++) {
		var toplammaliyet=22.5*(parseFloat(arrayfirma[i]['ucret_toplam'])+parseFloat(arrayfirma[i]['ikramiye_toplam']))/100+parseFloat(arrayfirma[i]['ucret_toplam'])+parseFloat(arrayfirma[i]['ikramiye_toplam']);
		departman_yuzde=parseFloat(toplammaliyet*100/firmatoplammaliyet).toFixed(1);
		firma_departman[i] = {"baslik":arrayfirma[i]['departman_adi'],"yuzde":departman_yuzde};	
		
	}
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = firma_departman;

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09"),am4core.color("#ff7f00"),am4core.color("#8a2be2"),am4core.color("#ffcc99"),am4core.color("#8b7355"),am4core.color("#97ffff"),am4core.color("#434A52")];

	});
</script>
<script type="text/javascript">
	var sektor_departman=new Array();
	var sektor_departman_yuzde=0;
	var sektortoplammaliyet = <?php echo $sektor_toplam_maliyet; ?>;
	var arraysektor=<?php echo json_encode($sectordata); ?>;
	for (var i =0 ; i < arraysektor.length; i++) {
		var toplammaliyet=22.5*(parseFloat(arraysektor[i]['ucret_toplam'])+parseFloat(arraysektor[i]['ikramiye_toplam']))/100+parseFloat(arraysektor[i]['ucret_toplam'])+parseFloat(arraysektor[i]['ikramiye_toplam']);
		sektor_departman_yuzde=parseFloat(toplammaliyet*100/sektortoplammaliyet).toFixed(1);
		sektor_departman[i] = {"baslik":arraysektor[i]['departman_adi'],"yuzde":sektor_departman_yuzde};	
		
	}
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chartsektor = am4core.create("sektorchartdiv", am4charts.PieChart3D);
		chartsektor.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chartsektor.legend = new am4charts.Legend();

		chartsektor.data = sektor_departman;

		var sektorseries = chartsektor.series.push(new am4charts.PieSeries3D());
		sektorseries.dataFields.value = "yuzde";
		sektorseries.dataFields.category = "baslik";
		sektorseries.colors = new am4core.ColorSet();
		sektorseries.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09"),am4core.color("#ff7f00"),am4core.color("#8a2be2"),am4core.color("#ffcc99"),am4core.color("#8b7355"),am4core.color("#97ffff"),am4core.color("#434A52")];

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


