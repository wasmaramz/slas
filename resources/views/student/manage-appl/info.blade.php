<!-- TEMPLATE FOR MODAL INFO FORM -->
@if (!empty($form))
	<div class="row pb-2">
		<div class="col-md-10 section-title">Application Information</div>
		<div class="col-md-2 text-right small">Ref.ID({{ $form->form_id }})</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Form Type</div>
		<div class="col-md-9 col-form-label">{{ $form->ftype_name . " (" . $form->ftype_memo . ")" }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Fullname</div>
		<div class="col-md-9 col-form-label">{{ $form->stud_fullname }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">No. Matric</div>
		<div class="col-md-9 col-form-label">{{ $form->stud_nomat }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">No. MyKad</div>
		<div class="col-md-9 col-form-label">{{ \App\Var\GlobalFunction::format_nokp($form->stud_nokp) }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Program</div>
		<div class="col-md-9 col-form-label">{{ $form->prog_name }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Application Date</div>
		<div class="col-md-9 col-form-label">{{ date("Y-m-d", strtotime($form->form_date)) }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Total Day</div>
		<div class="col-md-9 col-form-label">{{ $form->count_leave }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Leave Date</div>
		<div class="col-md-9 col-form-label">{{ date("Y-m-d", strtotime($form->start_leave)) . " - " . date("Y-m-d", strtotime($form->end_leave)) }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Reason</div>
		<div class="col-md-9 col-form-label">{{ $form->form_reason }}</div>
	</div>

	<div class="form-group row mb-2">
		<div class="col-md-3 col-form-label font-weight-bold">Status</div>
		<div class="col-md-9 col-form-label">
			@php
				$form_flag = $form->form_flag;
				if ($form_flag == "BARU") {
					$text_class = "text-primary";
				}
				else if ($form_flag == "PENDING") {
					$text_class = "text-info";
				}
				else if ($form_flag == "KIV") {
					$text_class = "text-warning";
				}
				else if ($form_flag == "LULUS") {
					$text_class = "text-success";
				}
				else {
					$text_class = "text-secondary";
				}
			@endphp
			<span class="{{ $text_class }}">{{ $form_flag }}</span>
		</div>
	</div>
@endif
