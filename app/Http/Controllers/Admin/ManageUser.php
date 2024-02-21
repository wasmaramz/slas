<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
		return response()->json(["success" => false, "msg" => "Proses Tambah Pengguna Staff."]);
	}


}
