<form id="ChangePasswordUserForm">
	@csrf

	@if (!empty($user_id))
		<input type="hidden" name="usrid" id="usrid" value="{{ $user_id }}">
	@endif

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

<script>
	$('#ChangePasswordUserForm').validate({
		rules: {
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
				text: 'Are you sure you want to change this user password?',
				icon: 'warning',
				buttons: true,
				dangerMode: true,
			})
			.then((willLogout) => {
				if (willLogout) {
					$.ajax({
						url: "/manage/user/change/password",
						type: "post",
						dataType: "json",
						data: form_data,
						success: function(resp){
							if (resp.success){
								@if ($user_role == "staff")
									$('.section').load('/manage/users/list/ADMN')
								@else
									$('.section').load('/manage/users/list/STUD')
								@endif
							}
						}
					});
				}
			});
		}
	});
</script>
