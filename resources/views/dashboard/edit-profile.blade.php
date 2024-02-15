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
							<div class="col-md-6">
								<div class="input-group">
									<label class="form-control mb-0">{{ $user->user_email }}</label>
									<div class="input-group-append">
										<button class="input-group-text btn btn-secondary" onclick="change_email()">
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row mb-0">
							<label class="col-md-3 col-form-label font-weight-bold" for="nophone">No. Phone</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="nophone" name="nophone">
							</div>
						</div>

						@if (!empty($user->stud_id))
							<div class="section-title">Student Details</div>

							<div class="form-group row mb-2">
								<label class="col-md-3 col-form-label font-weight-bold" for="sfname">Fullname <span class="text-danger">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="sfname" name="sfname">
								</div>
							</div>

							<div class="form-group row mb-2">
								<label class="col-md-3 col-form-label font-weight-bold" for="snomat">No. Matric <span class="text-danger">*</span></label>
								<div class="col-md-4">
									<input type="text" class="form-control" id="snomat" name="snomat">
								</div>
							</div>

							<div class="form-group row mb-2">
								<label class="col-md-3 col-form-label font-weight-bold" for="snoic">No. MyKad <span class="text-danger">*</span></label>
								<div class="col-md-4">
									<input type="text" class="form-control" id="snoic" name="snoic">
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
			$('#snoic').inputmask('999999-99-9999');
			$('#snomat').inputmask({ regex: "^[0-9]*$"});
		});
	</script>
@endsection
