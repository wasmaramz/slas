<!-- LAYOUT: SIDEBAR -->
<aside id="sidebar-wrapper">
	<!-- LOGO SYSTEM -->
	<div class="sidebar-brand">
		<a href="index.html">Stisla</a>
	</div>

	<!-- LOGO SYSTEM: MOBILE -->
	<div class="sidebar-brand sidebar-brand-sm">
		<a href="index.html">St</a>
	</div>

	<!-- SIDEBAR MENU -->
	<ul class="sidebar-menu">
		<li class="menu-header">Dashboard</li>

		<li>
			<a class="nav-link" href="/"><i class="fas fa-home"></i><span>Home</span></a>
		</li>

		@can ('ADMN')
			<li class="menu-header">System</li>

			<li class="nav-item dropdown">
				<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>Manage Users</span></a>
				<ul class="dropdown-menu">
					<li><a class="nav-link" href="javascript:void(0)" onclick="$('.section').load('/manage/users/list/ADMN')">Staff</a></li>
					<li><a class="nav-link" href="javascript:void(0)" onclick="$('.section').load('/manage/users/list/STUD')">Student</a></li>
				</ul>
			</li>

			<li class="nav-item dropdown">
				<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs"></i> <span>Settings</span></a>
				<ul class="dropdown-menu">
					<li><a class="nav-link" href="javascript:void(0)" onclick="swal('Still In Development')">Informations</a></li>
					<li><a class="nav-link" href="javascript:void(0)" onclick="swal('Still In Development')">Appearance</a></li>
				</ul>
			</li>
		@endcan 

		@can ('KEPR')
			<li class="menu-header">Leave Application</li>

			<li>
				<a href="javascript:void(0)" class="nav-link" onclick="swal('Still In Development')"><i class="fas fa-pen-fancy"></i><span>Approve Application</span></a>
			</li>
		@endcan

		@can ('TIPE')
			<li class="menu-header">Leave Application</li>

			<li>
				<a href="javascript:void(0)" class="nav-link" onclick="swal('Still In Development')"><i class="fas fa-check-double"></i><span>Verify Applications</span></a>
			</li>
		@endcan

		@can ('PEAK')
			<li class="menu-header">Leave Application</li>

			<li>
				<a href="javascript:void(0)" class="nav-link" onclick="swal('Still In Development')"><i class="fas fa-check"></i><span>Check Application</span></a>
			</li>
		@endcan

		@can ('STUD')
			<li>
				<a href="javascript:void(0)" class="nav-link" onclick="swal('Still In Development')"><i class="fas fa-list"></i><span>Leave Application</span></a>
			</li>
		@endcan

		@can ('STAFF')
			<li class="menu-header">Report</li>

			<li>
				<a href="javascript:void(0)" class="nav-link" onclick="swal('Still In Development')"><i class="fas fa-file-pdf"></i><span>Leave Record</span></a>
			</li>
		@endcan
	</ul>
	<!-- END SIDEBAR MENU -->

	<!-- DOCUMENTATION STISLA -->
	<div class="mt-4 mb-4 p-3 hide-sidebar-mini">
		{{--
		@if ( env('APP_DEBUG') )
		<a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
			<i class="fas fa-rocket"></i> Documentation
		</a>
		@endif
		--}}
	</div>

</aside>
