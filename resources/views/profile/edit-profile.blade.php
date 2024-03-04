@extends ('index')

@section ('main-content')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card shadow">
				<div class="card-header">
					<h4>Edit Profile</h4>
				</div>
				<div class="card-body">

					<form id="EditProfileForm">
						@csrf
						<div class="section-title">User Info</div>

						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold">User ID</label>
							<div class="col-md-6">
								<label class="col-form-label mb-0">{{ $user->user_name }}</label>
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold">Level</label>
							<div class="col-md-6">
								<label class="col-form-label mb-0">{{ $user->level_name }}</label>
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold">Email</label>
							<div class="col-md-4">
								<div class="input-group">
									<label class="form-control mb-0">{{ $user->user_email }}</label>
									<div class="input-group-append">
										<button type="button" class="input-group-text btn btn-icon btn-outline-secondary" title="Change Email" onclick="change_email()">
											<i class="fas fa-edit"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-md-3 col-form-label font-weight-bold" for="ufname">Fullname <span class="text-danger">*</span></label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="ufname" name="ufname" value="{{ $user->user_fullname }}">
							</div>
						</div>
						<div class="form-group row mb-0">
							<label class="col-md-3 col-form-label font-weight-bold" for="nophone">No. Phone</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="nophone" name="nophone" value="{{ $user->user_nophone }}">
							</div>
						</div>

						@if (!empty($user->stud_id))
							<div class="section-title">Student Details</div>

							<div class="form-group row mb-2">
								{{--
								<!--
								<label class="col-md-3 col-form-label font-weight-bold" for="snomat">No. Matric <span class="text-danger">*</span></label>
								<div class="col-md-4">
									<input type="text" class="form-control" id="snomat" name="snomat" value="{{ $user->stud_nomat }}">
								</div>
								-->
								--}}
								<label class="col-md-3 col-form-label font-weight-bold">No. Matric</label>
								<div class="col-md-9">
									<label class="col-form-label mb-0">{{ $user->stud_nomat }}</label>
								</div>
							</div>

							<div class="form-group row mb-2">
								{{--
								<!--
								<label class="col-md-3 col-form-label font-weight-bold" for="snokp">No. MyKad <span class="text-danger">*</span></label>
								<div class="col-md-4">
									<input type="text" class="form-control" id="snokp" name="snokp" value="{{ $user->stud_nokp }}">
								</div>
								-->
								--}}
								<label class="col-md-3 col-form-label font-weight-bold">No. MyKad</label>
								<div class="col-md-9">
									<label class="col-form-label mb-0">{{ $user->stud_nokp }}</label>
								</div>
							</div>

							<div class="form-group row mb-0">
								<label class="col-md-3 col-form-label font-weight-bold">Program</label>
								<div class="col-md-9">
									<label class="col-form-label mb-0">{{ $user->prog_name }}</label>
								</div>
							</div>
						@endif

						<div class="form-group row mt-4 mb-0">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-icon icon-left btn-primary">
									<i class="fas fa-save"></i> Save
								</button>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			$('#nophone').inputmask({ regex: "^[0-9]*$"});
			//$('#snomat').inputmask({ regex: "^[0-9]*$"});
			//$('#snokp').inputmask('999999-99-9999');
		});

		function change_email(){
			swal("Still in Development.", "This is change email button function.", 'info');
		}

		$('#EditProfileForm').validate({
			rules: {
				ufname: {
					required: true,
				},
			},
			messages: {
				ufname: {
					required: "Please enter your Fullname.",
				},
			},
			submitHandler: function(form){
				$.ajax({
					url: "/edit/profile",
					type: "post",
					dataType: "json",
					data: $(form).serialize(),
					success: function(resp){
						if (resp.success) {
							$('#badge-sp_usr_fname').text(resp.sp_usr_fname);
							$('.section').load('/edit/profile');
						}
					}
				});
			}
		});

	</script>
@endsection
