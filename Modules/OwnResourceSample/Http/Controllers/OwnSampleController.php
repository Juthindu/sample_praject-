<?php

namespace Modules\OwnResourceSample\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\District\Entities\District;
use Modules\Oic\Entities\Oic;
use Modules\OwnResourceSample\Entities\OwnSample;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleRepositoryInterface;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleDataRepositoryInterface;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleTestRepositoryInterface;
use Modules\Region\Entities\Region;

class OwnSampleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $ownSampleDataRepository;
    protected $ownSampleTestRepository;
    protected $ownSampleRepository;

    public function __construct(OwnSampleDataRepositoryInterface $ownSampleDataRepository,
                                OwnSampleTestRepositoryInterface $ownSampleTestRepository,
                                OwnSampleRepositoryInterface $ownSampleRepository,)
    {
        $this->ownSampleDataRepository = $ownSampleDataRepository;
        $this->ownSampleTestRepository = $ownSampleTestRepository;
        $this->ownSampleRepository = $ownSampleRepository;
    }
    public function index()
    {
        return Inertia::render("Modules/OwnResourceSample/OwnSampleManagement");
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->ownSampleRepository->all($request));
    }
    public function fetchFinalTableData(Request $request)
    {
        return response()->json($this->ownSampleRepository->fetchFinalTableData($request));
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // $owns = Own::get();
        return Inertia::render("Modules/OwnResourceSample/OwnSampleCreate"
        // ,['owns' => $owns]
    );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'required|exists:districts,id',
            'oic_id' => 'required|exists:oics,id',
            'tank_no' => 'required|string',
            'laboratory_no' => 'required',
            'sample_count' => 'required|integer',
            'samples' => 'required|array|min:1',
            'samples.*.reference_number' =>  'required|string',
            'samples.*.quantity' =>  'required',
            'samples.*.temperature' =>  'required',
            'samples.*.collected' =>  'required',
            'samples.*.weatherCondition' =>  'required',
        ]);
        $validator->validate();

        // try{
            DB::beginTransaction();
            $cSample = $this->ownSampleRepository->create([
                'date'=>$request->date,
                'region_id'=>$request->region_id,
                'district_id'=>$request->district_id,
                'oic_id'=>$request->oic_id,
                'tank_no'=>$request->tank_no,
                'laboratory_no'=>$request->laboratory_no,
                'sample_count'=>$request->sample_count,
            ]);

        $testGroups = [
            'physical' => ['colour', 'turbidity'],
            'bacteriological' => ['coliform', 'coli'],
            'chemical' => [
                'ph','electrical','chloride','alkalinity','nitrate',
                'nitrite','fluoride','phosphate','dissolvedSolid',
                'hardness','iron','sulphate','calcium','manganese'
            ]
        ];

        $allTests = array_keys([
            'physical'=>1,'colour'=>1,'turbidity'=>1,
            'bacteriological'=>1,'coliform'=>1,'coli'=>1,
            'chemical'=>1,'ph'=>1,'electrical'=>1,'chloride'=>1,
            'alkalinity'=>1,'nitrate'=>1,'nitrite'=>1,'fluoride'=>1,
            'phosphate'=>1,'dissolvedSolid'=>1,'hardness'=>1,
            'iron'=>1,'sulphate'=>1,'calcium'=>1,'manganese'=>1
        ]);

        foreach ($request->samples as $sample) {
            $sampleData = $this->ownSampleDataRepository->create([
                'own_sample_id'     => $cSample->id,
                'reference_number'  => $sample['reference_number'],
                'quantity'          => $sample['quantity'],
                'temperature'       => $sample['temperature'],
                'collected'         => $sample['collected'],
                'weather_condition' => $sample['weatherCondition'],
            ]);

            $insertedTests = [];

            foreach ($allTests as $testKey) {
                if (!isset($sample[$testKey]) || $sample[$testKey] != 1) continue;

                if (isset($testGroups[$testKey])) {
                    foreach ($testGroups[$testKey] as $subTest) {
                        if (in_array($subTest, $insertedTests)) continue;
                        $this->ownSampleTestRepository->create([
                            'own_sample_data_id' => $sampleData->id,
                            'test'   => $subTest,
                            'status' => 'pending',
                        ]);
                        $insertedTests[] = $subTest;
                    }
                } else {
                    if (in_array($testKey, $insertedTests)) continue;
                    $this->ownSampleTestRepository->create([
                        'own_sample_data_id' => $sampleData->id,
                        'test'   => $testKey,
                        'status' => 'pending',
                    ]);
                    $insertedTests[] = $testKey;
                }
            }
        }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Add Sample successfully.',
                'redirect' => route('own.resource.sample.index'),
            ]);

        // }catch(\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Something went wrong.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }

    public function payment(Request $request)
    {
        $request->validate([
            'own_sample_id' => 'required|exists:own_samples,id',
            'paid_amount' => 'required|numeric',
            'payment_status' => 'required|string',
        ]);
                if($request->payment_status == 'Paid'){
                    $status = 'Ongoing';
                }else{
                    $status = 'Created';
                }
            try{
                $sample = $this->ownSampleRepository->find($request->own_sample_id);
                $newPaidAmount = $sample->paid_amount + $request->paid_amount;
                $balance = $newPaidAmount - $sample->total_payment_amount;

                $sample->paid_amount = $newPaidAmount;
                $sample->balance = $balance;
                $sample->payment_status = $request->payment_status;
                $sample->testing_status = $status;
                $sample->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Payment Updated successfully.',
                'redirect' => route('payment.index'),
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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('ownsample::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $ownSample = OwnSample::with(['sampleData.tests'])->findOrFail($id);
        $regions = Region::get();
        $districts = District::get();
        $oic = Oic::get();
        return Inertia::render('Modules/OwnResourceSample/OwnResourceEdit', [
            'data' => $ownSample,
            'regions' => $regions,
            'districts' => $districts,
            'oic' => $oic,
        ]);
    }
    public function editPayment($id)
    {
        $ownSample = OwnSample::with(['sampleData.tests'])->findOrFail($id);
        // $owns = Own::all();
        return Inertia::render('Modules/OwnResourceSample/OwnSampleEdit', [
            'data' => $ownSample,
            // 'owns' => $owns,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
public function update(Request $request)
{
    $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'id' => 'required|exists:own_samples,id',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'required|exists:districts,id',
            'oic_id' => 'required|exists:oics,id',
            'tank_no' => 'required|string',
            'laboratory_no' => 'required',
            'sample_count' => 'required|integer',
            'samples' => 'required|array|min:1',
            'samples.*.reference_number' =>  'required|string',
            'samples.*.quantity' =>  'required',
            'samples.*.temperature' =>  'required',
            'samples.*.collected' =>  'required',
            'samples.*.weatherCondition' =>  'required',
    ]);
    $validator->validate();

    try {
        DB::beginTransaction();
        $cSample = $this->ownSampleRepository->find($request->id);
        if (!$cSample) {
            return response()->json(['success' => false, 'message' => 'Sample not found.'], 404);
        }

        $cSample->update([
                'date'=>$request->date,
                'region_id'=>$request->region_id,
                'district_id'=>$request->district_id,
                'oic_id'=>$request->oic_id,
                'tank_no'=>$request->tank_no,
                'laboratory_no'=>$request->laboratory_no,
                'sample_count'=>$request->sample_count,
        ]);

        foreach ($cSample->sampleData as $sample) {
            $this->ownSampleDataRepository->delete($sample['id']);
        }

        $testGroups = [
            'physical' => ['colour', 'turbidity'],
            'bacteriological' => ['coliform', 'coli'],
            'chemical' => [
                'ph','electrical','chloride','alkalinity','nitrate',
                'nitrite','fluoride','phosphate','dissolvedSolid',
                'hardness','iron','sulphate','calcium','manganese'
            ]
        ];

        $allTests = array_keys([
            'physical'=>1,'colour'=>1,'turbidity'=>1,
            'bacteriological'=>1,'coliform'=>1,'coli'=>1,
            'chemical'=>1,'ph'=>1,'electrical'=>1,'chloride'=>1,
            'alkalinity'=>1,'nitrate'=>1,'nitrite'=>1,'fluoride'=>1,
            'phosphate'=>1,'dissolvedSolid'=>1,'hardness'=>1,
            'iron'=>1,'sulphate'=>1,'calcium'=>1,'manganese'=>1
        ]);

        foreach ($request->samples as $sample) {
            $sampleData = $this->ownSampleDataRepository->create([
                'own_sample_id'     => $cSample->id,
                'reference_number'  => $sample['reference_number'],
                'quantity'          => $sample['quantity'],
                'temperature'       => $sample['temperature'],
                'collected'         => $sample['collected'],
                'weather_condition' => $sample['weatherCondition'],
            ]);

            $insertedTests = [];

            foreach ($allTests as $testKey) {
                if (!isset($sample[$testKey]) || $sample[$testKey] != 1) continue;

                if (isset($testGroups[$testKey])) {
                    foreach ($testGroups[$testKey] as $subTest) {
                        if (in_array($subTest, $insertedTests)) continue;
                        $this->ownSampleTestRepository->create([
                            'own_sample_data_id' => $sampleData->id,
                            'test'   => $subTest,
                            'status' => 'pending',
                        ]);
                        $insertedTests[] = $subTest;
                    }
                } else {
                    if (in_array($testKey, $insertedTests)) continue;
                    $this->ownSampleTestRepository->create([
                        'own_sample_data_id' => $sampleData->id,
                        'test'   => $testKey,
                        'status' => 'pending',
                    ]);
                    $insertedTests[] = $testKey;
                }
            }
        }


        DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'Sample updated successfully.',
            'redirect' => route('own.resource.sample.index'),
        ]);

    } catch (\Exception $e) {
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
    public function destroy(Request $request)
    {
        $id = $request->id;
        try {
            $this->ownSampleRepository->delete($id);
            return response()->json([
                'success' => true,
                'message' => 'Samples successfully deleted.',
                'redirect' => route('own.resource.sample.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
