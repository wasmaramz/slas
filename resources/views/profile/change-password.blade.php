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
								<input type="password" class="form-control" id="currpsswd" name="currpsswd">
							</div>
						</div>

						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold" for="newpsswd">New Password</label>
							<div class="col-md-5">
								<input type="password" class="form-control" id="newpsswd" name="newpsswd">
							</div>
						</div>

						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold" for="confpsswd">Current Password</label>
							<div class="col-md-5">
								<input type="password" class="form-control" id="confpsswd" name="confpsswd">
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
				currpsswd: {
					required: true,
				},
				newpsswd: {
					required: true,
					//minlength: 8, 
				},
				confpsswd: {
					required: true,
					//minlength: 8, 
					equalTo: "#newpsswd",
				},
			},
			messages: {
				currpsswd: {
					required: "Please enter your Current Password.",
				},
				newpsswd: {
					required: "Please enter your New Password.",
					//minlength: "Password must be at least 8 characters.", 
				},
				confpsswd: {
					required: "Please confirm your Password.",
					//minlength: "Password must be at least 8 characters.", 
					equalTo: "Your Confirm Password Is Not Match.",
				},
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
						$.ajax({
							url: "/change/password",
							type: "post",
							dataType: "json",
							data: form_data,
							success: function(resp){
								if (resp.success){
									$('.section').load('/change/password');
								}
							}
						});
					}
				});
			}
		});
	</script>
@endsection
