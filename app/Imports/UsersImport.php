<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        return new User([
            'name'     => $row[2],
            'pcode'     => $row[1],
            'pid'     => $row[0],
            'ptype'     => $row[3],
            'email'    => $row[4],
            'password' => \Hash::make('123456'),
        ]);

    }
}
