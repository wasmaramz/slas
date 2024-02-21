@extends ('index')

@section ('main-content')
	<!-- START: Outer .row -->
	<div class="row">
		<!-- START: Outer .col-lg-12 .col-md-12 .col-sm-12 -->
		<div class="col-lg-12 col-md-12 col-sm-12">
			
			<!-- START: .card .shadow -->
			<div class="card shadow">
				<div class="card-header">
					<h4>Manage Student</h4>
					<div class="card-header-action">
						<button type="button" class="btn btn-icon icon-left btn-primary" title="Add Student" onclick="add_stud()">
							<i class="fas fa-plus"></i> Add Student
						</button>
					</div>
				</div>

				<!-- START: .card-body -->
				<div class="card-body">

					<div class="content-top" style="display:none;"></div>

					<div class="content-bottom">
						<div class="table-responsive">
							<table class="table table-bordered" id="ListStudTable">	
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="text-left">Fullname</th>
										<th class="text-left">Level</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@if (!empty($list_users))
										@foreach ($list_users as $stud)
											<tr data-id="{{ $stud->user_id }}">
												<td class="text-center">{{ $loop->iteration }}</td>
												<td class="text-left">{{ $stud->user_fullname }}</td>
												<td class="text-left">{{ $stud->level_name }}</td>
												<td class="text-center">
													@if ($stud->user_status == "1")
														<span class="badge badge-success">Active</span>
													@else
														<span class="badge badge-danger">Inactive</span>
													@endif
												</td>
												<td class="text-center" style="whitespace:nowrap; vertical-align:middle;">
													<button type="button" class="btn btn-icon text-primary" title="Edit Student" onclick="edit_stud('{{ $stud->user_id }')">
														<i class="fas fa-edit"></i>
													</button>
													<button type="button" class="btn btn-icon text-success" title="Change Password Student" onclick="change_password('{{ $stud->user_id }')">
														<i class="fas fa-key"></i>
													</button>
													<button type="button" class="btn btn-icon text-danger" title="Delete Student" onclick="delete_stud('{{ $stud->user_id }')">
														<i class="fas fa-trash"></i>
													</button>
												</td>
											</tr>
										@endforeach
									@endif
								</tbody>
							</table>	
						</div>
					</div>

				</div>
				<!-- END: .card-body -->
			</div>
			<!-- END: .card .shadow -->

		</div>
		<!-- END: Outer .col-lg-12 .col-md-12 .col-sm-12 -->
	</div>
	<!-- END: Outer .row -->

	<!-- START: Javascript -->
	<script>
		$(document).ready(function(){
			$('#ListStudTable').DataTable({
				columns: [
					{width: '5%',},
					{width: '40%',},
					{width: '15%',},
					{width: '15%',},
					{width: '25%', sortable: false,},
				],
			});
		});

		$('#ListStudTable tbody tr').mouseover(function(){
			$(this).addClass('bg-light');
		}).mouseout(function(){
			$(this).removeClass('bg-light');
		});

		$('#ListStudTable tbody tr td').not(':last-child').css('cursor', 'pointer');

		$('#ListStudTable tbody tr td').not(':last-child').on('click', function(){
			var usid = $($(this).parent()).attr('data-id');
			info_stud(usid);
		});

		function info_stud(usid){
			swal('Alert!', 'Info Student Function Under Construction!', 'info');
		}

		function add_stud(usid){
			swal('Alert!', 'Add Student Function Under Construction!', 'info');
		}

		function edit_stud(usid){
			swal('Alert!', 'Edit Student Function Under Construction!', 'info');
		}

		function change_password(usid){
			swal('Alert!', 'Change Password Function Under Construction!', 'info');
		}

		function delete_stud(usid){
			swal('Alert!', 'Delete Student Function Under Construction!', 'info');
		}
	</script>
	<!-- END: Javascript -->
@endsection
