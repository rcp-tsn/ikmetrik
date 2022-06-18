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
			<h2>Giriş/Çıkış Kontrolü Nedir?</h2>
			<div class="metric-image-div">
				<img src="/pictures/20.jpg" style="width: 100%;height: auto;">
			</div>
			<div class="metric-define-div">
				<p style="text-align: justify;">
					İşletmeler işe giriş/işten çıkış yaptıkları personelleri işe girişler için işe girmeden bir gün önce(inşaat,balıkçılıkvb.hariç) işten çıkış bildirgesini ise çıkış tarihi itibari ile 10 gün geriden yapabilmektedir.Yapılan bu bildirimlerin 1774 sayılı Kimlik Bildirim Kanunu gereğince kolluk kuvvetlerine bildirilmelidir.Bu metrik her iki sistemin tarihlerini kontrol etmektedir.İş giriş/işten çıkış tarihlerinin 1774 sayılı Kimlik Bildirim usullerine yapılan güvenlik bildirimleri ile karşılaştırmayı tanımlar.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster <i class="fas fa-caret-down"></i></span><br>
					<span class="formul"><b>Hesaplama Formülü :</b> SGK ve Emliyet karşılaştırması</span><br>
				</p>
			</div>
		</div>
		<?php
			$sabitler=config('constants');
			$aylar = $sabitler['aylar'];
    		$yillar = $sabitler['yillar'];
    	?>
		<form method="GET" action="/sub-metrics/check-in-out-check" class="date-form">
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
							Firmanızın İşe Giriş Hata Bildirim Oranı : %{{$ise_giris_yuzdesi}}
						</h3>
					</div>
				</div>
				<div class="card-body" style="<?php if($ise_giris_yuzdesi==0 AND $isten_cikis_yuzdesi==0) echo 'display: none;' ?>">
					<input type="button" style="float: right;z-index: 1" class="btn btn-primary mr-2" data-toggle="modal" data-target="#exampleModalCenter" id="savepdf" value="RAPOR AL">
					<span><b>Firmanızın İşe Giriş Bildirim Sayısı : {{number_format($ise_giris_sayisi,0,',','.')}} Kişi</b></span><br>
					<span><b>Firmanızın İşe Giriş Hatalı Bildirim Sayısı : {{number_format($ise_giris_bildirim_hata_sayisi,0,',','.')}} Kişi</b></span>
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 300px;margin-bottom: 20px;"></div>
				</div>
			</div>
			<!--end::Card-->
		</div>
		<div class="col-lg-12">
			<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header">
					<div class="card-title" style="width: 100%;">
						<h3 class="card-label" style="width: 50%;text-align: left;">
							Firmanızın İşten Çıkış Hata Bildirim Oranı :  %{{$isten_cikis_yuzdesi}}
						</h3>
					</div>
				</div>
				<div class="card-body" style="<?php if($isten_cikis_yuzdesi==0) echo 'display: none;' ?>">
					<span><b>Firmanızın İşten Çıkış Bildirim Sayısı : {{number_format($isten_cikis_sayisi,0,',','.')}} Kişi</b></span><br>
					<span><b>Firmanızın İşten Çıkış Hatalı Bildirim Sayısı : {{number_format($isten_cikis_bildirim_hata_sayisi,0,',','.')}} Kişi</b></span>
					<!--begin::Chart-->
					<div id="chartdiv2" style="height: 300px;margin-bottom: 20px;"></div>

				</div>
				@if($ise_giris_yuzdesi==0 || $isten_cikis_yuzdesi==0)
					<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #ff7f00;border-color: #ff7f00;margin-top: 20px;">
						<div class="alert-icon">
							<i class="flaticon-warning" style="color: #fff;"></i>
						</div>
						<div class="alert-text" style="color: #fff;">
							@if($ise_giris_yuzdesi==0 AND $isten_cikis_yuzdesi==0)
								@if($error_message!='')
									{{$error_message}}
								@else
									Seçilen tarih aralığında firmanıza ait veri yoktur. Dolayısıyla karşılaştırma yapılamamaktadır.
								@endif
							@elseif($ise_giris_bildirim_hata_sayisi==0)
							Seçilen tarih aralığında firmanıza ait işe giriş bildirim hatası yoktur.
							@elseif($isten_cikis_bildirim_hata_sayisi==0)
							Seçilen tarih aralığında firmanıza ait işten çıkış bildirim hatası yoktur.
							@elseif($ise_giris_yuzdesi==0)
							Seçilen tarih aralığında firmanıza ait işe giriş datası olmadığından karşılaştırma yapılamamaktadır.
							@elseif($isten_cikis_yuzdesi==0)
							Seçilen tarih aralığında firmanıza ait işten çıkış datası olmadığından karşılaştırma yapılamamaktadır.
							@endif
						</div>
					</div>
				@endif
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
	var ise_giris_hatali=<?php echo $ise_giris_bildirim_hata_sayisi; ?>;
	var ise_giris_dogru=<?php  echo $ise_giris_sayisi-$ise_giris_bildirim_hata_sayisi; ?>;
	var isten_cikis_hatali=<?php echo $isten_cikis_bildirim_hata_sayisi; ?>;
	var isten_cikis_dogru=<?php  echo $isten_cikis_sayisi-$isten_cikis_bildirim_hata_sayisi; ?>;
	degerlendirme_mesaji=<?php if (isset($degerlendirme_mesaji)) {echo json_encode($degerlendirme_mesaji);}else{echo json_encode(["Hatalı bildiriminiz yoktur. SGK ve EGM'deki bildirim tarihleriniz aynıdır."]);} ?>;
	// Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end
	var chart = am4core.create("chartdiv", am4charts.PieChart3D);
	chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

	chart.legend = new am4charts.Legend();

	chart.data = [{
	  "baslik": "Hatalı Bildirilen İşe Giriş Sayısı",
	  "yuzde": ise_giris_hatali
	}, {
	  "baslik": "Doğru Bildirilen İşe Giriş Sayısı",
	  "yuzde": ise_giris_dogru
	},];

	var series = chart.series.push(new am4charts.PieSeries3D());
	series.dataFields.value = "yuzde";
	series.dataFields.category = "baslik";
	series.colors = new am4core.ColorSet();
	series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330")];
	chart.logo.height = -15000;

	//İkinci Grafik
	var chart2 = am4core.create("chartdiv2", am4charts.PieChart3D);
	chart2.hiddenState.properties.opacity = 0; // this creates initial fade-in

	chart2.legend = new am4charts.Legend();

	chart2.data = [{
	  "baslik": "Hatalı Bildirilen İşten Çıkış Sayısı",
	  "yuzde": isten_cikis_hatali
	}, {
	  "baslik": "Doğru Bildirilen İşten Çıkış Sayısı",
	  "yuzde": isten_cikis_dogru
	},];

	var series2 = chart2.series.push(new am4charts.PieSeries3D());
	series2.dataFields.value = "yuzde";
	series2.dataFields.category = "baslik";
	series2.colors = new am4core.ColorSet();
	series2.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330")];
	chart2.logo.height = -15000;
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
		chart2.responsive.enabled = true;
		chart2.responsive.rules.push({
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
		    chart2.exporting.getImage("png")
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
		    for (var i =0 ; i < degerlendirme_mesaji.length; i++) {
			  	doc.content.push({
			  		text:[{
				      	text: '- ',
				      	fontSize: 12,
				      	bold: true,
				    },{
				      	text: ''+degerlendirme_mesaji[i]+'',
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


