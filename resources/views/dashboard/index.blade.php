@extends ('index')

@section ('main-content')
	<!-- THIS IS DASHBOARD TEMPLATE FOR ADMIN -->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card shadow">
				<div class="card-header">
					<h4>Dashboard</h4>
					<div class="card-header-action dropdown">
						<a href="javascript:void(0)" data-toggle="dropdown" class="btn btn-danger dropdown-toggle">Example Button Dropdown</a>
						<ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
							<li class="dropdown-title">Example Dropdown Option</li>
							<li><a href="javascript:void(0)" onclick="swal('Alert!', 'This is example of option 1', 'info')" class="dropdown-item">Example Option 1</a></li>
							<li><a href="javascript:void(0)" onclick="swal('Alert!', 'This is example of option 2', 'info')" class="dropdown-item">Exampel Option 2</a></li>
						</ul>
					</div>
				</div>
				<div class="card-body">
					<h1>Welcome {{ session('sess_user_fullname') }} ({{ session('sess_user_name') }}), as {{ session('sess_level_name') }}</h1>
				</div>
			</div>
		</div>
	</div>
@endsection 
