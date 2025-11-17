<?php

use App\Models\Client;
use App\Models\Intervention;
use Laravel\Dusk\Browser;

test('le formulaire de contact crée un client et une intervention', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->assertSee('Atelier 404')
                ->scrollIntoView('#contact')
                ->type('nom', 'Jean Dupont Test')
                ->type('email', 'jean.dusk@example.com')
                ->type('telephone', '0123456789')
                ->select('type_appareil', 'Ordinateur portable')
                ->type('description', 'Mon ordinateur ne démarre plus depuis ce matin')
                ->pause(500)
                ->press('Envoyer ma demande')
                ->pause(1000)
                ->waitForText('Votre demande a été enregistrée avec succès')
                ->assertSee('Votre demande a été enregistrée avec succès');
    });

    expect(Client::where('email', 'jean.dusk@example.com')->exists())->toBeTrue();
    expect(Intervention::where('type_appareil', 'Ordinateur portable')->exists())->toBeTrue();
});

it('affiche des erreurs de validation quand les champs sont vides', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->scrollIntoView('#contact')
                ->pause(500)
                ->press('Envoyer ma demande')
                ->pause(1000)
                ->waitForText('Le nom est obligatoire')
                ->assertSee('Le nom est obligatoire')
                ->assertSee('L\'email est obligatoire');
    });
});

it('valide que la description doit contenir au moins 10 caractères', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->scrollIntoView('#contact')
                ->type('nom', 'Test User')
                ->type('email', 'test@example.com')
                ->type('telephone', '0123456789')
                ->select('type_appareil', 'Smartphone')
                ->type('description', 'Court')
                ->pause(500)
                ->press('Envoyer ma demande')
                ->pause(1000)
                ->waitForText('au moins 10 caractères')
                ->assertSee('au moins 10 caractères');
    });
});
