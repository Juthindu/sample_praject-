<?php
namespace Modules\Chemical\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\Chemical\Entities\Adjustment;
use Modules\Chemical\Repositories\Interfaces\AdjustmentRepositoryInterface;

class AdjustmentRepository implements AdjustmentRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(Adjustment $adjustment)
    {
        $this->model = $adjustment;
    }

    public function find($id){
        return $this->model->with('adjustmentItems','adjustmentItems.item')->findOrFail($id);
    }
}
?>