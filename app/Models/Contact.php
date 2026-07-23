<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'name',
        'position',
        'phone',
        'email',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function contactable()
    {
        return $this->morphTo();
    }
}
