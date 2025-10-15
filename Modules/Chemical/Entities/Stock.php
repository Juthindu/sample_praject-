<?php

namespace Modules\Chemical\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'chemical_code',
        'chemical_name',
        'quantity',
        'scal_metionment',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Chemical\Database\factories\StockFactory::new();
    }
}
