<?php
namespace Modules\District\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\District\Entities\District;
use Illuminate\Support\Facades\Schema;
use Modules\District\Repositories\Interfaces\DistrictRepositoryInterface;

class DistrictRepository implements DistrictRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(District $district)
    {
        $this->model = $district;
    }

    public function all($request)
    {
        $query = $this->model->newQuery()->with('region');

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