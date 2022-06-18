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
			<h2>Kıdem Tazminat Yükü Nedir?</h2>
			<div class="metric-image-div">
				<img src="/pictures/31.jpg" style="width: 100%;height: auto;">
			</div>
			<div class="metric-define-div">
				<p style="text-align: justify;">
					Kıdem tazminatı çalışanın çalıştığı yıl sayısı kadar, işine son verilmesi veya belirli koşullarda işten ayrılması (evlilik askerlik gibi) halinde aldığı giydirilmiş brüt maaştır. 2020 yılının ikinci yarısında uygulanacak kıdem tazminatı tavanı 7.177,17 TL şeklinde belirlenmiştir. Tüm personellerin olası çıkış durumunda işverene muhtemel kıdem yükünü ifade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster <i class="fas fa-caret-down"></i></span><br>
					<span class="formul"><b>Hesaplama Formülü :</b> <br>
						<span style="font-weight: normal;">
						Bir Günlük Kıdem = Brüt Ücret/365<br>
						Kıdem Esas Kazanç = Bir Günlük Kıdem*Çalışılan Gün<br>
						Damga Vergisi = Kıdem Esas Kazanç*0,00759<br>
						Net Kıdem Tazminatı = Kıdem Esas Kazanç-Damga Vergisi<br>
						Not: Brüt Tavan Ücreti maximum 7.117,17 TL şeklinde hesaplanmıştır.<br>
						Not2: Brüt ücrete sosyal hak edişler eklenmemiştir. </span>
					</span><br>
				</p>
			</div>
		</div>
		<?php
			$aylar = array('01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık', );
    		 $yillar = array('2020' =>'2020', '2019' =>'2019', '2018' =>'2018', '2017' =>'2017', '2016' =>'2016', '2015' =>'2015', '2014' =>'2014', );
    		 $degerlendirme_mesaji="";
    	?>
    	<form method="GET" action="/sub-metrics/severance-pay-burden" class="date-form">
			<div class="date-part-second">
				<label class="date-part-label">Ay</label>
				<select class="form-control" name="month" style="width: 80%;margin-right: 10px;float: left;">
					@foreach($aylar as $key => $ay)
					<option value="{{$key}}" <?php if($month==$key){echo "selected";} ?>>{{$ay}}</option>
					@endforeach
				</select>
			</div>
			<div class="date-part-third">
				<label class="date-part-label">Yıl</label>
				<select class="form-control" name="year" style="width: 58%;margin-right: 10px;float: left;">
					@foreach($yillar as $key => $yil)
					<option value="{{$key}}" <?php if($year==$key){echo "selected";} ?>>{{$yil}}</option>
					@endforeach
				</select>
				<button type="submit" class="btn btn-primary mr-2">Getir</button>
			</div>
			<a href="/severancepayperperson" target="_blank" class="btn btn-primary font-weight-bolder" style="float: right;">Kişi Bazında Kıdem Tazminatı Hesapla</a>
		</form>
		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title" style="width: 100%;">
						<h3 class="card-label"style="width: 50%;">
							Firmanızın Kıdem Tazminat Yükü : {{number_format($net_kidem_tazminati,2,',','.')}}₺
						</h3>
						<h3 class="card-label" style="width: 50%;text-align: right;">
							Ortalama Sektör Kıdem Tazminat Yükü :  {{number_format($sektor_yuzdesi,2,',','.')}}₺
						</h3>
					</div>
				</div>
				@if($firma_yuzdesi==0 || $sektor_yuzdesi==0)
					<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #ff7f00;border-color: #ff7f00;margin-top: 20px;">
						<div class="alert-icon">
							<i class="flaticon-warning" style="color: #fff;"></i>
						</div>
						<div class="alert-text" style="color: #fff;">
							@if($firma_yuzdesi==0 AND $sektor_yuzdesi==0)
							Seçilen tarih aralığında firmanıza ve sektöre ait veri yoktur. Dolayısıyla karşılaştırma yapılamamaktadır.
							@elseif($firma_yuzdesi==0)
								@if($kisiler->count()>0)
									Seçilen tarih aralığındaki çalışanlarınızdan 1 yılı aşkın çalışan olmadığından kıdem yükünüz yoktur.
								@else
									Seçilen tarih aralığında firmanıza ait veri olmadığından karşılaştırma yapılamamaktadır.
								@endif
							@elseif($sektor_yuzdesi==0)
							Seçilen tarih aralığında sektöre ait veri olmadığından karşılaştırma yapılamamaktadır.
							@endif
						</div>
					</div>
				@endif
				<div class="card-body" style="<?php if($firma_yuzdesi==0 || $sektor_yuzdesi==0) echo 'display: none;' ?>">
					<input type="button" style="float: right;z-index: 1" class="btn btn-primary mr-2"  data-toggle="modal" data-target="#exampleModalCenter" id="savepdf" value="RAPOR AL">
					<!--begin::Chart-->
					<div id="chartdiv" style="margin-bottom: 20px;height: 300px;margin-top: 50px;"></div>
					<!--end::Chart-->
					<?php
					$max=$sektor_yuzdesi+$sektor_yuzdesi*15/100;
					$min=$sektor_yuzdesi-$sektor_yuzdesi*15/100;
					if ($firma_yuzdesi<$min) {
						$degerlendirme_mesaji="Kıdem yükünüz değerlendirilmelidir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #fed403;border-color: #fed403">
								<div class="alert-icon">
									<i class="fas fa-search" style="color: #fff;"></i>
								</div>
							<div class="alert-text" style="color: #fff;">
								Kıdem yükünüz değerlendirilmelidir.
							</div>
						</div><?php

					}elseif ($firma_yuzdesi>$max) {
						$degerlendirme_mesaji="Kıdem yükünüz değerlendirilmelidir."; ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #fed403;border-color: #fed403">
								<div class="alert-icon">
									<i class="fas fa-search" style="color: #fff;"></i>
								</div>
							<div class="alert-text" style="color: #fff;">
								Kıdem yükünüz değerlendirilmelidir.
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
<script type="text/javascript">
	var firma_yuzdesi=parseFloat(<?php echo $firma_yuzdesi; ?>).toFixed(1);
	var sektor_yuzdesi=parseFloat(<?php  echo $sektor_yuzdesi; ?>).toFixed(1);
	var degerlendirme_mesaji= '<?php echo $degerlendirme_mesaji; ?>';
		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = [{
		  "baslik": "Firmanızın Sektöre Göre Kıdem Tazminatı Yükü",
		  "yuzde": firma_yuzdesi,
		  "color": am4core.color("#168FC6"),
		}, {
		  "baslik": "Sektörün Firmanıza Göre Kıdem Tazminatı Yükü",
		  "yuzde": sektor_yuzdesi,
		  "color": am4core.color("#E74332")
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330")];
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
		      text: "<?php echo $month."/".$year." tarihi itibariyle ".$metrik_adi." metriğinizin raporu aşağıda bilgilerinize sunulmuştur."; ?>",
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
		    if (degerlendirme_mesaji!="") {
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
		    }




		    pdfMake.createPdf(doc).download("<?php echo $metrik_adi; ?> Raporu.pdf",close_information_modal);

		 });
	});
	function close_information_modal(){
		$( "#closemodalbutton" ).trigger( "click" );
	}
</script>
@stop


