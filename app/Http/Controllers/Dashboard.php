<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
	public function index()
	{
		$sess_level_id = session('sess_level_id');

		return view("dashboard.index");
	}


}
