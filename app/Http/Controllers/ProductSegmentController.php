<?php

namespace App\Http\Controllers;


use App\Model\ProductSegment;
use App\Model\ProjectSegmentDetail;
use App\Model\ProjectSegmentDetailsLog;
use App\Model\PurchaseProduct;
use App\Model\Project;
use App\Model\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSegmentController extends Controller
{
    public function index() {
        $segments=ProductSegment::orderBy('project_id')->get();

        return view('purchase.segment.segment_all', compact('segments'));
    }

    public function add() {
        $projects= Project::where('status',1)->get();
        return view('purchase.segment.segment_add',compact('projects'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => [
                'required','max:255','string',
                Rule::unique('product_segments')
                    ->where('name', $request->name)
                    ->where('project_id', $request->project)
            ],
            'project' => 'required',
            'description' => 'nullable|string|max:255',
            'segment_percentage' => 'required|numeric|max:100|min:0',
            'total_unit' => 'required|numeric|min:0',
            'status' => 'required'
        ]);

        $totalProjectPercentage = ProductSegment::where('project_id',$request->project)->sum('segment_percentage');
        if($totalProjectPercentage + $request->segment_percentage>100){
            return redirect()->back()->withInput()->with('error','Total Project Percentage is more then 100');
        }

        $segment = new ProductSegment();
        $segment->name = $request->name;
        $segment->project_id = $request->project;
        $segment->description = $request->description;
        $segment->segment_percentage = $request->segment_percentage;
        $segment->total_unit = $request->total_unit;
        $segment->status = $request->status;
        $segment->save();

        return redirect()->route('segment')->with('message', 'Product Segment add successfully.');
    }

    public function edit(ProductSegment $segment) {

        $projects= Project::all();
        return view('purchase.segment.segment_edit', compact( 'segment','projects'));
    }

    public function editPost(ProductSegment $segment, Request $request) {
        $request->validate([
            'name' => [
                'required','max:255','string',
                Rule::unique('product_segments')
                    ->ignore($segment)
                    ->where('name', $request->name)
                    ->where('project_id', $request->project)
            ],
            'project' => 'required',
            'description' => 'nullable|string|max:255',
            'segment_percentage' => 'required|numeric|max:100|min:0',
            'total_unit' => 'required|numeric|min:0',
            'status' => 'required'
        ]);

        $segment->name = $request->name;
        $segment->description = $request->description;
        $segment->project_id = $request->project;
        $segment->segment_percentage = $request->segment_percentage;
        $segment->total_unit = $request->total_unit;
        $segment->status = $request->status;
        $segment->save();

        return redirect()->route('segment')->with('message', 'Product Segment edit successfully.');
    }

    public function addProductItem(ProductSegment $segment){

        return view('project_management.segment.add_item', compact( 'segment',));

    }

    public function addProductItemPost(Request $request, ProductSegment $segment){

        try{
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction
            $this->validate($request, [
                'product.*' => 'required',
                'quantity.*' => 'required|numeric|min:.01',
                'unit_price.*' => 'required|numeric|min:0',
            ]);
            $counter = 0;
            $total = 0;
            if ($request->product != ''){
                foreach ($request->product as $reqProduct)
                {
                    $product = PurchaseProduct::find($reqProduct);

                    $projectSegmentDetail =new ProjectSegmentDetail();
                    $projectSegmentDetail->product_segment_id = $segment->id;
                    $projectSegmentDetail->project_id = $segment->project_id;
                    $projectSegmentDetail->purchase_product_id = $product->id;
                    $projectSegmentDetail->name = $product->name;
                    $projectSegmentDetail->unit = $product->unit->name ?? '';
                    $projectSegmentDetail->quantity = $request->quantity[$counter];
                    $projectSegmentDetail->unit_price = $request->unit_price[$counter];
                    $projectSegmentDetail->total = $request->quantity[$counter] * $request->unit_price[$counter];
                    $projectSegmentDetail->save();

                    $projectSegmentDetailsLog = new ProjectSegmentDetailsLog();
                    $projectSegmentDetailsLog->product_segment_id = $segment->id;
                    $projectSegmentDetailsLog->project_segment_details_id = $projectSegmentDetail->id;
                    $projectSegmentDetailsLog->project_id = $segment->project_id;
                    $projectSegmentDetailsLog->purchase_product_id = $product->id;
                    $projectSegmentDetailsLog->name = $product->name;
                    $projectSegmentDetailsLog->unit = $product->unit->name ?? '';
                    $projectSegmentDetailsLog->quantity = $request->quantity[$counter];
                    $projectSegmentDetailsLog->unit_price = $request->unit_price[$counter];
                    $projectSegmentDetailsLog->total = $request->quantity[$counter] * $request->unit_price[$counter];
                    $projectSegmentDetailsLog->save();
                }
            }

            DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB
            return redirect()->route('project.segment.list',['project'=>$segment->project_id])->with('message','Segment Item Added Successfully');
        }
        catch(\Exception $e){

            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->with('message',$e->getMessage());
        }

    }

    public function productItemDetailsEdit(ProductSegment $segment){
        $purchaseProducts = PurchaseProduct::where('status', 1)->orderBy('name')->get();
        return view('project_management.segment.details_edit',compact('segment','purchaseProducts'));
    }

    public function productItemDetailsEditPost(Request $request,ProductSegment $segment) {
        try{
            DB::beginTransaction(); // Tell all the code beneath this is a transaction
            $this->validate($request, [
                'product.*' => 'required',
                'quantity.*' => 'required|numeric|min:.01',
                'unit_price.*' => 'required|numeric|min:0',
            ]);

            $counter = 0;
            $total = 0;
            if ($request->product != ''){
                $segmentDetails = ProjectSegmentDetail::where('product_segment_id',$segment->id);
                $segmentDetails->delete();

                foreach ($request->product as $reqProduct)
                {
                    $product = PurchaseProduct::find($reqProduct);

                    $projectSegmentDetail =new ProjectSegmentDetail();
                    $projectSegmentDetail->product_segment_id = $segment->id;
                    $projectSegmentDetail->project_id = $segment->project_id;
                    $projectSegmentDetail->purchase_product_id = $product->id;
                    $projectSegmentDetail->name = $product->name;
                    $projectSegmentDetail->unit = $product->unit->name ?? '';
                    $projectSegmentDetail->quantity = $request->quantity[$counter];
                    $projectSegmentDetail->unit_price = $request->unit_price[$counter];
                    $projectSegmentDetail->total = $request->quantity[$counter] * $request->unit_price[$counter];
                    $projectSegmentDetail->save();

                    //current time
                    $currentTime = Carbon::now();
                    $projectSegmentDetailsLog = new ProjectSegmentDetailsLog();
                    $projectSegmentDetailsLog->product_segment_id = $segment->id;
                    $projectSegmentDetailsLog->project_segment_details_id = $projectSegmentDetail->id;
                    $projectSegmentDetailsLog->project_id = $segment->project_id;
                    $projectSegmentDetailsLog->purchase_product_id = $product->id;
                    $projectSegmentDetailsLog->name = $product->name;
                    $projectSegmentDetailsLog->unit = $product->unit->name ?? '';
                    $projectSegmentDetailsLog->quantity = $request->quantity[$counter];
                    $projectSegmentDetailsLog->unit_price = $request->unit_price[$counter];
                    $projectSegmentDetailsLog->total = $request->quantity[$counter] * $request->unit_price[$counter];
                    $projectSegmentDetailsLog->note = 'Update project segment date is '.$currentTime->toDateTimeString();
                    $projectSegmentDetailsLog->save();
                }
            }

            DB::commit(); //  this transacion's all good and it can persist to DB
            return redirect()->route('project.segment.list',['project'=>$segment->project_id])->with('message','Segment Item Added Successfully');
        }
        catch(\Exception $e){

            DB::rollBack(); //  "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->with('message',$e->getMessage());
        }
    }

    public function productItemDetails(ProductSegment $segment){
        $projectSegmentDetails = ProjectSegmentDetail::where('product_segment_id',$segment->id)->get();
        return view('project_management.segment.details',compact('projectSegmentDetails','segment'));
    }

    public function getProjectSegment(Request $request) {

        $segments = ProductSegment::where('project_id', $request->projectId)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($segments);
    }
}
