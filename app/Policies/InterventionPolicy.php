<?php

namespace App\Policies;

use App\Models\Intervention;
use App\Models\User;

class InterventionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Intervention $intervention): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Technicien peut voir uniquement ses interventions
        return $intervention->technicien_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Intervention $intervention): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Technicien peut modifier uniquement ses interventions
        return $intervention->technicien_id === $user->id;
    }

    public function delete(User $user, Intervention $intervention): bool
    {
        return $user->isAdmin();
    }

    public function assignTechnicien(User $user): bool
    {
        return $user->isAdmin();
    }

    public function addNote(User $user, Intervention $intervention): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $intervention->technicien_id === $user->id;
    }
}
