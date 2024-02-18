@extends ('index')

@section ('main-content')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">

			<div class="card shadow">
				<div class="card-header">
					<h4>Change Password</h4>
				</div>
				<div class="card-body">
					
					<form id="ChangePasswordForm">
						@csrf

						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold" for="currpsswd">Current Password</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="currpsswd" name="currpsswd">
							</div>
						</div>

						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold" for="newpsswd">New Password</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="newpsswd" name="newpsswd">
							</div>
						</div>

						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold" for="confpsswd">Current Password</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="confpsswd" name="confpsswd">
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-icon icon-left btn-primary">
									<i class="fas fa-save"></i> Save
								</button>
							</div>
						</div>
					</form>	

				</div>
			</div>
			<!-- END: outer .card .shadow -->

		</div>
		<!-- END: outer .col-lg-12 .col-md-12 .col-sm-12  -->
	</div>
	<!-- END: outer .row -->

	<script>
		$('#ChangePasswordForm').validate({
			rules: {
			},
			messages: {
			},
			submitHandler: function(form) {
				var form_data = $(form).serialize();
				// confirmation dialog
				swal({
					title: 'Change Password!',
					text: 'Are you sure you want to change your password?',
					icon: 'warning',
					buttons: true,
					dangerMode: true,
				})
				.then((willLogout) => {
					if (willLogout) {
						swal("Still in Development.", "This is change email button function.", 'info');
					}
				});
			}
		});
	</script>
@endsection
