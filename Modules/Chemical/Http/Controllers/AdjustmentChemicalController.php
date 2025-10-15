<?php

namespace Modules\Chemical\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\Chemical\Repositories\Interfaces\AdjustmentRepositoryInterface;
use Modules\Chemical\Repositories\Interfaces\StockRepositoryInterface;

class AdjustmentChemicalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $adjustmentRepository;
    protected $stockRepository;

    public function __construct(AdjustmentRepositoryInterface $adjustmentRepository,
                                StockRepositoryInterface $stockRepository,
                                )
    {
        $this->adjustmentRepository = $adjustmentRepository;
        $this->stockRepository = $stockRepository;
    }
    public function index()
    {
        return Inertia::render("Modules/Chemical/AdjustmentChemical");
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
    */

    public function create()
    {
        return view('chemical::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'chemicals' => 'required|array|min:1',
            'chemicals.*.process' => 'required|in:addition,subtraction',
            'chemicals.*.quantity' => 'required|numeric|min:0',
            'chemicals.*.chemical_code' => 'required|string',
            'chemicals.*.chemical_name' => 'required|string',
            'chemicals.*.scal_metionment' => 'required|string',
        ]);
        DB::beginTransaction();
        try{
            foreach($request->chemicals as $chemical){
                $this->adjustmentRepository->create([
                    'process' => $chemical['process'],
                    'quantity' => $chemical['quantity'],
                    'chemical_code' => $chemical['chemical_code'],
                    'chemical_name' => $chemical['chemical_name'],
                    'scal_metionment' => $chemical['scal_metionment'],
                ]);

                if($chemical['process'] == 'addition'){
                    $this->stockRepository->updateQuantity($chemical['chemical_code'],'Add',$chemical['quantity'],0);
                } 

                if($chemical['process'] == 'subtraction'){
                    $this->stockRepository->updateQuantity($chemical['chemical_code'],'Remove',$chemical['quantity'],0);
                } 
                
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully.',
                'redirect' => route('chemical.stock.index'),
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
        return view('chemical::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('chemical::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
