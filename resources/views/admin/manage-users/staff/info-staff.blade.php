<!-- TEMPLATE FOR MODAL INFO STAFF -->
@if (!empty($user))
	<div class="row pb-2">
		<div class="col-md-10 section-title">User Information</div>
		<div class="col-md-2 text-right small">Ref.ID({{ $user->user_id }})</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">User ID</div>
		<div class="col-md-9 col-form-label">{{ $user->user_name }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Fullname</div>
		<div class="col-md-9 col-form-label">{{ $user->user_fullname }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Level</div>
		<div class="col-md-9 col-form-label">{{ $user->level_name }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Status</div>
		<div class="col-md-9 col-form-label">
			@if ($user->user_status == "1")
				<span class="badge badge-success">Active</span>
			@else 
				<span class="badge badge-danger">Inactive</span>
			@endif
		</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Email</div>
		<div class="col-md-9 col-form-label">{{ $user->user_email }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">No. Phone</div>
		<div class="col-md-9 col-form-label">{{ $user->user_nophone }}</div>
	</div>
@endif
