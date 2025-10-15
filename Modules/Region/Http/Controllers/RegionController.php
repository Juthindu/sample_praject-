<?php

namespace Modules\Region\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\District\Entities\District;
use Modules\Region\Repositories\Interfaces\RegionRepositoryInterface;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $regionRepository;

    public function __construct(RegionRepositoryInterface $regionRepository,
                                )
    {
        $this->regionRepository = $regionRepository;
    }

    public function fetchTableData(Request $request)
    {
        return response()->json($this->regionRepository->all($request));
    }
    public function index()
    {
        return Inertia::render("Modules/Region/RegionManagement");
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return Inertia::render("Modules/Region/RegionCreate");
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_code' => 'required|string|unique:regions,region_code',
            'region_name' => 'required|string',
            ]);
        try{
            $this->regionRepository->create($validated);
            return response()->json([
                    'success' => true,
                    'message' => 'Region Create Successfully.',
                    'redirect' => route('region.index'),
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
    public function districts($regionId)
    {
        // $districts = $this->regionRepository->where('region_id', $regionId);
        $districts = District::where('region_id', $regionId)->get();

        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = $this->regionRepository->find($id); 
        return Inertia::render('Modules/Region/RegionEdit',['data' => $data]);
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
            'region_code' => 'required|string|unique:regions,region_code,' . $id,
            'region_name' => 'required|string',
        ]);
        
        try {
            $this->regionRepository->update($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Region Updated Successfully.',
                'redirect' => route('region.index'),
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
            $this->regionRepository->delete($id);
            return response()->json([
                'success' => true,
                'message' => 'Region successfully deleted.',
                'redirect' => route('region.index'),
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
