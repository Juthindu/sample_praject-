<?php
namespace Modules\OwnResourceSample\Repositories\Interfaces;
use Illuminate\Http\Request;

interface OwnSampleTestRepositoryInterface{

    public function all(Request $request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function where($p1,$p2);
}
?>