@php
	$contr_func = Route::currentRouteAction();
	$contr_func = explode("@", $contr_func)[1];
	$contr_func = str_replace("_staff", "", $contr_func);

	if ($contr_func == "edit" AND $staff) {
		$user_id = $staff->user_id;
		$user_fullname = $staff->user_fullname;
		$level_id = $staff->level_id;
		$user_email = $staff->user_email;
		$user_nophone = $staff->user_nophone;
		$user_name = $staff->user_name;
		$user_status = $staff->user_status; 

		$arr_status = [
			(object)["status_id" => "ACTIVE", "status_text" => "Active"],
			(object)["status_id" => "PENDING", "status_text" => "Pending"],
			(object)["status_id" => "INACTIVE", "status_text" => "Inactive"],
		];
	}
	else {
		$user_id = null;
		$user_fullname = null;
		$level_id = null;
		$user_email = null;
		$user_nophone = null;
		$user_name = null;
		$user_status = null; 
	}
@endphp

<form id="DetailStaffForm">
	@csrf

	@if ($user_id)
		<input type="hidden" name="usid" id="usid" value="{{ $user_id }}">
	@endif

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="ufname">Fullname <span class="text-danger">*</span></label>
		<div class="col-md-7">
			<input type="text" class="form-control text-uppercase" id="ufname" name="ufname" value="{{ $user_fullname }}">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">Level <span class="text-danger">*</span></label>
		<div class="col-md-6">
			<select class="form-control" id="lvlid" name="lvlid">
				<option></option>
				@if (!$levels->isEmpty())
					@foreach ($levels as $lvl)
						<option value="{{ $lvl->level_id }}" @if ($lvl->level_id == $level_id){{ 'selected' }}@endif>{{ $lvl->level_name }}</option>
					@endforeach
				@endif
			</select>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="uemail">Email</label>
		<div class="col-md-6">
			<input type="text" class="form-control" id="uemail" name="uemail" value="{{ $user_email }}">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="unophone">No. Phone</label>
		<div class="col-md-6">
			<input type="text" class="form-control" id="unophone" name="unophone" value="{{ $user_nophone }}">
		</div>
	</div>

	<hr>

	@if ($contr_func == "add")
		<div class="form-group row mb-2">
			<label class="col-md-3 col-form-label font-weight-bold" for="usrname">User ID <span class="text-danger">*</span></label>
			<div class="col-md-6">
				<input type="text" class="form-control" id="usrname" name="usrname">
			</div>
		</div>

		<div class="form-group row mb-2">
			<label class="col-md-3 col-form-label font-weight-bold" for="upsswd">Password <span class="text-danger">*</span></label>
			<div class="col-md-7">
				<input type="password" class="form-control" id="upsswd" name="upsswd">
			</div>
		</div>

		<div class="form-group row mb-2">
			<label class="col-md-3 col-form-label font-weight-bold" for="ucnfpsswd">Confirm Password <span class="text-danger">*</span></label>
			<div class="col-md-7">
				<input type="password" class="form-control" id="ucnfpsswd" name="ucnfpsswd">
			</div>
		</div>
	@elseif ($contr_func == "edit")
		<div class="form-group row mb-2">
			<label class="col-md-3 col-form-label font-weight-bold">User ID</label>
			<div class="col-md-9">
				<label class="col-form-label">{{ $staff->user_name }}</label>
			</div>
		</div>

		<div class="form-group row mb-2">
			<label class="col-md-3 col-form-label font-weight-bold" for="ustatus">Status <span class="text-danger">*</span></label>
			<div class="col-md-4">
				<select class="form-control" name="ustatus" id="ustatus">
					@foreach ($arr_status as $astat)
						<option value="{{ $astat->status_id }}" @if ($astat->status_id == $user_status){{ 'selected' }}@endif>{{ $astat->status_text }}</option>
					@endforeach
				</select>
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

<script>
	$(document).ready(()=>{
		$('#lvlid').select2({
			placeholder: "Choose level..",
		});

		$('#unophone').inputmask({regex:"^[0-9]*$"});

		@if ($contr_func == "add")
			$('#usrname').inputmask({regex:"^[A-Za-z0-9]*$"});
		@elseif ($contr_func == "edit")
			$('#ustatus').select2();
		@endif
	});

	$('#DetailStaffForm').validate({
		rules: {
			ufname: {
				required: true,
			},
			lvlid: {
				required: true,
			},
			uemail: {
				regex: "^[A-Za-z0-9_!#$%&'*+\/=?`{|}~^.-]+@[A-Za-z0-9.-]+$",
			},
			unophone: {
				regex: "^[0-9]*$",
			},
			@if ($contr_func == "add")
				usrname: {
					required: true,
					regex: "^[A-Za-z0-9]*$",
				},
				upsswd: {
					required: true,
					//minlength: 8, 
				},
				ucnfpsswd: {
					required: true,
					//minlength: 8, 
					equalTo: "#upsswd",
				},
			@elseif ($contr_func == "edit")
				ustatus: {
					required: true,
				},
			@endif
		},
		messages: {
			ufname: {
				required: "Please enter Fullname.",
			},
			lvlid: {
				required: "Please choose Level.",
			},
			uemail: {
				regex: "Please enter a valid format of Email.",
			},
			unophone: {
				regex: "Please enter a valid format of No. Phone.",
			},
			@if ($contr_func == "add")
				usrname: {
					required: "Please enter User ID.",
					regex: "Please enter a valid format of User ID.",
				},
				upsswd: {
					required: "Please enter Password.",
					//minlength: "Password must be at least 8 characters.", 
				},
				ucnfpsswd: {
					required: "Please confirm Password.",
					//minlength: "Password must be at least 8 characters.", 
					equalTo: "Confirm Password Is Not Match.",
				},
			@elseif ($contr_func == "edit")
				ustatus: {
					required: "Please choose Status.",
				},
			@endif
		},
		submitHandler: function(form){
			var form_data = $(form).serialize();
			$.ajax({
				url: "/manage/user/{{ $contr_func }}/staff",
				type: "post",
				dataType: "json",
				data: form_data,
				success: function(resp) {
					if (resp.success) {
						$('.section').load('/manage/users/list/ADMN')
					}
					else if ('target_reset' in resp) {
						$(resp.target_reset).val("");
					}
				}
			});
		}
	});
</script>
