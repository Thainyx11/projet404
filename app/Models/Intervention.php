<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'technicien_id',
        'description',
        'type_appareil',
        'priorite',
        'statut',
        'date_prevue',
    ];

    protected function casts(): array
    {
        return [
            'date_prevue' => 'date',
        ];
    }

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    public function images()
    {
        return $this->hasMany(InterventionImage::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    // Méthodes utiles
    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'nouvelle_demande' => 'Nouvelle demande',
            'diagnostic' => 'Diagnostic',
            'en_reparation' => 'En réparation',
            'termine' => 'Terminé',
            'non_reparable' => 'Non réparable',
            default => $this->statut,
        };
    }

    public function getPrioriteLabelAttribute(): string
    {
        return match($this->priorite) {
            'basse' => 'Basse',
            'normale' => 'Normale',
            'haute' => 'Haute',
            default => $this->priorite,
        };
    }
}
