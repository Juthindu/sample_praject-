<?php
namespace Modules\OwnResourceSample\Repositories;

use App\Traits\ApiCrudTrait;
use Illuminate\Support\Facades\Schema;
use Modules\OwnResourceSample\Entities\OwnSample;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleRepositoryInterface;

class OwnSampleRepository implements OwnSampleRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(OwnSample $ownSample)
    {
        $this->model = $ownSample;
    }

    public function all($request)
    {
        $query = $this->model->newQuery()->with('region','district','oic');
        if ($request) {
            $pageSize = $request->input('page.pagesize', 10);
            $search = $request->input('search');
            $sortColumn = $request->input('sort_column', 'id');
            $sortDirection = $request->input('sort_direction', 'desc');

            if (!empty($search)) {
                $columns = Schema::getColumnListing($this->model->getTable());
                $query->where(function ($q) use ($columns, $search) {
                        foreach ($columns as $column) {
                            $q->orWhere($column, 'like', "%{$search}%");
                        }
                        $q->orWhereHas('region', function ($cq) use ($search) {
                            $cq->where('region_name', 'like', "%{$search}%");
                        });
                        $q->orWhereHas('district', function ($cq) use ($search) {
                            $cq->where('district_name', 'like', "%{$search}%");
                        });
                        $q->orWhereHas('oic', function ($cq) use ($search) {
                            $cq->where('oic_name', 'like', "%{$search}%");
                        });
                    });
            }

            $query->latest();

            $results = $query->paginate($pageSize, ['*'], 'page', $request->input('page.current_page', 1));

            return [
                'data' => $results->items(),
                'total' => $results->total(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
            ];
        }

        return $query->get();
    }
    
    public function fetchFinalTableData($request)
        {
        $query = $this->model->newQuery()
            ->whereDoesntHave('sampleData', function ($q) {
                $q->where('testing_status', '!=', 'Confirmed')
                ->orWhereNull('testing_status');
            })
            ->with('sampleData','region','district','oic');

        if ($request) {
            $pageSize = $request->input('page.pagesize', 10);
            $search = $request->input('search');
            $sortColumn = $request->input('sort_column', 'id');
            $sortDirection = $request->input('sort_direction', 'desc');


            if (!empty($search)) {
                $columns = Schema::getColumnListing($this->model->getTable());
                $query->where(function ($q) use ($columns, $search) {
                        foreach ($columns as $column) {
                            $q->orWhere($column, 'like', "%{$search}%");
                        }
                        $q->orWhereHas('region', function ($cq) use ($search) {
                            $cq->where('region_name', 'like', "%{$search}%");
                        });
                        $q->orWhereHas('district', function ($cq) use ($search) {
                            $cq->where('district_name', 'like', "%{$search}%");
                        });
                        $q->orWhereHas('oic', function ($cq) use ($search) {
                            $cq->where('oic_name', 'like', "%{$search}%");
                        });
                    });
            }

            $query->latest();
            // $query->orderBy($sortColumn, $sortDirection);

            $results = $query->paginate($pageSize, ['*'], 'page', $request->input('page.current_page', 1));

            return [
                'data' => $results->items(),
                'total' => $results->total(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
            ];
        }

        return $query->get();
    }
}
?>