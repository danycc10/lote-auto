<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use App\Support\DemoMode;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    public function __construct(
        private DemoMode $demoMode,
    ) {}

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        $this->demoMode->ensureChangesAreAllowed();

        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
