<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'operators';

    protected $fillable = [
        'transporter_id',
        'name',
        'license_number',
        'phone',
        'license_expiry',
        'activo',
    ];

    protected $casts = [
        'activo'         => 'boolean',
        'license_expiry' => 'date',
    ];

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }
}
