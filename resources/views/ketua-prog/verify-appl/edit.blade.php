@php
	$form_flags = [
		(object) ["fflag_id" => "BARU", "fflag_name" => "Baru"],
		(object) ["fflag_id" => "PENDING", "fflag_name" => "Pending"],
		(object) ["fflag_id" => "KIV", "fflag_name" => "Kiv"],
		(object) ["fflag_id" => "LULUS", "fflag_name" => "Lulus"],
		(object) ["fflag_id" => "TOLAK", "fflag_name" => "Tolak"],
	];
@endphp
<form id="EditApplForm">
	@csrf

	<input type="hidden" id="fid" name="fid"  value="{{ $form->form_id }}">

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="ftid">Form Type <span class="text-danger">*</span></label>
		<div class="col-md-4">
			<select class="form-control" id="ftid" name="ftid">
				<option></option>
				@if (!$form_types->isEmpty())
					@foreach ($form_types as $fty)
						<option value="{{ $fty->ftype_id }}" data-desc="{{ $fty->ftype_desc }}" @if($fty->ftype_id == $form->ftype_id){{ 'selected' }}@endif>{{ "$fty->ftype_name ($fty->ftype_memo)" }}</option>
					@endforeach
				@endif
			</select>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">Fullname</label>
		<div class="col-md-6">
			<label class="col-form-label">{{ $form->fstud_fullname }}</label>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">No. Matric</label>
		<div class="col-md-9">
			<label class="col-form-label">{{ $form->fstud_nomat }}</label>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">No. MyKad</label>
		<div class="col-md-9">
			<label class="col-form-label">{{ \App\Var\GlobalFunction::format_nokp($form->fstud_nokp) }}</label>
		</div>
	</div>

	<input type="hidden" id="prid" name="prid"  value="{{ $form->prog_id }}">

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">Program</label>
		<div class="col-md-9">
			<label class="col-form-label">{{ $form->fprog_name }}</label>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="appdate">Application Date <span class="text-danger">*</span></label>
		<div class="col-md-3">
			<input type="text" class="form-control" name="appdate" id="appdate" value="{{ date('Y-m-d', strtotime($form->form_date)) }}">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="totleave">Total Leave <span class="text-danger">*</span></label>
		<div class="col-md-3">
			<div class="input-group">
				<input type="number" class="form-control" name="totleave" id="totleave" value="{{ $form->count_leave }}">
				<div class="input-group-append">
					<span class="input-group-text">Day</span>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="strtdate">Leave Date <span class="text-danger">*</span></label>
		<div class="col-md-3">
			<input type="text" class="form-control" name="strtdate" id="strtdate" value="{{ date('Y-m-d', strtotime($form->start_leave)) }}">
		</div>
		<label class="col-md-1 col-form-label font-weight-bold text-center" for="enddate">To</label>
		<div class="col-md-3">
			<input type="text" class="form-control" name="enddate" id="enddate" value="{{ date('Y-m-d', strtotime($form->end_leave)) }}">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="appreason">Reason <span class="text-danger">*</span></label>
		<div class="col-md-8">
			<textarea class="form-control" name="appreason" id="appreason" rows="2" style="height:100%" maxlength="100">{{ $form->form_reason }}</textarea>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="appstatus">Application Status <span class="text-danger">*</span></label>
		<div class="col-md-4">
			<select class="form-control" id="appstatus" name="appstatus">
				<option></option>
				@foreach ($form_flags as $ffl)
					<option value="{{ $ffl->fflag_id }}" @if($ffl->fflag_id == $form->form_flag){{ 'selected' }}@endif>{{ $ffl->fflag_name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="appmemo">Memo</label>
		<div class="col-md-8">
			<textarea class="form-control" name="appmemo" id="appmemo" rows="2" style="height:100%" maxlength="100">{{ $form->form_memo }}</textarea>
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
	$(document).ready(function(){
		$('#ftid').select2({
			placeholder: "Choose Form Type ...",
		});

		$('#appdate, #strtdate, #enddate').attr('autocomplete', 'off');

		$('#appdate').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			locale: {
				format: "YYYY-MM-DD",
			},
		});

		$('#strtdate, #enddate').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			locale: {
				format: "YYYY-MM-DD",
			},
		});

		$('#appstatus').select2({
			placeholder: "Choose Form Status ...",
		});
	});

	$('#ftid').on('change', function(){
		if ($(this).find('option:selected').length > 0) {
			var data_desc = $(this).find('option:selected').attr('data-desc');
			swal("Notice!", data_desc, 'info');
		}
	});

	// prevent break line
	$("#appreason, #appmemo").keydown(function(e){
		if (e.keyCode == 13 && !e.shiftKey)
		{
			// prevent default behavior
			e.preventDefault();
			return false;
		}
	});

	$('#EditApplForm').validate({
		rules: {
			ftid: {
				required: true,
			},
			appdate: {
				required: true,
			},
			totleave: {
				required: true,
			},
			strtdate: {
				required: true,
			},
			enddate: {
				required: true,
			},
			appreason: {
				required: true,
				maxlength: 100,
			},
			appstatus: {
				required: true,
			},
			appmemo: {
				maxlength: 100,
			},
		},
		messages: {
			ftid: {
				required: "Please choose Form Type.",
			},
			appdate: {
				required: "Please enter Application Date.",
			},
			totleave: {
				required: "Please enter Total Leave",
			},
			strtdate: {
				required: "Please enter Start Leave Date.",
			},
			enddate: {
				required: "Please enter End Leave Date.",
			},
			appreason: {
				required: "Please enter Reason.",
				maxlength: "Reason must not exceed 100 words.",
			},
			appstatus: {
				required: "Please choose Application Status.",
			},
			appmemo: {
				maxlength: "Memo must not exceed 100 words.",
			},
		},
		submitHandler: function(form) {
			var form_data = $(form).serialize();
			$.ajax({
				url: "/verify/application/edit",
				type: "post",
				dataType: "json",
				data: form_data,
				success: function(resp) {
					if (resp.success){
						$('.section').load("/verify/application");
					}
				}
			});
		}
	});
</script>
