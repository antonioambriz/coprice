<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transporter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transporters';

    protected $fillable = [
        'company_name',
        'rfc',
        'authorization_number',
        'contact_person',
        'email_remissions',
        'address',
        'activo',
    ];

    protected $casts = [
        'activo' => 'integer',
    ];

    public function transportEquipments()
    {
        return $this->hasMany(TransportEquipment::class);
    }

    public function wastePrices()
    {
        return $this->hasMany(WastePrice::class);
    }
}
