<?php

namespace App\Http\Controllers;

use App\Model\Project;
use Illuminate\Http\Request;

class CementConcreteCalculationController extends Controller
{
    public function index(){
        return view('engineering_calculation.cement_calculation.all');
    }
    public function add(){
        $projects = Project::where('status',1)->get();
        return view('engineering_calculation.cement_calculation.add',compact('projects'));
    }
}
