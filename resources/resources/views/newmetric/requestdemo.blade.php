@extends('layouts.requestdemo')
@section('content')
<style type="text/css">
    .p5{
        padding: 5px 15px;
    }
    .form-group{
    	width: 48%;
    	float: left;
    	margin-right: 20px;
    }
    @media(max-width: 500px){
    	.form-group{
	    	width: 100% !important;
	    	float: left;
	    	margin-bottom: 10px;
	    }
	    .mblcoldiv{
	    	padding: 0px;
	    }
	    .card-footer{
	    	width: 100%;
	    	display: inline-block;
	    	margin-left: 20px;
	    	height: 50px;
	    	width: 300px;
	    }
	    .card-body{
	    	padding: 0px 20px;
	    }
	    .container{
	    	padding-left: 0px !important;
	    	padding-right: 0px !important;
	    }
    }
</style>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container" style="padding: 30px;">
	<div class="row">
		<div class="col-lg-12 mblcoldiv">
			<form class="form" method="post" action="/request-demo">
				@csrf
				<div class="card-body">
                    @include('partials.alerts.error')
					<div class="form-group">
						<input type="text" name="name" required="required" class="form-control form-control-solid" placeholder="Adınız Soyadınız"/>
						<span class="form-text text-muted">Lütfen adınızı ve soyadınızı girin.</span>
					</div>
					<div class="form-group">
						<input type="text" name="company" required="required" class="form-control form-control-solid" placeholder="Firma"/>
						<span class="form-text text-muted">Lütfen firmanızı girin</span>
					</div>
					<div class="form-group">
						<input type="text" name="phone" required="required" class="form-control form-control-solid" placeholder="Telefon"/>
						<span class="form-text text-muted">Lütfen telefon numaranızı girin</span>
					</div>
					<div class="form-group">
						<input type="email" name="email" required="required" class="form-control form-control-solid" placeholder="E-Mail Adresiniz"/>
						<span class="form-text text-muted">Lütfen e-mail adresinizi girin</span>
					</div>
					<div class="form-group" style="width: 96%;">
						<textarea name="message" class="form-control form-control-solid" placeholder="Özel bir notunuz var mı?" style="margin-bottom: 15px;"></textarea>
						
					</div>
					<div class="g-recaptcha" data-sitekey="6LfpBtAZAAAAAAVqe752nYqwVh0KV6-jK59HDN03"></div>
				</div>
				<div class="card-footer" style="text-align: center;margin-top: 60px;">
					<button type="submit" class="btn btn-primary mr-2" style="background-color: #fe4c1c;border: 1px solid transparent;color: #fff;font-size: 16px;text-align: center;">GÖNDER</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

@stop


