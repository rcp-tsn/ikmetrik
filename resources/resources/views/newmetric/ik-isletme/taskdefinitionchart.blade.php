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
			<h2>Görev Tanım Grafiği Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/14.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					Görev Tanımı ; bir kuruluşun üst düzey yöneticilerinden başlayarak en alt düzey çalışanına kadar ( kaliteyi uygulayan , etkileyen ve doğrulayan herkes ) bütün personelin kime bağlı çalıştığı , görevi , sorumluluğu ve yetkilerinin tariflendiği doküman olarak tanımlanmıştır.İşe giriş bildirgesi ve APHB'de tanımlanan meslek kodlarının dağılımını tanımlamaktadır.
					<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Görev dağılım pasta grafiği</b>
				</p>
			</div>
		</div>
		<?php 
			$aylar = array('01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık', );
    		 $yillar = array('2020' =>'2020', '2019' =>'2019', '2018' =>'2018', '2017' =>'2017', '2016' =>'2016', '2015' =>'2015', '2014' =>'2014', ); 
    	?>
    	<form method="GET" action="/taskdefinitionchart" style="width: 100%;">
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
						<h3 class="card-label">Görev Tanım Grafiği</h3>
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
						<h3 class="card-label">Sektör Görev Tanım Grafiği</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="sektorchartdiv" style="height: 400px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
					for ($i=0; $i <$firmadata->count() ; $i++) { 
						//$indis=array_search($firmadata[$i]['kod'], array_column($sectordata, 'kod'));
						$indis=-1;
						foreach ($sectordata as $key => $value) {
							if ($value['meslek_kod']==$firmadata[$i]['meslek_kod']) {
								$indis=$key;
							}
						}
						if ($indis!=-1) {
							$firma_yuzdesi=$firmadata[$i]['personelmesleksayisi'];
							$sektor_yuzdesi=+$sectordata[$indis]['personelmesleksayisi'];
							$max=$sektor_yuzdesi+$sektor_yuzdesi*15/100; 
							$min=$sektor_yuzdesi-$sektor_yuzdesi*15/100; 
							if ($firma_yuzdesi<=$max AND $firma_yuzdesi>=$min) { ?>
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
									<div class="alert-icon">
										<i class="fas fa-retweet" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['meslekadi']}} MESLEĞİNİN DAĞILIMININ SEKTÖRE GÖRE DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalamasının görev dağılımları ile orantılı seyretmetedir.
									</div>
								</div><?php
							}elseif ($firma_yuzdesi<$min) { ?><!-- Sarı Bölge -->
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #fed403;border-color: #fed403">
									<div class="alert-icon">
										<i class="fas fa-search" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['meslekadi']}} MESLEĞİNİN DAĞILIMININ SEKTÖRE GÖRE DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalamasının görev dağılımları ile farklılık arzetmektedir.Tekrar gözen geçilmesini tavsiye edilir.
									</div>
								</div><?php
								
							}elseif ($firma_yuzdesi>$max) { ?><!-- SARI BÖLGE -->
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #fed403;border-color: #fed403">
									<div class="alert-icon">
										<i class="fas fa-search" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['meslekadi']}} MESLEĞİNİN DAĞILIMININ SEKTÖRE GÖRE DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalamasının görev dağılımları ile farklılık arzetmektedir.Tekrar gözen geçilmesini tavsiye edilir.
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
	var firma_job=new Array();
	var job_yuzde=0;
	var arrayfirma=<?php echo json_encode($firmadata); ?>;
	for (var i =0 ; i < arrayfirma.length; i++) {
		firma_job[i] = {"baslik":arrayfirma[i]['meslekadi'],"yuzde":arrayfirma[i]['personelmesleksayisi']};	
		
	}
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = firma_job;

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09"),am4core.color("#ff7f00"),am4core.color("#8a2be2"),am4core.color("#ffcc99"),am4core.color("#8b7355"),am4core.color("#97ffff"),am4core.color("#434A52")];

	});
</script>
<script type="text/javascript">
	var sektor_job=new Array();
	var sektor_job_yuzde=0;
	var arraysektor=<?php echo json_encode($sectordata); ?>;
	for (var i =0 ; i < arraysektor.length; i++) {
		sektor_job[i] = {"baslik":arraysektor[i]['meslekadi'],"yuzde":arraysektor[i]['personelmesleksayisi']};
	}
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chartsektor = am4core.create("sektorchartdiv", am4charts.PieChart3D);
		chartsektor.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chartsektor.legend = new am4charts.Legend();

		chartsektor.data = sektor_job;

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


