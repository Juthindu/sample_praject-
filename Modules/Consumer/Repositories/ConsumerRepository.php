<?php
namespace Modules\Consumer\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\Consumer\Entities\Consumer;
use Modules\Consumer\Repositories\Interfaces\ConsumerRepositoryInterface;

class ConsumerRepository implements ConsumerRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(Consumer $consumer)
    {
        $this->model = $consumer;
    }

    public function search($request)
    {
        $search = $request->input('search');
        $results = $this->model->where('nic', 'like', "%{$search}%")->orWhere('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->get();
        return $results;
    }
}
?>