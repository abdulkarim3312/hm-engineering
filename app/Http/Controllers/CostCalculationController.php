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
use App\Models\BrickSolingConfigure;
use App\Models\BricksSoling;
use App\Models\ColumnCofigure;
use App\Models\GlassConfigure;
use App\Models\CommonConfigure;
use App\Models\EarthWork;
use App\Models\EstimateProductType;
use App\Models\ExtraCosting;
use App\Models\FootingConfigure;
use App\Models\GradeBeamConfigure;
use App\Models\GrillGlassTilesConfigure;
use App\Models\MatConfigure;
use App\Models\PaintConfigure;
use App\Models\PileConfigure;
use App\Models\MobilizationWork;
use App\Models\PileCapConfigure;
use App\Models\PlasterConfigure;
use App\Models\PlasterConfigureProduct;
use App\Models\ReturningWallConfigure;
use App\Models\SegmentConfigure;
use App\Models\TilesConfigure;
use App\Models\EarthWorkConfigure;
use App\Models\SandFilling;
use App\Models\SandFillingConfigure;
use App\Models\ShortColumnConfigure;
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
        $gradeBeamConfigures = [];
        $columnConfigures = [];
        $shortColumnConfigures = [];
        $footingConfigures = [];
        $commonConfigures = [];
        $pileCapConfigures = [];
        $matConfigures = [];
        $returningConfigures = [];
        $bricksConfigures = [];
        $plasterConfigures = [];
        $grillGlassTilesConfigures = [];
        $paintConfigures = [];
        $earthWorkConfigures = [];
        $glassConfigures = [];
        $tilesConfigures = [];
        $sandFillings = [];
        $bricksSolings = [];
        $extraCostings = [];
        $mobilizations = [];

        if ($request->project){
            $projectName = EstimateProject::where('id',$request->project)->first();
            $pileConfigures = PileConfigure::where('estimate_project_id',$request->project)
                ->with('pileConfigureProducts')
                ->get();

            $beamConfigures = BeamConfigure::where('estimate_project_id',$request->project)
                ->with('beamConfigureProducts')
                ->get();
            $gradeBeamConfigures = GradeBeamConfigure::where('estimate_project_id',$request->project)
                ->with('gradeBeamConfigureProducts')
                ->get();

            $columnConfigures = ColumnCofigure::where('estimate_project_id',$request->project)
                ->with('columnConfigureProducts')
                ->get();
            $shortColumnConfigures = ShortColumnConfigure::where('estimate_project_id',$request->project)
                ->with('shortColumnConfigureProducts')
                ->get();

            $footingConfigures = FootingConfigure::where('estimate_project_id',$request->project)
                ->with('footingConfigureProducts')
                ->get();

            $commonConfigures = CommonConfigure::where('estimate_project_id',$request->project)
                ->with('commonConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $pileCapConfigures = PileCapConfigure::where('estimate_project_id',$request->project)
                ->with('pileCapConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $matConfigures = MatConfigure::where('estimate_project_id',$request->project)
                ->with('matConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $returningConfigures = ReturningWallConfigure::where('estimate_project_id',$request->project)
                ->with('returningWallConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $bricksConfigures = BricksConfigure::where('estimate_project_id',$request->project)
                ->with('bricksConfigureProducts','estimateFloor','estimateFloorUnit','unitSection')
                ->get();

            $bricksConfigureProducts = BricksConfigureProduct::where('estimate_project_id',$request->project)
                ->pluck('id');

            $plasterConfigures = PlasterConfigure::where('estimate_project_id',$request->project)
            ->get();

            // $plasterConfigures = PlasterConfigure::whereIn('id',$plasterConfigures)->get();

            $grillGlassTilesConfigures = GrillGlassTilesConfigure::where('estimate_project_id',$request->project)
                ->get();

            $glassConfigures = GlassConfigure::where('estimate_project_id',$request->project)
            ->get();

            $tilesConfigures = TilesConfigure::where('estimate_project_id',$request->project)
            ->get();

            $sandFillings = SandFilling::where('estimate_project_id',$request->project)
            ->get();
            $bricksSolings = BricksSoling::where('estimate_project_id',$request->project)
            ->get();

            $paintConfigures = PaintConfigure::where('estimate_project_id',$request->project)
                ->get();

            $earthWorkConfigures = EarthWork::where('estimate_project_id',$request->project)
                ->get();

            $extraCostings = ExtraCosting::where('estimate_project_id',$request->project)
                ->get();

            $mobilizations = MobilizationWork::where('mobilization_project_id',$request->project)
                ->get();

        }
        return view('estimate.report.estimate_report',
            compact(
            'projects','pileCapConfigures', 'matConfigures','glassConfigures',
            'projectName','pileConfigures', 'gradeBeamConfigures','bricksSolings',
            'beamConfigures','columnConfigures', 'shortColumnConfigures','mobilizations',
            'commonConfigures','bricksConfigures','footingConfigures', 'extraCostings',
            'plasterConfigures','grillGlassTilesConfigures', 'tilesConfigures',
            'paintConfigures','earthWorkConfigures', 'returningConfigures','sandFillings'
        ));
    }
   public function costingReport(Request $request){
        $projects = EstimateProject::where('status',1)->get();

        $projectName = '';
        $pileConfigures = [];
        $beamConfigures = [];
        $gradeBeamConfigures = [];
        $columnConfigures = [];
        $shortColumnConfigures = [];
        $footingConfigures = [];
        $commonConfigures = [];
        $pileCapConfigures = [];
        $matConfigures = [];
        $returningConfigures = [];
        $glassConfigures = [];
        $tilesConfigures = [];
        $bricksConfigures = [];
        $plasterConfigures = [];
        $grillGlassTilesConfigures = [];
        $paintConfigures = [];
        $earthWorkConfigures = [];
        $sandFillings = [];
        $bricksSolings = [];
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

            $gradeBeamConfigures = GradeBeamConfigure::where('estimate_project_id',$request->project)
                ->with('gradeBeamConfigureProducts')
                ->get();

            $columnConfigures = ColumnCofigure::where('estimate_project_id',$request->project)
                ->with('columnConfigureProducts')
                ->get();

            $shortColumnConfigures = ShortColumnConfigure::where('estimate_project_id',$request->project)
                ->with('shortColumnConfigureProducts')
                ->get();

            $footingConfigures = FootingConfigure::where('estimate_project_id',$request->project)
            ->with('footingConfigureProducts')
            ->get();

            $commonConfigures = CommonConfigure::where('estimate_project_id',$request->project)
                ->with('commonConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $pileCapConfigures = PileCapConfigure::where('estimate_project_id',$request->project)
            ->with('pileCapConfigureProducts','costingSegment')
            ->orderBy('costing_segment_id')
            ->get();

            $matConfigures = MatConfigure::where('estimate_project_id',$request->project)
                ->with('matConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $returningConfigures = ReturningWallConfigure::where('estimate_project_id',$request->project)
                ->with('returningWallConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $glassConfigures = GlassConfigure::where('estimate_project_id',$request->project)
            ->get();

            $tilesConfigures = TilesConfigure::where('estimate_project_id',$request->project)
            ->get();

            $sandFillings = SandFillingConfigure::where('estimate_project_id',$request->project)
            ->get();
            $bricksSolings = BrickSolingConfigure::where('estimate_project_id',$request->project)
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
                'projects','shortColumnConfigures', 'pileCapConfigures',
                'projectName','pileConfigures','gradeBeamConfigures',
                'beamConfigures','columnConfigures', 'footingConfigures',
                'commonConfigures','bricksConfigures', 'matConfigures', 'bricksSolings',
                'plasterConfigures','grillGlassTilesConfigures','glassConfigures',
                'paintConfigures','earthWorkConfigures','returningConfigures', 'sandFillings',
                'extraCostingConfigures','mobilizationWorkConfigures', 'tilesConfigures',

            ));
    }
    public function estimationCostingSummary(Request $request){
        $projects = EstimateProject::where('status',1)->get();

        $projectName = '';
        $pileConfigures = [];
        $beamConfigures = [];
        $gradeBeamConfigures = [];
        $columnConfigures = [];
        $shortColumnConfigures = [];
        $footingConfigures = [];
        $pileCapConfigures = [];
        $matConfigures = [];
        $returningWallConfigures = [];
        $returningWallConfigures = [];
        $commonConfigures = [];
        $bricksConfigures = [];
        $plasterConfigures = [];
        $grillGlassTilesConfigures = [];
        $paintConfigures = [];
        $earthWorkConfigures = [];
        $sandFilling = [];
        $bricksSoling = [];
        $extraCostingConfigures = [];
        $grillConfigures = [];
        $glassConfigures = [];
        $tilesConfigures = [];
        $mobilization = [];


        if ($request->project){
            $projectName = EstimateProject::where('id',$request->project)->first();
            $pileConfigures = PileConfigure::where('estimate_project_id',$request->project)
                ->with('pileConfigureProducts')
                ->get();

            $beamConfigures = BeamConfigure::where('estimate_project_id',$request->project)
                ->with('beamConfigureProducts')
                ->get();

            $gradeBeamConfigures = GradeBeamConfigure::where('estimate_project_id',$request->project)
                ->with('gradeBeamConfigureProducts')
                ->get();

            $columnConfigures = ColumnCofigure::where('estimate_project_id',$request->project)
                ->with('columnConfigureProducts')
                ->get();
            $shortColumnConfigures = ShortColumnConfigure::where('estimate_project_id',$request->project)
                ->with('shortColumnConfigureProducts')
                ->get();
                // dd($shortColumnConfigures);
            $footingConfigures = FootingConfigure::where('estimate_project_id',$request->project)
                ->with('footingConfigureProducts')
                ->get();

            $commonConfigures = CommonConfigure::where('estimate_project_id',$request->project)
                ->with('commonConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $pileCapConfigures = PileCapConfigure::where('estimate_project_id',$request->project)
                ->with('pileCapConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();
            $matConfigures = MatConfigure::where('estimate_project_id',$request->project)
                ->with('matConfigureProducts','costingSegment')
                ->orderBy('costing_segment_id')
                ->get();

            $returningWallConfigures = ReturningWallConfigure::where('estimate_project_id',$request->project)
                ->with('returningWallConfigureProducts','costingSegment')
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
            $grillConfigures = GrillGlassTilesConfigure::where('estimate_project_id',$request->project)
                ->get();

            $glassConfigures = GlassConfigure::where('estimate_project_id',$request->project)
                ->get();

            $tilesConfigures = TilesConfigure::where('estimate_project_id',$request->project)
                ->get();

            $paintConfigures = PaintConfigure::where('estimate_project_id',$request->project)
                ->get();

            $earthWorkConfigures = EarthWorkConfigure::where('estimate_project_id',$request->project)
                ->orderBy('id')
                ->get();
            $sandFilling = SandFillingConfigure::where('estimate_project_id',$request->project)
            ->get();
            $bricksSoling = BrickSolingConfigure::where('estimate_project_id',$request->project)
            ->get();

            $mobilization = MobilizationWork::where('mobilization_project_id',$request->project)
            ->first();
            // dd($mobilization);
            $extraCostingConfigures = ExtraCosting::where('estimate_project_id',$request->project)
                ->first();

        }

        return view('estimate.report.estimation_costing_summary',
            compact(
                'projects','bricksSoling', 'mobilization',
                'projectName','pileConfigures','sandFilling',
                'beamConfigures','columnConfigures',
                'commonConfigures','bricksConfigures',
                'plasterConfigures','grillGlassTilesConfigures',
                'paintConfigures','earthWorkConfigures',
                'extraCostingConfigures','grillConfigures',
                'tilesConfigures','glassConfigures', 'gradeBeamConfigures',
                'footingConfigures', 'pileCapConfigures','matConfigures',
                'returningWallConfigures','shortColumnConfigures'
            ));
    }

}
