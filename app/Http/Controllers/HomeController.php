<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function welcome()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('welcome');
    }
    
    public function index()
    {
        return view('home');
    }
}
