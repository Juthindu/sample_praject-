<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

trait ApiCrudTrait
{
    public function all($request)
    {
        $query = $this->model->newQuery();

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

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }
    public function where($p1,$p2)
    {
        return $this->model->where($p1,$p2);
    }
}
