<?php
namespace Modules\ConsumerSample\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\ConsumerSample\Entities\ConsumerSampleTest;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleTestRepositoryInterface;

class ConsumerSampleTestRepository implements ConsumerSampleTestRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(ConsumerSampleTest $consumerSampleTest)
    {
        $this->model = $consumerSampleTest;
    }
}
?>