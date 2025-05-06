<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Criando um usuário de teste
        User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@teste.com',
            'email_verified_at' => now(),
            'password' => Hash::make('senha123'),
        ]);
    }
}
