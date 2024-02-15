<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
	public function index()
	{
		$sess_level_id = session('sess_level_id');

		$dir_level = strtolower($sess_level_id);

		$dashboard_url = "dashboard.$dir_level.index";

		return view($dashboard_url);
	}


	public function edit_profile(Request $request)
	{
		if ($request->isMethod('post')) {
			return $this->proc_edit_profile($request);
		}

		if (empty($request->user_id)) {
			abort('500', 'Not Enough Informations.');
		}
		else if (
			!$check_user = DB::table('users as usr')
				->select('usr.*', 'lvl.*', 'usr.level_id', 'usr.user_id', 'std.*', 'std.prog_id', 'prg.*')
				->join('levels as lvl', 'lvl.level_id', 'usr.level_id')
				->leftJoin('students as std', 'std.user_id', 'usr.user_id')
				->leftJoin('programs as prg', 'prg.prog_id', 'std.prog_id')
				->where('usr.user_id', $request->user_id)
				->first()
		)
		{
			abort('404', 'No Record Founded.');
		}
		else 
		{
			return view('dashboard.edit-profile', [
				'user' => $check_user,
			]);
		}
	}


	private function proc_edit_profile($request)
	{
	}
}
