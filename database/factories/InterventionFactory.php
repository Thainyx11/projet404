<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterventionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'technicien_id' => User::where('role', 'technicien')->inRandomOrder()->first()?->id,
            'description' => fake()->paragraph(),
            'type_appareil' => fake()->randomElement(['Ordinateur portable', 'PC fixe', 'Smartphone', 'Tablette', 'Imprimante']),
            'priorite' => fake()->randomElement(['basse', 'normale', 'haute']),
            'statut' => fake()->randomElement(['nouvelle_demande', 'diagnostic', 'en_reparation', 'termine', 'non_reparable']),
            'date_prevue' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
