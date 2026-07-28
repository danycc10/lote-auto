<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use App\Services\Security\RoleAssignmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public string $busqueda = '';

    public string $filtroRol = '';

    public string $modo = '';

    public ?int $usuarioId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $rol = '';

    public function mount(): void
    {
        $this->authorizeUsers();
    }

    public function updatingBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatingFiltroRol(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->usuarioId)],
            'rol' => ['required', 'string', 'exists:roles,name'],
        ];

        if ($this->modo === 'crear') {
            $rules['password'] = ['required', 'string', Password::min(12)->letters()->mixedCase()->numbers()->symbols()];
        } elseif ($this->password !== '') {
            $rules['password'] = ['string', Password::min(12)->letters()->mixedCase()->numbers()->symbols()];
        }

        return $rules;
    }

    protected array $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'Debe ser un correo válido.',
        'email.unique' => 'Este correo ya está en uso.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 12 caracteres.',
        'rol.required' => 'Selecciona un rol.',
        'rol.exists' => 'El rol seleccionado no existe.',
    ];

    public function iniciarCrear(): void
    {
        $this->authorizeUsers();

        $this->resetCampos();
        $this->modo = 'crear';
    }

    public function iniciarEditar(int $id): void
    {
        $actor = $this->authorizeUsers();
        $usuario = User::with('roles')->findOrFail($id);

        if ($usuario->hasRole('administrador')) {
            abort_unless($actor->can('seguridad.roles.asignar_administrador'), 403);
        }

        $this->usuarioId = $id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->password = '';
        $this->rol = $usuario->roles->first()?->name ?? '';
        $this->modo = 'editar';
    }

    public function guardar(RoleAssignmentService $roleAssignment): void
    {
        $actor = $this->authorizeUsers();
        $this->validate();

        if ($this->rol === 'administrador') {
            abort_unless($actor->can('seguridad.roles.asignar_administrador'), 403);
        }

        if ($this->modo === 'crear') {
            DB::transaction(function () use ($actor, $roleAssignment): void {
                $usuario = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);

                $roleAssignment->syncRoles($actor, $usuario, [$this->rol]);
            });

            session()->flash('success', 'Usuario creado correctamente.');
        } else {
            DB::transaction(function () use ($actor, $roleAssignment): void {
                $usuario = User::findOrFail($this->usuarioId);
                $usuario->name = $this->name;
                $usuario->email = $this->email;

                if ($this->password !== '') {
                    $usuario->password = Hash::make($this->password);
                }

                $usuario->save();
                $roleAssignment->syncRoles($actor, $usuario, [$this->rol]);
            });

            session()->flash('success', 'Usuario actualizado correctamente.');
        }

        $this->cancelar();
    }

    public function eliminar(int $id, RoleAssignmentService $roleAssignment): void
    {
        $actor = $this->authorizeUsers();
        $target = User::findOrFail($id);

        try {
            $roleAssignment->delete($actor, $target);
        } catch (RuntimeException $exception) {
            session()->flash('error', $exception->getMessage());

            return;
        }

        session()->flash('success', 'Usuario eliminado correctamente.');

        if ($this->usuarioId === $id) {
            $this->cancelar();
        }
    }

    public function cancelar(): void
    {
        $this->modo = '';
        $this->resetCampos();
    }

    private function resetCampos(): void
    {
        $this->usuarioId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->rol = '';
        $this->resetValidation();
    }

    public function render()
    {
        $actor = $this->authorizeUsers();

        $usuarios = User::query()
            ->with('roles')
            ->when($this->busqueda !== '', fn ($q) => $q->where('name', 'like', "%{$this->busqueda}%")
                ->orWhere('email', 'like', "%{$this->busqueda}%")
            )
            ->when($this->filtroRol !== '', fn ($q) => $q->whereHas('roles', fn ($r) => $r->where('name', $this->filtroRol))
            )
            ->orderBy('name')
            ->paginate(15);

        $roles = Role::query()
            ->when(
                ! $actor->can('seguridad.roles.asignar_administrador'),
                fn ($query) => $query->where('name', '!=', 'administrador'),
            )
            ->orderBy('name')
            ->pluck('name');

        return view('livewire.admin.usuarios.index', [
            'usuarios' => $usuarios,
            'roles' => $roles,
        ])->layout('layouts.app');
    }

    private function authorizeUsers(): User
    {
        $user = Auth::user();

        abort_unless($user instanceof User && $user->can('seguridad.usuarios'), 403);

        return $user;
    }
}
