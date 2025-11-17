<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Intervention;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un admin
        User::factory()->admin()->create([
            'name' => 'Admin Atelier',
            'email' => 'admin@atelier404.be',
        ]);

        // Créer des techniciens
        User::factory()->count(5)->create();

        // Créer des clients et interventions
        Client::factory()
            ->count(20)
            ->has(Intervention::factory()->count(rand(1, 3)))
            ->create();
    }
}
