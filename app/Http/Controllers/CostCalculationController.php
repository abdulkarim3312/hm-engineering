<?php

namespace App\Http\Controllers;

use App\Model\Costing;
use App\Model\CostingType;
use App\Model\EstimateProject;
use App\Models\AssignSegment;
use App\Models\AssignSegmentItem;
use App\Models\AssignSegmentProduct;
use App\Models\BeamConfigure;
use App\Models\BricksConfigure;
use App\Models\BricksConfigureProduct;
use App\Models\ColumnCofigure;
use App\Models\CommonConfigure;
use App\Models\EarthWorkConfigure;
use App\Models\EstimateProductType;
use App\Models\ExtraCosting;
use App\Models\GrillGlassTilesConfigure;
use App\Models\PaintConfigure;
use App\Models\PileConfigure;
use App\Models\MobilizationWork;
use App\Models\PlasterConfigure;
use App\Models\PlasterConfigureProduct;
use App\Models\SegmentConfigure;
use Illuminate\Http\Request;

class CostCalculationController extends Controller
{
    public function costCalculation(Request $request){

        $estimate_product_types = $request->estimate_product_type;

        $projects = EstimateProject::where('status',1)->get();
        $estimateProducts = EstimateProductType::select('estimate_product_id')
            ->groupBy('estimate_product_id')
            ->get();

        $assignSegmentItems = [];
        $assignSegmentItemSingle = '';
        $projectName = '';

        if ($request->project != '' && $request->segment != ''){

            $projectName = EstimateProject::where('id',$request->project)->first();
            $assignSegments = AssignSegment::where('estimate_project_id',$request->project)
                ->pluck('id');

            $segment_configures = AssignSegmentItem::whereIn('assign_segment_id',$assignSegments)
                ->select('segment_configure_id')
                ->pluck('segment_configure_id');

            $segmentConfigures = SegmentConfigure::whereIn('id',$segment_configures)->get();

            foreach ($segmentConfigures as $segmentConfigure){
                if ($segmentConfigure->costingSegment->id == $request->segment ){
                    $assignSegmentItemSingle = AssignSegmentItem::where('segment_configure_id',$segmentConfigure->id)
                        ->where('estimate_project_id',$request->project)
                        ->first();
                }
            }

            $estimateProducts = EstimateProductType::whereIn('id',$request->estimate_product_type)
                ->get();

        }elseif ($request->project != ''){
            $projectName = EstimateProject::where('id',$request->project)->first();
            $assignSegments = AssignSegment::where('estimate_project_id',$request->project)
                ->pluck('id');

            $assignSegmentItems = AssignSegmentItem::whereIn('assign_segment_id',$assignSegments)->get();

            //$assignSegmentProducts = AssignSegmentProduct::whereIn('assign_segment_item_id',$assignSegmentItems)->get();

            $estimateProducts = EstimateProductType::whereIn('id',$request->estimate_product_type)
                ->get();

        }else{

        }

        return view('estimate.cost_calculation.create', compact('projects',
            'estimateProducts','assignSegmentItems','estimateProducts','projectName',
            'estimate_product_types','assignSegmentItemSingle'));

    }

    public function estimateReport(Request $request){
        $projects = EstimateProject::where('status',1)->get();

        $projectName = '';
        $pileConfigures = [];
        $beamConfigures = [];
        $columnConfigures = [];
        $commonConfigures = [];
        $bricksConfigures = [];
        $plasterConfigures = [];
        $grillGlassTilesConfigures = [];
        $paintConfigures = [];
        $earthWorkConfigures = [];

        if ($request->project){
            $projectName = EstimateProject::where('id',$request->project)->first();
            $pileConfigures = PileConfigure::where('estimate_project_id',$request->project)
                ->with('pileConfigureProducts')
                ->get();

            $beamConfigures = BeamConfigure::where('estimate_project_id',$request->project)
                ->with('beamConfigureProducts')
                ->get();

            $columnConfigures = ColumnCofigure::where('estimate_project_id',$request->project)
                ->with('columnConfigureProducts')
                ->get();

            $commonConfigures = CommonConfigure::where('estimate_project_id',$request->project)
                ->with('commonConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $bricksConfigures = BricksConfigure::where('estimate_project_id',$request->project)
                ->with('bricksConfigureProducts','estimateFloor','estimateFloorUnit','unitSection')
                ->get();

            $bricksConfigureProducts = BricksConfigureProduct::where('estimate_project_id',$request->project)
                ->pluck('id');

            $plasterConfigureIds = PlasterConfigureProduct::whereIn('bricks_configure_product_id',$bricksConfigureProducts)
            ->select('plaster_configure_id')
            ->groupBy('plaster_configure_id')
            ->get();

            $plasterConfigures = PlasterConfigure::whereIn('id',$plasterConfigureIds)->get();

            $grillGlassTilesConfigures = GrillGlassTilesConfigure::where('estimate_project_id',$request->project)
                ->get();

            $paintConfigures = PaintConfigure::where('estimate_project_id',$request->project)
                ->get();

            $earthWorkConfigures = EarthWorkConfigure::where('estimate_project_id',$request->project)
                ->get();

        }
        return view('estimate.report.estimate_report',
            compact(
                'projects',
            'projectName','pileConfigures',
                      'beamConfigures','columnConfigures',
                      'commonConfigures','bricksConfigures',
                      'plasterConfigures','grillGlassTilesConfigures',
                      'paintConfigures','earthWorkConfigures'
        ));
    }
   public function costingReport(Request $request){
        $projects = EstimateProject::where('status',1)->get();

        $projectName = '';
        $pileConfigures = [];
        $beamConfigures = [];
        $columnConfigures = [];
        $commonConfigures = [];
        $bricksConfigures = [];
        $plasterConfigures = [];
        $grillGlassTilesConfigures = [];
        $paintConfigures = [];
        $earthWorkConfigures = [];
        $extraCostingConfigures = [];
        $mobilizationWorkConfigures = [];

        if ($request->project){
            $projectName = EstimateProject::where('id',$request->project)->first();
            $pileConfigures = PileConfigure::where('estimate_project_id',$request->project)
                ->with('pileConfigureProducts')
                ->get();

            $beamConfigures = BeamConfigure::where('estimate_project_id',$request->project)
                ->with('beamConfigureProducts')
                ->get();

            $columnConfigures = ColumnCofigure::where('estimate_project_id',$request->project)
                ->with('columnConfigureProducts')
                ->get();

            $commonConfigures = CommonConfigure::where('estimate_project_id',$request->project)
                ->with('commonConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $bricksConfigures = BricksConfigure::where('estimate_project_id',$request->project)
                ->with('bricksConfigureProducts','estimateFloor','estimateFloorUnit','unitSection')
                ->get();

            $bricksConfigureProducts = BricksConfigureProduct::where('estimate_project_id',$request->project)
                ->pluck('id');

            $plasterConfigureIds = PlasterConfigureProduct::whereIn('bricks_configure_product_id',$bricksConfigureProducts)
                ->select('plaster_configure_id')
                ->groupBy('plaster_configure_id')
                ->get();

            $plasterConfigures = PlasterConfigure::whereIn('id',$plasterConfigureIds)->get();

            $grillGlassTilesConfigures = GrillGlassTilesConfigure::where('estimate_project_id',$request->project)
                ->get();

            $paintConfigures = PaintConfigure::where('estimate_project_id',$request->project)
                ->get();

            $earthWorkConfigures = EarthWorkConfigure::where('estimate_project_id',$request->project)
                ->get();

            $extraCostingConfigures = ExtraCosting::where('estimate_project_id',$request->project)
                ->get();
            $mobilizationWorkConfigures = MobilizationWork::where('mobilization_project_id',$request->project)
                ->get();

        }

        return view('estimate.report.new_costing_report',
            compact(
                'projects',
                'projectName','pileConfigures',
                'beamConfigures','columnConfigures',
                'commonConfigures','bricksConfigures',
                'plasterConfigures','grillGlassTilesConfigures',
                'paintConfigures','earthWorkConfigures',
                'extraCostingConfigures','mobilizationWorkConfigures'

            ));
    }
    public function estimationCostingSummary(Request $request){
        $projects = EstimateProject::where('status',1)->get();

        $projectName = '';
        $pileConfigures = [];
        $beamConfigures = [];
        $columnConfigures = [];
        $commonConfigures = [];
        $bricksConfigures = [];
        $plasterConfigures = [];
        $grillGlassTilesConfigures = [];
        $paintConfigures = [];
        $earthWorkConfigures = [];
        $extraCostingConfigures = [];
        $grillConfigures = [];
        $glassConfigures = [];
        $tilesConfigures = [];

        if ($request->project){
            $projectName = EstimateProject::where('id',$request->project)->first();
            $pileConfigures = PileConfigure::where('estimate_project_id',$request->project)
                ->with('pileConfigureProducts')
                ->get();

            $beamConfigures = BeamConfigure::where('estimate_project_id',$request->project)
                ->with('beamConfigureProducts')
                ->get();

            $columnConfigures = ColumnCofigure::where('estimate_project_id',$request->project)
                ->with('columnConfigureProducts')
                ->get();

            $commonConfigures = CommonConfigure::where('estimate_project_id',$request->project)
                ->with('commonConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();
            //dd($commonConfigures);

//            $commonConfigures = CommonConfigure::whereIn('costing_segment_id',$commonConfiguresId)
//                ->where('estimate_project_id',$request->project)
//                ->get();
//            dd($commonConfigures);

            $bricksConfigures = BricksConfigure::where('estimate_project_id',$request->project)
                ->with('bricksConfigureProducts','estimateFloor','estimateFloorUnit','unitSection')
                ->get();

            $bricksConfigureProducts = BricksConfigureProduct::where('estimate_project_id',$request->project)
                ->pluck('id');

            $plasterConfigureIds = PlasterConfigureProduct::whereIn('bricks_configure_product_id',$bricksConfigureProducts)
                ->select('plaster_configure_id')
                ->groupBy('plaster_configure_id')
                ->get();

            $plasterConfigures = PlasterConfigure::whereIn('id',$plasterConfigureIds)->get();

            $grillGlassTilesConfigures = GrillGlassTilesConfigure::where('estimate_project_id',$request->project)
                ->get();
            $grillConfigures = GrillGlassTilesConfigure::where('estimate_project_id',$request->project)
                ->where('configure_type',1)
                ->get();

            $glassConfigures = GrillGlassTilesConfigure::where('estimate_project_id',$request->project)
                ->where('configure_type',2)
                ->get();

            $tilesConfigures = GrillGlassTilesConfigure::where('estimate_project_id',$request->project)
                ->where('configure_type',3)
                ->get();

            $paintConfigures = PaintConfigure::where('estimate_project_id',$request->project)
                ->get();

            $earthWorkConfigures = EarthWorkConfigure::where('estimate_project_id',$request->project)
                ->get();

            $extraCostingConfigures = ExtraCosting::where('estimate_project_id',$request->project)
                ->get();

        }

        return view('estimate.report.estimation_costing_summary',
            compact(
                'projects',
                'projectName','pileConfigures',
                'beamConfigures','columnConfigures',
                'commonConfigures','bricksConfigures',
                'plasterConfigures','grillGlassTilesConfigures',
                'paintConfigures','earthWorkConfigures',
                'extraCostingConfigures','grillConfigures',
                'tilesConfigures','glassConfigures'

            ));
    }

}
