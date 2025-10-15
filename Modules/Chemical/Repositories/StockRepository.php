<?php
namespace Modules\Chemical\Repositories;

use App\Traits\ApiCrudTrait;
use Modules\Chemical\Entities\Stock;
use Modules\Chemical\Repositories\Interfaces\StockRepositoryInterface;

class StockRepository implements StockRepositoryInterface{
    use ApiCrudTrait;

    protected $model;

    public function __construct(Stock $stock)
    {
        $this->model = $stock;
    }
    public function findOrCreate($data)
    {
        $stock = $this->model->where('chemical_code', $data['chemical_code'])->first();

        if ($stock) {
            $stock->quantity += $data['quantity'];
            $stock->save();
        } else {
            $stock = $this->model->create([
                'chemical_code'    => $data['chemical_code'],
                'chemical_name'    => $data['chemical_name'] ?? null,
                'quantity'         => $data['quantity'],
                'scal_metionment'  => $data['scal_metionment'] ?? null,
            ]);
        }
    }
    public function updateQuantity($id,$operation,$quantity,$oldQuantity){

        if($operation === 'Add'){
            $stockItem = $this->model->where('chemical_code', $id)->first();
            $stockItem->quantity += $quantity;
            $stockItem->save();
        }
        if($operation === 'Remove'){
            $stockItem = $this->model->where('chemical_code', $id)->first();
            $stockItem->quantity -= $quantity;
            if($stockItem->quantity < 0){
                 throw new \Exception("Insufficient stock to reduce. Available: {$stockItem->quantity}, Required: {$quantity}");
            }else{
                $stockItem->save();
            }
        }
        if($operation === 'Update'){
            $stockItem = $this->model->where('chemical_code', $id)->first();
            $stockItem->quantity += $oldQuantity;
            $stockItem->quantity -= $quantity;
            if($stockItem->quantity < 0){
                 throw new \Exception("Insufficient stock to reduce. Available: {$stockItem->quantity}, Required: {$quantity}");
            }else{
                $stockItem->save();
            }
        }
    }

    public function search($request)
    {
        $search = $request->input('search');
        $results = $this->model->where('chemical_code', 'like', "%{$search}%")->orWhere('chemical_name', 'like', "%{$search}%")->get();
        return $results;
    }
}
?>