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
			<h2>Engelli Uygunluk Tespiti Nedir?</h2>
			<div class="metric-image-div">
				<img src="/pictures/30.jpg" style="width: 100%;height: auto;">
			</div>
			<div class="metric-define-div">
				<p style="text-align: justify;">
					Özel sektör işverenlerinin kontenjan dâhilinde veya kontenjan fazlası olarak yada yükümlü olmadıkları halde engelli çalıştırmaları durumunda işverenlerin bu şekilde çalıştırdıkları her bir engelli için asgari ücret düzeyindeki sosyal güvenlik primi işveren paylarının tamamı Hazinece karşılanması şeklinde değiştirilmiştir.50 ve daha fazla personel çalıştıran işletmelerin toplam personel sayısının %3 oranında engelli istihdamınıifade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster <i class="fas fa-caret-down"></i></span><br>
					<span class="formul"><b>Hesaplama Formülü :</b> Toplam personel sayısı*3%</b><br>
				</p>
			</div>
		</div>
		<?php
			$sabitler=config('constants');
			$aylar = $sabitler['aylar'];
    		$yillar = $sabitler['yillar'];
    	?>
		<form method="GET" action="/sub-metrics/disability-assessment" class="date-form">
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
							Firmanızın Engelli Uygunluk Oranı : %{{number_format($firma_yuzdesi,1)}}
						</h3>
						<h3 class="card-label" style="width: 50%;text-align: right;">
							Mevzuat Engelli Uygunluk Oranı :  %{{number_format($sektor_yuzdesi,1)}}
						</h3>
					</div>
				</div>
				@if($firma_yuzdesi==0)
					<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #ff7f00;border-color: #ff7f00;margin-top: 20px;">
						<div class="alert-icon">
							<i class="flaticon-warning" style="color: #fff;"></i>
						</div>
						<div class="alert-text" style="color: #fff;">
							@if($firma_personel_sayisi==0)
								Seçilen tarih aralığında firmanıza ait veri olmadığından karşılaştırma yapılamamaktadır.
							@elseif($olmasi_gereken_engelli_sayisi==0 && $firma_engelli_sayisi==0)
								Engelli personel sayınız bulunmamaktadır. Personel sayınız 50'nin altında olduğu için engelli personel çalıştırma zorunluluğunuz yoktur.
							@elseif($olmasi_gereken_engelli_sayisi>0 && $firma_engelli_sayisi==0)
								Engelli personel sayınız bulunmamaktadır. Seçilen tarihler arasında ortalama {{round($firma_personel_sayisi)}} personeliniz vardır. Dolayısıyla {{$olmasi_gereken_engelli_sayisi}} engelli personel çalıştırma zorunluluğunuz vardır.
							@endif
						</div>
					</div>
				@endif
				<div class="card-body" style="<?php if($firma_yuzdesi==0) echo 'display: none;' ?>">
					<input type="button" style="float: right;z-index: 1" class="btn btn-primary mr-2" data-toggle="modal" data-target="#exampleModalCenter" id="savepdf" value="RAPOR AL">
					<span><b>Belirtilen Tarih Aralığında Çalışan Ortalama Engelli Personel Sayısı : {{number_format($firma_engelli_sayisi,0,',','.')}} Kişi</b></span><br>
					<span><b>Firmanızın Ortalama Personel Sayısı : {{number_format($firma_personel_sayisi,0,',','.')}} Kişi</b></span>
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
									echo "Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)." kişi fazladır. ";
									$degerlendirme_mesaji="Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)." kişi fazladır. ";
								}elseif ($olmasi_gereken_engelli_sayisi>$firma_engelli_sayisi) {
									echo "Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)." kişi eksiktir. ";
									$degerlendirme_mesaji="Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)." kişi eksiktir. ";
								}else{
									echo "Engelli oranınız uygundur.";
									$degerlendirme_mesaji="Engelli oranınız uygundur. ";
								}$degerlendirme_mesaji.="Sektöre göre engelli oranınız ise sektör oranına göre nominal orandadır."; ?> Sektöre göre engelli oranınız ise sektör oranına göre nominal orandadır.
							</div>
						</div><?php
					}elseif ($firma_yuzdesi<$min) { ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
							<div class="alert-icon">
								<i class="flaticon-warning" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								<?php if($olmasi_gereken_engelli_sayisi<$firma_engelli_sayisi){
									echo "Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)." kişi fazladır. ";
									$degerlendirme_mesaji="Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)." kişi fazladır. ";
								}elseif ($olmasi_gereken_engelli_sayisi>$firma_engelli_sayisi) {
									echo "Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)." kişi eksiktir. ";
									$degerlendirme_mesaji="Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)." kişi eksiktir. ";
								}else{
									echo "Engelli oranınız uygundur.";
									$degerlendirme_mesaji="Engelli oranınız uygundur. ";
								} $degerlendirme_mesaji.="Sektöre göre engelli oranınız ise sektör oranının aşağısındadır."; ?> Sektöre göre engelli oranınız ise sektör oranının aşağısındadır.
							</div>
						</div><?php

					}elseif ($firma_yuzdesi>$max) { ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
							<div class="alert-icon">
								<i class="far fa-check-circle" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								<?php if($olmasi_gereken_engelli_sayisi<$firma_engelli_sayisi){
									echo "Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)." kişi fazladır. ";
									$degerlendirme_mesaji="Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($firma_engelli_sayisi-$olmasi_gereken_engelli_sayisi)." kişi fazladır. ";
								}elseif ($olmasi_gereken_engelli_sayisi>$firma_engelli_sayisi) {
									echo "Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)." kişi eksiktir. ";
									$degerlendirme_mesaji="Yapılan karşılaştırmada 4857. sayılı İş Kanunu'nun 30. maddesine göre engelli sayınızda ".($olmasi_gereken_engelli_sayisi-$firma_engelli_sayisi)." kişi eksiktir. ";
								}else{
									echo "Engelli oranınız uygundur.";
									$degerlendirme_mesaji="Engelli oranınız uygundur. ";
								} $degerlendirme_mesaji.="Sektöre göre engelli oranınız ise sektör oranından fazladır."; ?> Sektöre göre engelli oranınız ise sektör oranından fazladır.
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
	var firma_yuzdesi=<?php echo $firma_yuzdesi; ?>;
	var sektor_yuzdesi=<?php  echo $sektor_yuzdesi; ?>;
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
		  "baslik": "Mevzuatın Firmanıza Göre Engelli Uygunluk Oranı",
		  "yuzde": sektor_yuzdesi
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "yuzde";
		series.dataFields.category = "baslik";
		chart.legend.markers.template.width = 15;
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
			      	text: "<?php echo $degerlendirme_mesaji; ?>",
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


