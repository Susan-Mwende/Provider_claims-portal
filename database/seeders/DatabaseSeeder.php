<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'=>'Portal Admin',
            'pname'=>'Portal Admin',
        'pcode'=>'no-reply',
            'slug'=>'admin-2021-03-10-23824',
        'ptype'=>'Admin',
            'pid'=>'no-reply',
            'admin' => 1,
            'approved_at' => now(),
        'email'=>'info-alerts@aar.co.ke',
        'password'=>Hash::make('Jumuia@2021'),
        'role'=>'admin',
            'created_at'=>Carbon::now(),

        ]);
        $user = User::where('email','info-alerts@aar.co.ke') -> first();
        $userID = $user->id;
        DB::table('password_securities')->insert([
            'user_id'=>$userID,
            'password_expiry_days'=>30,
            'password_updated_at'=>Carbon::now(),
        ]);
    }
}
