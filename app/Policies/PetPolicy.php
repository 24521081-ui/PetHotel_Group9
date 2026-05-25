<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    public function book(User $user, Pet $pet): bool
    {
        return $user->customer
            && (int) $user->customer->customer_id === (int) $pet->customer_id;
    }
}
