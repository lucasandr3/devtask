<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => '098.613.856-81 Lucas Vieira de Andrade',
            'email' => 'lucas@gmail.com',
            'password' => Hash::make('123456'),
            'cnpj' => '31.226.405/0001-76',
            'company_name' => '098.613.856-81 Lucas Vieira de Andrade',
        ]);
    }
}
