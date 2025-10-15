<?php

namespace Modules\Chemical\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Chemical\Entities\Chemical;
use Modules\Chemical\Repositories\Interfaces\ChemicalRepositoryInterface;
use Modules\Chemical\Repositories\Interfaces\StockRepositoryInterface;

class ChemicalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $chemicalRepository;
    protected $stockRepository;

    public function __construct(ChemicalRepositoryInterface $chemicalRepository,
                                StockRepositoryInterface $stockRepository,
                                )
    {
        $this->chemicalRepository = $chemicalRepository;
        $this->stockRepository = $stockRepository;
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->chemicalRepository->all($request));
    }

    public function index()
    {
        return Inertia::render("Modules/Chemical/ChemicalManagement");
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
       return Inertia::render("Modules/Chemical/CreateChemical");
    }
    

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chemical_code' => 'required|string',
            'chemical_name' => 'required|string',
            'quantity' => 'required|integer',
            'scal_metionment' => 'required|string',
        ]);
        try{
            DB::beginTransaction();
            $existing = Chemical::where('chemical_code', $validated['chemical_code'])->first();
            if ($existing) {
                if ($existing->scal_metionment !== $validated['scal_metionment']) {
                    return response()->json([
                        'message' => "Scale measurement must match existing chemical for this code. Existing scale: {$existing->scal_metionment}",
                    ], 422);
                }
            }
            $this->chemicalRepository->create($validated);
            $this->stockRepository->findOrCreate($validated);
            DB::commit();
            return response()->json([
                    'success' => true,
                    'message' => 'Chemical Create Successfully.',
                    'redirect' => route('chemical.index'),
                ]);
        }catch(\Exception $e) {
            DB::rollback();
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
        $data = $this->chemicalRepository->find($id); 
        return Inertia::render("Modules/Chemical/EditChemical",['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $validated = $request->validate([
            'chemical_code' => 'required|string|unique:chemicals,chemical_code,' . $id,
            'chemical_name' => 'required|string',
            'quantity' => 'required|integer',
            'scal_metionment' => 'required|string',
        ]);
            $existing = Chemical::where('chemical_code', $validated['chemical_code'])->first();
            if ($existing) {
                if ($existing->scal_metionment !== $validated['scal_metionment']) {
                    return response()->json([
                        'message' => "Scale measurement must match existing chemical for this code. Existing scale: {$existing->scal_metionment}",
                    ], 422);
                }
            }
        
        try {
            DB::beginTransaction();
            $chemical = $this->chemicalRepository->find($id);
            $this->chemicalRepository->update($id, $validated);
            $this->stockRepository->updateQuantity($validated['chemical_code'],'Update',$validated['quantity'],$chemical->quantity);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Chemical Updated Successfully.',
                'redirect' => route('chemical.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
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
            DB::beginTransaction();
            $chemical = $this->chemicalRepository->find($id);
            $this->stockRepository->updateQuantity($chemical->chemical_code,'Remove',$chemical->quantity,0);
            $this->chemicalRepository->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Chemical successfully deleted.',
                'redirect' => route('chemical.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function search(Request $request)
    {
        $results = response()->json($this->stockRepository->search($request));

        if ($results->isEmpty()) {
            return response()->json(['error' => 'No matching chemical found'], 404);
        }
        return response()->json($results);
    }
}
