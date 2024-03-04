<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Manual extends Controller
{
	public function leave_info()
	{
		$url_leave_info="/manual_doc/leave_form_kktm.pdf";

		return view('manual.iframe', ["source" => $url_leave_info]);
	}
}
