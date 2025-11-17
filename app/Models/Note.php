<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervention_id',
        'user_id',
        'contenu',
    ];

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
