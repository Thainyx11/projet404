<?php

use App\Models\User;
use App\Models\Client;
use App\Models\Intervention;
use Laravel\Dusk\Browser;

test('un technicien ne peut pas créer de client', function () {
    $technicien = User::factory()->create(['role' => 'technicien']);

    $this->browse(function (Browser $browser) use ($technicien) {
        $browser->loginAs($technicien)
                ->visit('/clients')
                ->pause(1000)
                ->assertDontSee('+ Nouveau client');
    });
});

it('affiche uniquement les interventions assignées au technicien connecté', function () {
    $technicien = User::factory()->create(['role' => 'technicien']);
    $autreTechnicien = User::factory()->create(['role' => 'technicien']);

    $mesInterventions = Intervention::factory()->create([
        'technicien_id' => $technicien->id,
    ]);

    $autresInterventions = Intervention::factory()->create([
        'technicien_id' => $autreTechnicien->id,
    ]);

    $this->browse(function (Browser $browser) use ($technicien, $mesInterventions, $autresInterventions) {
        $browser->loginAs($technicien)
                ->visit('/interventions')
                ->pause(1000)
                ->assertSee("#{$mesInterventions->id}")
                ->assertDontSee("#{$autresInterventions->id}");
    });
});

test('un admin peut supprimer un client', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $client = Client::factory()->create();

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
                ->visit('/clients')
                ->pause(1000)
                ->assertSee('Supprimer');
    });
});

it('empêche un technicien de supprimer un client', function () {
    $technicien = User::factory()->create(['role' => 'technicien']);
    $client = Client::factory()->create();

    $this->browse(function (Browser $browser) use ($technicien) {
        $browser->loginAs($technicien)
                ->visit('/clients')
                ->pause(1000)
                ->assertDontSee('Supprimer');
    });
});

test('un admin peut réassigner une intervention à un autre technicien', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $technicien1 = User::factory()->create(['role' => 'technicien', 'name' => 'Tech 1']);
    $technicien2 = User::factory()->create(['role' => 'technicien', 'name' => 'Tech 2']);

    $intervention = Intervention::factory()->create([
        'technicien_id' => $technicien1->id,
    ]);

    $this->browse(function (Browser $browser) use ($admin, $intervention, $technicien2) {
        $browser->loginAs($admin)
                ->visit("/interventions/{$intervention->id}/edit")
                ->pause(500)
                ->assertPresent('select[name="technicien_id"]')
                ->select('technicien_id', $technicien2->id)
                ->pause(500)
                ->press('Enregistrer')
                ->pause(1000)
                ->waitForText('Intervention modifiée avec succès');
    });

    $intervention->refresh();
    expect($intervention->technicien_id)->toBe($technicien2->id);
});
