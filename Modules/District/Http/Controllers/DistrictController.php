<?php

namespace Modules\District\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;// Remove When start Backend
use Illuminate\Pagination\LengthAwarePaginator; // Remove When start Backend
use Modules\District\Repositories\Interfaces\DistrictRepositoryInterface;
use Modules\Oic\Entities\Oic;
use Modules\Region\Entities\Region;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $districtRepository;
    public function __construct(DistrictRepositoryInterface $districtRepository,
                                )
    {
        $this->districtRepository = $districtRepository;
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->districtRepository->all($request));
    }
    public function index()
    {
        return Inertia::render("Modules/District/DistrictManagement");
    }
    
    public function oic($oicId)
    {
        // $districts = $this->regionRepository->where('region_id', $regionId);
        $oic = Oic::where('district_id', $oicId)->get();

        return response()->json([
            'success' => true,
            'data' => $oic
        ]);
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $regions = Region::get();
        return Inertia::render("Modules/District/CreateDistrict",['regions' => $regions]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|integer|exists:regions,id',
            // 'district_code' => 'required|string|unique:districts,district_code',
            'district_name' => 'required|string|unique:districts,district_name',
            ]);
        try{
            $this->districtRepository->create($validated);
            return response()->json([
                    'success' => true,
                    'message' => 'District Create Successfully.',
                    'redirect' => route('district.index'),
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
        return view('district::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $regions = Region::get();
        $data = $this->districtRepository->find($id);
        return Inertia::render("Modules/District/DistrictEdit",[
            'data' => $data,
            'regions' => $regions, 
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
        $id = $request->id;
        $validated = $request->validate([
            'region_id' => 'required|integer|exists:regions,id',
            // 'district_code' => 'required|string|unique:districts,district_code,' . $id,
            'district_name' => 'required|string|unique:districts,district_name,' . $id,
        ]);
        
        try {
            $this->districtRepository->update($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'District Updated Successfully.',
                'redirect' => route('district.index'),
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
            $this->districtRepository->delete($id);
            return response()->json([
                'success' => true,
                'message' => 'District successfully deleted.',
                'redirect' => route('district.index'),
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
