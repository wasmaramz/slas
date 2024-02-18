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


}
