<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubGenerator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'generator_id',
        'name',
        'assumed_weight',
        'report_frequency',
        'requires_manifest',
        'status',
    ];

    protected $casts = [
        'assumed_weight'    => 'decimal:2',
        'requires_manifest' => 'boolean',
        'status'            => 'boolean',
    ];

    public function generator()
    {
        return $this->belongsTo(Generator::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function manifests()
    {
        return $this->hasMany(Manifest::class);
    }
}
