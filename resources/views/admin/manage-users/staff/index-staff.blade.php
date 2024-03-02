@extends ('index')

@section ('main-content')
	<!-- START: Outer .row -->
	<div class="row">
		<!-- START: Outer .col-lg-12 .col-md-12 .col-sm-12 -->
		<div class="col-lg-12 col-md-12 col-sm-12">
			
			<!-- START: .card .shadow -->
			<div class="card shadow" id="card_manage_staff">
				<div class="card-header">
					<h4>Manage Staff</h4>
					<div class="card-header-action">
						<button type="button" class="btn btn-icon icon-left btn-primary" title="Add Staff" onclick="add_staff()">
							<i class="fas fa-plus"></i> Add Staff
						</button>
					</div>
				</div>

				<!-- START: .card-body -->
				<div class="card-body">

					<div id="content-top" style="display:none;"></div>

					<div id="content-bottom">
						<div class="table-responsive">
							<table class="table table-bordered" id="ListStaffTable">	
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
										@foreach ($list_users as $staff)
											<tr data-id="{{ $staff->user_id }}">
												<td class="text-center">{{ $loop->iteration }}</td>
												<td class="text-left">{{ $staff->user_fullname }}</td>
												<td class="text-left">{{ $staff->level_name }}</td>
												<td class="text-center">
													@php
														$user_status = $staff->user_status;
														if ($user_status == "ACTIVE")
															$bdg_clss_color = "success";
														else if ($user_status == "PENDING")
															$bdg_clss_color = "info";
														else if ($user_status == "INACTIVE")
															$bdg_clss_color = "warning";
														else 
															$bdg_clss_color = "light";
													@endphp
													<span class="badge badge-{{ $bdg_clss_color }}">{{ $user_status }}</span>
												</td>
												<td class="text-center" style="whitespace:nowrap; vertical-align:middle;">
													<button type="button" class="btn btn-icon text-primary" title="Edit Staff" onclick="edit_staff('{{ $staff->user_id }}')">
														<i class="fas fa-edit"></i>
													</button>
													<button type="button" class="btn btn-icon text-success" title="Change Password Staff" onclick="change_password('{{ $staff->user_id }}')">
														<i class="fas fa-key"></i>
													</button>
													<button type="button" class="btn btn-icon text-info" title="Send Email Notify Staff" onclick="send_notify_staff('{{ $staff->user_id }}')">
														<i class="fas fa-paper-plane"></i>
													</button>
													<button type="button" class="btn btn-icon text-danger" title="Delete Staff" onclick="delete_staff('{{ $staff->user_id }}')">
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
			$('#ListStaffTable').DataTable({
				columns: [
					{width: '5%',},
					{width: '40%',},
					{width: '15%',},
					{width: '15%',},
					{width: '25%', sortable: false,},
				],
			});
		});

		$('#ListStaffTable tbody tr').mouseover(function(){
			$(this).addClass('bg-light');
		}).mouseout(function(){
			$(this).removeClass('bg-light');
		});

		$('#ListStaffTable tbody tr td').not(':last-child').css('cursor', 'pointer');

		$('#ListStaffTable tbody tr td').not(':last-child').on('click', function(){
			var usid = $($(this).parent()).attr('data-id');
			info_staff(usid);
		});

		function info_staff(usid) {
			$.get(`/manage/user/info/${usid}`, function (resp){
				if (resp.success){
					var modal_title = '<i class="fas fa-info-circle"></i> Info Staff';
					$('#modal_info_staff').remove();
					$('body').append(
						'<div class="modal fade" tabindex="-1" role="dialog" id="modal_info_staff">' +
							'<div class="modal-dialog modal-xl" role="document">' + 
								'<div class="modal-content">' + 
									'<div class="modal-header">' + 
										'<h5 class="modal-title">' + modal_title + '</h5>' +
										'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>' +
									'</div>' + 
									'<div class="modal-body"></div>' + 
								'</div>' + 
							'</div>' + 
						'</div>'
					);
					$('#modal_info_staff').find('.modal-body').empty().append(resp.view_temp);
					$('#modal_info_staff').modal('toggle')
				}
				else {
					pop_swal('warning', resp.msg);
				}
			});
		}

		function add_staff(){
			$.get('/manage/user/add/staff', function(resp){
				if (resp.success) {
					var ori_card_header = $('#card_manage_staff .card-header').html();
					var close_btn = $(
						'<buttton type="butiton" class="btn btn-icon btn-danger" title="Close">' + 
							'<i class="fas fa-times"></i>' + 
						'</button>'
					);
					$(close_btn).on('click', function(e){
						$('#card_manage_staff .card-header').empty().html(ori_card_header);
						$('#card_manage_staff #content-top').slideUp();
						$('#card_manage_staff #content-bottom').slideDown();
						$('.section').load('/manage/users/list/ADMN');
					});
					$('#card_manage_staff .card-header')
						.empty()
						.append('<h4>Add Staff</h4>')
						.append($('<div class="card-header-action"></div>').append(close_btn));
					$('#card_manage_staff #content-top').empty().html(resp.view_temp);
					$('#card_manage_staff #content-top').slideDown();
					$('#card_manage_staff #content-bottom').slideUp();
				}
				else {
					pop_swal('warning', resp.msg);
				}
			});
		}

		function edit_staff(usid){
			$.get(`/manage/user/edit/staff/${usid}`, function(resp){
				if (resp.success){
					var ori_card_header = $('#card_manage_staff .card-header').html();
					var close_btn = $(
						'<buttton type="butiton" class="btn btn-icon btn-danger" title="Close">' + 
							'<i class="fas fa-times"></i>' + 
						'</button>'
					);
					$(close_btn).on('click', function(e){
						$('#card_manage_staff .card-header').empty().html(ori_card_header);
						$('#card_manage_staff #content-top').slideUp();
						$('#card_manage_staff #content-bottom').slideDown();
						$('.section').load('/manage/users/list/ADMN');
					});
					$('#card_manage_staff .card-header')
						.empty()
						.append('<h4>Edit Staff</h4>')
						.append($('<div class="card-header-action"></div>').append(close_btn));
					$('#card_manage_staff #content-top').empty().html(resp.view_temp);
					$('#card_manage_staff #content-top').slideDown();
					$('#card_manage_staff #content-bottom').slideUp();
				}
				else{
					pop_swal('warning', resp.msg);
				}
			});
		}

		function change_password(usid){
			$.get(`/manage/user/change/password/${usid}`, function(resp){
				if (resp.success){
					var ori_card_header = $('#card_manage_staff .card-header').html();
					var close_btn = $(
						'<buttton type="butiton" class="btn btn-icon btn-danger" title="Close">' + 
							'<i class="fas fa-times"></i>' + 
						'</button>'
					);
					$(close_btn).on('click', function(e){
						$('#card_manage_staff .card-header').empty().html(ori_card_header);
						$('#card_manage_staff #content-top').slideUp();
						$('#card_manage_staff #content-bottom').slideDown();
						$('.section').load('/manage/users/list/ADMN');
					});
					$('#card_manage_staff .card-header')
						.empty()
						.append('<h4>Change Password User</h4>')
						.append($('<div class="card-header-action"></div>').append(close_btn));
					$('#card_manage_staff #content-top').empty().html(resp.view_temp);
					$('#card_manage_staff #content-top').slideDown();
					$('#card_manage_staff #content-bottom').slideUp();
				}
				else{
					pop_swal('warning', resp.msg);
				}
			});
		}

		function send_notify_staff(usid){
			swal('Alert!', 'Send Email Notify Staff Function Under Construction!', 'info');
		}

		function delete_staff(usid){
			// confirmation dialog
			swal({
				title: 'Delete User!',
				text: 'Are you sure you want to delete this user record?',
				icon: 'warning',
				buttons: true,
				dangerMode: true,
			})
			.then((willLogout) => {
				if (willLogout) {
					$.ajax({
						url: "/manage/user/delete",
						type: "post",
						dataType: "json",
						data: {
							"_token": "{{ csrf_token() }}",
							"usrid": usid,
						},
						success: function(resp){
							if (resp.success){
								$('.section').load('/manage/users/list/ADMN')
							}
						}
					});
				}
			});
		}
	</script>
	<!-- END: Javascript -->
@endsection
