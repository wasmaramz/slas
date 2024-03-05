<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Form;

class ManageApplication extends Controller
{
	public function list_appl()
	{
		$sess_user_id = session('sess_user_id');
		$sess_stud_id = session('sess_stud_id');
		$sess_prog_id = session('sess_prog_id');

		if (! $check_user = DB::table('users')->where('user_id', $sess_user_id)->first()){
			abort(404, "User Information Invalid.");
		}

		if (! $check_stud = DB::table('students')->where('stud_id', $sess_stud_id)->first()){
			abort(404, "Student Information Invalid.");
		}

		$get_forms = DB::table('forms as frm')
						->selectRaw('frm.*, frm.ftype_id, frt.ftype_name')
						->join('form_types as frt', 'frt.ftype_id', 'frm.ftype_id')
						->where('frm.stud_id', $sess_stud_id)
						->get();
		$get_forms = (!$get_forms->isEmpty()) ? $get_forms : null;

		$view_params = [
			"stud" => $check_stud, 
			"list_forms" => $get_forms,
		];

		return view('student.manage-appl.index', $view_params);
	}


	public function add_appl(Request $request)
	{
		if ($request->isMethod('post')) {
			return $this->proc_add_appl($request);
		}

		$form_types = DB::table('form_types')->get();

		$sess_user_id = session('sess_user_id');
		$sess_stud_id = session('sess_stud_id');
		$sess_prog_id = session('sess_prog_id');

		// check unfinish (FLAG = BARU, PENDING, KIV)
		if ($check_unfinish = DB::table('forms')->where('stud_id', $sess_stud_id)->whereIn('form_flag', ["BARU", "PENDING", "KIV"])->first()){
			return response()->json(["success" => false, "msg" => "Please wait until past appplication settled."]);
		}

		if (! $check_user = DB::table('users')->where('user_id', $sess_user_id)->first()){
			return response()->json(["success" => false, "msg" => "User Information Invalid."]);
		}

		if ( 
			!$check_stud = DB::table('students as stu')
				->selectRaw('stu.*, pro.*, pro.prog_id')
				->join('programs as pro', 'pro.prog_id', 'stu.prog_id')
				->where('stu.stud_id', $sess_stud_id)
				->first()
		) {
			return response()->json(["success" => false, "msg" => "Student Information Invalid."]);
		}

		$view_params = [
			"stud" => $check_stud, 
			"form_types" => $form_types,
		];

		$view_temp = view('student.manage-appl.detail', $view_params)->render();

		return response()->json(["success" => true, "msg" => "...", "view_temp" => $view_temp]);
	}

	private function proc_add_appl($request)
	{
		if (
			empty($request->stid) OR empty($request->ftid) OR empty($request->prid) OR empty($request->appdate) OR 
			empty($request->totleave) OR empty($request->strtdate) OR empty($request->enddate) OR empty($request->appreason)
		){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$stud_id = $request->stid;
		$ftype_id = $request->ftid;

		// check unfinish (FLAG = BARU, PENDING, KIV)
		if ($check_unfinish = DB::table('forms')->where('stud_id', $stud_id)->whereIn('form_flag', ["BARU", "PENDING", "KIV"])->first()){
			return response()->json(["success" => false, "msg" => "Please wait until past appplication settled."]);
		}

		// check for stud_id 
		if (! $check_stid = DB::table('students')->where('stud_id', $stud_id)->first()){
			return response()->json(["success" => false, "msg" => "Student Record Not Found."]);
		} 
		$fstud_fullname = $check_stid->stud_fullname;
		$fstud_nomat = $check_stid->stud_nomat;
		$fstud_nokp = $check_stid->stud_nokp;

		$prog_id = $request->prid;

		// check for prog_id
		if (! $check_prid = DB::table('programs')->where('prog_id', $prog_id)->first()){
			return response()->json(["success" => false, "msg" => "Program Record Not Found."]);
		}
		$fprog_name = $check_prid->prog_name;
		$fprog_code = $check_prid->prog_code;

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

		$sess_user_id = session('sess_user_id');

		DB::beginTransaction();

		try {
			$form_params = [
				"stud_id" => $stud_id,
				"ftype_id" => $ftype_id,
				"fstud_fullname" => $fstud_fullname,
				"fstud_nomat" => $fstud_nomat,
				"fstud_nokp" => $fstud_nokp,
				"prog_id" => $prog_id,
				"fprog_name" => $fprog_name,
				"fprog_code" => $fprog_code,
				"form_date" => $form_date,
				"count_leave" => $count_leave,
				"start_leave" => $start_leave,
				"end_leave" => $start_leave,
				"form_reason" => $form_reason,
				"form_flag" => "BARU",
				"created_by" => $sess_user_id,
				"updated_by" => $sess_user_id,
			];

			if (! $add_form = Form::create($form_params)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Create New Application Failed."]);
			}

			DB::commit();

			return response()->json(["success" => true, "msg" => "Create New Application Success."]);
		}
		catch(Exception $e) {
			return response()->json($e->getMessage());
		}
	}


	public function info_appl($form_id)
	{
		$get_form = DB::table('forms as frm')
						->selectRaw("frm.form_id, frm.*, stu.stud_id, stu.*, usr.user_id, usr.*, prg.prog_id, prg.*, fty.ftype_id, fty.*")
						->join('students as stu', 'stu.stud_id', 'frm.stud_id')
						->join('users as usr', 'usr.user_id', 'stu.user_id')
						->join('programs as prg', 'prg.prog_id', 'stu.prog_id')
						->join('form_types as fty', 'fty.ftype_id', 'frm.ftype_id')
						->where('frm.form_id', $form_id)	
						->first();

		if (!$get_form) {
			return response()->json(["success" => false, "msg" => "Form Record Not Found."]);
		}
		else {
			$view_params = [
				"form" => $get_form,
			];
			$view_temp = view("student.manage-appl.info", $view_params)->render();
			return response()->json(["success" => true, "msg" => "....", "view_temp" => $view_temp]);
		}
	}	
}
