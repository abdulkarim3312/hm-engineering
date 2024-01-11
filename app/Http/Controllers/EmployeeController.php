<?php

namespace App\Http\Controllers;

use App\Model\Warehouse;
use App\Models\EmployeeJobLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class EmployeeController extends Controller
{
    public function jobLetterIndex(){
        $jobLetter = EmployeeJobLetter::where('letter_type', 1)->get();
        return view('hr.job_letter.all', compact('jobLetter'));
    }
    public function jobLetterAdd(){
        return view('hr.job_letter.add');
    }
    public function jobLetterPost(Request $request){
        // dd($request->all());
        $request->validate([
            'letter_description' => 'required'
        ]);
        $letter = null;
        if ($request->job_letter_title) {
            $file = $request->file('job_letter_title');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/job_letter';
            $file->move($destinationPath, $filename);

            $letter = 'uploads/employee/job_letter/'.$filename;
        }
        $letter_content = new EmployeeJobLetter();
        $letter_content->job_letter_title = $letter;
        $letter_content->letter_type = $request->letter_type;
        $letter_content->letter_description = $request->letter_description;
        $letter_content->save();
        return redirect()->route('job_letter')->with('message', 'Job Letter added successfully.');
    }
    public function jobLetterEdit($id){
        $letter = EmployeeJobLetter::find($id);
        return view('hr.job_letter.edit', compact('letter'));
    }
    public function jobLetterEditPost(Request $request, $id){
        $request->validate([
            'letter_description' => 'required'
        ]);

        $letter = null;
        if ($request->job_letter_title) {
            $file = $request->file('job_letter_title');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/job_letter';
            $file->move($destinationPath, $filename);

            $letter = 'uploads/employee/job_letter/'.$filename;
        }
        $letter_content = EmployeeJobLetter::find($id);
        $letter_content->job_letter_title = $letter;
        $letter_content->letter_type = $request->letter_type;
        $letter_content->letter_description = $request->letter_description;
        $letter_content->save();
        return redirect()->route('job_letter')->with('message', 'Job Letter Updated successfully.');
    }

    public function jobLetterView($id){
        $letter = EmployeeJobLetter::find($id);
        return view('hr.job_letter.view', compact('letter'));
    }
    public function jobLetterPrint($id){
        $letter = EmployeeJobLetter::find($id);
        return view('hr.job_letter.print', compact('letter'));
    }

    public function jobConfirmLetterIndex(){
        $jobLetter = EmployeeJobLetter::where('letter_type', 2)->get();
        return view('hr.job_confirm_letter.all', compact('jobLetter'));
    }

    public function jobConfirmLetterAdd(){
        return view('hr.job_confirm_letter.add');
    }

    public function jobConfirmLetterPost(Request $request){
        // dd($request->all());
        $request->validate([
            'job_letter_title' => 'required',
            'letter_description' => 'required'
        ]);
        $letter = null;
        if ($request->job_letter_title) {
            $file = $request->file('job_letter_title');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/job_letter';
            $file->move($destinationPath, $filename);

            $letter = 'uploads/employee/job_letter/'.$filename;
        }
        $letter_content = new EmployeeJobLetter();
        $letter_content->job_letter_title = $letter;
        $letter_content->letter_type = $request->letter_type;
        $letter_content->letter_description = $request->letter_description;
        $letter_content->save();
        return redirect()->route('job_confirm_letter')->with('message', 'Job confirm Letter added successfully.');
    }

    public function jobConfirmLetterEdit($id){
        $letter = EmployeeJobLetter::find($id);
        return view('hr.job_confirm_letter.edit', compact('letter'));
    }

    public function jobConfirmLetterEditPost(Request $request, $id){
        // dd($request->all());

        $letter = null;
        if ($request->job_letter_title) {
            $file = $request->file('job_letter_title');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/job_confirm_letter';
            $file->move($destinationPath, $filename);

            $letter = 'uploads/employee/job_confirm_letter/'.$filename;
        }
        $letter_content = EmployeeJobLetter::find($id);
        $letter_content->job_letter_title = $letter;
        $letter_content->letter_type = $request->letter_type;
        $letter_content->letter_description = $request->letter_description;
        $letter_content->save();
        return redirect()->route('job_confirm_letter')->with('message', 'Job promotion Letter Updated successfully.');
    }

    public function jobConfirmLetterView($id){
        $letter = EmployeeJobLetter::find($id);
        return view('hr.job_confirm_letter.view', compact('letter'));
    }

    public function jobPromotionLetterIndex(){
        $jobLetter = EmployeeJobLetter::where('letter_type', 3)->get();
        return view('hr.job_promotion_letter.all', compact('jobLetter'));
    }

    public function jobPromotionLetterAdd(){
        return view('hr.job_promotion_letter.add');
    }

    public function jobPromotionLetterPost(Request $request){
        // dd($request->all());
        $request->validate([
            'job_letter_title' => 'required',
            'letter_description' => 'required'
        ]);
        $letter = null;
        if ($request->job_letter_title) {
            $file = $request->file('job_letter_title');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/job_promotion_letter';
            $file->move($destinationPath, $filename);

            $letter = 'uploads/employee/job_promotion_letter/'.$filename;
        }
        $letter_content = new EmployeeJobLetter();
        $letter_content->job_letter_title = $letter;
        $letter_content->letter_type = $request->letter_type;
        $letter_content->letter_description = $request->letter_description;
        $letter_content->save();
        return redirect()->route('job_promotion_letter')->with('message', 'Job promotion Letter added successfully.');
    }

    public function jobPromotionLetterEdit($id){
        $letter = EmployeeJobLetter::find($id);
        return view('hr.job_promotion_letter.edit', compact('letter'));
    }

    public function jobPromotionLetterEditPost(Request $request, $id){
        // dd($request->all());

        $letter = null;
        if ($request->job_letter_title) {
            $file = $request->file('job_letter_title');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/job_letter';
            $file->move($destinationPath, $filename);

            $letter = 'uploads/employee/job_letter/'.$filename;
        }
        $letter_content = EmployeeJobLetter::find($id);
        $letter_content->job_letter_title = $letter;
        $letter_content->letter_type = $request->letter_type;
        $letter_content->letter_description = $request->letter_description;
        $letter_content->save();
        return redirect()->route('job_promotion_letter')->with('message', 'Job promotion Letter Updated successfully.');
    }

    public function jobPromotionLetterView($id){
        $letter = EmployeeJobLetter::find($id);
        return view('hr.job_promotion_letter.view', compact('letter'));
    }
}
