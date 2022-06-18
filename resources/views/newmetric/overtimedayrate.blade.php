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
			<h2>Fazla Mesai Günü Oranı Nedir?</h2>
			<div class="metric-image-div">
				<img src="/pictures/2.jpg" style="width: 100%;height: auto;">
			</div>
			<div class="metric-define-div">
				<p style="text-align: justify;">
					Bir işyerinde haftalık çalışma süresini aşan çalışmalar fazla mesai olarak tanımlanmıştır. Normal mesaisi için bir ücret alan işçi, fazla mesaisi için de ayrıca bir ücret alır. İş kanununda yazılı koşullar çerçevesinde haftalık 45 saati aşan çalışmaları ifade etmektedir. İşletmelerde meydana gelen değişiklikler ve planlı/plansız üretim artışları sonucunda oluşan fazla çalışmayı ifade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster <i class="fas fa-caret-down"></i></span><br>
					<span class="formul"><b>Hesaplama Formülü :</b> Fazla mesai saati/Planlanan toplam saat</span><br>
				</p>
			</div>
		</div>
		<?php
			$sabitler=config('constants');
			$aylar = $sabitler['aylar'];
    		$yillar = $sabitler['yillar'];
    	?>
		<form method="GET" action="/sub-metrics/overtime-day-rate" style="width: 100%;">
			<div class="row">
				<div class="col-md-6" style="height: 50px;">
					<div style="float: left;">
						<label style="float: left;font-size: 17px;font-weight: 700;margin-right: 4px;line-height: 40px;">Yıl Seçiniz </label>
						<select class="form-control" name="year" style="width: 150px;margin-right: 10px;float: left;">
							@foreach($years as $y)
							<option value="{{$y->value_year}}" <?php if($y->value_year==$year){echo "selected";} ?>>{{$y->value_year}}</option>
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
							Firmanızın Fazla Mesai Günü Oranı : %{{number_format($firma_yuzdesi,1)}}
						</h3>
					</div>
				</div>
				@if($firma_toplam_fazla_mesai_saati==0 || $firma_yuzdesi==0)
					<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #ff7f00;border-color: #ff7f00;margin-top: 20px;">
						<div class="alert-icon">
							<i class="flaticon-warning" style="color: #fff;"></i>
						</div>
						<div class="alert-text" style="color: #fff;">
							@if($firma_toplam_fazla_mesai_saati==0)
							Firmanıza ait fazla mesai saati verilerinizin girişi yapılmadığından karşılaştırma yapılamamaktadır. Lütfen veri girişi yapınız.
							@else
							Firmanıza ait veri yoktur. Lütfen sayfanın solundaki menüden metrik tanımlamaları bölümünden sgk verilerinizi çekiniz.
							@endif
						</div>
						@if($firma_toplam_fazla_mesai_saati==0)
						<a href="/sgk_companies/ky94V16nZQXQNR7oQgwz" class="btn btn-primary mr-2" style="line-height: 23px;">Veri Girişi</a>
						@endif
					</div>
				@endif
				<div class="card-body" style="<?php if($firma_yuzdesi==0) echo 'display: none;' ?>">
					<input type="button" style="float: right;z-index: 1" class="btn btn-primary mr-2"  data-toggle="modal" data-target="#exampleModalCenter" id="savepdf" value="RAPOR AL">
					<span><b>Firmanızın Toplam Fazla Mesai Saati : {{number_format($firma_toplam_fazla_mesai_saati,0,',','.')}} saat</b></span><br>
					<span><b>Firmanızın Toplam Planlanan Mesai Saati : {{number_format($firma_toplam_planlanan_gun_saati,0,',','.')}} saat</b></span>
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 300px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
					$max=$genel_ortalama+$genel_ortalama*15/100;
					$min=$genel_ortalama-$genel_ortalama*15/100;
					if ($firma_current_rate<=$max AND $firma_current_rate>=$min) {
						$degerlendirme_mesaji="Değerlendirme oranınız önceki ayların ortalama değerlerinizin içerisinde olup fazla mesai oranınız önceki ayların ortalamasına göre nominal değerdedir.";?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
							<div class="alert-icon">
								<i class="fas fa-retweet" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız önceki ayların ortalama değerlerinizin içerisinde olup fazla mesai oranınız önceki ayların ortalamasına göre nominal değerdedir.
							</div>
						</div><?php
					}elseif ($firma_current_rate<$min) {
						$degerlendirme_mesaji="Değerlendirme oranınız önceki ayların ortalama değerlerinizin altında olup fazla mesai durumunuz olumlu yönde seyretmektedir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
							<div class="alert-icon">
								<i class="far fa-check-circle" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								@if($firma_current_rate==0)
								Firmanızın bu ayki fazla mesaisi 0 olup mesai durumunuz nominal değerdedir.
								@else
								Değerlendirme oranınız önceki ayların ortalama değerlerinizin altında olup fazla mesai durumunuz olumlu yönde seyretmektedir.
								@endif
							</div>
						</div><?php

					}elseif ($firma_current_rate>$max) {
						$degerlendirme_mesaji="Değerlendirme oranınız önceki ayların ortalama değerlerinizin üzerinde olup, artış durumu neticesinde iş gücü kayıpları,iş kazası,turnover ve birim maliyet  oranlarında artış görülmesi muhtemeldir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
							<div class="alert-icon">
								<i class="flaticon-warning" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız önceki ayların ortalama değerlerinizin üzerinde olup, artış durumu neticesinde iş gücü kayıpları,iş kazası,turnover ve birim maliyet  oranlarında artış görülmesi muhtemeldir.
							</div>
						</div><?php

					} ?>
				</div>
			</div>
			<!--end::Card-->
		</div>
	</div>
</div>


<!-- Modal-->
<div class="modal fade" id="exampleModalCenter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        	<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Bilgilendirme</h5>
				<button type="button" id="closemodalbutton" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body" style="font-size: 20px;font-weight: 700;">
				<div class="paperanimationdiv">
					<div class="pen">
					    <div class="body-pen">
					      <div class="white-stripe"></div>
					      <div class="black-stripe"></div>
					    </div>
					    <div class="head-pen">
					      <div class="mine"></div>
					    </div>
					</div>
					<div class="paper"></div>
				</div>
				Rapor Hazırlanıyor Lütfen Bekleyiniz..
			</div>

        </div>
    </div>
</div>
<style type="text/css">
	.amcharts-amexport-item {
	  width: 70px !important;
	  line-height: 30px !important;
	  margin-top: -20px !important;
	}
</style>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script type="text/javascript">
	    var firmovertimedata=<?php echo json_encode($firmovertimedata); ?>;
		var overtime_chart_data=new Array();
		var mesaiorani=0;
		for (var i = 0; i < firmovertimedata.length; i++) {
			if (firmovertimedata[i]['planlanansaat']==0) {
			  mesaiorani=0;
			  overtime_chart_data[i] = {"baslik":firmovertimedata[i]['ay'],"mesaiorani":mesaiorani};
			}else{
			  mesaiorani=parseFloat(firmovertimedata[i]['mesaisaati']*100/firmovertimedata[i]['planlanansaat']).toFixed(2);
			  overtime_chart_data[i] = {"baslik":firmovertimedata[i]['ay'],"mesaiorani":mesaiorani};
			}

		}
		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.XYChart3D);
		// Add data
		chart.data = overtime_chart_data;
		chart.logo.height = -15000;
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "baslik";
		categoryAxis.renderer.minGridDistance = 20;
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
		valueAxis.title.text = "Fazla Mesai Günü Oranı";
		valueAxis.title.fontWeight = "bold";
		valueAxis.renderer.labels.template.adapter.add("text", function(text) {
		  return text + " %";
		});

		// Create series
		var series = chart.series.push(new am4charts.ColumnSeries3D());
		series.dataFields.valueY = "mesaiorani";
		series.dataFields.categoryX = "baslik";
		series.name = "Mesai Oranı";
		series.columns.template.tooltipText = "Mesai Oranı: [bold]{valueY}[/] %";
		series.columns.template.fillOpacity = 0.8;

		var columnTemplate = series.columns.template;
		columnTemplate.strokeWidth = 2;
		columnTemplate.strokeOpacity = 1;
		columnTemplate.stroke = am4core.color("#FFFFFF");
		var colorlists=[am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09"),am4core.color("#ff7f00"),am4core.color("#8a2be2"),am4core.color("#ffcc99"),am4core.color("#8b7355"),am4core.color("#97ffff"),am4core.color("#434A52")];
		columnTemplate.adapter.add("fill", function(fill, target) {
			if (target.dataItem.valueY==parseFloat(overtime_chart_data[0]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Ocak") {
				return am4core.color("#126FC9");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[1]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Şubat") {
				return am4core.color("#E74330");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[2]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Mart") {
				return am4core.color("#08cc1b");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[3]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Nisan") {
				return am4core.color("#ffff09");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[4]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Mayıs") {
				return am4core.color("#ff7f00");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[5]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Haziran") {
				return am4core.color("#8a2be2");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[6]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Temmuz") {
				return am4core.color("#ffcc99");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[7]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Ağustos") {
				return am4core.color("#8b7355");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[8]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Eylül") {
				return am4core.color("#97ffff");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[9]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Ekim") {
				return am4core.color("#434A52");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[10]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Kasım") {
				return am4core.color("#ff69b4");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[11]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Aralık") {
				return am4core.color("#008080");
			}else{
				return am4core.color("#126FC9");
			}

		});
		columnTemplate.adapter.add("stroke", function(stroke, target) {
		  	if (target.dataItem.valueY==parseFloat(overtime_chart_data[0]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Ocak") {
				return am4core.color("#126FC9");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[1]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Şubat") {
				return am4core.color("#E74330");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[2]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Mart") {
				return am4core.color("#08cc1b");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[3]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Nisan") {
				return am4core.color("#ffff09");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[4]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Mayıs") {
				return am4core.color("#ff7f00");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[5]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Haziran") {
				return am4core.color("#8a2be2");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[6]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Temmuz") {
				return am4core.color("#ffcc99");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[7]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Ağustos") {
				return am4core.color("#8b7355");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[8]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Eylül") {
				return am4core.color("#97ffff");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[9]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Ekim") {
				return am4core.color("#434A52");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[10]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Kasım") {
				return am4core.color("#ff69b4");
			}else if(target.dataItem.valueY==parseFloat(overtime_chart_data[11]['mesaiorani']).toFixed(2) && target.dataItem.categoryX=="Aralık") {
				return am4core.color("#008080");
			}else{
				return am4core.color("#126FC9");
			}
		});

		chart.responsive.useDefault = false
		chart.responsive.enabled = true;

		chart.responsive.rules.push({
		  relevant: function(target) {
		    if (target.pixelWidth <= 400) {
		      return true;
		    }
		    
		    return false;
		  },
		  state: function(target, stateId) {
		    if (target instanceof am4charts.Chart) {
		      var state = target.states.create(stateId);
		      state.properties.paddingTop = 5;
		      state.properties.paddingRight = 15;
		      state.properties.paddingBottom = 5;
		      state.properties.paddingLeft = 0;
		      return state;
		    }
		    return null;
		  }
		});

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script type="text/javascript">
	$('.formulgoster').click(function(){
		$('.formul').slideToggle("slow");
		var x = document.getElementById("formulgoster");
	  if (x.innerHTML === 'Formülü Göster <i class="fas fa-caret-down"></i>') {
	    x.innerHTML = 'Formülü Gizle <i class="fas fa-caret-up"></i>';
	  } else {
	    x.innerHTML = 'Formülü Göster <i class="fas fa-caret-down"></i>';
	  }
	});
</script>
<script type="text/javascript">
		$('#savepdf').click(function() {
  		  console.log("reported working");
		  Promise.all([
		    chart.exporting.pdfmake,
		    chart.exporting.getImage("png"),
		    ]).then(function(res) {
		    var pdfMake = res[0];

		    // pdfmake is ready
		    // Create document template
		    var doc = {
		      pageSize: "A4",
		      pageOrientation: "portrait",
		      pageMargins: [30, 140, 30, 110],
		      info: {
				title: '<?php echo $metrik_adi; ?>',
				author: 'İK Metrik Yazılım A.Ş.',
				subject: '<?php echo $metrik_adi; ?> Raporu',
				keywords: '<?php echo $metrik_adi; ?> Raporu',
			  },
		      header:{
		      	alignment: 'center',
		      	margin: [0, 0,0,40],
		      	columns: [{
					      image: 'data:image/png;base64,<?php echo $base_logo; ?>',
						fit: [225, 199]
					    }, {
					      alignment: 'center',
					      text: "<?php echo $metrik_adi; ?> Raporu",
					      margin: [0, 40,30,0],
						    style: {
						      fontSize: 18,
						      bold: true,

						    }
					    }],
					},
				footer:{

					image: '<?php echo $base_footer; ?>',
					width: 537,
					margin: [30, 0,0,0],

				},

		       content: []
		    };

		    doc.content.push({
		      text:[{
		      	text: "Firma Adı : ",
		      	fontSize: 15,
		      	bold: true,
		      },{
		      	text: "<?php echo $firma_adi; ?>",
		      	fontSize: 15,
		      	bold: false,
		      }],

		      margin: [0, 20, 0, 7]
		    });

		    doc.content.push({
		      text:[{
		      	text: "Tarih : ",
		      	fontSize: 15,
		      	bold: true,
		      },{
		      	text: "<?php echo date("d-m-Y"); ?>",
		      	fontSize: 15,
		      	bold: false,
		      }],
		      margin: [0, 0, 0, 7]
		    });

		    doc.content.push({
		    	alignment: 'justify',
		      text: "<?php echo $rapor_metni; ?>",
		      fontSize: 14,
		      bold: false,
		      margin: [0, 0, 0, 7]
		    });

		    doc.content.push({
		      text: "<?php echo $year." yılına ait ".$metrik_adi." metriğinizin raporu aşağıda bilgilerinize sunulmuştur."; ?>",
		      fontSize: 14,
		      bold: false,
		      margin: [0, 0, 0, 15]
		    });

		    doc.content.push({
		      text: "Saygılarımızla..",
		      fontSize: 14,
		      bold: false,
		      margin: [0, 0, 0, 15]
		    });


		    doc.content.push({
		      text: "İK Metrik Yazılım Bilişim A.Ş.",
		      fontSize: 14,
		      bold: false,
		      margin: [0, 0, 0, 30]
		    });

		    doc.content.push({
		      image: res[1],
		      width: 530
		    });

		    doc.content.push({
		  		alignment: 'left',
		  		columns: [{
		  			image: 'data:image/png;base64,<?php echo $base_degerlendirme_ikonu; ?>',
			    	fit: [20, 20],
			    	width: 23,
		  		},{
		  			text: 'Metrik Değerlendirmesi:',
			  		style: {
			  			fontSize: 14,
						bold: true,
						decoration: 'underline',
			  		}
		  		}],
		  		margin: [0,40,0,0],
		  	});

		  	doc.content.push({
		  		text:[{
			      	text: '- ',
			      	fontSize: 12,
			      	bold: true,
			    },{
			      	text: '<?php echo $degerlendirme_mesaji; ?>',
			      	fontSize: 12,
			      	bold: false,
			    }],
		  		margin: [0,10],
		  	});
		    var report_pdf=pdfMake.createPdf(doc);
		    report_pdf.download("<?php echo $metrik_adi; ?> Raporu.pdf",close_information_modal);
		 });
	});
	function close_information_modal(){
		$( "#closemodalbutton" ).trigger( "click" );
	}
</script>

@stop


