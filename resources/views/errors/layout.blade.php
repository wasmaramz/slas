@extends ('layouts.base')

@section ('header-title')
	<title>@yield ('ERROR_CODE') - {{ env('APP_NAME') }}</title>
@endsection

@section ('body-content')
	<div id="app">
		<section class="section">
			<div class="container mt-5">
				<div class="page-error">
					<div class="page-inner">
						<h1>@yield ('ERROR_CODE')</h1>
						<div class="page-description">
							@yield ('ERROR_MESSAGE')
						</div>
						<div class="page-search">
							{{-- HIDE THIS FOR NOW
							<!-- Search Box -->
							<form>
								<div class="form-group floating-addon floating-addon-not-append">
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<i class="fas fa-search"></i>
											</div>
										</div>
										<input type="text" class="form-control" placeholder="Search">
										<div class="input-group-append">
											<button class="btn btn-primary btn-lg">
												Search
											</button>
										</div>
									</div>
								</div>
							</form>
							HIDE THIS FOR NOW --}}

							<!-- Redirect To Home Button -->
							<div class="mt-3">
								<a href="/">Back to Home</a>
							</div>
						</div>
					</div>
				</div>
				<div class="simple-footer mt-5">
				Copyright &copy; Stisla 2018
				</div>
			</div>
		</section>
	</div>
@endsection 
