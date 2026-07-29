<?php

namespace App\Services\Security;

use App\Models\User;
use App\Support\DemoMode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Spatie\Permission\Models\Role;

class RoleAssignmentService
{
    public function __construct(
        private DemoMode $demoMode,
    ) {}

    /**
     * @param  array<int, string>  $roleNames
     */
    public function syncRoles(User $actor, User $target, array $roleNames): void
    {
        $this->demoMode->ensureChangesAreAllowed();

        $this->authorizeAssignment($actor, $roleNames);

        DB::transaction(function () use ($actor, $target, $roleNames): void {
            $administratorRole = $this->lockAdministratorRole();
            $lockedTarget = User::query()->lockForUpdate()->findOrFail($target->getKey());
            $targetIsAdministrator = $lockedTarget->roles()->whereKey($administratorRole->getKey())->exists();

            if (
                ($targetIsAdministrator || in_array($administratorRole->name, $roleNames, true))
                && ! $actor->can('seguridad.roles.asignar_administrador')
            ) {
                throw new AuthorizationException;
            }

            if (
                $targetIsAdministrator
                && ! in_array($administratorRole->name, $roleNames, true)
            ) {
                $this->ensureAnotherAdministratorExists($administratorRole, $lockedTarget);
            }

            $lockedTarget->syncRoles($roleNames);
        }, 3);
    }

    public function delete(User $actor, User $target): void
    {
        $this->demoMode->ensureChangesAreAllowed();

        if (! $actor->can('seguridad.usuarios')) {
            throw new AuthorizationException;
        }

        if ($actor->is($target)) {
            throw new RuntimeException('No puedes eliminar tu propia cuenta.');
        }

        DB::transaction(function () use ($actor, $target): void {
            $administratorRole = $this->lockAdministratorRole();
            $lockedTarget = User::query()->lockForUpdate()->findOrFail($target->getKey());

            if ($lockedTarget->roles()->whereKey($administratorRole->getKey())->exists()) {
                if (! $actor->can('seguridad.roles.asignar_administrador')) {
                    throw new AuthorizationException;
                }

                $this->ensureAnotherAdministratorExists($administratorRole, $lockedTarget);
            }

            $lockedTarget->delete();
        }, 3);
    }

    /**
     * @param  array<int, string>  $roleNames
     */
    private function authorizeAssignment(User $actor, array $roleNames): void
    {
        if (! $actor->can('seguridad.usuarios')) {
            throw new AuthorizationException;
        }

        if (
            in_array('administrador', $roleNames, true)
            && ! $actor->can('seguridad.roles.asignar_administrador')
        ) {
            throw new AuthorizationException;
        }
    }

    private function lockAdministratorRole(): Role
    {
        return Role::query()
            ->where('name', 'administrador')
            ->where('guard_name', 'web')
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function ensureAnotherAdministratorExists(Role $administratorRole, User $target): void
    {
        $anotherAdministratorExists = User::query()
            ->whereKeyNot($target->getKey())
            ->whereHas('roles', fn ($query) => $query->whereKey($administratorRole->getKey()))
            ->exists();

        if (! $anotherAdministratorExists) {
            throw new RuntimeException('Debe existir al menos otro usuario administrador.');
        }
    }
}
