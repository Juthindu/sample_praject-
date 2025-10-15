<?php

namespace Modules\Chemical\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'process',
        'quantity',
        'chemical_code',
        'chemical_name',
        'scal_metionment',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Inventory\Database\factories\AdjustmentFactory::new();
    }

}
