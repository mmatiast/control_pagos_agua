<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
	public function welcome()
	{
		return view('welcome');
	}
	public function any()
    {
    	return view('layouts.adminlte');
    }
}
