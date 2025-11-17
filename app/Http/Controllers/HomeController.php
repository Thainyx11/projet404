<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function submitContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'type_appareil' => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'telephone.required' => 'Le téléphone est obligatoire',
            'type_appareil.required' => 'Le type d\'appareil est obligatoire',
            'description.required' => 'La description est obligatoire',
            'description.min' => 'La description doit contenir au moins 10 caractères',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Créer ou récupérer le client
        $client = Client::firstOrCreate(
            ['email' => $request->email],
            [
                'nom' => $request->nom,
                'telephone' => $request->telephone,
            ]
        );

        // Créer l'intervention
        $intervention = Intervention::create([
            'client_id' => $client->id,
            'description' => $request->description,
            'type_appareil' => $request->type_appareil,
            'priorite' => 'normale',
            'statut' => 'nouvelle_demande',
        ]);

        return redirect()->back()->with('success', 'Votre demande a été enregistrée avec succès ! Nous vous contacterons bientôt.');
    }
}
