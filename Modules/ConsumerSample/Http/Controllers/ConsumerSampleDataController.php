<?php

namespace Modules\ConsumerSample\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\Chemical\Repositories\Interfaces\StockRepositoryInterface;
use Modules\ConsumerSample\Entities\ConsumerSampleData;
use Modules\ConsumerSample\Entities\ConsumerSampleTest;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleDataRepositoryInterface;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleRepositoryInterface;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleTestRepositoryInterface;

class ConsumerSampleDataController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $consumerSampleDataRepository;
    protected $consumerSampleTestRepository;
    protected $consumerSampleRepository;
    protected $stockRepository;
    public function __construct(ConsumerSampleDataRepositoryInterface $consumerSampleDataRepository,
                                ConsumerSampleTestRepositoryInterface $consumerSampleTestRepository,
                                ConsumerSampleRepositoryInterface $consumerSampleRepository,
                                StockRepositoryInterface $stockRepository,)
    {
        $this->consumerSampleDataRepository = $consumerSampleDataRepository;
        $this->consumerSampleTestRepository = $consumerSampleTestRepository;
        $this->consumerSampleRepository = $consumerSampleRepository;
        $this->stockRepository = $stockRepository;
    }
    
    public function index()
    {
        return Inertia::render("Modules/ConsumerSampleData/ConsumerSampleDataManagement");
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->consumerSampleDataRepository->all($request));
    }
    public function fetchReleaseTableData(Request $request)
    {
        return response()->json($this->consumerSampleDataRepository->fetchReleaseTableData($request));
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $sampleData = ConsumerSampleData::where('testing_status','Ongoing')
            ->with('tests')
            ->get();
        return Inertia::render("Modules/ConsumerSampleData/ConsumerSampleDataCreate",['sampleData' => $sampleData]);
    }
    public function release()
    {
        return Inertia::render("Modules/ConsumerSampleData/ConsumerSampleDataReleaseManagement");
    }
    public function final()
    {
        return Inertia::render("Modules/ConsumerSampleData/ConsumerSampleDataFinalManagement");
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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = ConsumerSampleData::with('tests')->find($id);
        return Inertia::render("Modules/ConsumerSampleData/ConsumerSampleDataShow",['data' => $data,'sampleData' => null]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = ConsumerSampleData::with('tests')->find($id);
        $sampleData = ConsumerSampleData::with('tests')->get();
        return Inertia::render("Modules/ConsumerSampleData/ConsumerSampleDataCreate",['data' => $data,'sampleData' => $sampleData]);
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
            'sample_data_id' => 'required|exists:consumer_sample_data,id',
            'testing_status' => 'required|string',
            'tests' => 'required|array'
        ]);

        DB::beginTransaction();
        try{
            $sampleData = $this->consumerSampleDataRepository->find($request->sample_data_id);
            $sampleData->testing_status = $request->testing_status;
            $sampleData->save();

            foreach($request->tests as $test){
                $testRecord = ConsumerSampleTest::where('test', $test['name'])
                    ->where('consumer_sample_data_id', $sampleData->id)
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
                'redirect' => route('consumer.sample.data.test.index'),
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
            'sample_data_id' => 'required|exists:consumer_sample_data,id',
        ]);
        try{
        $sampleData = $this->consumerSampleDataRepository->find($request->sample_data_id);
        $sampleData->testing_status = 'Confirmed';
        $sampleData->save();
                    return response()->json([
                'success' => true,
                'message' => 'Test result Confirmed successfully.',
                'redirect' => route('consumer.sample.data.release.index'),
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
