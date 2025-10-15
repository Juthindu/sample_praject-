<?php

namespace Modules\ConsumerSample\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsumerSampleTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_sample_data_id',
        'test',
        'result',
        'status',
        'times',
    ];
    
    protected static function newFactory()
    {
        return \Modules\ConsumerSample\Database\factories\ConsumerSampleTestFactory::new();
    }

    public function sampleData() { return $this->belongsTo(ConsumerSampleData::class); }
}
