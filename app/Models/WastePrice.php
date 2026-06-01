<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WastePrice extends Model
{
    protected $table = 'waste_prices';

    protected $fillable = [
        'transporter_id',
        'waste_id',
        'price_per_ton',
    ];

    protected $casts = [
        'price_per_ton' => 'decimal:4',
    ];

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function waste()
    {
        return $this->belongsTo(Waste::class);
    }
}
