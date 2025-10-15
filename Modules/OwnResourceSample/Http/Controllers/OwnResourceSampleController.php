<?php

namespace Modules\OwnResourceSample\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Region\Entities\Region;

class OwnResourceSampleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       return Inertia::render("Modules/OwnResourceSample/OwnResourceSampleManagement");
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function dataTable(Request $request) // Remove When start Backend
    {
    }

    public function create()
    {
        $regions = Region::get();
        return Inertia::render("Modules/OwnResourceSample/CreateOWnResource",['regions' =>$regions]);
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
        return view('ownresourcesample::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('ownresourcesample::edit');
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
