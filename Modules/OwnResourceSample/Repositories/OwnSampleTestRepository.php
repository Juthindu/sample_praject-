<?php
namespace Modules\OwnResourceSample\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\OwnResourceSample\Entities\OwnSampleTest;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleTestRepositoryInterface;

class OwnSampleTestRepository implements OwnSampleTestRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(OwnSampleTest $ownSampleTest)
    {
        $this->model = $ownSampleTest;
    }
}
?>