<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@exemplo.com',
            'password' => bcrypt('senha'), // Crie uma senha forte
            'role' => 'admin', // Definindo o papel como admin
        ]);
        $this->call(SistemaConfiguracoesSeeder::class);
        $this->call(AtualizarImpostosProdutosSeeder::class);
    }
    
}
