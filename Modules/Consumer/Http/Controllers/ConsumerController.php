<?php

namespace Modules\Consumer\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Consumer\Repositories\Interfaces\ConsumerRepositoryInterface;

class ConsumerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $consumerRepository;
    public function __construct(ConsumerRepositoryInterface $consumerRepository,
                                )
    {
        $this->consumerRepository = $consumerRepository;
    }
    public function fetchTableData(Request $request)
    {
        return response()->json($this->consumerRepository->all($request));
    }

    public function index()
    {
         return Inertia::render("Modules/Consumer/ConsumerManagement");
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
       return Inertia::render("Modules/Consumer/ConsumerCreate");
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'nic' => 'required|string',
            'contact_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string|unique:consumers,email',
            ]);
        try{
            $this->consumerRepository->create($validated);
            return response()->json([
                    'success' => true,
                    'message' => 'Consumer Create Successfully.',
                    'redirect' => route('new.consumer.index'),
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
        return view('consumer::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = $this->consumerRepository->find($id); 
        return Inertia::render("Modules/Consumer/ConsumerEdit",['data' => $data]);
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'nic' => 'required|string',
            'contact_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string|unique:consumers,email,' . $id,
            ]);
        
        try {
            $this->consumerRepository->update($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Consumer Updated Successfully.',
                'redirect' => route('new.consumer.index'),
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
            $this->consumerRepository->delete($id);
            return response()->json([
                'success' => true,
                'message' => 'Consumer successfully deleted.',
                'redirect' => route('new.consumer.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        public function search(Request $request)
    {
        $results = response()->json($this->consumerRepository->search($request));

        if ($results->isEmpty()) {
            return response()->json(['error' => 'No matching chemical found'], 404);
        }
        return response()->json($results);
    }
}
