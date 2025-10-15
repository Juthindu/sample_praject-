<?php

namespace Modules\OwnResourceSample\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OwnSampleData extends Model
{
    use HasFactory;

    protected $fillable = [
        'own_sample_id',
        'reference_number',
        'quantity',
        'temperature',
        'collected',
        'weather_condition',
        'testing_status',
    ];
    
    protected static function newFactory()
    {
        return \Modules\OwnResourceSample\Database\factories\OwnSampleDataFactory::new();
    }

    public function tests() {
        return $this->hasMany(OwnSampleTest::class);
    }
    public function sample() {
        return $this->belongsTo(OwnSample::class,'own_sample_id');
    }
}
