<?php

namespace Modules\ConsumerSample\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsumerSampleData extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_sample_id',
        'reference_number',
        'source',
        'sample_locations',
        'quantity',
        'temperature',
        'collected',
        'weather_condition',
        'testing_status',
    ];
    
    protected static function newFactory()
    {
        return \Modules\ConsumerSample\Database\factories\ConsumerSampleDataFactory::new();
    }

    public function tests() {
        return $this->hasMany(ConsumerSampleTest::class);
    }
    public function sample() {
        return $this->belongsTo(ConsumerSample::class,'consumer_sample_id');
    }
}
