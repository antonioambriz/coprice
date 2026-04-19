<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportEquipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transport_equipments';

    protected $fillable = [
        'transporter_id',
        'description',
        'plate_number',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }
}
