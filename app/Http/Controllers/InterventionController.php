<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Client;
use App\Models\User;
use App\Models\InterventionImage;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class InterventionController extends Controller
{
    public function index(Request $request)
    {
        $query = Intervention::with(['client', 'technicien']);

        // Filtrage pour les techniciens
        if (Auth::user()->isTechnicien()) {
            $query->where('technicien_id', Auth::id());
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('type_appareil', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        if ($request->filled('technicien_id')) {
            $query->where('technicien_id', $request->technicien_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $interventions = $query->latest()->paginate(15);

        $techniciens = User::where('role', 'technicien')->get();

        return view('interventions.index', compact('interventions', 'techniciens'));
    }

    public function show(Intervention $intervention)
    {
        Gate::authorize('view', $intervention);

        $intervention->load(['client', 'technicien', 'images', 'notes.user']);

        return view('interventions.show', compact('intervention'));
    }

    public function create()
    {
        Gate::authorize('create', Intervention::class);

        $clients = Client::orderBy('nom')->get();
        $techniciens = User::where('role', 'technicien')->orderBy('name')->get();

        return view('interventions.create', compact('clients', 'techniciens'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Intervention::class);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'required|string|min:10',
            'type_appareil' => 'required|string|max:255',
            'priorite' => 'required|in:basse,normale,haute',
            'technicien_id' => 'nullable|exists:users,id',
            'date_prevue' => 'nullable|date|after_or_equal:today',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $intervention = Intervention::create($validated);

        // Upload des images
        if ($request->hasFile('images')) {
            $this->uploadImages($request->file('images'), $intervention);
        }

        return redirect()->route('interventions.show', $intervention)
            ->with('success', 'Intervention créée avec succès');
    }

    public function edit(Intervention $intervention)
    {
        Gate::authorize('update', $intervention);

        $clients = Client::orderBy('nom')->get();
        $techniciens = User::where('role', 'technicien')->orderBy('name')->get();

        return view('interventions.edit', compact('intervention', 'clients', 'techniciens'));
    }

    public function update(Request $request, Intervention $intervention)
    {
        Gate::authorize('update', $intervention);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'required|string|min:10',
            'type_appareil' => 'required|string|max:255',
            'priorite' => 'required|in:basse,normale,haute',
            'statut' => 'required|in:nouvelle_demande,diagnostic,en_reparation,termine,non_reparable',
            'technicien_id' => 'nullable|exists:users,id',
            'date_prevue' => 'nullable|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Seul l'admin peut réassigner
        if (!Auth::user()->isAdmin()) {
            unset($validated['technicien_id']);
        }

        $intervention->update($validated);

        // Upload des nouvelles images
        if ($request->hasFile('images')) {
            $this->uploadImages($request->file('images'), $intervention);
        }

        return redirect()->route('interventions.show', $intervention)
            ->with('success', 'Intervention modifiée avec succès');
    }

    public function destroy(Intervention $intervention)
    {
        Gate::authorize('delete', $intervention);

        // Supprimer les images physiques
        foreach ($intervention->images as $image) {
            Storage::disk('public')->delete($image->chemin);
            if ($image->chemin_miniature) {
                Storage::disk('public')->delete($image->chemin_miniature);
            }
        }

        $intervention->delete();

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention supprimée avec succès');
    }

    public function addNote(Request $request, Intervention $intervention)
    {
        Gate::authorize('addNote', $intervention);

        $request->validate([
            'contenu' => 'required|string|min:5',
        ]);

        Note::create([
            'intervention_id' => $intervention->id,
            'user_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        return redirect()->back()->with('success', 'Note ajoutée avec succès');
    }

    public function deleteImage(InterventionImage $image)
    {
        $intervention = $image->intervention;
        Gate::authorize('update', $intervention);

        // Supprimer les fichiers physiques
        Storage::disk('public')->delete($image->chemin);
        if ($image->chemin_miniature) {
            Storage::disk('public')->delete($image->chemin_miniature);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès');
    }

    public function export(Request $request)
    {
        Gate::authorize('create', Client::class); // Seuls les admins

        $query = Intervention::with(['client', 'technicien']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('type_appareil', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $interventions = $query->get();

        $filename = 'interventions_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($interventions) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-têtes
            fputcsv($file, [
                'ID',
                'Client',
                'Email Client',
                'Téléphone',
                'Type Appareil',
                'Description',
                'Priorité',
                'Statut',
                'Technicien',
                'Date Prévue',
                'Date Création',
            ], ';');

            // Données
            foreach ($interventions as $intervention) {
                fputcsv($file, [
                    $intervention->id,
                    $intervention->client->nom,
                    $intervention->client->email,
                    $intervention->client->telephone,
                    $intervention->type_appareil,
                    $intervention->description,
                    $intervention->priorite_label,
                    $intervention->statut_label,
                    $intervention->technicien?->name ?? 'Non assigné',
                    $intervention->date_prevue?->format('d/m/Y') ?? '',
                    $intervention->created_at->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function uploadImages($files, Intervention $intervention)
    {
        foreach ($files as $file) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('interventions', $filename, 'public');

            // Créer miniature (optionnel si vous avez Intervention/Image installé)
            // Sinon, laissez chemin_miniature à null
            $thumbnailPath = null;

            InterventionImage::create([
                'intervention_id' => $intervention->id,
                'chemin' => $path,
                'chemin_miniature' => $thumbnailPath,
            ]);
        }
    }
}
