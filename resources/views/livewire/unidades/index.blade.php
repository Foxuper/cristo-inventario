<?php

use App\Models\Unidad;
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
    public function eliminar(Unidad $unidad)
    {
        $unidad->delete();
        $this->info(__('Unit deleted'), position: 'toast-bottom');
    }

    // Encabezado de la tabla
    public function encabezado(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'nombre', 'label' => ucfirst(__('validation.attributes.name'))],
        ];
    }

    // Consulta de usuarios
    public function unidades(): Collection
    {
        return Unidad::query()
            ->when($this->busqueda, function (Builder $query) {
                $query->where('nombre', 'like', "%$this->busqueda%");
            })
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'unidades' => $this->unidades(),
            'encabezado' => $this->encabezado(),
            'cantidad_filtros' => $this->cantidad_filtros(),
        ];
    }
}; ?>

<div>
    <!-- Encabezado -->
    <x-header :title="__('Units')" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input :placeholder="__('Search').'...'" wire:model.live.debounce="busqueda" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" :badge="$cantidad_filtros" x-on:click="$wire.mostrar_filtros = true" responsive icon="o-funnel" />
            <x-button :label="__('Create')" link="/unidades/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- Tabla  -->
    <x-card>
        <x-table :headers="$encabezado" :rows="$unidades" :sort-by="$sortBy" link="unidades/{id}/edit">
            @scope('actions', $unidad)
            <x-button icon="o-trash" wire:click="eliminar({{ $unidad['id'] }})" :wire:confirm="__('Are you sure?')" spinner class="btn-ghost btn-sm text-red-500" />
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
