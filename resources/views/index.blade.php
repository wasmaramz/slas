@extends ('layouts.base')

@section ('header-title')
	<title>Dashboard - {{ env('APP_NAME') }}</title>
@endsection

@section ('header-refer')
	@parent
	<!-- All Page CSS Libraries -->
	<link rel="stylesheet" href="/node_modules/jqvmap/dist/jqvmap.min.css">
	<link rel="stylesheet" href="/node_modules/summernote/dist/summernote-bs4.css">

	<!-- All Page CSS Files -->

	<!-- Page Specific CSS Libraries -->
	<link rel="stylesheet" href="/node_modules/owl.carousel/dist/assets/owl.carousel.min.css">
	<link rel="stylesheet" href="/node_modules/owl.carousel/dist/assets/owl.theme.default.min.css">
@endsection

@section ('body-content')
	<div id="app">
		<div class="main-wrapper">
			<!-- Navbar -->
			@include ('layouts.navbar')

			<!-- SideBar -->
			<div class="main-sidebar sidebar-style-2">
				@include ('layouts.sidebar')
			</div>

			<!-- Main Content -->
			<div class="main-content">
				<section class="section">
					@yield ('main-content')	
				</section>
			</div>

			<!-- Footer -->
			<footer class="main-footer">
				@include ('layouts.footer')
			</footer>
		</div>
	</div>
@endsection

@section ('footer-refer')
	@parent
	<!-- All Page JS Libraries -->
	<script src="/node_modules/chart.js/dist/Chart.min.js"></script>
	<script src="/node_modules/summernote/dist/summernote-bs4.js"></script>
	<script src="/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>

	<!-- All Page JS Files -->

	<!-- Page Specific JS Libraries -->
	<script src="/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
	<script src="/node_modules/owl.carousel/dist/owl.carousel.min.js"></script>
@endsection
