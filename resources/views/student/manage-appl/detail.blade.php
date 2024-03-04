<form id="DetailApplForm">
	@csrf

	<input type="hidden" id="stid" name="stid"  value="{{ $stud->stud_id }}">

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="ftid">Form Type <span class="text-danger">*</span></label>
		<div class="col-md-4">
			<select class="form-control" id="ftid" name="ftid">
				<option></option>
				@if (!$form_types->isEmpty())
					@foreach ($form_types as $fty)
						<option value="{{ $fty->ftype_id }}" data-desc="{{ $fty->ftype_desc }}">{{ $fty->ftype_name . " (" . $fty->ftype_memo . ")" }}</option>
					@endforeach
				@endif
			</select>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">Fullname</label>
		<div class="col-md-6">
			<label class="col-form-label">{{ $stud->stud_fullname }}</label>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">No. Matric</label>
		<div class="col-md-9">
			<label class="col-form-label">{{ $stud->stud_nomat }}</label>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">No. MyKad</label>
		<div class="col-md-9">
			<label class="col-form-label">{{ \App\Var\GlobalFunction::format_nokp($stud->stud_nokp) }}</label>
		</div>
	</div>

	<input type="hidden" id="prid" name="prid"  value="{{ $stud->prog_id }}">

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold">Program</label>
		<div class="col-md-9">
			<label class="col-form-label">{{ $stud->prog_name }}</label>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="appdate">Application Date <span class="text-danger">*</span></label>
		<div class="col-md-3">
			<input type="text" class="form-control" name="appdate" id="appdate">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="totleave">Total Leave <span class="text-danger">*</span></label>
		<div class="col-md-3">
			<div class="input-group">
				<input type="number" class="form-control" name="totleave" id="totleave">
				<div class="input-group-append">
					<span class="input-group-text">Day</span>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="strtdate">Leave Date <span class="text-danger">*</span></label>
		<div class="col-md-3">
			<input type="text" class="form-control" name="strtdate" id="strtdate">
		</div>
		<label class="col-md-1 col-form-label font-weight-bold text-center" for="enddate">To</label>
		<div class="col-md-3">
			<input type="text" class="form-control" name="enddate" id="enddate">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-md-3 col-form-label font-weight-bold" for="appreason">Reason <span class="text-danger">*</span></label>
		<div class="col-md-8">
			<textarea class="form-control" name="appreason" id="appreason" rows="2" style="height:100%" maxlength="100"></textarea>
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
	});

	$('#ftid').on('change', function(){
		if ($(this).find('option:selected').length > 0) {
			var data_desc = $(this).find('option:selected').attr('data-desc');
			swal("Notice!", data_desc, 'info');
		}
	});

	// prevent break line
	$("#appreason").keydown(function(e){
		if (e.keyCode == 13 && !e.shiftKey)
		{
			// prevent default behavior
			e.preventDefault();
			return false;
		}
	});

	$('#DetailApplForm').validate({
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
		},
		submitHandler: function(form) {
			var form_data = $(form).serialize();
			$.ajax({
				url: "/manage/add/application",
				type: "post",
				dataType: "json",
				data: form_data,
				success: function(resp) {
					if (resp.success){
						$('.section').load("/manage/application");
					}
				}
			});
		}
	});
</script>
