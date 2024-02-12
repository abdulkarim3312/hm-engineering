<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutoCadTrainingController extends Controller
{
    public function index(){
        return view('auto_cad.index');
    }
    public function home(){
        return view('auto_cad.home');
    }
}
