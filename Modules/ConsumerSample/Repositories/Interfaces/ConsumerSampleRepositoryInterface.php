<?php
namespace Modules\ConsumerSample\Repositories\Interfaces;
use Illuminate\Http\Request;

interface ConsumerSampleRepositoryInterface{

    public function all(Request $request);
    public function fetchPaymentTableData(Request $request);
    public function fetchFinalTableData(Request $request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
?>