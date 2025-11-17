<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Dashboard admin
            $interventions = Intervention::with(['client', 'technicien'])
                ->latest()
                ->paginate(15);

            $stats = [
                'total' => Intervention::count(),
                'nouvelle_demande' => Intervention::where('statut', 'nouvelle_demande')->count(),
                'en_cours' => Intervention::whereIn('statut', ['diagnostic', 'en_reparation'])->count(),
                'termine' => Intervention::where('statut', 'termine')->count(),
                'non_reparable' => Intervention::where('statut', 'non_reparable')->count(),
            ];

            return view('dashboard.admin', compact('interventions', 'stats'));
        } else {
            // Dashboard technicien
            $interventions = Intervention::with(['client'])
                ->where('technicien_id', $user->id)
                ->latest()
                ->paginate(15);

            return view('dashboard.technicien', compact('interventions'));
        }
    }
}
