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
			<h2>Fazla Mesai Günü Oranı Nedir?</h2>
			<div style="width: 33%;min-height: 200px;float: left;padding: 5px 10px;">
				<img src="/pictures/fazlamesai.png" style="width: 100%;height: auto;">
			</div>
			<div style="width: 60%;float: left;font-size: 15px;padding-top: 10px;">
				<p style="text-align: justify;">
					Bir işyerinde haftalık çalışma süresini aşan çalışmalar fazla mesai olarak tanımlanmıştır. Normal mesaisi için bir ücret alan işçi, fazla mesaisi için de ayrıca bir ücret alır.İş Kanununda yazılı koşullar çerçevesinde haftalık 45 saati aşan çalışmaları ifade etmektedir. İşletmelerde meydana gelen değişiklikler ve planlı/plansız üretim artışları sonucunda oluşan fazla çalışmayı ifade eder.<br><br>
					<span class="formulgoster" id="formulgoster">Formülü Göster</span><br>
					<b class="formul">Hesaplama Formülü:Fazla mesai saati/Planlanan toplam saat</b><br>
				</p>
			</div>
		</div>
		<?php 
		$aylar = array('01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık', );
		$yillar = array('2020' =>'2020', '2019' =>'2019', '2018' =>'2018', '2017' =>'2017', '2016' =>'2016', '2015' =>'2015', '2014' =>'2014', ); 
		?>
		<form method="GET" action="/overtimedayrate" style="width: 100%;">
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
					<div class="card-title" style="width: 100%;">
						<h3 class="card-label"style="width: 50%;">
							Firmanızın Fazla Mesai Günü Oranı : %{{number_format($firma_mesai_yuzdesi,1)}}
						</h3>
						<h3 class="card-label" style="width: 50%;text-align: right;">
							Sektör Fazla Mesai Günü Oranı :  %{{number_format($sektor_mesai_yuzdesi,1)}}
						</h3>
					</div>
				</div>
				<div class="card-body">
					<span><b>Firmanızın Fazla Mesai Saati : {{number_format($firma_fazla_mesai_saati,0,',','.')}} saat</b></span><br>
					<span><b>Firmanızın Planlanan Mesai Saati : {{number_format($firma_planlanan_gun_saati,0,',','.')}} saat</b></span>
					<!--begin::Chart-->
					<div id="chartdiv" style="height: 300px;margin-bottom: 20px;"></div>
					<!--end::Chart-->
					<?php
					$max=$sektor_mesai_yuzdesi+$sektor_mesai_yuzdesi*15/100; 
					$min=$sektor_mesai_yuzdesi-$sektor_mesai_yuzdesi*15/100; 
					if ($firma_mesai_yuzdesi<=$max AND $firma_mesai_yuzdesi>=$min) { ?>
						<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #424a52;border-color: #424a52">
							<div class="alert-icon">
								<i class="fas fa-retweet" style="color: #fff;"></i>
							</div>
							<div class="alert-text" style="color: #fff;">
								Değerlendirme oranınız sektör ortalamasının içerisinde olup fazla mesai oranınız sektör ortalamasına göre nominal değerdedir.
							</div>
							</div><?php
						}elseif ($firma_mesai_yuzdesi<$min) { ?>
							<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #2a6ab2;border-color: #2a6ab2">
								<div class="alert-icon">
									<i class="far fa-check-circle" style="color: #fff;"></i>
								</div>
								<div class="alert-text" style="color: #fff;">
									Değerlendirme oranınız sektör ortalama değerinin altında olup fazla mesai durumunuz olumlu yönde seyretmektedir.
								</div>
								</div><?php

							}elseif ($firma_mesai_yuzdesi>$max) { ?>
								<div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert" style="background-color: #e74332;border-color: #e74332">
									<div class="alert-icon">
										<i class="flaticon-warning" style="color: #fff;"></i>
									</div>
									<div class="alert-text" style="color: #fff;">
										Değerlendirme oranınız sektör ortalama değerinin üzerinde olup, artış durumu neticesinde iş gücü kayıpları,iş kazası,turnover ve birim maliyet  oranlarında artış görülmesi muhtemeldir.
									</div>
									</div><?php

								} ?>

							</div>
						</div>
						<!--end::Card-->
					</div>
				</div>
			</div>
<style type="text/css">
	.amcharts-amexport-item {
	  width: 70px !important;
	  line-height: 30px !important;
	}
</style>

			<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
			<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
			<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
			<script type="text/javascript">
				var firma_fazla_mesai_saati=<?php echo $firma_fazla_mesai_saati; ?>;
				var firma_planlanan_gun_saati=<?php echo $firma_planlanan_gun_saati; ?>;
				var firma_mesai_yuzdesi=<?php echo $firma_mesai_yuzdesi; ?>;
				var sektor_fazla_mesai_saati=<?php  echo $sektor_fazla_mesai_saati; ?>;
				var sektor_planlanan_gun_saati=<?php  echo $sektor_planlanan_gun_saati; ?>;
				var sektor_mesai_yuzdesi=<?php  echo $sektor_mesai_yuzdesi; ?>;

				am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		var chart = am4core.create("chartdiv", am4charts.PieChart3D);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.legend = new am4charts.Legend();

		chart.data = [{
			"baslik": "Sektör Bazlı Firmanızın Fazla Mesai Günü Oranı",
			"saat": firma_mesai_yuzdesi,
			"color": am4core.color("#168FC6"),
		}, {
			"baslik": "Sektörün Firmanıza Göre Fazla Mesai Günü Oranı",
			"saat": sektor_mesai_yuzdesi,
			"color": am4core.color("#E74332")
		},];

		var series = chart.series.push(new am4charts.PieSeries3D());
		series.dataFields.value = "saat";
		series.dataFields.category = "baslik";
		series.colors = new am4core.ColorSet();
		series.colors.list = [am4core.color("#126FC9"),am4core.color("#E74330")];
		
		chart.exporting.menu = new am4core.ExportMenu();
		chart.exporting.menu.items = [
		  {
		    "label": "RAPOR",
		    "menu": [
		      {
		        "label": "İNDİR",
		        "menu": [
		          { "type": "pdf", "label": "PDF" }
		        ]
		      }
		    ]
		  }
		];
		chart.exporting.getFormatOptions("pdf").addURL = false;
		chart.exporting.adapter.add("pdfmakeDocument", function(pdf, target) {
			pdf.doc.content.unshift({
				alignment: 'left',
				text: 'İK Metrik Yazılım Bilişim A.Ş.',
				margin: [0, 20],
			});
			pdf.doc.content.unshift({
				alignment: 'left',
				text: 'Saygılarımızla..',
				margin: [0, 2],
			});
			pdf.doc.content.unshift({
				alignment: 'left',
				text: '<?php echo date("d-m-Y")." Tarihli ".$metrik_adi." metriğinizin raporu aşağıda bilgilerinize sunulmuştur."; ?>',
				margin: [0, 10],
			});
			pdf.doc.content.unshift({
				alignment: 'justify',
				text: '<?php echo $rapor_metni; ?>',
				margin: [0, 2],
			});
			pdf.doc.content.unshift({
				alignment: 'left',
				text: 'Tarih : <?php echo date("d-m-Y"); ?>',
				margin: [0, 10],
			});
			pdf.doc.content.unshift({
				alignment: 'left',
				text: 'Firma Adı : <?php echo $firma_adi; ?>',
				margin: [0, 7],
			});
			
			pdf.doc.content.unshift({
				alignment: 'left',
			    columns: [{
			      image: 'data:image/png;base64,<?php echo $base_logo; ?>',
				fit: [225, 199]
			    }, {
			      text: "Fazla Mesai Günü Oranı Raporu",
			      margin: [0, 40],
				    style: {
				      fontSize: 17,
				      bold: true,
				      
				    }
			    }],
	
			    margin: [0, 0]
		  	});
		  	pdf.doc.content.push({
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
		  	pdf.doc.content.push({
	  			text: 'Değerlendirme oranınız sektör ortalama değerinin altında olup fazla mesai durumunuz olumlu yönde seyretmektedir.',
		  		style: {
		  			fontSize: 12,
					bold: false,
		  		},
		  		margin: [0,10],
		  	});
		  	pdf.doc.content.push({
				alignment: 'left',
			    columns: [{
			    	columns: [{
			    		image: 'data:image/png;base64,<?php echo $base_ikon_konum; ?>',
			    		fit: [20, 20],
			    		width: 23,
			    	}, {
			    		text: "İstanbul Ofis",
			    		style: {
					      fontSize: 12,
					      bold: true,
					    }
			    	}],
			    }, {
		
				    columns: [{
			    		image: 'data:image/png;base64,<?php echo $base_ikon_konum; ?>',
			    		fit: [20, 20],
			    		width: 23,
			    	}, {
			    		text: "Bursa Ofis",
			    		style: {
					      fontSize: 12,
					      bold: true,
					    }
			    	}],
			    }, {
		
				    columns: [{
			    		image: 'data:image/png;base64,<?php echo $base_ikon_konum; ?>',
			    		fit: [20, 20],
			    		width: 23,
			    	}, {
			    		text: "Antalya Ofis",
			    		style: {
					      fontSize: 12,
					      bold: true,
					    }
			    	}],
			    }, {
		
				    columns: [{
			    		image: 'data:image/png;base64,<?php echo $base_ikon_telefon; ?>',
			    		fit: [20, 20],
			    		width: 23,
			    	}, {
			    		
			    		text: "Bizi Arayın",
			    		style: {
					      fontSize: 12,
					      bold: true,
					    },
					   
			    	}],
			    }],
	
			    margin: [0, 120,0,0]
		  	});
		  	pdf.doc.content.push({
				alignment: 'left',
			    columns: [{
		    		text: "Kısıklı Cad. NO:28 Kat:2 D:215 Altunizade/Üsküdar İstanbul",
		    		style: {
				      fontSize: 10,
				      bold: true,
				    }

			    }, {
				    text: "İzmir Yolu Cad. Aktaş Plaza No:178 Kat:3 Beşevler Metro İstasyonu Çıkışı",
		    		style: {
				      fontSize: 10,
				      bold: false,
				    }

			    }, {
					text: "Remel Plaza Kat:1 No:106 Muratpaşa/ANTALYA",
		    		style: {
				      fontSize: 10,
				      bold: false,
				    }
			    }, {
		
				   text: "İstanbul:(216) 266-34-38 Bursa:(224) 453-20-72 Antalya:(242) 745-09-24",
		    		style: {
				      fontSize: 10,
				      bold: false,
				    }
			    }],
				margin: [0,0,0,0]
		  	});
			

			return pdf;
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


