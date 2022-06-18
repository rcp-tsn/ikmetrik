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
			<h2>Ücretin Toplam Gelire Oranı Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/29.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					İşgücü maliyetlerinin işletmelerin bütçesinde önemli bir payı olması stratejik insan kaynakları süreçlerinde ücret yönetimi önemli bir girdi olarak karşımıza çıkmaktadır.Bu nedenle çalışan maliyetin toplam ücret gelirine oranı işletmelerin rekabet edebilirliğine olumlu/olumsuz etk eden önemli bir girdidir.Şirket toplam gelirin  toplam personele oarnını iafade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü : Toplam peronel maliyeti/Toplam ciro</b><br>
				</p>
			</div>
		</div>
		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title">
						<h3 class="card-label">Ücretin Toplam Gelire Oranı </h3>
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
<script src="https://cdn.amcharts.com/lib/4/themes/dataviz.js"></script>
<script type="text/javascript">

	var ucretin_toplam_gelire_orani_1=<?php echo $ucretin_toplam_gelire_orani_1; ?>;
	var ucretin_toplam_gelire_orani_2=<?php echo $ucretin_toplam_gelire_orani_2; ?>;
	var ucretin_toplam_gelire_orani_3=<?php echo $ucretin_toplam_gelire_orani_3; ?>;
	var ucretin_toplam_gelire_orani_4=<?php echo $ucretin_toplam_gelire_orani_4; ?>;
	var ucretin_toplam_gelire_orani_5=<?php echo $ucretin_toplam_gelire_orani_5; ?>;
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
		    "ucretintoplamgelireorani": ucretin_toplam_gelire_orani_1.toFixed(1),
		}, {
		    "baslik": String(date_2),
		    "ucretintoplamgelireorani": ucretin_toplam_gelire_orani_2.toFixed(1),
		}, {
		    "baslik": String(date_3),
		    "ucretintoplamgelireorani": ucretin_toplam_gelire_orani_3.toFixed(1),
		}, {
		    "baslik": String(date_4),
		    "ucretintoplamgelireorani": ucretin_toplam_gelire_orani_4.toFixed(1),
		}, {
		    "baslik": String(date_5),
		    "ucretintoplamgelireorani": ucretin_toplam_gelire_orani_5.toFixed(1),
		}];
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "baslik";
		categoryAxis.renderer.minGridDistance = 20;
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
		valueAxis.title.text = "Ücretin Toplam Gelire Oranı";
		valueAxis.title.fontWeight = "bold";
		valueAxis.renderer.labels.template.adapter.add("text", function(text) {
		  return text + " %";
		});

		// Create series
		var series = chart.series.push(new am4charts.ColumnSeries3D());
		series.dataFields.valueY = "ucretintoplamgelireorani";
		series.dataFields.categoryX = "baslik";
		series.name = "Ücretin Toplam Gelire Oranı";
		series.columns.template.tooltipText = "Ücretin Toplam Gelire Oranı: [bold]{valueY}[/] %";
		series.columns.template.fillOpacity = 0.8;

		var columnTemplate = series.columns.template;
		columnTemplate.strokeWidth = 2;
		columnTemplate.strokeOpacity = 1;
		columnTemplate.stroke = am4core.color("#FFFFFF");
		var colorlists=[am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09"),am4core.color("#ff7f00"),am4core.color("#8a2be2"),am4core.color("#ffcc99"),am4core.color("#8b7355"),am4core.color("#97ffff"),am4core.color("#434A52")];
		columnTemplate.adapter.add("fill", function(fill, target) {
			if (target.dataItem.valueY==ucretin_toplam_gelire_orani_1.toFixed(1)) {
				return am4core.color("#126FC9");
			}else if(target.dataItem.valueY==ucretin_toplam_gelire_orani_2.toFixed(1)) {
				return am4core.color("#E74330");
			}else if(target.dataItem.valueY==ucretin_toplam_gelire_orani_3.toFixed(1)) {
				return am4core.color("#08cc1b");
			}else if(target.dataItem.valueY==ucretin_toplam_gelire_orani_4.toFixed(1)) {
				return am4core.color("#ffff09");
			}else if(target.dataItem.valueY==ucretin_toplam_gelire_orani_5.toFixed(1)) {
				return am4core.color("#ff7f00");
			}else{
				return am4core.color("#126FC9");
			}
		  	
		});
		columnTemplate.adapter.add("stroke", function(stroke, target) {
		  	if (target.dataItem.valueY==ucretin_toplam_gelire_orani_1.toFixed(1)) {
				return am4core.color("#126FC9");
			}else if(target.dataItem.valueY==ucretin_toplam_gelire_orani_2.toFixed(1)) {
				return am4core.color("#E74330");
			}else if(target.dataItem.valueY==ucretin_toplam_gelire_orani_3.toFixed(1)) {
				return am4core.color("#08cc1b");
			}else if(target.dataItem.valueY==ucretin_toplam_gelire_orani_4.toFixed(1)) {
				return am4core.color("#ffff09");
			}else if(target.dataItem.valueY==ucretin_toplam_gelire_orani_5.toFixed(1)) {
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


