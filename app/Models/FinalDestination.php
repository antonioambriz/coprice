<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinalDestination extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'final_destinations';

    protected $fillable = [
        'name',
        'authorization_number',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        return $this->authorization_number
            ? $this->name . ' (' . $this->authorization_number . ')'
            : $this->name;
    }
}
