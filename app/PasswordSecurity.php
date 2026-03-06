<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class PasswordSecurity extends Model
{

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
