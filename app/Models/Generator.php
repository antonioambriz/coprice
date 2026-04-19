<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'rfc',
        'address',
        'status',
        'has_sub_generators',
        'preferred_transporter_id',
    ];

    protected $casts = [
        'status'             => 'boolean',
        'has_sub_generators' => 'boolean',
    ];

    public function preferredTransporter()
    {
        return $this->belongsTo(Transporter::class, 'preferred_transporter_id');
    }

    public function subGenerators()
    {
        return $this->hasMany(SubGenerator::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function manifests()
    {
        return $this->hasMany(Manifest::class);
    }

    public function wastes()
    {
        return $this->hasMany(Waste::class);
    }
}
