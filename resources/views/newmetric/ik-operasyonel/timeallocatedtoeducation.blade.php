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
			<h2>Eğitime Ayrılan Süre Oranı Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/17.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					Eğitim genel olarak bireye yeni bir davranış kazandırmak yada mevcut davranışını değiştirmek olarak tanımlanmaktadır.Eğitim şirketin organizasyonel performansının artması,çalışanların bererilerinin ve bilgilerinin kurumsal düzene uygun olarak yapılmasını sağlaaktır.İşletme içerisinde gerçekleştirilen eğitim süresinin oranını ifade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Toplam eğitim süresi/Ortalam çalışan sayısı(ay başı +ay sonu personel sayısı/2)</b><br>
				</p>
			</div>
		</div>

		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title">
						<h3 class="card-label">Eğitime Ayrılan Süre Oranı </h3>
					</div>
				</div>
				<div class="card-body">
					<div id="chartdiv" style="margin-bottom: 20px;height: 350px;"></div>
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

	var egitime_ayrilan_sure_orani_1=<?php echo $egitime_ayrilan_sure_orani_1; ?>;
	var egitime_ayrilan_sure_orani_2=<?php echo $egitime_ayrilan_sure_orani_2; ?>;
	var egitime_ayrilan_sure_orani_3=<?php echo $egitime_ayrilan_sure_orani_3; ?>;
	var egitime_ayrilan_sure_orani_4=<?php echo $egitime_ayrilan_sure_orani_4; ?>;
	var egitime_ayrilan_sure_orani_5=<?php echo $egitime_ayrilan_sure_orani_5; ?>;
	var date_1=<?php echo $date_1; ?>;
	var date_2=<?php echo $date_2; ?>;
	var date_3=<?php echo $date_3; ?>;
	var date_4=<?php echo $date_4; ?>;
	var date_5=<?php echo $date_5; ?>;
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end

		// Create chart instance
		var chart = am4core.create("chartdiv", am4charts.XYChart3D);
		// Add data
		chart.data = [{
		    "baslik": String(date_1),
		    "egitimeayrilansureorani": egitime_ayrilan_sure_orani_1.toFixed(2),
		}, {
		    "baslik": String(date_2),
		    "egitimeayrilansureorani": egitime_ayrilan_sure_orani_2.toFixed(2),
		}, {
		    "baslik": String(date_3),
		    "egitimeayrilansureorani": egitime_ayrilan_sure_orani_3.toFixed(2),
		}, {
		    "baslik": String(date_4),
		    "egitimeayrilansureorani": egitime_ayrilan_sure_orani_4.toFixed(2),
		}, {
		    "baslik": String(date_5),
		    "egitimeayrilansureorani": egitime_ayrilan_sure_orani_5.toFixed(2),
		}];
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "baslik";
		categoryAxis.renderer.minGridDistance = 20;
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
		valueAxis.title.text = "Kişi Başı Eğitime Ayrılan Süre Oranı";
		valueAxis.title.fontWeight = "bold";
		valueAxis.renderer.labels.template.adapter.add("text", function(text) {
		  return text + " s";
		});

		// Create series
		var series = chart.series.push(new am4charts.ColumnSeries3D());
		series.dataFields.valueY = "egitimeayrilansureorani";
		series.dataFields.categoryX = "baslik";
		series.name = "Eğitime Ayrılan Süre Oranı";
		series.columns.template.tooltipText = "Eğitime Ayrılan Süre Oranı: [bold]{valueY}[/] s";
		series.columns.template.fillOpacity = 0.8;

		var columnTemplate = series.columns.template;
		columnTemplate.strokeWidth = 2;
		columnTemplate.strokeOpacity = 1;
		columnTemplate.stroke = am4core.color("#FFFFFF");
		var colorlists=[am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09"),am4core.color("#ff7f00"),am4core.color("#8a2be2"),am4core.color("#ffcc99"),am4core.color("#8b7355"),am4core.color("#97ffff"),am4core.color("#434A52")];
		columnTemplate.adapter.add("fill", function(fill, target) {
			if (target.dataItem.valueY==egitime_ayrilan_sure_orani_1.toFixed(2)) {
				return am4core.color("#126FC9");
			}else if(target.dataItem.valueY==egitime_ayrilan_sure_orani_2.toFixed(2)) {
				return am4core.color("#E74330");
			}else if(target.dataItem.valueY==egitime_ayrilan_sure_orani_3.toFixed(2)) {
				return am4core.color("#08cc1b");
			}else if(target.dataItem.valueY==egitime_ayrilan_sure_orani_4.toFixed(2)) {
				return am4core.color("#ffff09");
			}else if(target.dataItem.valueY==egitime_ayrilan_sure_orani_5.toFixed(2)) {
				return am4core.color("#ff7f00");
			}else{
				return am4core.color("#126FC9");
			}
		  	
		});
		columnTemplate.adapter.add("stroke", function(stroke, target) {
		  	if (target.dataItem.valueY==egitime_ayrilan_sure_orani_1.toFixed(2)) {
				return am4core.color("#126FC9");
			}else if(target.dataItem.valueY==egitime_ayrilan_sure_orani_2.toFixed(2)) {
				return am4core.color("#E74330");
			}else if(target.dataItem.valueY==egitime_ayrilan_sure_orani_3.toFixed(2)) {
				return am4core.color("#08cc1b");
			}else if(target.dataItem.valueY==egitime_ayrilan_sure_orani_4.toFixed(2)) {
				return am4core.color("#ffff09");
			}else if(target.dataItem.valueY==egitime_ayrilan_sure_orani_5.toFixed(2)) {
				return am4core.color("#ff7f00");
			}else{
				return am4core.color("#126FC9");
			}
		});	
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


