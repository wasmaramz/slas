<?php

namespace App\Http\Controllers\KetuaProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Form;
use Exception;

class VerifyApplication extends Controller
{
	public function list_appl()
	{
		$get_forms = DB::table('forms as frm')
						->selectRaw("frm.form_id, frm.*, stu.stud_id, stu.*, usr.user_id, usr.*, prg.prog_id, prg.*, fty.ftype_id, fty.*")
						->join('students as stu', 'stu.stud_id', 'frm.stud_id')
						->join('users as usr', 'usr.user_id', 'stu.user_id')
						->join('programs as prg', 'prg.prog_id', 'stu.prog_id')
						->join('form_types as fty', 'fty.ftype_id', 'frm.ftype_id')
						->get();
		$get_forms = (!$get_forms->isEmpty()) ? $get_forms : null;

		$view_params = [
			"list_forms" => $get_forms
		];

		return view('ketua-prog.verify-appl.index', $view_params);
	}

	public function edit_form(Request $request)
	{
		if ($request->isMethod('post')){
			return $this->proc_edit_form($request);
		}

		if (empty($request->form_id)){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$form_id = $request->form_id;

		$form_types = DB::table('form_types')->get();

		$check_form = DB::table('forms as frm')
						->selectRaw("frm.form_id, frm.*, stu.stud_id, stu.*, usr.user_id, usr.*, prg.prog_id, prg.*, fty.ftype_id, fty.*")
						->join('students as stu', 'stu.stud_id', 'frm.stud_id')
						->join('users as usr', 'usr.user_id', 'stu.user_id')
						->join('programs as prg', 'prg.prog_id', 'stu.prog_id')
						->join('form_types as fty', 'fty.ftype_id', 'frm.ftype_id')
						->where('frm.form_id', $form_id)
						->first();

		$view_params = [
			"form_types" => $form_types,
			"form" => $check_form,
		];

		$view_temp = view('ketua-prog.verify-appl.edit', $view_params)->render();

		return response()->json(["success" => true, "msg" => "...", "view_temp" => $view_temp]);
	}

	private function proc_edit_form($request)
	{
		if (
			empty($request->fid) OR empty($request->ftid) OR empty($request->prid) OR empty($request->appdate) OR 
			empty($request->totleave) OR empty($request->strtdate) OR empty($request->enddate) OR empty($request->appreason) OR 
			empty($request->appstatus) 
		){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$form_id = $request->fid;

		// check form
		if (! $check_form = DB::table('forms')->where('form_id', $form_id)->first()){
			return response()->json(["success" => false, "msg" => "Form Record Not Found."]);
		}

		$ftype_id = $request->ftid;
		$prog_id = $request->prid;
		$form_date = $request->appdate;
		$count_leave = $request->totleave;

		// check count leave
		if ($ftype_id == "1" AND $count_leave <= 3) {
			return response()->json(["success" => false, "msg" => "Please choose Form Type `LAMPIRAN B` For Total Day Less Than and Equal To 3 Days."]);
		}
		else if ($ftype_id == "2" AND $count_leave > 3) {
			return response()->json(["success" => false, "msg" => "Please choose Form Type `LAMPIRAN A` For Total Day More Than 3 Days."]);
		}

		$start_leave = $request->strtdate;
		$end_leave = $request->enddate;

		// check for end_leave and start_leave
		if ($end_leave < $start_leave) {
			return response()->json(["success" => false, "msg" => "Leave Date Must Have A Valid Range Of Date."]);
		}

		$form_reason = $request->appreason;
		$form_flag = $request->appstatus;
		$form_memo = (!empty($request->appmemo)) ? $request->appmemo : null;

		$sess_user_id = session('sess_user_id');

		DB::beginTransaction();

		try {
			$form_params = [
				"ftype_id" => $ftype_id,
				"form_date" => $form_date,
				"count_leave" => $count_leave,
				"start_leave" => $start_leave,
				"end_leave" => $start_leave,
				"form_reason" => $form_reason,
				"form_flag" => $form_flag,
				"form_memo" => $form_memo,
				"updated_by" => $sess_user_id,
			];

			if (! $add_form = Form::where('form_id', $form_id)->update($form_params)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Edit Application Failed."]);
			}

			DB::commit();

			return response()->json(["success" => true, "msg" => "Edit Application Success."]);
		}
		catch(Exception $e) {
			return response()->json($e->getMessage());
		}
	}
}
