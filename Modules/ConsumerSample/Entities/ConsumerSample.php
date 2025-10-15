<?php

namespace Modules\ConsumerSample\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Consumer\Entities\Consumer;

class ConsumerSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'consumer_id',
        'laboratory_no',
        'transport',
        'vat',
        'paid_amount',
        'payment_status',
        'subtotal',
        'total_payment_amount',
        'balance',
        'sample_count',
        'status',
    ];
    
    protected static function newFactory()
    {
        return \Modules\ConsumerSample\Database\factories\ConsumerSampleFactory::new();
    }

    public function consumer(){
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }
    public function sampleData(){
        return $this->hasMany(ConsumerSampleData::class);
    }

    public function allTestsCompleted(): bool {
        return $this->sampleData->every(fn($sampleData) =>
            $sampleData->tests->every(fn($t) => strtolower($t->status) === 'completed')
        );
    }
}
