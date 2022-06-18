@extends('layouts.metric')
@section('content')
<style type="text/css">
    .p5{
        padding: 5px 15px;
    }
    .divyilay{
    	width: 210px;padding-right: 10px;float: right;
    }
    .divyilaylabel{
    	float: left;font-size: 17px;font-weight: 500;margin-right: 4px;line-height: 40px;
    }
    .divyilselect{
    	width: 58%;margin-right: 10px;float: left;
    }
    .divayselect{
    	width: 80%;margin-right: 10px;float: left;
    }
    .maindivyilay{
    	width: 903px;height: 55px;margin-top: 13px;text-align: right;
    }
    .cikislabel{
    	font-weight: 700;color: #000;float: right;margin-right: 10px;font-size: 18px;height: 40px;
    	line-height: 40px;
    }
    .mbllabel{
    	display: none;
    }
    .kideminfo{
    	float: left;margin-left: 0px;padding-left: 0px;line-height: 37px;height: 37px;
    }
    .ihbarinfo{
    	float: left;line-height: 37px;
    }
    @media (max-width: 425px)
	{
		.offcanvas {
	    	width: 53% !important;
		}
		.maindivyilay{
			width: 100%;
			height: 250px;
			text-align: left;
		}
		.divyilay{
			float: left;
		}
		.cikislabel{
			float: left;
			display: none;
		}
		.mbllabel{
	    	display: block;
	    	width: 100%;
	    	float: left;
	    }
	    .kideminfo{
	    	height: 70px;
	    }
	    .ihbarinfo{
	    	height: 100px;
	    	padding-left: 0px;
	    }
	}

</style>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/custom/datatables/datatables.bundle.css">
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<div class="container" style="padding: 30px;">
	<div class="row">
		<div class="col-lg-12">
			<h2>Kişi Bazında Kıdem ve İhbar Tazminat Yükü Hesapla</h2>
		</div>
		<!--begin::Card-->
		<?php
			$aylar = array('01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran', '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık', );
    		 $yillar = array('2020' =>'2020', '2019' =>'2019', '2018' =>'2018', '2017' =>'2017', '2016' =>'2016', '2015' =>'2015', '2014' =>'2014', );
    	?>
		<div class="card card-custom">
			<div class="card-header" style="padding-right: 0px;">
				<div class="card-title">
					<span class="card-icon">
						<i class="icon-2x text-dark-50 flaticon-users-1"></i>
					</span>
					<h3 class="card-label">Personel Listesi</h3>
				</div>
				<div class="maindivyilay">
					<label class="cikislabel mbllabel">Çıkış Tarihi : </label>
					<div class="divyilay">
						<label class="divyilaylabel">Yıl</label>
						<select class="form-control divyilselect" name="year" id="year">
							@foreach($yillar as $key => $yil)
							<option value="{{$key}}" <?php if($year==$key){echo "selected";} ?>>{{$yil}}</option>
							@endforeach
						</select>
					</div>
					<div class="divyilay">
						<label class="divyilaylabel">Ay</label>
						<select class="form-control divayselect" name="month" id="month">
							@foreach($aylar as $key => $ay)
							<option value="{{$key}}" <?php if($month==$key){echo "selected";} ?>>{{$ay}}</option>
							@endforeach
						</select>
					</div>
					<label class="cikislabel">Çıkış Tarihi : </label>
				</div>
				<div class="card-toolbar">
					<!--begin::Dropdown-->
					<div class="dropdown dropdown-inline mr-2" style="display: none;">
						<button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="la la-download"></i>Dışa Aktar</button>
						<!--begin::Dropdown Menu-->
						<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
							<ul class="nav flex-column nav-hover">
								<li class="nav-header font-weight-bolder text-uppercase text-primary pb-2">Seçim Yapın :</li>
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="nav-icon la la-print"></i>
										<span class="nav-text">Yazdır</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="nav-icon la la-copy"></i>
										<span class="nav-text">Kopyala</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="nav-icon la la-file-excel-o"></i>
										<span class="nav-text">Excel</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="nav-icon la la-file-text-o"></i>
										<span class="nav-text">CSV</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="nav-icon la la-file-pdf-o"></i>
										<span class="nav-text">PDF</span>
									</a>
								</li>
							</ul>
						</div>
						<!--end::Dropdown Menu-->
					</div>
					<!--end::Dropdown-->
					<!--begin::Button-->
					<a href="#" class="btn btn-primary font-weight-bolder" style="display: none;">
					<i class="la la-plus"></i>New Record</a>
					<!--end::Button-->
				</div>
			</div>
			<div class="card-body">
				<div style="display: inline-block;width: 100%;margin-bottom: 20px;">
					<div class="col-sm-6 col-xs-12 kideminfo">
						<b>Seçili Personellerin Toplam Kıdem Tazminatı : </b>
						<span class="toplamkidemtazminati" id="toplamkidemtazminati">0.00</span>₺
					</div>
					<div class="col-sm-6 col-xs-12 ihbarinfo">
						<b>Seçili Personellerin Toplam İhbar Tazminatı : </b>
						<span class="toplamihbartazminati" id="toplamihbartazminati">0.00</span>₺
						<a href="/severancepayperperson" class="btn btn-primary font-weight-bolder" style="float: right;">Sayfayı Yenile</a>
					</div>
				</div>
				<!--begin: Datatable-->
				<table class="table table-bordered table-hover table-checkable" id="myTable" style="margin-top: 13px !important">
					<thead>
						<tr>
							<th>TCK</th>
							<th>İSİM</th>
							<th>SOYİSİM</th>
							<th>İŞE GİRİŞ TARİHİ</th>
							<th>KIDEM TAZMİNATI</th>
							<th>İHBAR TAZMİNATI</th>
							<th style="width: 300px;">İŞLEMLER</th>
							
						</tr>
					</thead>
					<tbody>
						<?php $i=0; ?>
						@foreach($kisiler as $kisi)
						<tr>
							<td class="satirtck<?php echo $i; ?>">{{$kisi['tck']}}</td>
							<td class="satirisim<?php echo $i; ?>">{{$kisi['isim']}}</td>
							<td class="satirsoyisim<?php echo $i; ?>">{{$kisi['soyisim']}}</td>
							<td class="satirjobstart<?php echo $i; ?>">{{$kisi['job_start']}}</td>
							<td id="satirkidem<?php echo $i; ?>">-</td>
							<td id="satirihbar<?php echo $i; ?>">-</td>
							<td>
								<button class="btn btn-primary font-weight-bolder kidemhesapla<?php echo $i; ?>" id="kidemhesapla<?php echo $i; ?>" onclick="kidemhesapla(<?php echo $i; ?>)" style="margin: 10px 0px;">
									Kıdem Hesapla
								</button>
							
								<button class="btn btn-primary font-weight-bolder ihbarhesapla<?php echo $i; ?>" id="ihbarhesapla<?php echo $i; ?>" onclick="ihbarhesapla(<?php echo $i; ?>)">
									İhbar Hesapla</button>
							</td>
						</tr>
						<?php $i++; ?>
						@endforeach
					</tbody>
				</table>
				<!--end: Datatable-->
				<div class="col-lg-12" style="margin-left: 0px; padding-left: 0px;margin-top: 20px;">
					<span><b>NOT 1 : </b>Kıdem yükü tazminatı hesaplaması için brüt tavan ücreti maximum 7.117,17 TL şeklinde hesaplanmıştır.</span><br>
					<span><b>NOT 2 : </b>Her iki hesaplama için de brüt ücrete sosyal hak edişler eklenmemiştir.</span><br>
					<span><b>NOT 3 : </b>İhbar Günü toplam çalışma ayı 0-6 ay arası ise 14 gün, 6-18 ay arası ise 28 gün, 19-36 ay arası ise 42 gün, 36 ay ve üstü ise 56 gün olarak tanımlanmıştır.</span><br>
					<span><b>NOT 4 : </b>İhbar tazminatı için muhtemel hesaplama %15 gelir vergisi dilimine göre hesaplanmıştır.</span><br>
				</div>
			</div>
		</div>
		<!--end::Card-->
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).ready(function () {
	    $('#myTable').DataTable({responsive: true});
	    $('.align-items-center').trigger("click");
	});
	function kidemhesapla(i){
		var index=i;
		var month = $('#month').val();
		var year = $('#year').val();
		var tck = $('.satirtck'+index).text();
		var job_start = $('.satirjobstart'+index).text();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
	    $.ajax({
           	type:'POST',
           	url:'/severancepayperperson',
           	data:{tck:tck, job_start:job_start, month:month, year:year, index:index, token:csrf_token},
           	success:function(data){
           		console.log(data);
           		if (data=='0') {
           			document.getElementById('satirkidem'+index).innerHTML = "Kıdem tazminatı yok.";
           		}else if(data=='-1'){
           			document.getElementById('satirkidem'+index).innerHTML = "Seçilen tarih personelin işe giriş tarihinden küçük olamaz!";
           		}else{
           			var toplam_kidem=parseFloat(document.getElementById("toplamkidemtazminati").textContent)+parseFloat(data);
           			document.getElementById('satirkidem'+index).innerHTML = parseFloat(data).toFixed(2)+" ₺";
           			document.getElementById('toplamkidemtazminati').innerHTML = toplam_kidem.toFixed(2);
           		}
           		document.getElementById("kidemhesapla"+index).disabled = true;
           }
        });
	}
	function ihbarhesapla(i){
		var index=i;
		var month = $('#month').val();
		var year = $('#year').val();
		var tck = $('.satirtck'+index).text();
		var job_start = $('.satirjobstart'+index).text();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
	    $.ajax({
           	type:'POST',
           	url:'/calculatenotice',
           	data:{tck:tck, job_start:job_start, month:month, year:year, index:index, token:csrf_token},
           	success:function(data){
           		if (data=='0') {
           			document.getElementById('satirihbar'+index).innerHTML = "İhbar tazminatı yok.";
           		}else if(data=='-1'){
           			document.getElementById('satirihbar'+index).innerHTML = "Seçilen tarih personelin işe giriş tarihinden küçük olamaz!";
           		}else{
           			var toplam_ihbar=parseFloat(document.getElementById("toplamihbartazminati").textContent)+parseFloat(data);

           			document.getElementById('satirihbar'+index).innerHTML = parseFloat(data).toFixed(2)+" ₺";
           			document.getElementById('toplamihbartazminati').innerHTML = toplam_ihbar.toFixed(2);
           		}
           		document.getElementById("ihbarhesapla"+index).disabled = true;
           }
        });
	}
</script>
@stop


