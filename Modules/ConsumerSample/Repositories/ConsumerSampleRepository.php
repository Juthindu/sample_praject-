<?php
namespace Modules\ConsumerSample\Repositories;

use App\Traits\ApiCrudTrait;
use Illuminate\Support\Facades\Schema;
use Modules\ConsumerSample\Entities\ConsumerSample;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleRepositoryInterface;

class ConsumerSampleRepository implements ConsumerSampleRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(ConsumerSample $consumerSample)
    {
        $this->model = $consumerSample;
    }

    public function all($request)
    {
        $query = $this->model->newQuery()->with('consumer');
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
                        $q->orWhereHas('consumer', function ($cq) use ($search) {
                            $cq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('nic', 'like', "%{$search}%")
                            ->orWhere('contact_number', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
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
     public function fetchPaymentTableData($request)
    {
         $query = $this->model->with('consumer')->newQuery()->where('payment_status', 'Unpaid');
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
                        $q->orWhereHas('consumer', function ($cq) use ($search) {
                            $cq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('nic', 'like', "%{$search}%")
                            ->orWhere('contact_number', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
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
            ->with('sampleData','consumer');


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