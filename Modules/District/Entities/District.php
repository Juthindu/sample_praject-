<?php

namespace Modules\District\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Region\Entities\Region;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'district_code',
        'region_id',
        'district_name',        
    ];
    
    protected static function newFactory()
    {
        return \Modules\District\Database\factories\DistrictFactory::new();
    }

    public function region(){
        return $this->belongsTo(Region::class,'region_id');
    }
}
