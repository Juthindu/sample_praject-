<?php

namespace Modules\Consumer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ConsumerSample\Entities\ConsumerSample;

class Consumer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'nic',
        'contact_number',
        'address',
        'email',
];
    
    protected static function newFactory()
    {
        return \Modules\Consumer\Database\factories\ConsumerFactory::new();
    }

    public function samples()
    {
        return $this->hasMany(ConsumerSample::class);
    }

    public function getFullNameAttribute() {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
