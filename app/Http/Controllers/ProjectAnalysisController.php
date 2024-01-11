<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\ProductSegment;
use App\Model\Project;
use App\Model\ProjectAnalysis;
use App\Model\ProjectAnalysisSegmentsDetailsLog;
use App\Model\ProjectSegmentDetail;
use App\Model\ProjectSegmentDetailsLog;
use App\Model\ProjectServiceDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SakibRahaman\DecimalToWords\DecimalToWords;

class ProjectAnalysisController extends Controller
{
    public function index()
    {
    }
    public function projectAnalysisListAll()
    {
        $projectAnalyses = ProjectAnalysis::all();
        return view('project_analysis.project_analysis', compact('projectAnalyses'));
    }

    public function add(Project $project)
    {
        $segments = ProductSegment::where('status', 1)->where('project_id', $project->id)->get();
        return view('project_analysis.add', compact('segments', 'project'));
    }

    public function addPost(Project $project, Request $request)
    {

        try {
            DB::beginTransaction(); // db transaction
            $this->validate($request, [
                'total_land' => 'required|numeric',
                'usable_land_percent' => 'required|numeric',
                'developer_percent' => 'required|numeric',
                'owner_percent' => 'required|numeric',
                'common_percentage' => 'required|numeric',
                'date' => 'required',
                'service_name.*' => 'required',
                'service_quantity.*' => 'required|numeric|min:.01',
                'service_unit_price.*' => 'required|numeric|min:0',
            ]);

            $totalSale = $request->sale_total;
            $totalCost = $request->total_cost;
            $totalProfit = $request->total_profit;

            $counter = 0;
            $total = 0;

            $projectAnalysis = new ProjectAnalysis();
            $projectAnalysis->project_id = $project->id;
            $projectAnalysis->total_land = $request->total_land;
            $projectAnalysis->total_land_sqe = $request->total_land_sqe;
            $projectAnalysis->usable_land_percent = $request->usable_land_percent;
            $projectAnalysis->total_usable_land = $request->total_usable_land;
            $projectAnalysis->developer_percent = $request->developer_percent;
            $projectAnalysis->developer_space = $request->developer_space;
            $projectAnalysis->owner_percent = $request->owner_percent;
            $projectAnalysis->owner_space = $request->owner_space;
            $projectAnalysis->date = $request->date;
            $projectAnalysis->developer_common_space = $request->developer_common_space;
            $projectAnalysis->common_percentage = $request->common_percentage;
            $projectAnalysis->sellable_land = $request->sellable_land;
            $projectAnalysis->sale_total = $request->sale_total;
            $projectAnalysis->total_cost = $request->total_cost;
            $projectAnalysis->total_profit = $request->total_profit;
            $projectAnalysis->save();

            if ($request->service_name) {

                foreach ($request->service_name as $key => $value) {

                    $projectServiceDetail = new ProjectServiceDetails();
                    $projectServiceDetail->project_id = $project->id;
                    $projectServiceDetail->project_analysis_id = $projectAnalysis->id;
                    $projectServiceDetail->service_name = $request->service_name[$key];
                    $projectServiceDetail->service_quantity = $request->service_quantity[$key];
                    $projectServiceDetail->service_unit_price = $request->service_unit_price[$key];
                    $projectServiceDetail->total = $request->service_quantity[$key] * $request->service_unit_price[$key];
                    $projectServiceDetail->save();
                }
            }

            $projectSegmentDetails = ProjectSegmentDetail::where('project_id', $project->id)
                ->where('status', 1)
                ->get();
            foreach ($projectSegmentDetails as $projectSegmentDetail) {

                $projectAnalysisSegmentDetailLogs = new ProjectAnalysisSegmentsDetailsLog();
                $projectAnalysisSegmentDetailLogs->project_id = $project->id;
                $projectAnalysisSegmentDetailLogs->project_analysis_id = $projectAnalysis->id;
                $projectAnalysisSegmentDetailLogs->product_segment_id = $projectSegmentDetail->product_segment_id;
                $projectAnalysisSegmentDetailLogs->project_segment_detail_id = $projectSegmentDetail->id;
                $projectAnalysisSegmentDetailLogs->purchase_product_id = $projectSegmentDetail->purchase_product_id;
                $projectAnalysisSegmentDetailLogs->name = $projectSegmentDetail->name;
                $projectAnalysisSegmentDetailLogs->unit = $projectSegmentDetail->unit;
                $projectAnalysisSegmentDetailLogs->quantity = $projectSegmentDetail->quantity;
                $projectAnalysisSegmentDetailLogs->unit_price = $projectSegmentDetail->unit_price;
                $projectAnalysisSegmentDetailLogs->total = $projectSegmentDetail->total;
                $projectAnalysisSegmentDetailLogs->date = $request->date;
                $projectAnalysisSegmentDetailLogs->save();
            }

            DB::commit();
            return redirect()->route('project.analysis.all')->with('message', 'Project Analysis data stored successfully.');
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function projectAnalysisDetails(ProjectAnalysis $projectAnalysis)
    {
        $projectAnalysisSegmentDetailLogs = ProjectAnalysisSegmentsDetailsLog::where('project_analysis_id', $projectAnalysis->id)
            ->get();
        $projectServiceDetails = ProjectServiceDetails::where('project_analysis_id', $projectAnalysis->id)
            ->get();
        $projectAnalysisDetailLogs = DB::table('project_analysis_segments_details_logs')
            ->where('project_analysis_id', $projectAnalysis->id)
            ->where('project_id', $projectAnalysis->project_id)
            ->get();
        $projectSegments = ProductSegment::where('project_id', $projectAnalysis->project_id)
            ->get();

        $totalCost = 0;
        $totalCostWord = '';
        foreach ($projectSegments as $segment){
            $segment->segmentDetails->sum('total');
            $totalCost+=$segment->segmentDetails->sum('total');
        }

        $projectAnalysis->amount_in_word = DecimalToWords::convert($totalCost, 'Taka',
            'Poisa');
        return view('project_analysis.project_analysis_details', compact('projectAnalysis', 'projectServiceDetails', 'projectSegments', 'projectAnalysisDetailLogs'));
    }

    public function projectAnalysisApprovedPost(Request $request){
       dd($request->all());
        // $rules = [
        //     'date' => 'required|date',
        //     'note' => 'nullable|string|max:255',
        // ];
        // $validator = Validator::make($request->all(), $rules);
        // dd($request->all());

    }
}
