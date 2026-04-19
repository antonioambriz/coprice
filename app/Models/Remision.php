<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remision extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'remision_number',
        'generator_id',
        'sub_generator_id',
        'emission_date',
        'status',
        'total',
        'notes',
    ];

    protected $casts = [
        'emission_date' => 'date',
        'total'         => 'decimal:2',
    ];

    public function generator()
    {
        return $this->belongsTo(Generator::class);
    }

    public function subGenerator()
    {
        return $this->belongsTo(SubGenerator::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
