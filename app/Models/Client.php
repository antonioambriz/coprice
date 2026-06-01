<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_name',
        'rfc',
        'contact_person',
        'email',
        'address',
        'activo',
    ];

    public function generators()
    {
        return $this->belongsToMany(Generator::class, 'client_generator_wastes')
                    ->withPivot(['sub_generator_id', 'waste_id', 'final_destination_id']);
    }

    public function wastes()
    {
        return $this->belongsToMany(Waste::class, 'client_generator_wastes')
                    ->withPivot(['generator_id', 'sub_generator_id', 'final_destination_id']);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
