<?php

namespace Modules\Chemical\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Chemical\Repositories\Interfaces\ChemicalRepositoryInterface;
use Modules\Chemical\Repositories\Interfaces\StockRepositoryInterface;

class StockController extends Controller
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
    public function index()
    {
        return Inertia::render("Modules/Chemical/StockChemical");
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('chemical::create');
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->stockRepository->all($request));
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
