<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
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
        'approved_at'
    ];
}
