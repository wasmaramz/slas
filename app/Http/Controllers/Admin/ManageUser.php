<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
			return $this->process_add_staff($request);
		}

		if (! $levels = DB::table('levels')->whereNotIn('level_id', ["SYS", "STUD"])->get()){
			return response()->json(["success" => false, "msg" => "Level Record Not Found."]);
		}

		$view_temp = view('admin.manage-users.staff.detail-staff', ["levels" => $levels])->render();

		return response()->json(["success" => true, "msg" => "...", "view_temp" => $view_temp]);
	}


	private function process_add_staff($request)
	{
		if (
			empty($request->ufname) OR empty($request->lvlid) OR 
			empty($request->usrname) OR empty($request->upsswd) OR empty($request->ucnfpsswd)
		){
			return response()->json(["success" => false, "msg" => "Not Enpugh Information."]);
		}

		$user_fullname = strtoupper($request->ufname);
		$level_id = $request->lvlid;
		$user_email = (!empty($request->uemail)) ? $request->uemail : null;
		$user_nophone = (!empty($request->unophone)) ? $request->unophone : null;

		// check format email if not empty
		if ($user_email AND !filter_var($user_email, FILTER_VALIDATE_EMAIL)){
			return response()->json(["success" => false, "msg" => "Please enter a valid format of Email.", "target_reset" => "#uemail"]);
		}

		// check format nophone if not empty
		if ($user_nophone AND !is_numeric($user_nophone)){
			return response()->json(["success" => false, "msg" => "Please enter a valid format of No. Phone.", "target_reset" => "#unophone"]);
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

		$view_params = [
			"staff" => $check_user,
			"levels" => $levels,
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

		// check format email if not empty
		if ($user_email AND !filter_var($user_email, FILTER_VALIDATE_EMAIL)){
			return response()->json(["success" => false, "msg" => "Please enter a valid format of Email.", "target_reset" => "#uemail"]);
		}

		// check format nophone if not empty
		if ($user_nophone AND !is_numeric($user_nophone)){
			return response()->json(["success" => false, "msg" => "Please enter a valid format of No. Phone.", "target_reset" => "#unophone"]);
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
