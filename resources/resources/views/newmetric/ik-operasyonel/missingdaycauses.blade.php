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
			<h2>Eksik Gün Nedenleri Oranı Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/19.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					Eksik gün kodları, işverenin çalışanlarını 30 günden az çalıştırması durumunda, kişi bazında sigortalının kaç gün eksik çalıştığını ve eksik çalışma nedeni ispatlamak için kullanılan kodlardır. Ay içinde bazı işgünlerinde çalıştırılmayan ve ücret ödenmeyen sigortalıların eksik gün nedeni ve eksik gün sayısı, işverence ilgili aya ait aylık prim ve hizmet belgesi veya muhtasar ve prim hizmet beyannamesiyle beyan edilir. Eksik günkodları aşağıda açıklanmış olup, e-Bildirge sistemi üzerinden aylık prim ve hizmet belgesinin/ e-beyanname sistemi üzerinden muhtasar ve prim hizmet beyannamesinin gönderilmesi esnasında bu kodlar ile eksik gün sayısının SGK’ ya bildirilmesi gerekmektedir. APHB(Aylık prim hizmet bildirgesi) üzerinden verilen eksik gün nedenlerinin dağılımını ifade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Eksik gün neden dağılım pasta grafiği</b><br>
				</p>
			</div>
		</div>
		<?php 
			$sabitler=config('constants');
			$aylar = $sabitler['aylar'];
    		$yillar = $sabitler['yillar']; 
    	?>
		<form method="GET" action="/missingdaycauses" style="width: 100%;">
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
						<h3 class="card-label">Eksik Gün Nedenleri</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 600px;margin-bottom: 20px;"></div>
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
						<h3 class="card-label">Sektör Eksik Gün Nedenleri</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="sektorchartdiv" style="height: 650px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
					for ($i=0; $i <$firmadata->count() ; $i++) { 
						//$indis=array_search($firmadata[$i]['kod'], array_column($sectordata, 'kod'));
						$indis=-1;
						foreach ($sectordata as $key => $value) {
							if ($value['egn']==$firmadata[$i]['egn']) {
								$indis=$key;
							}
						}
						if ($indis!=-1) {
							$firma_yuzdesi=$firmadata[$i]['toplamneden'];
							$sektor_yuzdesi=+$sectordata[$indis]['toplamneden'];
							$max=$sektor_yuzdesi+$sektor_yuzdesi*15/100; 
							$min=$sektor_yuzdesi-$sektor_yuzdesi*15/100; 
							if ($firma_yuzdesi<=$max AND $firma_yuzdesi>=$min) { ?>
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
									<div class="alert-icon">
										<i class="fas fa-retweet" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['eksikgunnedeni']}} NEDENİYLE EKSİK GÜN DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalamasının içerisinde olup eksik gün oranınız sektör ortalamasına göre nominal değerdedir.
									</div>
								</div><?php
							}elseif ($firma_yuzdesi<$min) { ?>
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
									<div class="alert-icon">
										<i class="far fa-check-circle" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['eksikgunnedeni']}} NEDENİYLE EKSİK GÜN DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalama değerinin altında olup ,eksik gün  oranınız   olumlu yönde seyretmektedir.
									</div>
								</div><?php
								
							}elseif ($firma_yuzdesi>$max) { ?>
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
									<div class="alert-icon">
										<i class="flaticon-warning" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										<strong>{{$firmadata[$i]['eksikgunnedeni']}} NEDENİYLE EKSİK GÜN DEĞERLENDİRMESİ : </strong>Değerlendirme oranınız sektör ortalama değerinin üzerinde olup, artış durumu neticesinde iş gücü kayıpları,iş kazası ,birim maliyet  ve eğitim oranlarında artış görülmesi muhtemeldir.
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
	var firma_missingdays=new Array();
	var missingdays_yuzde=0;
	var arrayfirma=<?php echo json_encode($firmadata); ?>;
	for (var i =0 ; i < arrayfirma.length; i++) {
		firma_missingdays[i] = {"baslik":arrayfirma[i]['eksikgunnedeni'],"yuzde":arrayfirma[i]['toplamneden']};	
		
	}
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = firma_missingdays;

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09"),am4core.color("#ff7f00"),am4core.color("#8a2be2"),am4core.color("#ffcc99"),am4core.color("#8b7355"),am4core.color("#97ffff"),am4core.color("#434A52")];

	});
</script>
<script type="text/javascript">
	var sektor_missingdays=new Array();
	var sektor_missingdays_yuzde=0;
	var arraysektor=<?php echo json_encode($sectordata); ?>;
	for (var i =0 ; i < arraysektor.length; i++) {
		sektor_missingdays[i] = {"baslik":arraysektor[i]['eksikgunnedeni'],"yuzde":arraysektor[i]['toplamneden']};
	}
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chartsektor = am4core.create("sektorchartdiv", am4charts.PieChart3D);
		chartsektor.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chartsektor.legend = new am4charts.Legend();

		chartsektor.data = sektor_missingdays;

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


