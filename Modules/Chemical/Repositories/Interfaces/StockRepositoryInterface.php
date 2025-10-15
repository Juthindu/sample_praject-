<?php
namespace Modules\Chemical\Repositories\Interfaces;
use Illuminate\Http\Request;

interface StockRepositoryInterface{

    public function all(Request $request);
    public function findOrCreate($data);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function updateQuantity($id,$operation,$quantity,$oldQuantity);
    public function search($request);
}
?>