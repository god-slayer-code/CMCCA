<?php

namespace App\Policies;

use App\Models\Facture;
use App\Models\User;

class FacturePolicy
{
    public function appliquerRemise(User $user, Facture $facture): bool
    {
        if (in_array($facture->statut, ['payee', 'annulee'])) {
            return false;
        }

        return $user->aRole('super_admin', 'admin');
    }
}