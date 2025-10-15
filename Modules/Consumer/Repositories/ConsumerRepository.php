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
}
?>