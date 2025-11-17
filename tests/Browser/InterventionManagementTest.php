<?php

use App\Models\User;
use App\Models\Client;
use App\Models\Intervention;
use Laravel\Dusk\Browser;

test('un admin peut créer une intervention', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $client = Client::factory()->create([
        'nom' => 'Client Dusk Test',
        'email' => 'client.dusk@test.com',
    ]);

    $this->browse(function (Browser $browser) use ($admin, $client) {
        $browser->loginAs($admin)
                ->visit('/interventions/create')
                ->pause(500)
                ->assertSee('Nouvelle intervention')
                ->select('client_id', $client->id)
                ->select('type_appareil', 'Smartphone')
                ->type('description', 'Écran cassé suite à une chute importante')
                ->select('priorite', 'haute')
                ->pause(500)
                ->press('Créer l\'intervention')
                ->pause(1000)
                ->waitForText('Intervention créée avec succès')
                ->assertSee('Intervention créée avec succès');
    });

    expect(Intervention::where('type_appareil', 'Smartphone')
        ->where('priorite', 'haute')
        ->exists())->toBeTrue();
});

it('permet à un admin de changer le statut d\'une intervention', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $intervention = Intervention::factory()->create([
        'statut' => 'nouvelle_demande',
    ]);

    $this->browse(function (Browser $browser) use ($admin, $intervention) {
        $browser->loginAs($admin)
                ->visit("/interventions/{$intervention->id}/edit")
                ->pause(500)
                ->select('statut', 'en_reparation')
                ->pause(500)
                ->press('Enregistrer')
                ->pause(1000)
                ->waitForText('Intervention modifiée avec succès')
                ->assertSee('Intervention modifiée avec succès');
    });

    $intervention->refresh();
    expect($intervention->statut)->toBe('en_reparation');
});

test('un admin peut ajouter une note à une intervention', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $intervention = Intervention::factory()->create();

    $this->browse(function (Browser $browser) use ($admin, $intervention) {
        $browser->loginAs($admin)
                ->visit("/interventions/{$intervention->id}")
                ->pause(500)
                ->type('contenu', 'Ceci est une note de test Dusk importante')
                ->pause(500)
                ->press('Ajouter une note')
                ->pause(1000)
                ->waitForText('Note ajoutée avec succès')
                ->assertSee('Note ajoutée avec succès')
                ->assertSee('Ceci est une note de test Dusk importante');
    });

    expect($intervention->notes()->where('contenu', 'Ceci est une note de test Dusk importante')->exists())->toBeTrue();
});

it('permet de filtrer les interventions par statut', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Intervention::factory()->create(['statut' => 'nouvelle_demande']);
    Intervention::factory()->create(['statut' => 'termine']);

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
                ->visit('/interventions')
                ->pause(500)
                ->select('statut', 'termine')
                ->pause(500)
                ->press('Filtrer')
                ->pause(1000)
                ->waitForText('Terminé')
                ->assertSee('Terminé');
    });
});
