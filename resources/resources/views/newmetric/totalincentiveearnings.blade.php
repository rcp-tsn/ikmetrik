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
			<h2>Toplam Teşvik Kazancı Nedir?</h2>
			<div class="metric-image-div">
				<img src="/pictures/16.jpg" style="width: 100%;height: auto;">
			</div>
			<div class="metric-define-div">
				<p style="text-align: justify;">
					Sosyal Güvenlik Kurumu (SGK) teşvik, istihdamın artması ve işsizliğin azalmasını sağlamak amacıyla, yine Sosyal Güvenlik Kurumu'nun belirlediği şartlara göre birçok iş yerinin ve işçinin desteklenmesi olarak açıklanmaktadır. Rekabetçi yaklaşımın tüm unsurları ile çalışma hayatının dinamiklerini etkilemek ile birlikte işverenler üzerindeki maliyet – verimlilik ilişkisi önem arz etmektedir. İşverenlere destek amacı ile yürürlükte bulunan 15 ayrı teşvik işlemlerinin faydalanma oranını SGK nezdinde tanımlar.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster <i class="fas fa-caret-down"></i></span><br>
					<span class="formul"><b>Hesaplama Formülü :</b> Toplam teşvik kazanç pasta grafiği</span><br>
				</p>
			</div>
		</div>
		<?php
			$sabitler=config('constants');
			$aylar = $sabitler['aylar'];
    		$yillar = $sabitler['yillar'];
    	?>
		<form method="GET" action="/sub-metrics/total-incentive-earnings" class="date-form">
			<div class="row">
				<div class="col-md-6 col-xs-12 date-part-main">
					<div class="date-part">
						<label class="date-part-label">Başlangıç Tarihi </label>
						<select class="form-control date-month" name="minmonth">
							@foreach($aylar as $key => $ay)
							<option value="{{$key}}" <?php if($minmonth==$key){echo "selected";} ?>>{{$ay}}</option>
							@endforeach
						</select>
					</div>
					<div class="date-part">
						<label class="date-part-label"></label>
						<select class="form-control date-year" name="minyear">
							@foreach($yillar as $key => $yil)
							<option value="{{$key}}" <?php if($minyear==$key){echo "selected";} ?>>{{$yil}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-6 col-xs-12 date-part-main">
					<div class="date-part">
						<label class="date-part-label">Bitiş Tarihi </label>
						<select class="form-control date-month" name="maxmonth">
							@foreach($aylar as $key => $ay)
							<option value="{{$key}}" <?php if($maxmonth==$key){echo "selected";} ?>>{{$ay}}</option>
							@endforeach
						</select>
					</div>
					<div class="date-part">
						<label class="date-part-label"></label>
						<select class="form-control date-year" name="maxyear">
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
					<div class="card-title" style="width: 100%;">
						<h3 class="card-label"style="width: 50%;">
							Firmanızın Toplam Teşvik Tutarı : {{number_format($firma_toplam_tesvik_kazanci,2,',','.')}} ₺
						</h3>
					</div>
				</div>
				@if($firma_yuzdesi==0)
					<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #ff7f00;border-color: #ff7f00;margin-top: 20px;">
						<div class="alert-icon">
							<i class="flaticon-warning" style="color: #fff;"></i>
						</div>
						<div class="alert-text" style="color: #fff;">
							@if($firma_yuzdesi==0 AND $sektor_yuzdesi==0)
							Seçilen tarih aralığında firmanıza ve sektöre ait veri yoktur. Dolayısıyla karşılaştırma yapılamamaktadır.
							@elseif($firma_yuzdesi==0)
								@if($firma_toplam_tesvik_kazanci==0)
								Seçilen tarih aralığında firmanızın teşvik kazancı yoktur. Dolayısıyla karşılaştırma yapılamamaktadır.
								@else
								Seçilen tarih aralığında firmanıza ait veri olmadığından karşılaştırma yapılamamaktadır.
								@endif
							@elseif($sektor_yuzdesi==0)
							Seçilen tarih aralığında sektöre ait veri olmadığından karşılaştırma yapılamamaktadır.
							@endif
						</div>
					</div>
				@endif
				<div class="card-body" style="<?php if($firma_yuzdesi==0) echo 'display: none;' ?>">
					<input type="button" style="float: right;z-index: 1" class="btn btn-primary mr-2"  data-toggle="modal" data-target="#exampleModalCenter" id="savepdf" value="RAPOR AL">
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 380px;margin-bottom: 20px;margin-top: 50px;"></div>
					<!--end::Chart-->
				</div>
			</div>
			<!--end::Card-->
		</div>
		<div class="col-lg-12" style="<?php if($firma_yuzdesi==0 || $sektor_yuzdesi==0) echo 'display: none;' ?>">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title" style="width: 100%;">
						<h3 class="card-label"style="width: 50%;">
							Sektörün Toplam Teşvik Tutarı : {{number_format($sektor_toplam_tesvik_kazanci,2,',','.')}} ₺
						</h3>
					</div>
				</div>
				<div class="card-body">
					<!--begin::Chart-->
					<div id="sektorchartdiv" style="height: 380px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
					$max=$sektor_yuzdesi+$sektor_yuzdesi*15/100;
					$min=$sektor_yuzdesi-$sektor_yuzdesi*15/100;
					if ($firma_yuzdesi<=$max AND $firma_yuzdesi>=$min) {
						$degerlendirme_mesaji="Değerlendirme oranınız sektör ortalamasının içerisinde olup teşvik faydalanma  oranınızın max. düzeyde olması hedeflenmelidir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
							<div class="alert-icon">
								<i class="fas fa-retweet" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız sektör ortalamasının içerisinde olup teşvik faydalanma  oranınızın max. düzeyde olması hedeflenmelidir
							</div>
						</div><?php
					}elseif ($firma_yuzdesi<$min) {
						$degerlendirme_mesaji="Değerlendirme oranınız sektör ortalama değerinin altında olup ,teşvik faydalanma oranınız   olumsuz yönde seyretmektedir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
							<div class="alert-icon">
								<i class="flaticon-warning" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız sektör ortalama değerinin altında olup ,teşvik faydalanma oranınız   olumsuz yönde seyretmektedir.
							</div>
						</div><?php

					}elseif ($firma_yuzdesi>$max) {
						$degerlendirme_mesaji="Değerlendirme oranınız sektör ortalama değerinin üzerinde olup, artış durumu neticesinde teşvik süreçlerinden max düzeyde faydalanılması gerekmetedir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
							<div class="alert-icon">
								<i class="far fa-check-circle" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız sektör ortalama değerinin üzerinde olup, artış durumu neticesinde teşvik süreçlerinden max düzeyde faydalanılması gerekmetedir.
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
</div><script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script type="text/javascript">
	var law_5510 = <?php echo $law_5510; ?>;
	var law_27103 = <?php echo $law_27103; ?>;
	var law_6111 = <?php echo $law_6111; ?>;
	var law_14857 = <?php echo $law_14857; ?>;


		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = [{
		  "baslik": "5510 Sayılı Kanununa Göre Kazanılan Teşvik Tutarı",
		  "yuzde": law_5510.toFixed(2),
		}, {
		  "baslik": "27103 Sayılı Kanununa Göre Kazanılan Teşvik Tutarı",
		  "yuzde": law_27103.toFixed(2),
		}, {
		  "baslik": "6111 Sayılı Kanununa Göre Kazanılan Teşvik Tutarı",
		  "yuzde": law_6111.toFixed(2),
		}, {
		  "baslik": "14857 Sayılı Kanununa Göre Kazanılan Teşvik Tutarı",
		  "yuzde": law_14857.toFixed(2),
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09")];
		chart.logo.height = -15000;
		chart.responsive.enabled = true;
		chart.responsive.rules.push({
		  relevant: function(target) {
		    if (target.pixelWidth <= 600) {
		      return true;
		    }
		    return false;
		  },
		  state: function(target, stateId) {
		    if (target instanceof am4charts.PieSeries) {
		      var state = target.states.create(stateId);

		      var labelState = target.labels.template.states.create(stateId);
		      labelState.properties.disabled = true;

		      var tickState = target.ticks.template.states.create(stateId);
		      tickState.properties.disabled = true;
		      return state;
		    }

		    return null;
		  }
		});
</script>
<script type="text/javascript">
	var sektor_law_5510 = <?php echo $sektor_law_5510; ?>;
	var sektor_law_27103 = <?php echo $sektor_law_27103; ?>;
	var sektor_law_6111 = <?php echo $sektor_law_6111; ?>;
	var sektor_law_14857 = <?php echo $sektor_law_14857; ?>;


		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chartsektor = am4core.create("sektorchartdiv", am4charts.PieChart3D);
		chartsektor.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chartsektor.legend = new am4charts.Legend();

		chartsektor.data = [{
		  "baslik": "5510 Sayılı Kanununa Göre Kazanılan Teşvik Tutarı",
		  "yuzde": sektor_law_5510.toFixed(2),
		}, {
		  "baslik": "27103 Sayılı Kanununa Göre Kazanılan Teşvik Tutarı",
		  "yuzde": sektor_law_27103.toFixed(2),
		}, {
		  "baslik": "6111 Sayılı Kanununa Göre Kazanılan Teşvik Tutarı",
		  "yuzde": sektor_law_6111.toFixed(2),
		}, {
		  "baslik": "14857 Sayılı Kanununa Göre Kazanılan Teşvik Tutarı",
		  "yuzde": sektor_law_14857.toFixed(2),
		},];

		var seriessektor = chartsektor.series.push(new am4charts.PieSeries3D());
		seriessektor.dataFields.value = "yuzde";
		seriessektor.dataFields.category = "baslik";
		seriessektor.colors = new am4core.ColorSet();
		seriessektor.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330"),am4core.color("#08cc1b"),am4core.color("#ffff09")];
		chartsektor.logo.height = -15000;
		chart.responsive.enabled = true;
		chart.responsive.rules.push({
		  relevant: function(target) {
		    if (target.pixelWidth <= 600) {
		      return true;
		    }
		    return false;
		  },
		  state: function(target, stateId) {
		    if (target instanceof am4charts.PieSeries) {
		      var state = target.states.create(stateId);

		      var labelState = target.labels.template.states.create(stateId);
		      labelState.properties.disabled = true;

		      var tickState = target.ticks.template.states.create(stateId);
		      tickState.properties.disabled = true;
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
		    chartsektor.exporting.getImage("png")
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
					      margin: [0, 40,0,0],
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
		      text: "<?php echo $minmonth."/".$minyear." - ". $maxmonth."/".$maxyear." tarih aralığındaki ".$metrik_adi." metriğinizin raporu aşağıda bilgilerinize sunulmuştur."; ?>",
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
		      text: "Sektöre Ait <?php echo $metrik_adi; ?> Grafiği",
		      fontSize: 14,
		      bold: true,
		      margin: [0, 170, 0, 10]
		    });

		    doc.content.push({
		      image: res[2],
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


