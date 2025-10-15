<?php
namespace Modules\District\Repositories\Interfaces;
use Illuminate\Http\Request;

interface DistrictRepositoryInterface{

    public function all(Request $request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
?>