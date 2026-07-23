<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdrawal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'withdrawals';

    protected $fillable = [
        'folio_interno',
        'ticket_externo',
        'folio_salida',
        'user_id',
        'generator_id',
        'sub_generator_id',
        'transporter_id',
        'client_id',
        'manifest_id',
        'remision_id',
        'reception_date',
        'departure_date',
        'is_estimated_weight',
        'requires_manifest',
        'requires_transport_equipment',
        'transport_equipment_id',
        'operator_id',
        'treatment_stage',
        'final_destination_id',
        'payment_status',
        'observaciones',
    ];

    protected $casts = [
        'reception_date'      => 'date',
        'departure_date'      => 'datetime',
        'is_estimated_weight'          => 'boolean',
        'requires_manifest'            => 'boolean',
        'requires_transport_equipment' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function generator()
    {
        return $this->belongsTo(Generator::class);
    }

    public function subGenerator()
    {
        return $this->belongsTo(SubGenerator::class);
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function manifest()
    {
        return $this->belongsTo(Manifest::class);
    }

    public function remision()
    {
        return $this->belongsTo(Remision::class);
    }

    public function finalDestination()
    {
        return $this->belongsTo(FinalDestination::class);
    }

    public function transportEquipment()
    {
        return $this->belongsTo(TransportEquipment::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function items()
    {
        return $this->hasMany(WithdrawalItem::class);
    }
}
