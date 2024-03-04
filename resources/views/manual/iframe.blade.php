@extends ('index')

@section ('main-content')
	<!-- 16:9 aspect ratio -->
	<div class="embed-responsive embed-responsive-4by3">
		<iframe class="embed-responsive-item" src="{{ $source }}"></iframe>
	</div>
@endsection
