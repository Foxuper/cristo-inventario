<?php

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

new class extends Component
{
    use Toast;

    public string $busqueda = '';
    public bool $mostrar_filtros = false;
    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    // Limpiar filtros
    public function limpiar(): void
    {
        $this->reset();
        $this->success(__('Filters cleared'), position: 'toast-bottom');
    }

    // Cantidad de filtros activos
    public function cantidad_filtros(): ?int
    {
        $filtros = 0;
        if ($this->busqueda) $filtros++;
        return $filtros > 0 ? $filtros : null;
    }

    // Eliminar usuario
    public function eliminar(User $usuario)
    {
        if ($es_usuario_actual = $usuario == auth()->user())
            auth()->logout();
        if ($usuario->delete() && $es_usuario_actual)
            return redirect()->to('/login');
        $this->info(__('User deleted'), position: 'toast-bottom');
    }

    // Encabezado de la tabla
    public function encabezado(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => ucfirst(__('name')), 'class' => 'w-64'],
            ['key' => 'email', 'label' => ucfirst(__('email'))],
        ];
    }

    // Consulta de usuarios
    public function usuarios(): Collection
    {
        return User::query()
            ->when($this->busqueda, function (Builder $query) {
                $query->where('name', 'like', "%$this->busqueda%")
                    ->orWhere('email', 'like', "%$this->busqueda%");
            })
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'usuarios' => $this->usuarios(),
            'encabezado' => $this->encabezado(),
            'cantidad_filtros' => $this->cantidad_filtros(),
        ];
    }
}; ?>

<div>
    <!-- Encabezado -->
    <x-header :title="__('Users')" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input :placeholder="__('Search').'...'" wire:model.live.debounce="busqueda" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" :badge="$cantidad_filtros" x-on:click="$wire.mostrar_filtros = true" responsive icon="o-funnel" />
        </x-slot:actions>
    </x-header>

    <!-- Tabla  -->
    <x-card>
        <x-table :headers="$encabezado" :rows="$usuarios" :sort-by="$sortBy">
            @scope('actions', $user)
            <x-button icon="o-trash" wire:click="eliminar({{ $user['id'] }})" :wire:confirm="__('Are you sure?')" spinner class="btn-ghost btn-sm text-red-500" />
            @endscope
        </x-table>
    </x-card>

    <!-- Filtros -->
    <x-drawer wire:model="mostrar_filtros" :title="__('Filters')" right separator with-close-button class="lg:w-1/3">
        <x-input :placeholder="__('Search').'...'" wire:model.live.debounce="busqueda" icon="o-magnifying-glass" x-on:keydown.enter="$wire.mostrar_filtros = false" />

        <x-slot:actions>
            <x-button :label="__('Reset')" icon="o-x-mark" wire:click="limpiar" spinner />
            <x-button :label="__('Accept')" icon="o-check" class="btn-primary" x-on:click="$wire.mostrar_filtros = false" />
        </x-slot:actions>
    </x-drawer>
</div>
