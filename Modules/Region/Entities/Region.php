<?php

namespace Modules\Region\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_code',
        'region_name'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Region\Database\factories\RegionFactory::new();
    }
}
