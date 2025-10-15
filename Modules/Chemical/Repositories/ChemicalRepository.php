<?php
namespace Modules\Chemical\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\Chemical\Entities\Chemical;
use Modules\Chemical\Repositories\Interfaces\ChemicalRepositoryInterface;

class ChemicalRepository implements ChemicalRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(Chemical $chemical)
    {
        $this->model = $chemical;
    }
}
?>