<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Waste extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'waste_code',    // <--- Verifica que esta línea exista
        'unit',
        'default_price',
        'is_hazardous',
        'notes'
    ];

    protected $casts = [
        'is_hazardous' => 'boolean',
        'default_price' => 'decimal:2'
    ];
}
