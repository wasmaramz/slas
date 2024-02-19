<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\Hash;

class Profile extends Controller
{
	public function edit_profile(Request $request)
	{
		if ($request->isMethod('post')) {
			return $this->proc_edit_profile($request);
		}

		$sess_user_id = session('sess_user_id');

	 	if ($sess_user_id == "") {
			abort("500", "Not Enough Information.");
		}
		else if (
			!$check_user = DB::table('users as usr')
				->selectRaw(
					"usr.user_id, usr.user_name, usr.level_id, lvl.level_name, usr.user_email, usr.user_nophone, " . 
					"std.stud_id, std.stud_fullname, std.stud_nomat, std.stud_nokp, std.prog_id, prg.prog_name"
				)
				->join('levels as lvl', 'lvl.level_id', 'usr.level_id')
				->leftJoin('students as std', 'std.user_id', 'usr.user_id')
				->leftJoin('programs as prg', 'prg.prog_id', 'std.prog_id')
				->where('usr.user_id', $sess_user_id)
				->first()
		)
		{
			abort('404', 'No Record Founded.');
		}
		else 
		{
			return view('profile.edit-profile', [
				'user' => $check_user,
			]);
		}
	}


	private function proc_edit_profile($request)
	{
		$user_nophone = ($request->nophone) ? $request->nophone : null;
		$sess_user_id = session('sess_user_id');

		if (!$check_user = DB::table('users')->where('user_id', $sess_user_id)->first()) {
			return response()->json(["success" => false, "msg" => "User Record Not Found."]);
		}

		// check student information 
		if ($check_user->level_id == "STUD"){
			/*
			if (empty($request->sfname) OR empty($request->snomat) || empty($request->snokp)){
				return response()->json(["success" => false, "msg" => "Not Enough Student Information"]);
			}
			$stud_fullname = $request->sfname;
			$stud_nomat = $request->snomat;
			$stud_nokp = preg_replace('/[^0-9]/', '', $request->snokp;
			if ($check_stud_nokp = DB::table('students')->where('stud_nokp', $stud_nokp)->first()){
				return response()->json(["success" => false, "msg" => "No. MyKad Already Exist."]);
			}
			 */
			if (!$check_stud = DB::table('students')->where('user_id', $sess_user_id)->first()) {
				return response()->json(["success" => false, "msg" => "Student Record Not Found."]);
			}
			$stud_id = $check_stud->stud_id;
		} 

		DB::beginTransaction();

		try {
			$user_params = [
				"user_nophone" => $user_nophone,
				"updated_by" => $sess_user_id,
			];
			if (!$upd_user = User::where('user_id', $sess_user_id)->update($user_params)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Update User Record Was Failed."]);
			}

			/*
			$stud_params = [
				"stud_fullname" => $stud_fullname,
				"stud_nomat" => $stud_nomat,
				"stud_nokp" => $stud_nokp,
				"updated_by" => $sess_user_id,
			];
			if (!$upd_stud = Student::where('stud_id', $stud_id)->update($stud_params)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Update Student Record Was Failed."]);
			}
			 */

			DB::commit(); //commit those transaction ...
			
			return response()->json(["success" => true, "msg" => "Edit Profile Was Successful."]);
		}
		catch (Exception $e){
			return response()->json($e->getMessage());
		}
	}


	public function change_password(Request $request)
	{
		if ($request->isMethod('post')){
			return $this->process_change_password($request);
		}

		return view('profile.change-password');
	} 


	private function process_change_password($request)
	{
		if (empty($request->currpsswd) OR empty($request->newpsswd) OR empty($request->confpsswd)){
			return response()->json(["success" => false, "msg" => "Not Enough Information."]);
		}

		$curr_psswd = $request->currpsswd;
		$new_psswd = $request->newpsswd;
		$conf_psswd = $request->confpsswd;
		$sess_user_id = session('sess_user_id');

		if (!$check_user = DB::table('users')->where('user_id', $sess_user_id)->first()){
			return response()->json(["success" => false, "msg" => "Invalid Informations."]);
		}

		if (!Hash::check($curr_psswd, $check_user->password)){
			return response()->json(["success" => false, "msg" => "Your Current Password Is Incorrect."]);
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

			if (!$chg_psswd = User::where('user_id', $sess_user_id)->update($psswd_param)){
				DB::rollBack();
				return response()->json(["success" => false, "msg" => "Update Password Failed."]);
			}

			DB::commit(); // commit those transactions ...

			return response()->json(["success" => true, "msg" => "Change Password Successful."]);
		}
		catch (Exception $e){
			return response()->json($e->getMessage());
		}
	}


}
