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
			<h2>Kişi Başı Eğitim Maliyeti Nedir?</h2>
			<div class="metric-image-div">
				<img src="/pictures/27.jpg" style="width: 100%;height: auto;">
			</div>
			<div class="metric-define-div">
				<p style="text-align: justify;">
					Bireyin gelişiminin temel noktalarından biri olan işletme bünyesinde planlanan/yapılan eğitim organizasyonları maliyetinin kişi bazında formüle edilmesi ile elde edilen değeri ifade eder. Artan eğitim saati ve maliyeti işletme üzerinde olumlu birçok yansıması beklenmektedir. İşletme içerisinde gerçekleştirilen eğitim maliyetinin kişi başı eğitim maliyeti oranını ifade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster <i class="fas fa-caret-down"></i></span><br>
					<span class="formul"><b>Hesaplama Formülü :</b> Toplam eğitim maliyeti/Ortalama Personel Sayısı</span>
					<br>
				</p>
			</div>
		</div>
		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title">
						<h3 class="card-label">Kişi Başı Eğitim Maliyeti</h3>
					</div>
				</div>
				@if($error_message==1 || $personelsayisi==0)
					<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #ff7f00;border-color: #ff7f00;margin-top: 20px;">
						<div class="alert-icon">
							<i class="flaticon-warning" style="color: #fff;"></i>
						</div>
						<div class="alert-text" style="color: #fff;">
							@if($error_message==1)
							Firmanıza ait veri girişi yapılmadığından karşılaştırma yapılamamaktadır. Lütfen veri girişi yapınız.
							@else
							Firmanıza ait veri yoktur. Lütfen sayfanın solundaki menüden metrik tanımlamaları bölümünden sgk verilerinizi çekiniz.
							@endif
						</div>
						@if($error_message==1)
						<a href="/sgk_companies/ky94V16nZQXQNR7oQgwz" class="btn btn-primary mr-2" style="line-height: 23px;">Veri Girişi</a>
						@endif
					</div>
				@endif
				<div class="card-body" style="<?php if($error_message==1) echo 'display: none;' ?>">
					<input type="button" style="float: right;z-index: 1" class="btn btn-primary mr-2"  data-toggle="modal" data-target="#exampleModalCenter" id="savepdf" value="RAPOR AL">
					<span><b>Toplam Kişi Başı Eğitim Maliyeti : </b> {{number_format($firma_toplam__egitim_maliyeti,2,',','.')}} ₺</span>
					<br>
					<br>
					<div id="chartdiv" style="margin-bottom: 20px;height: 350px;"></div>
					<?php
					$max=$genel_ortalama+$genel_ortalama*15/100;
					$min=$genel_ortalama-$genel_ortalama*15/100;
					if ($firma_current_rate<=$max AND $firma_current_rate>=$min) {
						$degerlendirme_mesaji="Değerlendirme oranınız önceki yılların ortalama değerlerinizin içerisinde olup kişi başı eğitim maliyetiniz önceki yılların ortalamasına göre nominal değerdedir.";?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
							<div class="alert-icon">
								<i class="fas fa-retweet" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız önceki yılların ortalama değerlerinizin içerisinde olup kişi başı eğitim maliyetiniz önceki yılların ortalamasına göre nominal değerdedir.
							</div>
						</div><?php
					}elseif ($firma_current_rate<$min) {
						$degerlendirme_mesaji="Değerlendirme oranınız önceki yılların ortalama değerlerinizin altında olup ,eğitime ayrılan süre oranınızı olumsuz yönde seyretmektedir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
							<div class="alert-icon">
								<i class="flaticon-warning" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız önceki yılların ortalama değerlerinizin altında olup ,eğitime ayrılan süre oranınızı olumsuz yönde seyretmektedir.
							</div>
						</div><?php

					}elseif ($firma_current_rate>$max) {
						$degerlendirme_mesaji="Değerlendirme oranınız önceki yılların ortalama değerlerinizin üzerinde olup, artış durumu neticesindei ş kazasının azalması,verimlilik,performans süreçlerinde artış görülmesi muhtemeldir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
							<div class="alert-icon">
								<i class="far fa-check-circle" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız önceki yılların ortalama değerlerinizin üzerinde olup, artış durumu neticesinde iş kazasının azalması,verimlilik,performans süreçlerinde artış görülmesi muhtemeldir.
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
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/dataviz.js"></script>
<script type="text/javascript">
	var firmaeducationdata=<?php if(isset($firmaeducationdata)) {echo json_encode($firmaeducationdata);}else{echo 0;} ?>;
	if (firmaeducationdata!=0) {
		var education_chart_data=new Array();
	var maliyetorani=0;
	var renkler=new Array('#126FC9','#E74330','#08cc1b','#ffff09','#ff7f00','#8a2be2','#ffcc99','#8b7355','#97ffff','#434A52','#ff69b4','#008080');
	for (var i = 0; i < firmaeducationdata.length; i++) {
		if (firmaeducationdata[i]['personelsayisi']==0) {
		  maliyetorani=0;
		  education_chart_data[i] = {"baslik":String(firmaeducationdata[i]['yil']),"egitimmaliyetiorani":maliyetorani};
		}else{
		  maliyetorani=parseFloat(firmaeducationdata[i]['educationcost']/firmaeducationdata[i]['personelsayisi']).toFixed(2);
		  education_chart_data[i] = {"baslik":String(firmaeducationdata[i]['yil']),"egitimmaliyetiorani":maliyetorani};
		}

	}

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end

		// Create chart instance
		var chart = am4core.create("chartdiv", am4charts.XYChart3D);
		// Add data
		chart.data = education_chart_data;
		chart.logo.height = -15000;
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "baslik";
		categoryAxis.renderer.minGridDistance = 20;
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
		valueAxis.title.text = "Kişi Başı Eğitim Maliyeti";
		valueAxis.title.fontWeight = "bold";
		valueAxis.renderer.labels.template.adapter.add("text", function(text) {
		  return text + " ₺";
		});

		// Create series
		var series = chart.series.push(new am4charts.ColumnSeries3D());
		series.dataFields.valueY = "egitimmaliyetiorani";
		series.dataFields.categoryX = "baslik";
		series.name = "Eğitim Maliyeti";
		series.columns.template.tooltipText = "Eğitim Maliyeti: [bold]{valueY}[/] ₺";
		series.columns.template.fillOpacity = 0.8;

		var columnTemplate = series.columns.template;
		columnTemplate.strokeWidth = 2;
		columnTemplate.strokeOpacity = 1;
		columnTemplate.stroke = am4core.color("#FFFFFF");
		var colorlists=[am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09"),am4core.color("#ff7f00"),am4core.color("#8a2be2"),am4core.color("#ffcc99"),am4core.color("#8b7355"),am4core.color("#97ffff"),am4core.color("#434A52")];
		columnTemplate.adapter.add("fill", function(fill, target) {
			for(var i = 0; i < education_chart_data.length; i++){
				if (target.dataItem.valueY==parseFloat(education_chart_data[i]['egitimmaliyetiorani']).toFixed(2)) {
					return am4core.color(renkler[i]);
				}
			}

		});
		columnTemplate.adapter.add("stroke", function(stroke, target) {
		  	for(var i = 0; i < education_chart_data.length; i++){
				if (target.dataItem.valueY==parseFloat(education_chart_data[i]['egitimmaliyetiorani']).toFixed(2)) {
					return am4core.color(renkler[i]);
				}
			}
		});
		chart.responsive.enabled = true;

		chart.responsive.rules.push({
		  relevant: function(target) {
		    return false;
		  },
		  state: function(target, stateId) {
		    return;
		  }
		});
	}

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
		    chart.exporting.getImage("png")
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
		      text: "<?php echo $metrik_adi." metriğinizin raporu aşağıda bilgilerinize sunulmuştur."; ?>",
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

		    pdfMake.createPdf(doc).download("<?php echo $metrik_adi; ?> Raporu.pdf",close_information_modal);

		 });
	});
	function close_information_modal(){
		$( "#closemodalbutton" ).trigger( "click" );
	}
</script>
@stop


