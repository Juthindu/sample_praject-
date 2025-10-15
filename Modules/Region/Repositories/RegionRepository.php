<?php
namespace Modules\Region\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\Region\Entities\Region;
use Modules\Region\Repositories\Interfaces\RegionRepositoryInterface;

class RegionRepository implements RegionRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(Region $region)
    {
        $this->model = $region;
    }
}
?>