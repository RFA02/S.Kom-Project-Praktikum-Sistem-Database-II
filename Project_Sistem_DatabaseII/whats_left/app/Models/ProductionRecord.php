<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRecord extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'food_id',
        'record_date',
        'production_quantity',
        'regular_sold_quantity',
        'surplus_quantity',
        'waste_quantity',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
        ];
    }

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }
}
