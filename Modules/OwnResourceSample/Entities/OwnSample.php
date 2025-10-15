<?php

namespace Modules\OwnResourceSample\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\District\Entities\District;
use Modules\Oic\Entities\Oic;
use Modules\Region\Entities\Region;

class OwnSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'region_id',
        'district_id',
        'oic_id',
        'tank_no',
        'laboratory_no',
        'tank_no',
        'sample_count',
    ];
    
    protected static function newFactory()
    {
        return \Modules\OwnResourceSample\Database\factories\OwnSampleFactory::new();
    }

    public function sampleData(){
        return $this->hasMany(OwnSampleData::class);
    }
    public function region(){
        return $this->belongsTo(Region::class);
    }
    public function district(){
        return $this->belongsTo(District::class);
    }
    public function oic(){
        return $this->belongsTo(Oic::class);
    }

}
