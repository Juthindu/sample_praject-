<?php

namespace Modules\Payment\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\ConsumerSample\Entities\ConsumerSample;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return Inertia::render("Modules/Payment/PaymentManagement");
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $samples = ConsumerSample::where('payment_status','Unpaid')->with('consumer')->get();
        return Inertia::render("Modules/Payment/PaymentCreate",[
            'samples'=>$samples,
        ]);
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
        return view('payment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = ConsumerSample::with('consumer')->find($id);
        $samples = ConsumerSample::where('payment_status','Unpaid')->with('consumer')->get();
        return Inertia::render("Modules/Payment/PaymentCreate",[
            'samples'=>$samples,
            'data'=>$data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        
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
