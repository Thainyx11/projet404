<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount('interventions')
            ->latest()
            ->paginate(20);

        return view('clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        $client->load(['interventions' => function($query) {
            $query->with('technicien')->latest();
        }]);

        return view('clients.show', compact('client'));
    }

    public function create()
    {
        Gate::authorize('create', Client::class);

        return view('clients.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Client::class);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:500',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès');
    }

    public function edit(Client $client)
    {
        Gate::authorize('update', $client);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        Gate::authorize('update', $client);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:500',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client modifié avec succès');
    }

    public function destroy(Client $client)
    {
        Gate::authorize('delete', $client);

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès');
    }
}
