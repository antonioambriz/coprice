<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manifest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'manifest_number',
        'emission_date',
        'generator_id',
        'sub_generator_id',
        'period_start',
        'period_end',
        'generated',
        'notes',
    ];

    protected $casts = [
        'generated'     => 'boolean',
        'emission_date' => 'date',
        'period_start'  => 'date',
        'period_end'    => 'date',
    ];

    public function generator()
    {
        return $this->belongsTo(Generator::class);
    }

    public function subGenerator()
    {
        return $this->belongsTo(SubGenerator::class);
    }

    // Un manifiesto puede agrupar uno o varios retiros
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
