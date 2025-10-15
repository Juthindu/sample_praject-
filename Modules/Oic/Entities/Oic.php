<?php

namespace Modules\Oic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\District\Entities\District;

class Oic extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'oic_code',
        'district_id',
        'oic_name',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Oic\Database\factories\OicFactory::new();
    }

    public function district(){
        return $this->belongsTo(District::class,'district_id');
    } 
}
