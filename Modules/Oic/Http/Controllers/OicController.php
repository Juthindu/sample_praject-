<?php

namespace Modules\Oic\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\District\Entities\District;
use Modules\Oic\Repositories\Interfaces\OicRepositoryInterface;

class OicController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
        protected $oicRepository;

    public function __construct(OicRepositoryInterface $oicRepository,
                                )
    {
        $this->oicRepository = $oicRepository;
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->oicRepository->all($request));
    }
    public function index()
    {
        return Inertia::render("Modules/OIC/OicManagement");
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $districts = District::get();
       return Inertia::render("Modules/OIC/OicCreate",[
        'districts' => $districts,
       ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'oic_code' => 'required|string|unique:oics,oic_code',
            'district_id' => 'required|integer|exists:districts,id',
            'oic_name' => 'required|string',
            ]);
        try{
            $this->oicRepository->create($validated);
            return response()->json([
                    'success' => true,
                    'message' => 'Oic Create Successfully.',
                    'redirect' => route('oic.index'),
                ]);
        }catch(\Exception $e) {
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
        return Inertia::render("Modules/OIC/OicEdit");
    
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $districts = District::get();
        $data = $this->oicRepository->find($id); 
         return Inertia::render("Modules/OIC/OicEdit",['data' => $data,'districts' => $districts]);
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
            // 'oic_code' => 'required|string|unique:oics,oic_code,' . $id,
            'district_id' => 'required|integer|exists:districts,id',
            'oic_name' => 'required|string',
        ]);
        
        try {
            $this->oicRepository->update($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Oic Updated Successfully.',
                'redirect' => route('oic.index'),
            ]);
        } catch (\Exception $e) {
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
            $this->oicRepository->delete($id);
            return response()->json([
                'success' => true,
                'message' => 'Oic successfully deleted.',
                'redirect' => route('oic.index'),
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
