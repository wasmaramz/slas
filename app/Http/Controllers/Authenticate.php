<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Authenticate extends Controller
{
	public function login(Request $request)
	{
		// check request type
		if ($request->isMethod('POST')){
			return $this->process_login($request);
		}

		// got to login box 
		return view('authenticate.login');
	}


	private function process_login($request)
	{
		if (empty($request->username) OR empty($request->password)){
			return response()->json(["success" => false, "msg" => "No information."]);
		}
		
		$username = $request->username;
		$password = $request->password;

		$auth_cred = [
			'user_name' => $username,
			'password' => $password,
		];

		if (Auth::attempt($auth_cred)) {
			$user = Auth::user();

			if ($user->user_status == "INACTIVE") {
				Auth::logout();
				return response()->json(["success" => false, "msg" => "Login failed, Your accout has been disabled. Please contact the administrator for more informations."]);
			}

			if ($user->user_status == "PENDING") {
				Auth::logout();
				/* STILL IN DEVELOPMENT (MAYBE FOR 2ND PHASE)
					return response()->json(["success" => false, "msg" => "Login failed, Your need to verify your Email Address first.", "go_verify_email" => true]);
				 */
				return response()->json(["success" => false, "msg" => "Login failed, Your User Activation Still In Progress."]);
			}

			if (! $level = DB::table('levels')->where('level_id', $user->level_id)->first()){
				Auth::logout();
				return response()->json(["success" => false, "msg" => "Record of level not found."]);
			}

			$arr_session = [
				"sess_user_id" => $user->user_id,
				"sess_user_fullname" => $user->user_fullname,
				"sess_user_name" => $user->user_name,
				"sess_level_id" => $level->level_id,
				"sess_level_name" => $level->level_name,
				"sess_user_status" => $user->user_status,
				"sess_user_email" => $user->user_email,
				"sess_user_nophone" => $user->user_nophone,
			];

			if ($user->level_id == "STUD"){
				if (! $check_stud = DB::table('students')->where('user_id', $user->user_id)->first()){
					Auth::logout();
					return response()->json(["success" => false, "msg" => "Student Record Not Found."]);
				}
				$arr_session['sess_stud_id'] = $check_stud->stud_id;
				$arr_session['sess_prog_id'] = $check_stud->prog_id;
			}

			session()->regenerate();
			session($arr_session);

			return response()->json(["success" => true, "msg" => "Login Successed, Welcome $user->user_fullname!"]);
		}
		else{
			return response()->json(["success" => false, "msg" => "Login failed."]);
		}
	}


	public function logout(Request $request)
	{
		Auth::logout();
	 
		$request->session()->invalidate();
		$request->session()->flush();
	 
		return redirect('/login');
	}
}
