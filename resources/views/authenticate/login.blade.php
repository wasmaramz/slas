@extends ('layouts.base')

@section ('header-title')
	<title>Sign In - {{ env('APP_NAME') }}</title>
@endsection

@section ('header-refer')
	@parent
	<!-- CSS Libraries -->
	<link rel="stylesheet" href="/node_modules/bootstrap-social/bootstrap-social.css">
@endsection

@section ('body-content')
	<div id="app">
		<section class="section">
			<div class="d-flex flex-wrap align-items-stretch">
				<!-- LOGIN BOX -->
				<div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
					<div class="p-4 m-3">
						<img src="../assets/img/stisla-fill.svg" alt="logo" width="80" class="shadow-light rounded-circle mb-5 mt-2">
						<h4 class="text-dark font-weight-normal">Student Leave Application System (SLAS)</h4>
						<p class="text-muted">Please enter your User ID and Password.</p>

						<form id="LoginForm">
							@csrf
							<div class="form-group">
								<label for="username">User ID</label>
								<input type="text" class="form-control" id="username" name="username" tabindex="1" autofocus>
								<!-- example of invalid feedback
								<div class="invalid-feedback">
									Please fill in your email
								</div>
								-->
							</div>

							<div class="form-group">
								<label for="password" class="control-label">Password</label>
								<input type="password" class="form-control" id="password" name="password" tabindex="2">
							</div>

							<!-- TUTUP DULU --
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
									<label class="custom-control-label" for="remember-me">Remember Me</label>
								</div>
							</div>
							-- TUTUP DULU --!>

							<div class="form-group text-right">
								<!-- TUTUP DULU --
								<a href="/forgot/password" class="float-left mt-3">
									Forgot Password?
								</a>
								-- TUTUP DULU --!>
								<button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="3">
									Login
								</button>
							</div>

							<!-- TUTUP DULU --
							<div class="mt-5 text-center">
								Don't have an account? <a href="auth-register.html">Create new one</a>
							</div>
							-- TUTUP DULU --!>
						</form>

						<!-- TUTUP DULU --
						<div class="text-center mt-5 text-small">
							Copyright &copy; Your Company. Made with 💙 by Stisla
							<div class="mt-2">
								<a href="#">Privacy Policy</a>
								<div class="bullet"></div>
								<a href="#">Terms of Service</a>
							</div>
						</div>
						-- TUTUP DULU --!>
					</div>
				</div>

				<!--LOGIN ASIDE WALLPAPER -->
				<div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom" data-background="../assets/img/unsplash/login-bg.jpg">
					<div class="absolute-bottom-left index-2">
						<!-- TUTUP DULU --
						-- TUTUP DULU --!>
						<div class="text-light p-5 pb-2">
							<div class="mb-5 pb-3">
								<h1 class="mb-2 display-4 font-weight-bold">Good Morning</h1>
								<h5 class="font-weight-normal text-muted-transparent">Bali, Indonesia</h5>
							</div>
							Photo by <a class="text-light bb" target="_blank" href="https://unsplash.com/photos/a8lTjWJJgLA">Justin Kauffman</a> on <a class="text-light bb" target="_blank" href="https://unsplash.com">Unsplash</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<script>
		$('#LoginForm').validate({
			rules:{
				username:{
					required: true,
					regex: "^[A-Za-z0-9]+$",
				},
				password:{
					required: true,
				},
			},
			messages:{
				username:{
					required: "Please enter User ID.",
					regex: "Please enter valid User ID.",
				},
				password:{
					required: "Please enter Password.",
				},
			},
			submitHandler: function(form){
				$.ajax({
					url: "/login",
					type: "post",
					dataType: "json",
					data: $(form).serialize(),
					success: function(resp) {
						if (resp.success) {
							window.location.href = "/"; 
						}
					}
				});
			}
		});
	</script>
@endsection

@section ('footer-refer')
	@parent
	<!-- JS Libraries -->
@endsection
