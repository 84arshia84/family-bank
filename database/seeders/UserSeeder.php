<?php

namespace Database\Seeders;

use App\Models\User;
use http\Env\Response;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->create([
            'name' => 'arshia',
            "family"=>'akhtari',
            "father_name"=>'shahram',
            "phone_number"=>'09036786610',
            "national_id"=>'1810808080',
            "password"=>'185'
        ]);
        $user->assignRole('admin');

    }
}
