@php
	$contr_func = Route::currentRouteAction();
	$contr_func = explode("@", $contr_func)[1];
	$contr_func = str_replace("_staff", "", $contr_func);
@endphp
<form id="DetailStaffForm">
	@csrf

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="ufname">Fullname <span class="text-danger">*</span></label>
		<div class="col-md-7">
			<input type="text" class="form-control" id="ufname" name="ufname" value="">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">Level <span class="text-danger">*</span></label>
		<div class="col-md-6">
			<select class="form-control" id="lvlid" name="lvlid">
				<option></option>
				@if (!$levels->isEmpty())
					@foreach ($levels as $lvl)
						<option value="{{ $lvl->level_id }}">{{ $lvl->level_name }}</option>
					@endforeach
				@endif
			</select>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="uemail">Email</label>
		<div class="col-md-6">
			<input type="text" class="form-control" id="uemail" name="uemail" value="">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="unophone">No. Phone</label>
		<div class="col-md-6">
			<input type="text" class="form-control" id="unophone" name="unophone" value="">
		</div>
	</div>

	<hr>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="usrid">User ID <span class="text-danger">*</span></label>
		<div class="col-md-6">
			<input type="text" class="form-control" id="usrid" name="usrid" value="">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="upsswd">Password <span class="text-danger">*</span></label>
		<div class="col-md-7">
			<input type="password" class="form-control" id="upsswd" name="upsswd" value="">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="ucnfpsswd">Confirm Password <span class="text-danger">*</span></label>
		<div class="col-md-7">
			<input type="password" class="form-control" id="ucnfpsswd" name="ucnfpsswd" value="">
		</div>
	</div>

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
			//multiple: true,
			//maximumSelectionLength: 1,
		});

		$('#unophone').inputmask({regex:"^[0-9]*$"});
		$('#usrid').inputmask({regex:"^[A-Za-z0-9]*$"});
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
			usrid: {
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
				regex: "Please enter a valid format of No. Phone",
			},
			usrid: {
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
		},
		submitHandler: function(form){
			var form_data = $(form).serialize();
			$.ajax({
				url: "/manage/users/{{ $contr_func }}/staff",
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
