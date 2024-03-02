<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class ManageUser extends Controller
{
	public function index($level)
	{
		$arr_staff = ["ADMN", "KEPR", "TIPE", "PEAK"];

		if ($level == "SYS"){
			abort(404, "Record Not Found."); // anoymous error message ...
		}
		else if (in_array($level, $arr_staff)){
			$view_temp = "admin.manage-users.staff.index-staff";
			$get_users = DB::table('users as usr')
							->selectRaw("usr.user_id, lvl.level_id, usr.*, lvl.*")
							->join('levels as lvl', 'lvl.level_id', 'usr.level_id')
							->whereIn('usr.level_id', $arr_staff)
							->get();
		}
		else if ($level == "STUD") {
			$view_temp = "admin.manage-users.student.index-stud";
			$get_users = DB::table('users as usr')
							->selectRaw("usr.user_id, lvl.level_id, stud.stud_id, usr.*, lvl.*, stud.*")
							->join('levels as lvl', 'lvl.level_id', 'usr.level_id')
							->join('students as stud', 'stud.user_id', 'usr.user_id')
							->where('usr.level_id', "STUD")
							->get();
		}
		else {
			abort(404, "Level Not Found.");
		}

		// standardize set null for attribute isEmpty
		$get_users = (!$get_users->isEmpty()) ? $get_users : null;

		$view_params = [
			"list_users" => $get_users,
		];

		return view($view_temp, $view_params);
	}	


	public function info_user ($user_id){
		$get_user = DB::table('users as usr')
						->selectRaw('usr.*, lvl.*, stu.*, usr.user_id, lvl.level_id, stu.stud_id')
						->join('levels as lvl', 'lvl.level_id', 'usr.level_id')
						->leftJoin('students as stu', 'stu.user_id', 'usr.user_id')
						->where('usr.user_id', $user_id)
						->first();

		if (!$get_user) {
			return response()->json(["success" => false, "msg" => "User Record Not Found."]);
		}
		else {
			if ($get_user->level_id != "STUD") {
				$view_temp_path = 'admin.manage-users.staff.info-staff'; 
			}
			else {
				$view_temp_path = 'admin.manage-users.student.info-stud'; 
			}

			$view_temp = view($view_temp_path, [ "user" => $get_user])->render();

			return response()->json(["success" => true, "msg" => "....", "view_temp" => $view_temp]);
		}
	}


	public function add_staff (Request $request) 
	{
		if ($request->isMethod('post')){
			return $this->proc_add_staff($request);
		}

		if (! $levels = DB::table('levels')->whereNotIn('level_id', ["SYS", "STUD"])->get()){
			return response()->json(["success" => false, "msg" => "Level Record Not Found."]);
		}

		$view_temp = view('admin.manage-users.staff.detail-staff', ["levels" => $levels])->render();

		return response()->json(["success" => true, "msg" => "...", "view_temp" => $view_temp]);
	}


	private function proc_add_staff($request)
	{
		if (
			empty($request->ufname) OR empty($request->lvlid) OR 
			empty($request->usrname) OR empty($request->upsswd) OR empty($request->ucnfpsswd)
		){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_fullname = strtoupper($request->ufname);
		$level_id = $request->lvlid;
		$user_email = (!empty($request->uemail)) ? $request->uemail : null;
		$user_nophone = (!empty($request->unophone)) ? $request->unophone : null;

		// check user email 
		if ($user_email) {
			// check format email if not empty
			if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
				return response()->json(["success" => false, "msg" => "Please enter a valid format of Email.", "target_reset" => "#uemail"]);
			}
			// check user_email available or not
			if ( $check_uemail = DB::table('users')->where("user_email", $user_email)->first()){
				return response()->json(["success" => false, "msg" => "That Email already Exist.", "target_reset" => "#uemail"]);
			}
		}

		// check user_nophone 
		if ($user_nophone) {
			// check format nophone if not empty
			if (!is_numeric($user_nophone)){
				return response()->json(["success" => false, "msg" => "Please enter a valid format of No. Phone.", "target_reset" => "#unophone"]);
			}
			// check user_nophone available or not
			if ( $check_uemail = DB::table('users')->where("user_email", $user_email)->first()){
				return response()->json(["success" => false, "msg" => "That No. Phone already Exist.", "target_reset" => "#unophone"]);
			}
		}

		$user_name = $request->usrname;

		// check format user name 
		if (preg_match("/[^a-z\-A-Z\-0-9]/i", $user_name)){
			return response()->json(["success" => false, "msg" => "Please enter a valid format of User ID.", "target_reset" => "#usrname"]);
		}

		// check whether user_name available or not 
		if ($check_usrname = DB::table('users')->where('user_name', $user_name)->first()){
			return response()->json(["success" => false, "msg" => "That User ID already exist.", "target_reset" => "#usrname"]);
		}

		$password = $request->upsswd;
		$conf_password = $request->ucnfpsswd;

		// check length password up to 8 char 
		/*
		if (strlen($password) < 8) {
			return response()->json(["success" => false, "msg" => "Password must be at least 8 characters.", "target_reset" => "#upsswd, #ucnfpsswd"]);
		}
		 */

		// check confirmation of password
		if ($conf_password <> $password){
			return response()->json(["success" => false, "msg" => "Confirm Password Is Not Match.", "target_reset" => "#upsswd, #ucnfpsswd"]);
		}

		// hashed password
		//$hash_password = Hash::make($password);
		$hash_password = Hash::make($password);

		$sess_user_id = session('sess_user_id');

		DB::beginTransaction();

		try {
			$param_staff = [
				"user_fullname" => $user_fullname, 
				"user_name" => $user_name,
				"password" => $hash_password,
				"level_id" => $level_id,
				"user_status" => "PENDING",
				"user_email" => $user_email,
				"user_nophone" => $user_nophone,
				"created_by" => $sess_user_id,
				"updated_by" => $sess_user_id,
			];

			if (! $add_staff = User::create($param_staff)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Add Staff Failed."]);
			}

			DB::commit();

			return response()->json(["success" => true, "msg" => "Add Staff Successful."]);
		}
		catch (Exception $e) {
			return response()->json($e->getMessage());
		}
	}


	public function edit_staff(Request $request)
	{
		if ($request->isMethod('post')){
			return $this->proc_edit_staff($request);
		}

		if (empty($request->user_id)){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_id = $request->user_id;

		if (! $check_user = DB::table('users')->where('user_id', $user_id)->first()){
			return response()->json(["success" => false, "msg" => "User Record Not Found."]);
		}

		if (! $levels = DB::table('levels')->whereNotIn('level_id', ["SYS", "STUD"])->get()){
			return response()->json(["success" => false, "msg" => "Level Record Not Found."]);
		}

		$user_statuses = DB::table('user_statuses')->get();

		$view_params = [
			"staff" => $check_user,
			"levels" => $levels,
			"user_statuses" => $user_statuses,
		];

		$view_temp = view('admin.manage-users.staff.detail-staff', $view_params)->render();

		return response()->json(["success" => true, "msg" => "...", "view_temp" => $view_temp]);
	}


	private function proc_edit_staff($request)
	{
		if (empty($request->usid) OR empty($request->ufname) OR empty($request->lvlid) OR empty($request->ustatus)){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_id = $request->usid;

		// check user
		if (! $check_user = DB::table('users')->where('user_id', $user_id)->first()){
			return response()->json(["success" => false, "msg" => "User Record Not Found."]);
		}

		$user_fullname = $request->ufname;
		$user_email = (!empty($request->uemail)) ? $request->uemail : null;
		$user_nophone = (!empty($request->unophone)) ? $request->unophone : null;

		// check user_email
		if ($user_email) {
			// check format email if not empty
			if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
				return response()->json(["success" => false, "msg" => "Please enter a valid format of Email.", "target_reset" => "#uemail"]);
			}
			// check user_email available or not
			if ( $check_uemail = DB::table('users')->whereRaw("user_id <> $user_id AND user_email = '$user_email'")->first()){
				return response()->json(["success" => false, "msg" => "That Email already Exist.", "target_reset" => "#uemail"]);
			}
		}

		// check user_nophone
		if ($user_nophone) {
			// check format nophone if not empty
			if (!is_numeric($user_nophone)){
				return response()->json(["success" => false, "msg" => "Please enter a valid format of No. Phone.", "target_reset" => "#unophone"]);
			}
			// check user_nophone available or not
			if ( $check_uemail = DB::table('users')->whereRaw("user_id <> $user_id AND user_email = '$user_email'")->first()){
				return response()->json(["success" => false, "msg" => "That No. Phone already Exist.", "target_reset" => "#unophone"]);
			}
		}

		$level_id = $request->lvlid;
		$user_status = $request->ustatus;

		// check user_email required before edit user_status to `ACTIVE`
		/* 
		if (empty($user_email) AND $user_status == "ACTIVE") {
			return response()->json(["success" => false, "msg" => "Please Update Email Before Activate User."]);
		}
		*/

		DB::beginTransaction();

		try {
			$user_params = [
				'user_fullname' => $user_fullname,
				'user_email' => $user_email,
				'user_nophone' => $user_nophone,
				'level_id' => $level_id,
				'user_status' => $user_status,
			];

			if (! $edit_user = User::where('user_id', $user_id)->update($user_params)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Edit User Failed."]);
			}

			DB::commit();

			return response()->json(["success" => true, "msg" => "Edit User Successed."]);
		}
		catch (Exception $e) {
			return response()->json($e->getMessage());
		}
	}


	public function add_stud (Request $request) 
	{
		if ($request->isMethod('post')){
			return $this->proc_add_stud($request);
		}

		$programs = DB::table('programs')->get();

		$view_params = [
			"programs" => $programs,
		];

		$view_temp = view('admin.manage-users.student.detail-stud', $view_params)->render();

		return response()->json(["success" => true, "msg" => "...", "view_temp" => $view_temp]);
	}	


	private function proc_add_stud($request)
	{
		if (
			empty($request->ufname) OR empty($request->snomat) OR empty($request->snokp) OR empty($request->prgid) OR 
			empty($request->usrname) OR empty($request->upsswd) OR empty($request->ucnfpsswd)
		){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_fullname = strtoupper($request->ufname);
		$stud_nomat = preg_replace("/[^0-9A-Za-z]/", "", $request->snomat);
		$stud_nokp = preg_replace("/[^0-9]/", "", $request->snokp);
		$prog_id = $request->prgid;
		$user_email = (!empty($request->uemail)) ? $request->uemail : null;
		$user_nophone = (!empty($request->unophone)) ? $request->unophone : null;

		// check no. matric
		if ($check_nomat = DB::table('students')->where('stud_nomat', $stud_nomat)->first()){
			return response()->json(["success" => false, "msg" => "That No. Matric already exist.", "target_reset" => "#snomat"]);
		}

		// check no. mykad
		if ($check_nokp = DB::table('students')->where('stud_nokp', $stud_nokp)->first()){
			return response()->json(["success" => false, "msg" => "That No. MyKad already exist.", "target_reset" => "#snokp"]);
		}

		// check user email 
		if ($user_email) {
			// check format email if not empty
			if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
				return response()->json(["success" => false, "msg" => "Please enter a valid format of Email.", "target_reset" => "#uemail"]);
			}
			// check user_email available or not
			if ( $check_uemail = DB::table('users')->where("user_email", $user_email)->first()){
				return response()->json(["success" => false, "msg" => "That Email already Exist.", "target_reset" => "#uemail"]);
			}
		}

		// check user_nophone 
		if ($user_nophone) {
			// check format nophone if not empty
			if (!is_numeric($user_nophone)){
				return response()->json(["success" => false, "msg" => "Please enter a valid format of No. Phone.", "target_reset" => "#unophone"]);
			}
			// check user_nophone available or not
			if ( $check_uemail = DB::table('users')->where("user_email", $user_email)->first()){
				return response()->json(["success" => false, "msg" => "That No. Phone already Exist.", "target_reset" => "#unophone"]);
			}
		}

		$user_name = $request->usrname;

		// check format user name 
		if (preg_match("/[^a-z\-A-Z\-0-9]/i", $user_name)){
			return response()->json(["success" => false, "msg" => "Please enter a valid format of User ID.", "target_reset" => "#usrname"]);
		}

		// check whether user_name available or not 
		if ($check_usrname = DB::table('users')->where('user_name', $user_name)->first()){
			return response()->json(["success" => false, "msg" => "That User ID already exist.", "target_reset" => "#usrname"]);
		}

		$password = $request->upsswd;
		$conf_password = $request->ucnfpsswd;

		// check length password up to 8 char 
		/*
		if (strlen($password) < 8) {
			return response()->json(["success" => false, "msg" => "Password must be at least 8 characters.", "target_reset" => "#upsswd, #ucnfpsswd"]);
		}
		 */

		// check confirmation of password
		if ($conf_password <> $password){
			return response()->json(["success" => false, "msg" => "Confirm Password Is Not Match.", "target_reset" => "#upsswd, #ucnfpsswd"]);
		}

		// hashed password
		$hash_password = Hash::make($password);

		$sess_user_id = session('sess_user_id');

		DB::beginTransaction();

		try {
			$param_user_stud = [
				"user_fullname" => $user_fullname, 
				"user_name" => $user_name,
				"password" => $hash_password,
				"level_id" => "STUD",
				"user_status" => "PENDING",
				"user_email" => $user_email,
				"user_nophone" => $user_nophone,
				"created_by" => $sess_user_id,
				"updated_by" => $sess_user_id,
			];

			if (! $add_user_stud = User::create($param_user_stud)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Add User Student Failed."]);
			}

			$stud_user_id = $add_user_stud->user_id;

			$param_stud = [
				'user_id' => $stud_user_id,
				'stud_fullname' => $user_fullname,
				'stud_nomat' => $stud_nomat,
				'stud_nokp' => $stud_nokp,
				'prog_id' => $prog_id,
				"created_by" => $sess_user_id,
				"updated_by" => $sess_user_id,
			];

			if (! $add_stud = Student::create($param_stud)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Add Student Failed."]);
			}

			DB::commit();

			return response()->json(["success" => true, "msg" => "Add Student Successful."]);
		}
		catch (Exception $e) {
			return response()->json($e->getMessage());
		}
	}


	public function edit_stud (Request $request) 
	{
		if ($request->isMethod('post')){
			return $this->proc_edit_stud($request);
		}

		if (empty($request->user_id)){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_id = $request->user_id;

		if (
			! $check_stud = DB::table('users as usr')
					->selectRaw("usr.*, stu.*, usr.user_id")
					->join('students as stu', 'stu.user_id', 'usr.user_id')
					->where('usr.user_id', $user_id)
					->first()
		){
			return response()->json(["success" => false, "msg" => "User Record Not Found."]);
		}

		$programs = DB::table('programs')->get();

		$user_statuses = DB::table('user_statuses')->get();

		$view_params = [
			'stud' => $check_stud,
			'programs' => $programs,
			'user_statuses' => $user_statuses,
		];

		$view_temp = view('admin.manage-users.student.detail-stud', $view_params)->render();

		return response()->json(["success" => true, "msg" => "...", "view_temp" => $view_temp]);
	}	


	private function proc_edit_stud($request)
	{
		if (
			empty($request->usrid) OR empty($request->ufname) OR empty($request->snomat) OR empty($request->snokp) OR empty($request->prgid) OR empty($request->ustatus) 
		){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_id = $request->usrid;
		$user_fullname = strtoupper($request->ufname);
		$stud_nomat = preg_replace("/[^0-9A-Za-z]/", "", $request->snomat);
		$stud_nokp = preg_replace("/[^0-9]/", "", $request->snokp);
		$prog_id = $request->prgid;
		$user_email = (!empty($request->uemail)) ? $request->uemail : null;
		$user_nophone = (!empty($request->unophone)) ? $request->unophone : null;
		$user_status = $request->ustatus;

		// check user
		if (! $check_user = DB::table('users')->where('user_id', $user_id)->first()){
			return response()->json(["success" => false, "msg" => "User Record Not Found."]);
		}

		// check no. matric
		if ($check_nomat = DB::table('students')->whereRaw("user_id <> $user_id AND stud_nomat = '$stud_nomat'")->first()){
			return response()->json(["success" => false, "msg" => "That No. Matric already exist.", "target_reset" => "#snomat"]);
		}

		// check no. mykad
		if ($check_nokp = DB::table('students')->whereRaw("user_id <> $user_id AND stud_nokp = '$stud_nokp'")->first()){
			return response()->json(["success" => false, "msg" => "That No. MyKad already exist.", "target_reset" => "#snokp"]);
		}

		// check user_email
		if ($user_email) {
			// check format email if not empty
			if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
				return response()->json(["success" => false, "msg" => "Please enter a valid format of Email.", "target_reset" => "#uemail"]);
			}
			// check user_email available or not
			if ( $check_uemail = DB::table('users')->whereRaw("user_id <> $user_id AND user_email = '$user_email'")->first()){
				return response()->json(["success" => false, "msg" => "That Email already Exist.", "target_reset" => "#uemail"]);
			}
		}

		// check user_nophone
		if ($user_nophone) {
			// check format nophone if not empty
			if (!is_numeric($user_nophone)){
				return response()->json(["success" => false, "msg" => "Please enter a valid format of No. Phone.", "target_reset" => "#unophone"]);
			}
			// check user_nophone available or not
			if ( $check_uemail = DB::table('users')->whereRaw("user_id <> $user_id AND user_email = '$user_email'")->first()){
				return response()->json(["success" => false, "msg" => "That No. Phone already Exist.", "target_reset" => "#unophone"]);
			}
		}

		$sess_user_id = session('sess_user_id');

		DB::beginTransaction();

		try {
			$param_user_stud = [
				"user_fullname" => $user_fullname, 
				"user_status" => $user_status,
				"user_email" => $user_email,
				"user_nophone" => $user_nophone,
				"updated_by" => $sess_user_id,
			];

			if (! $edit_user_stud = User::where('user_id', $user_id)->update($param_user_stud)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Edit User Student Failed."]);
			}

			$param_stud = [
				'stud_fullname' => $user_fullname,
				'stud_nomat' => $stud_nomat,
				'stud_nokp' => $stud_nokp,
				'prog_id' => $prog_id,
				"updated_by" => $sess_user_id,
			];

			if (! $edit_stud = Student::where('user_id', $user_id)->update($param_stud)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Edit Student Failed."]);
			}

			DB::commit();

			return response()->json(["success" => true, "msg" => "Edit Student Successful."]);
		}
		catch (Exception $e) {
			return response()->json($e->getMessage());
		}
	}


	public function change_password_user(Request $request) 
	{
		if ($request->isMethod('post')){
			return $this->proc_change_password_user($request);
		}

		if (empty($request->user_id)){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_id = $request->user_id;

		if (! $check_uid = DB::table('users')->where('user_id', $user_id)->first()){
			return response()->json(["success" => false, "msg" => "User Record Not Founded!"]);
		}

		if ($check_uid->level_id == "STUD")
			$user_role = "student";
		else 
			$user_role = "staff";

		$view_params = [
			"user_id" => $user_id,
			"user_role" => $user_role,
		];

		$view_temp = view('admin.manage-users.change-password-user', $view_params)->render();

		return response()->json(["success" => true, "msg" => "...", "view_temp" => $view_temp]);
	}


	private function proc_change_password_user($request)
	{
		if (empty($request->usrid) OR empty($request->newpsswd) OR empty($request->confpsswd)){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_id = $request->usrid;
		$new_psswd = $request->newpsswd;
		$conf_psswd = $request->confpsswd;
		$sess_user_id = session('sess_user_id');

		if (!$check_user = DB::table('users')->where('user_id', $user_id)->first()){
			return response()->json(["success" => false, "msg" => "Invalid Informations."]);
		}

		if ($conf_psswd <> $new_psswd){
			return response()->json(["success" => false, "msg" => "Your Confirm Password Is Not Match."]);
		}

		$hash_psswd = Hash::make($conf_psswd);

		DB::beginTransaction();

		try {
			$psswd_param = [
				'password' => $hash_psswd,
				'updated_by' => $sess_user_id,
			];

			if (!$chg_psswd = User::where('user_id', $user_id)->update($psswd_param)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Change Password User Failed."]);
			}

			DB::commit(); // commit those transactions ...

			return response()->json(["success" => true, "msg" => "Change Password User Successful."]);
		}
		catch (Exception $e){
			return response()->json($e->getMessage());
		}
	}


	public function delete_user(Request $request)
	{
		if (empty($request->usrid)) {
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$user_id = $request->usrid;

		if (!$check_user = DB::table('users')->where('user_id', $user_id)->first()){
			return response()->json(["success" => false, "msg" => "Invalid Record Of User."]);
		}

		DB::beginTransaction();

		try {
			if (! $del_user = User::where('user_id', $user_id)->delete()) {
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Delete User Failed."]);
			}

			if ($check_user->level_id == "STUD") {
				if (! $del_stud = Student::where('user_id', $user_id)->delete()){
					DB::rollBack();
					return response()->json(["success" => false, "msg" => "Delete Student Failed."]);
				}
			}

			DB::commit();

			return response()->json(["success" => true, "msg" => "Delete User Successful."]);
		}
		catch (Exception $e){
			return response()->json($e->getMessage());
		}
	}


}
