<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Usuario',
            'email' => 'usuario@correo.com',
            'password' => '1234',
        ]);

        $this->call(UnidadSeeder::class);
        $this->call(LineaSeeder::class);
        $this->call(MaterialSeeder::class);
    }
}
