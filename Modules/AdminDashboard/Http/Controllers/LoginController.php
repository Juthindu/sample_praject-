<?php

namespace Modules\AdminDashboard\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Consumer\Entities\Consumer;
use Modules\ConsumerSample\Entities\ConsumerSample;
use Modules\ConsumerSample\Entities\ConsumerSampleData;
use Modules\District\Entities\District;
use Modules\Oic\Entities\Oic;
use Modules\OwnResourceSample\Entities\OwnSample;
use Modules\OwnResourceSample\Entities\OwnSampleData;
use Modules\Region\Entities\Region;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function login(){

        $now = Carbon::now();
        $consumers = Consumer::count();
        $regions = Region::count();
        $districts = District::count();
        $oics = Oic::count();

        $thisMonthOwnerSamples = OwnSample::whereMonth('date', $now->month)
        ->whereYear('date', $now->year)
        ->count();

        $thisMonthSamples = ConsumerSample::whereMonth('date', $now->month)
        ->whereYear('date', $now->year)
        ->count();

        $thisMonthCompletedResults = ConsumerSampleData::where('testing_status', 'Completed')
        ->whereHas('sample', function ($query) use ($now) {
            $query->whereMonth('date', $now->month)
                  ->whereYear('date', $now->year);
        })
        ->count();
        
        $thisMonthTotalPaid = ConsumerSample::whereMonth('date', $now->month)
        ->whereYear('date', $now->year)
        ->sum('paid_amount');
        
        $monthlySamples = ConsumerSample::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('COUNT(*) as total')
            )
            ->whereYear('date', $now->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyOwnSamples = OwnSample::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('COUNT(*) as total')
            )
            ->whereYear('date', $now->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $sampleStatusCounts = ConsumerSampleData::select('testing_status', DB::raw('COUNT(*) as total'))
        ->groupBy('testing_status')
        ->pluck('total', 'testing_status');
        $ownSampleStatusCounts = OwnSampleData::select('testing_status', DB::raw('COUNT(*) as total'))
        ->groupBy('testing_status')
        ->pluck('total', 'testing_status');
            
        return Inertia::render('Modules/AdminDashBoard/Dashboard',[
            'consumersCount' => $consumers,
            'regions' => $regions,
            'districts' => $districts,
            'oics' => $oics,
            'thisMonthOwnerSamples' => $thisMonthOwnerSamples,
            'thisMonthSamples' => $thisMonthSamples,
            'thisMonthCompletedResults' => $thisMonthCompletedResults,
            'thisMonthTotalPaid' => $thisMonthTotalPaid,

            'monthlySamples' => $monthlySamples,
            'monthlyOwnSamples' => $monthlyOwnSamples,
            'sampleStatusCounts' => $sampleStatusCounts,  
            'ownSampleStatusCounts' => $ownSampleStatusCounts,  
        ]);
    }

    public function index()
    {
        
    }

    public function create()
    {
       
    }

    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        return view('admindashboard::show');
    }

   
    public function edit($id)
    {
        return view('admindashboard::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
