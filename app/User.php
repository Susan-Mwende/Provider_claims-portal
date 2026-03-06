<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'pname','email', 'password', 'pcode', 'pid', 'ptype','role', 'two_factor_expires_at','slug','status',
        'admin', 'approved_at','sendalert'
    ];

    public $sortable = [
        'name', 'pname','email', 'password', 'pcode', 'pid', 'ptype','role', 'two_factor_expires_at','slug','status',
        'admin', 'approved_at','sendalert'
    ];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_expires_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }
    public function generateTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }
    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
    public function passwordSecurity()
    {
        return $this->hasOne(\App\Models\PasswordSecurity::class);
    }
}
