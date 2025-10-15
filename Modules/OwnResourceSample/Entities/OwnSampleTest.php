<?php

namespace Modules\OwnResourceSample\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OwnSampleTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'own_sample_data_id',
        'test',
        'result',
        'status',
        'times',
    ];
    
    protected static function newFactory()
    {
        return \Modules\OwnResourceSample\Database\factories\OwnSampleTestFactory::new();
    }
}
