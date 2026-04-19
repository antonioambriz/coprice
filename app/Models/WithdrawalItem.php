<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawalItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'withdrawal_items';

    protected $fillable = [
        'withdrawal_id',
        'waste_id',
        'quantity',
        'unit',
        'physical_state',
        'packaging_type',
        'container_capacity',
        'container_type',
        'unit_price',
    ];

    public function withdrawal()
    {
        return $this->belongsTo(Withdrawal::class);
    }

    public function waste()
    {
        return $this->belongsTo(Waste::class);
    }
}
