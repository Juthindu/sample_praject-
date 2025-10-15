<?php

namespace Modules\ConsumerSample\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Consumer\Entities\Consumer;
use Modules\ConsumerSample\Entities\ConsumerSample;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleRepositoryInterface;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleDataRepositoryInterface;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleTestRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\SampleResultsReady;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConsumerSampleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $consumerSampleDataRepository;
    protected $consumerSampleTestRepository;
    protected $consumerSampleRepository;

    public function __construct(ConsumerSampleDataRepositoryInterface $consumerSampleDataRepository,
                                ConsumerSampleTestRepositoryInterface $consumerSampleTestRepository,
                                ConsumerSampleRepositoryInterface $consumerSampleRepository,)
    {
        $this->consumerSampleDataRepository = $consumerSampleDataRepository;
        $this->consumerSampleTestRepository = $consumerSampleTestRepository;
        $this->consumerSampleRepository = $consumerSampleRepository;
    }
    public function index()
    {
        return Inertia::render("Modules/ConsumerSample/ConsumerSampleManagement");
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->consumerSampleRepository->all($request));
    }
    public function fetchPaymentTableData(Request $request)
    {
        return response()->json($this->consumerSampleRepository->fetchPaymentTableData($request));
    }
    public function fetchFinalTableData(Request $request)
    {
        return response()->json($this->consumerSampleRepository->fetchFinalTableData($request));
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $consumers = Consumer::get();
        return Inertia::render("Modules/ConsumerSample/ConsumerSampleCreate",['consumers' => $consumers]);
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
            'consumer_id' => 'required|exists:consumers,id',
            'laboratory_no' => 'required',
            'transport' => 'required|numeric',
            'vat' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'payment_status' => 'required|string',
            'subtotal' => 'required|numeric',
            'total_payment_amount' => 'required|numeric',
            'balance' => 'required|numeric',
            'sample_count' => 'required|integer',
            'samples' => 'required|array|min:1',
            'samples.*.reference_number' =>  'required|string',
            'samples.*.source' =>  'required|string',
            'samples.*.sample_locations' =>  'required|string',
            'samples.*.quantity' =>  'required',
            'samples.*.temperature' =>  'required',
            'samples.*.collected' =>  'required',
            'samples.*.weatherCondition' =>  'required',
        ]);
        
        $validator->after(function ($validator) use ($request) {
            $total = $request->total_payment_amount ?? 0;
            $paid = $request->paid_amount ?? 0;
            $status = $request->payment_status ?? '';

            if (strtolower($status) === 'paid' && $paid < $total) {
                $validator->errors()->add('paid_amount', 'Paid amount must be equal or greater than total payment when status is Paid.');
            }

            if (strtolower($status) === 'unpaid' && $paid > $total) {
                $validator->errors()->add('paid_amount', 'Paid amount must be less than total payment when status is Unpaid.');
            }
        });

        $validator->validate();

        try{
            DB::beginTransaction();
            $cSample = $this->consumerSampleRepository->create([
                'date'=>$request->date,
                'consumer_id'=>$request->consumer_id,
                'laboratory_no'=>$request->laboratory_no,
                'transport'=>$request->transport,
                'vat'=>$request->vat,
                'paid_amount'=>$request->paid_amount,
                'payment_status'=>$request->payment_status,
                'subtotal'=>$request->subtotal,
                'total_payment_amount'=>$request->total_payment_amount,
                'balance'=>$request->balance,
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
                if($request->payment_status == 'Paid'){
                            $status = 'Ongoing';
                        }else{
                            $status = 'Created';
                        }
                $sampleData = $this->consumerSampleDataRepository->create([
                    'consumer_sample_id'     => $cSample->id,
                    'reference_number'  => $sample['reference_number'],
                    'source' => $sample['source'],
                    'sample_locations' => $sample['sample_locations'],
                    'quantity'          => $sample['quantity'],
                    'temperature'       => $sample['temperature'],
                    'collected'         => $sample['collected'],
                    'weather_condition' => $sample['weatherCondition'],
                    'testing_status' => $status,
                ]);

                $insertedTests = [];

                foreach ($allTests as $testKey) {
                    if (!isset($sample[$testKey]) || $sample[$testKey] != 1) continue;

                    if (isset($testGroups[$testKey])) {
                        foreach ($testGroups[$testKey] as $subTest) {
                            if (in_array($subTest, $insertedTests)) continue;
                            $this->consumerSampleTestRepository->create([
                                'consumer_sample_data_id' => $sampleData->id,
                                'test'   => $subTest,
                                'status' => 'pending',
                            ]);
                            $insertedTests[] = $subTest;
                        }
                    } else {
                        if (in_array($testKey, $insertedTests)) continue;
                        $this->consumerSampleTestRepository->create([
                            'consumer_sample_data_id' => $sampleData->id,
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
                'message' => 'Add Consumer Sample successfully.',
                'redirect' => route('con.sample.index'),
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

    public function payment(Request $request)
    {
        $request->validate([
            'consumer_sample_id' => 'required|exists:consumer_samples,id',
            'paid_amount' => 'required|numeric',
            'payment_status' => 'required|string',
        ]);
               
            try{
                DB::beginTransaction();
                $sample = $this->consumerSampleRepository->find($request->consumer_sample_id);
                $newPaidAmount = $sample->paid_amount + $request->paid_amount;
                $balance = $newPaidAmount - $sample->total_payment_amount;

                $sample->paid_amount = $newPaidAmount;
                $sample->balance = $balance;
                $sample->payment_status = $request->payment_status;
                $sample->save();
                 if($request->payment_status == 'Paid'){
                    $status = 'Ongoing';
                }else{
                    $status = 'Created';
                }
                foreach($sample->sampleData as $dataData){
                    $data = $this->consumerSampleDataRepository->find($dataData['id']);
                    $data->testing_status = $status;
                    $data->save();
                }
                
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
        return view('consumersample::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $consumerSample = ConsumerSample::with(['sampleData.tests'])->findOrFail($id);
        $consumers = Consumer::all();
        return Inertia::render('Modules/ConsumerSample/ConsumerSampleEdit', [
            'data' => $consumerSample,
            'consumers' => $consumers,
        ]);
    }
    public function editPayment($id)
    {
        $consumerSample = ConsumerSample::with(['sampleData.tests'])->findOrFail($id);
        $consumers = Consumer::all();
        return Inertia::render('Modules/ConsumerSample/ConsumerSampleEdit', [
            'data' => $consumerSample,
            'consumers' => $consumers,
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
            'id' => 'required|exists:consumer_samples,id',
            'consumer_id' => 'required|exists:consumers,id',
            'laboratory_no' => 'required',
            'transport' => 'required|numeric',
            'vat' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'payment_status' => 'required|string',
            'subtotal' => 'required|numeric',
            'total_payment_amount' => 'required|numeric',
            'balance' => 'required|numeric',
            'sample_count' => 'required|integer',
            'samples' => 'required|array|min:1',
            'samples.*.reference_number' =>  'required|string',
            'samples.*.source' =>  'required|string',
            'samples.*.sample_locations' =>  'required|string',
            'samples.*.quantity' =>  'required',
            'samples.*.temperature' =>  'required',
            'samples.*.collected' =>  'required',
            'samples.*.weatherCondition' =>  'required',
        ]);
        $validator->after(function ($validator) use ($request) {
                $total = $request->total_payment_amount ?? 0;
                $paid = $request->paid_amount ?? 0;
                $status = $request->payment_status ?? '';

                if (strtolower($status) === 'paid' && $paid < $total) {
                    $validator->errors()->add('paid_amount', 'Paid amount must be equal or greater than total payment when status is Paid.');
                }

                if (strtolower($status) === 'unpaid' && $paid > $total) {
                    $validator->errors()->add('paid_amount', 'Paid amount must be less than total payment when status is Unpaid.');
                }
        });

        $validator->validate();

        try {
            DB::beginTransaction();
            $cSample = $this->consumerSampleRepository->find($request->id);
            if (!$cSample) {
                return response()->json(['success' => false, 'message' => 'Consumer Sample not found.'], 404);
            }

            $cSample->update([
                'date' => $request->date,
                'consumer_id' => $request->consumer_id,
                'laboratory_no' => $request->laboratory_no,
                'transport' => $request->transport,
                'vat' => $request->vat,
                'paid_amount' => $request->paid_amount,
                'payment_status' => $request->payment_status,
                'subtotal' => $request->subtotal,
                'total_payment_amount' => $request->total_payment_amount,
                'balance' => $request->balance,
                'sample_count' => $request->sample_count,
            ]);

            foreach ($cSample->sampleData as $sample) {
                $this->consumerSampleDataRepository->delete($sample['id']);
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
                if($request->payment_status == 'Paid'){
                            $status = 'Ongoing';
                        }else{
                            $status = 'Created';
                        }
                $sampleData = $this->consumerSampleDataRepository->create([
                    'consumer_sample_id'     => $cSample->id,
                    'reference_number'  => $sample['reference_number'],
                    'source' => $sample['source'],
                    'sample_locations' => $sample['sample_locations'],
                    'quantity'          => $sample['quantity'],
                    'temperature'       => $sample['temperature'],
                    'collected'         => $sample['collected'],
                    'weather_condition' => $sample['weatherCondition'],
                    'testing_status' => $status,
                ]);

                $insertedTests = [];

                foreach ($allTests as $testKey) {
                    if (!isset($sample[$testKey]) || $sample[$testKey] != 1) continue;

                    if (isset($testGroups[$testKey])) {
                        foreach ($testGroups[$testKey] as $subTest) {
                            if (in_array($subTest, $insertedTests)) continue;
                            $this->consumerSampleTestRepository->create([
                                'consumer_sample_data_id' => $sampleData->id,
                                'test'   => $subTest,
                                'status' => 'pending',
                            ]);
                            $insertedTests[] = $subTest;
                        }
                    } else {
                        if (in_array($testKey, $insertedTests)) continue;
                        $this->consumerSampleTestRepository->create([
                            'consumer_sample_data_id' => $sampleData->id,
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
                'message' => 'Consumer Sample updated successfully.',
                'redirect' => route('con.sample.index'),
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
            $this->consumerSampleRepository->delete($id);
            return response()->json([
                'success' => true,
                'message' => 'Samples successfully deleted.',
                'redirect' => route('con.sample.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendMail(Request $request)
    {
        $id = $request->id;

        $sample = ConsumerSample::with([
            'consumer',
            'sampleData.tests'
        ])->findOrFail($id);
        if (!$sample->consumer || empty($sample->consumer->email)) {
            return response()->json(['message' => 'Consumer email not found.'], 422);
        }
        $testDetails = [
            'physical'        => ['name' => 'Physical Quality'],
            'colour'          => ['name' => 'Colour', 'standard' => 'APHA 2120 C', 'limit' => 15, 'unit' => 'Pt/Co unit'],
            'turbidity'       => ['name' => 'Turbidity', 'standard' => 'APHA 2130 B', 'limit' => 2, 'unit' => 'NTU'],
            'ph'              => ['name' => 'pH @25°C', 'standard' => 'A 4500-H B', 'limit' => '6.5 to 8.5', 'unit' => ''],
            'electrical'      => ['name' => 'Electrical Conductivity', 'standard' => 'APHA 2510 B', 'limit' => '', 'unit' => 'µS/cm'],
            'chloride'        => ['name' => 'Chloride (as CI)', 'standard' => 'APHA 4500-C1-B', 'limit' => 250, 'unit' => 'mg/L'],
            'alkalinity'      => ['name' => 'Total Alkalinity (as CaCO3)', 'standard' => 'APHA 2320', 'limit' => 200, 'unit' => 'mg/L'],
            'nitrate'         => ['name' => 'Nitrate (as NO3-)', 'standard' => 'APHA 4500- NO3- E, Adapted method', 'limit' => 50, 'unit' => 'mg/L'],
            'nitrite'         => ['name' => 'Nitrite (as NO2)', 'standard' => 'APHA 4500- NO₂- B, Adapted method', 'limit' => 3, 'unit' => 'mg/L'],
            'fluoride'        => ['name' => 'Fluoride (as F-)', 'standard' => 'APHA 4500-F-D, Adapted method', 'limit' => 1, 'unit' => 'mg/L'],
            'phosphate'       => ['name' => 'Total phosphate (as PO43-)', 'standard' => 'APHA 3500-P E, Adapted method', 'limit' => 2, 'unit' => 'mg/L'],
            'dissolvedSolid'  => ['name' => 'Total Dissolved Solid', 'standard' => 'APHA 2540 C', 'limit' => 500, 'unit' => 'mg/L'],
            'hardness'        => ['name' => 'Total Hardness (as CaCO3)', 'standard' => 'APHA 2340 C', 'limit' => 250, 'unit' => 'mg/L'],
            'iron'            => ['name' => 'Total Iron', 'standard' => 'APHA 4500- Fe B, Adapted method', 'limit' => 0.3, 'unit' => 'mg/L'],
            'sulphate'        => ['name' => 'Sulphate (as SO42-)', 'standard' => 'APHA 4500-SO42- E, Adapted method', 'limit' => 250, 'unit' => 'mg/L'],
            'calcium'         => ['name' => 'Calcium', 'standard' => 'APHA 3500-Ca B', 'limit' => 100, 'unit' => 'mg/L'],
            'manganese'       => ['name' => 'Manganese', 'standard' => 'APHA 3111 Mn B, Adapted method', 'limit' => 0.1, 'unit' => 'mg/L'],
            'bacteriological' => ['name' => 'Bacteriological Quality'],
            'coliform'        => ['name' => 'Total Coliform', 'standard' => 'ISO 9308-1:2014', 'limit' => 10, 'unit' => 'Nos/100mL'],
            'coli'            => ['name' => 'E.Coli', 'standard' => 'ISO 9308-1:2014', 'limit' => 'Nil', 'unit' => 'Nos/100mL'],
        ];

        $pdf = Pdf::loadView('pdf.sample_results', [
            'sample' => $sample,
            'testDetails' => $testDetails,
        ])->output();

        // $pdf = Pdf::loadView('pdf.sample_results', ['sample' => $sample])->output();
        Mail::to($sample->consumer->email)->send(new SampleResultsReady($sample, $pdf));
        $sample->status = 'Mail successfully sent';
        $sample->save();
        return response()->json(['message' => 'Results emailed successfully.']);
    }

}
