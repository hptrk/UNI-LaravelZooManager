<?php

namespace App\Policies;

use App\Models\Enclosure;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnclosurePolicy
{
    public function viewAny(User $user): bool
    {
        // every authenticated user can view the list of enclosures
        return true;
    }

    public function view(User $user, Enclosure $enclosure): bool
    {
        // admin or user assigned to the enclosure can view it
        return $user->admin || $user->enclosures->contains($enclosure);
    }

    public function create(User $user): bool
    {
        // only admin can create enclosures
        return $user->admin;
    }

    public function update(User $user, Enclosure $enclosure): bool
    {
        // only admin can update enclosures
        return $user->admin;
    }

    public function delete(User $user, Enclosure $enclosure): bool
    {
        // only admin can delete enclosures
        return $user->admin;
    }

    public function restore(User $user, Enclosure $enclosure): bool
    {
        return false;
    }

    public function forceDelete(User $user, Enclosure $enclosure): bool
    {
        return false;
    }
}
