<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Block;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	public function __construct(Request $request)
	{
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
	}

	public function home(Request $request)
	{
		$blocks = Block::orderBy('name', 'asc')->limit(50)->get();

		if( !isset($_COOKIE["MANAGERNAME"]) ) {
			return view("pick");
		}

		return view('checkin', ["name" => $_COOKIE["MANAGERNAME"], "blocks" => $blocks]);
	}

	public function submit(Request $request)
	{
		setcookie("MANAGERNAME", $request['name']);
		return response()->json(["success" => true, "message" => "Login success."]);
	}

	public function logout(Request $request)
	{
		unset($_COOKIE['MANAGERNAME']);
		setcookie('MANAGERNAME', null, -1, '/'); 
	}
}
