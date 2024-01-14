<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ایجاد رول‌ها
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        // اضافه کردن رول‌های دیگر
    }
}
