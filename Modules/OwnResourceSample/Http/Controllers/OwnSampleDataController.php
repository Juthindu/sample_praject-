<?php

namespace Modules\OwnResourceSample\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\Chemical\Repositories\Interfaces\StockRepositoryInterface;
use Modules\OwnResourceSample\Entities\OwnSampleData;
use Modules\OwnResourceSample\Entities\OwnSampleTest;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleDataRepositoryInterface;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleRepositoryInterface;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleTestRepositoryInterface;

class OwnSampleDataController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $ownSampleDataRepository;
    protected $ownSampleTestRepository;
    protected $ownSampleRepository;
    protected $stockRepository;
    public function __construct(OwnSampleDataRepositoryInterface $ownSampleDataRepository,
                                OwnSampleTestRepositoryInterface $ownSampleTestRepository,
                                OwnSampleRepositoryInterface $ownSampleRepository,
                                StockRepositoryInterface $stockRepository,)
    {
        $this->ownSampleDataRepository = $ownSampleDataRepository;
        $this->ownSampleTestRepository = $ownSampleTestRepository;
        $this->ownSampleRepository = $ownSampleRepository;
        $this->stockRepository = $stockRepository;
    }
    
    public function index()
    {
        return Inertia::render("Modules/OwnSampleData/OwnResourceSampleDataManagement");
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->ownSampleDataRepository->all($request));
    }
    public function fetchReleaseTableData(Request $request)
    {
        return response()->json($this->ownSampleDataRepository->fetchReleaseTableData($request));
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $sampleData = OwnSampleData::where('testing_status','Ongoing')
            ->with('tests')
            ->get();
        return Inertia::render("Modules/OwnSampleData/OwnSampleDataCreate",['sampleData' => $sampleData]);
    }
    public function release()
    {
        return Inertia::render("Modules/OwnSampleData/OwnSampleDataReleaseManagement");
    }
    public function final()
    {
        return Inertia::render("Modules/OwnSampleData/OwnSampleDataFinalManagement");
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource. attendan 
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = OwnSampleData::with('tests')->find($id);
        return Inertia::render("Modules/OwnSampleData/OwnSampleDataShow",['data' => $data,'sampleData' => null]);
    }
    public function finalShow($id)
    {
        $data = OwnSampleData::with('tests')->find($id);
        return Inertia::render("Modules/OwnSampleData/OwnSampleDataFinalShow",['data' => $data,'sampleData' => null]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = OwnSampleData::with('tests')->find($id);
        $sampleData = OwnSampleData::with('tests')->get();
        return Inertia::render("Modules/OwnSampleData/OwnSampleDataCreate",['data' => $data,'sampleData' => $sampleData]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $request->validate([
            'sample_data_id' => 'required|exists:own_sample_data,id',
            'testing_status' => 'required|string',
            'tests' => 'required|array'
        ]);

        DB::beginTransaction();
        try{
            $sampleData = $this->ownSampleDataRepository->find($request->sample_data_id);
            $sampleData->testing_status = $request->testing_status;
            $sampleData->save();

            foreach($request->tests as $test){
                $testRecord = OwnSampleTest::where('test', $test['name'])
                    ->where('own_sample_data_id', $sampleData->id)
                    ->first();

                    $oldTimes = $testRecord->times;
                    $testRecord->result = $test['result'];
                    $testRecord->times = $test['times'] ?? 0;
                    $testRecord->save();

                    $oldQuantity = $oldTimes*$test['quantity'];
                    $newQuantity = $test['times']*$test['quantity'];

                    $this->stockRepository->updateQuantity($test['name'],'Update',$newQuantity,$oldQuantity);
                    
                }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Result Updated successfully.',
                'redirect' => route('own.sample.data.index'),
            ]);

        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'sample_data_id' => 'required|exists:own_sample_data,id',
        ]);
        try{
        $sampleData = $this->ownSampleDataRepository->find($request->sample_data_id);
        $sampleData->testing_status = 'Confirmed';
        $sampleData->save();
                    return response()->json([
                'success' => true,
                'message' => 'Test result Confirmed successfully.',
                'redirect' => route('release.own.sample.data.index'),
            ]);

        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
