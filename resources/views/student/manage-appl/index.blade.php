@extends ('index')

@section ('main-content')
	<!-- START: Outer .row -->
	<div class="row">
		<!-- START: Outer .col-lg-12 .col-md-12 .col-sm-12 -->
		<div class="col-lg-12 col-md-12 col-sm-12">
			
			<!-- START: .card .shadow -->
			<div class="card shadow" id="card_manage_appl">
				<div class="card-header">
					<h4>Manage Leave Application</h4>
					<div class="card-header-action">
						<button type="button" class="btn btn-icon icon-left btn-primary" title="New Application" onclick="new_appl()">
							<i class="fas fa-plus"></i> New Application
						</button>
					</div>
				</div>

				<!-- START: .card-body -->
				<div class="card-body">

					<div id="content-top" style="display:none;"></div>

					<div id="content-bottom">
						<div class="table-responsive">
							<table class="table table-bordered" id="ManApplTbl">	
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="text-center">Appl. Date</th>
										<th class="text-center">Total Day</th>
										<th class="text-center">Start At</th>
										<th class="text-center">End At</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@if ($list_forms)
										@foreach ($list_forms as $lf)
											<tr data-id="{{ $lf->form_id }}">
												<td class="text-center">{{ $loop->iteration }}</td>
												<td class="text-center">{{ date("Y-m-d", strtotime($lf->form_date)) }}</td>
												<td class="text-center">{{ $lf->count_leave }}</td>
												<td class="text-center">{{ date("Y-m-d", strtotime($lf->start_leave)) }}</td>
												<td class="text-center">{{ date("Y-m-d", strtotime($lf->end_leave)) }}</td>
												<td class="text-center">
													@php
														$form_flag = $lf->form_flag;
														if ($form_flag == "BARU") {
															$text_class = "text-primary";
														}
														else if ($form_flag == "PENDING") {
															$text_class = "text-info";
														}
														else if ($form_flag == "KIV") {
															$text_class = "text-warning";
														}
														else if ($form_flag == "LULUS") {
															$text_class = "text-success";
														}
														else {
															$text_class = "text-secondary";
														}
													@endphp
													<span class="{{ $text_class }}">{{ $form_flag }}</span>
												</td>
												<td class="text-center" style="whitespace:nowrap;vertical-align:middle;">
													@if ($lf->form_memo)
														<button type="button" class="btn btn-icon text-info" title="View Application Memo" onclick="view_memo('{{ $lf->form_memo }}')">
															<i class="fas fa-bell"></i>
														</button>
													@endif
													@if ($lf->form_flag == "KIV")
														<button type="button" class="btn btn-icon text-primary" title="Edit Application" onclick="edit_appl('{{ $lf->form_id }}')">
															<i class="fas fa-edit"></i>
														</button>
													@elseif ($lf->form_flag == "LULUS")
														<button type="button" class="btn btn-icon text-success" title="Print Leave Application Form" onclick="print_appl('{{ $lf->form_id }}')">
															<i class="fas fa-print"></i>
														</button>
													@endif
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

	<script>
		$(document).ready(function(){
			$('#ManApplTbl').DataTable({
				columns: [
					{width: '5%',},
					{width: '15%',},
					{width: '10%',},
					{width: '15%',},
					{width: '15%',},
					{width: '20%',},
					{width: '20%', sortable: false,},
				],
			});
		});

		$('#ManApplTbl tbody tr').mouseover(function(){
			$(this).addClass('bg-light');
		}).mouseout(function(){
			$(this).removeClass('bg-light');
		});

		$('#ManApplTbl tbody tr td').not(':last-child').css('cursor', 'pointer');

		$('#ManApplTbl tbody tr td').not(':last-child').on('click', function(){
			var ftid = $($(this).parent()).attr('data-id');
			info_appl(ftid);
		});

		function info_appl(ftid) {
			$.get(`/manage/application/info/${ftid}`, function (resp){
				if (resp.success){
					var modal_title = '<i class="fas fa-info-circle"></i> Info Form';
					$('#modal_info_form').remove();
					$('body').append(
						'<div class="modal fade" tabindex="-1" role="dialog" id="modal_info_form">' +
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
					$('#modal_info_form').find('.modal-body').empty().append(resp.view_temp);
					$('#modal_info_form').modal('toggle')
				}
				else {
					pop_swal('warning', resp.msg);
				}
			});
		}

		function new_appl(){
			$.get('/manage/add/application', function(resp){
				if (resp.success) {
					var ori_card_header = $('#card_manage_appl .card-header').html();
					var close_btn = $(
						'<buttton type="butiton" class="btn btn-icon btn-danger" title="Close">' + 
							'<i class="fas fa-times"></i>' + 
						'</button>'
					);
					$(close_btn).on('click', function(e){
						$('#card_manage_appl .card-header').empty().html(ori_card_header);
						$('#card_manage_appl #content-top').slideUp();
						$('#card_manage_appl #content-bottom').slideDown();
						$('.section').load("/manage/application");
					});
					$('#card_manage_appl .card-header')
						.empty()
						.append('<h4>New Application</h4>')
						.append($('<div class="card-header-action"></div>').append(close_btn));
					$('#card_manage_appl #content-top').empty().html(resp.view_temp);
					$('#card_manage_appl #content-top').slideDown();
					$('#card_manage_appl #content-bottom').slideUp();
				}
				else {
					pop_swal('warning', resp.msg);
				}
			});
		}

		function view_memo(memo){
			swal("Application Memo", memo, "warning");
		}

		function edit_appl(fid){
			swal("Info!", "Function `Edit Application` Still In Progress!", "info");
		}

		function print_appl(fid){
			swal("Info!", "Function `Print Leave Application Form` Still In Progress!", "info");
		}
	</script>
@endsection
