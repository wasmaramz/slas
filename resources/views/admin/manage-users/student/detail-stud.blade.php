@php
	$contr_func = Route::currentRouteAction(); 
	$contr_func = explode("@", $contr_func)[1];
	$contr_func = str_replace("_stud", "", $contr_func);

	if ($contr_func == "edit" AND !empty($stud)) {
		$user_id = $stud->user_id;
		$stud_id = $stud->stud_id;
		$user_fullname = $stud->user_fullname;
		$stud_nomat = $stud->stud_nomat;
		$stud_nokp = $stud->stud_nokp;
		$prog_id = $stud->prog_id;
		$user_email = $stud->user_email;
		$user_nophone = $stud->user_nophone;
		$user_status = $stud->user_status;
	}
	else {
		$user_fullname = null;
		$stud_nomat = null;
		$stud_nokp = null;
		$prog_id = null;
		$user_email = null;
		$user_nophone = null;
	}
@endphp

<form id="DetailStudForm">
	@csrf

	@if ($contr_func == "edit" AND !empty($user_id))
		<input type="hidden" name="usrid" id="usrid" value="{{ $user_id }}">
	@endif

	<div class="section-title mt-0">Student Information</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="ufname">Fullname <span class="text-danger">*</span></label>
		<div class="col-md-7">
			<input type="text" class="form-control text-uppercase" id="ufname" name="ufname" value="{{ $user_fullname }}">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="snomat">No. Matric <span class="text-danger">*</span></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="snomat" name="snomat" value="{{ $stud_nomat }}">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="snokp">No. MyKad <span class="text-danger">*</span></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="snokp" name="snokp" value="{{ $stud_nokp }}">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="prgid">Program <span class="text-danger">*</span></label>
		<div class="col-md-6">
			<select class="form-control" id="prgid" name="prgid">
				<option></option>
				@if (!$programs->isEmpty())
					@foreach ($programs as $prg)
						<option value="{{ $prg->prog_id }}" @if ($prg->prog_id == $prog_id){{ 'selected' }}@endif>{{ $prg->prog_name }}</option>
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

	<div class="section-title mt-0">User Information</div>

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
	@else
		<div class="form-group row mb-2">
			<label class="col-md-3 col-form-label font-weight-bold">User ID</label>
			<div class="col-md-9">
				<label class="col-form-label">{{ $stud->user_name }}</label>
			</div>
		</div>

		<div class="form-group row mb-2">
			<label class="col-md-3 col-form-label font-weight-bold" for="ustatus">Status <span class="text-danger">*</span></label>
			<div class="col-md-4">
				<select class="form-control" name="ustatus" id="ustatus">
					@foreach ($user_statuses as $ustat)
						<option value="{{ $ustat->ustat_id }}" @if ($ustat->ustat_id == $user_status){{ 'selected' }}@endif>{{ $ustat->ustat_name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	@endif

	<div class="form-group row mb-0">
		<div class="col-md-12 text-right">
			<button type="submit" class="btn btn-icon icon-left btn-primary">
				<i class="fas fa-save"></i> Save
			</button>
		</div>
	</div>
</form>

<script>
	$(document).ready(function(){
		$('#prgid').select2({
			placeholder: "Choose program..",
		});
		$('#snomat').inputmask({regex:"^[A-Za-z0-9]*$"});
		$('#snokp').inputmask({regex:"[0-9]{6}-[0-9]{2}-[0-9]{4}"});
		$('#unophone').inputmask({regex:"^[0-9]*$"});

		@if ($contr_func == "add")
			$('#usrname').inputmask({regex:"^[A-Za-z0-9]*$"});
		@else 
			$('#ustatus').select2();
		@endif
	});

	$('#DetailStudForm').validate({
		rules: {
			ufname: {
				required: true,
			},
			snomat: {
				required: true,
				regex: "^[A-Za-z0-9]*$",
			},
			snokp: {
				required: true,
				regex: "[0-9]{6}-[0-9]{2}-[0-9]{4}",
			},
			prgid: {
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
			@else 
				ustatus: {
					required: true,
				},
			@endif
		},
		messages: {
			ufname: {
				required: "Please enter Fullname.",
			},
			snomat: {
				required: "Please enter No. Matric.",
				regex: "Please enter a valid format of No. Matric.",
			},
			snokp: {
				required: "Please enter No. MyKad.",
				regex: "Please enter a valid format of No. MyKad.",
			},
			prgid: {
				required: "Please choose Program.",
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
			@else
				ustatus: {
					required: "Please choose Status.",
				},
			@endif
		},
		submitHandler: function(form){
			var form_data = $(form).serialize();
			$.ajax({
				url: "/manage/user/{{ $contr_func }}/stud",
				type: "post",
				dataType: "json",
				data: form_data,
				success: function(resp) {
					if (resp.success) {
						$('.section').load('/manage/users/list/STUD')
					}
					else if ('target_reset' in resp) {
						$(resp.target_reset).val("");
					}
				}
			});
		}
	});
</script>
