<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'ACL',
        'PROVIDER',
        'PROVIDER_CITY',
        'PROVIDER_CODE',
        'PROVIDER_COUNTRY',
        'PROVIDER_EMAIL',
        'PROVIDER_FAX',
        'PROVIDER_ID',
        'PROVIDER_TYPE',
        'SPECIALIZATION',
        'approved_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
