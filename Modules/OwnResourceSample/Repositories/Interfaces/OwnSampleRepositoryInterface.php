<?php
namespace Modules\OwnResourceSample\Repositories\Interfaces;
use Illuminate\Http\Request;

interface OwnSampleRepositoryInterface{

    public function all(Request $request);
    public function fetchFinalTableData(Request $request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
?>