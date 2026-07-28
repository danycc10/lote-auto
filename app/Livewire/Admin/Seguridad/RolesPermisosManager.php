<?php

namespace App\Livewire\Admin\Seguridad;

use App\Models\User;
use App\Services\Security\RoleAssignmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use RuntimeException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesPermisosManager extends Component
{
    public string $nuevoRol = '';

    public ?int $rolSeleccionadoId = null;

    public array $permisosSeleccionados = [];

    public ?int $usuarioSeleccionadoId = null;

    public array $rolesUsuario = [];

    public string $buscarUsuario = '';

    public function mount(): void
    {
        $this->authorizePermission('seguridad.roles');

        $this->rolSeleccionadoId = Role::query()->orderBy('name')->value('id');

        if ($this->rolSeleccionadoId) {
            $this->cargarPermisosRol();
        }
    }

    public function crearRol(): void
    {
        $this->authorizePermission('seguridad.roles');

        $this->validate([
            'nuevoRol' => ['required', 'string', 'max:100', 'unique:roles,name'],
        ]);

        $rol = Role::create([
            'name' => strtolower(trim($this->nuevoRol)),
            'guard_name' => 'web',
        ]);

        $this->nuevoRol = '';
        $this->rolSeleccionadoId = $rol->id;
        $this->permisosSeleccionados = [];

        session()->flash('success', 'Rol creado correctamente.');
    }

    public function seleccionarRol(int $rolId): void
    {
        $this->authorizePermission('seguridad.roles');

        $this->rolSeleccionadoId = $rolId;
        $this->cargarPermisosRol();
    }

    public function cargarPermisosRol(): void
    {
        $this->authorizePermission('seguridad.roles');

        $rol = Role::find($this->rolSeleccionadoId);

        $this->permisosSeleccionados = $rol
            ? $rol->permissions()->pluck('name')->toArray()
            : [];
    }

    public function guardarPermisosRol(): void
    {
        $this->authorizePermission('seguridad.roles');

        $rol = Role::findOrFail($this->rolSeleccionadoId);

        if ($rol->name === 'administrador') {
            $rol->syncPermissions(Permission::query()->pluck('name')->toArray());
        } else {
            $rol->syncPermissions($this->permisosSeleccionados);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        session()->flash('success', 'Permisos actualizados correctamente.');
    }

    public function eliminarRol(int $rolId): void
    {
        $this->authorizePermission('seguridad.roles');

        $rol = Role::findOrFail($rolId);

        if ($rol->name === 'administrador') {
            session()->flash('error', 'No puedes eliminar el rol administrador.');

            return;
        }

        if ($rol->users()->exists()) {
            session()->flash('error', 'No puedes eliminar un rol que tiene usuarios asignados.');

            return;
        }

        $rol->delete();

        $this->rolSeleccionadoId = Role::query()->orderBy('name')->value('id');
        $this->cargarPermisosRol();

        session()->flash('success', 'Rol eliminado correctamente.');
    }

    public function seleccionarUsuario(int $usuarioId): void
    {
        $this->authorizePermission('seguridad.usuarios');

        $this->usuarioSeleccionadoId = $usuarioId;

        $usuario = User::findOrFail($usuarioId);

        $this->rolesUsuario = $usuario->roles()->pluck('name')->toArray();
    }

    public function guardarRolesUsuario(RoleAssignmentService $roleAssignment): void
    {
        $actor = $this->authorizePermission('seguridad.usuarios');

        $this->validate([
            'usuarioSeleccionadoId' => ['required', 'integer', 'exists:users,id'],
            'rolesUsuario' => ['array'],
            'rolesUsuario.*' => ['string', 'exists:roles,name'],
        ]);

        $usuario = User::findOrFail($this->usuarioSeleccionadoId);

        try {
            $roleAssignment->syncRoles($actor, $usuario, $this->rolesUsuario);
        } catch (RuntimeException $exception) {
            session()->flash('error', $exception->getMessage());

            return;
        }

        session()->flash('success', 'Roles del usuario actualizados correctamente.');
    }

    public function render()
    {
        $roles = Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->get();

        $permisos = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($permiso) => explode('.', $permiso->name)[0] ?? 'otros');

        $usuarios = collect();

        if (Auth::user()?->can('seguridad.usuarios')) {
            $usuarios = User::query()
                ->with('roles')
                ->when($this->buscarUsuario, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%'.$this->buscarUsuario.'%')
                            ->orWhere('email', 'like', '%'.$this->buscarUsuario.'%');
                    });
                })
                ->orderBy('name')
                ->limit(30)
                ->get();
        }

        return view('livewire.admin.seguridad.roles-permisos-manager', [
            'roles' => $roles,
            'permisos' => $permisos,
            'usuarios' => $usuarios,
        ])->layout('layouts.app');
    }

    private function authorizePermission(string $permission): User
    {
        $user = Auth::user();

        abort_unless($user instanceof User && $user->can($permission), 403);

        return $user;
    }
}
