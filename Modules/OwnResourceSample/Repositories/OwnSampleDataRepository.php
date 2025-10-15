<?php
namespace Modules\OwnResourceSample\Repositories;

use App\Traits\ApiCrudTrait;
use Illuminate\Support\Facades\Schema;
use Modules\OwnResourceSample\Entities\OwnSampleData;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleDataRepositoryInterface;

class OwnSampleDataRepository implements OwnSampleDataRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(OwnSampleData $ownSampleData)
    {
        $this->model = $ownSampleData;
    }

    public function all($request)
    {
        $query = $this->model->newQuery()->where('testing_status','Ongoing')->with('tests');

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
                    });
            }

            $query->latest();
            // $query->orderBy($sortColumn, $sortDirection);

            $results = $query->paginate($pageSize, ['*'], 'page', $request->input('page.current_page', 1));
            $formattedData = collect($results->items())->map(function ($item) {
                $item->collected = $item->collected
                    ? \Carbon\Carbon::parse($item->collected)->format('Y-m-d H:i')
                    : null;
                return $item;
            });
            return [
                'data' => $formattedData,
                'total' => $results->total(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
            ];
        }

        return $query->get();
    }

    public function fetchReleaseTableData($request)
    {
        $query = $this->model->newQuery()->where('testing_status','Completed')->with('tests');

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