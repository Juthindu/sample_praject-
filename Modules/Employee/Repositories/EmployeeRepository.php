<?php
namespace Modules\Employee\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\Employee\Entities\Employee;
use Modules\Employee\Repositories\Interfaces\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    public function allData()
    {
        return $this->model->query();
    }
    
}
?>