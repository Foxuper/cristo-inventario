<?php

use App\Models\Linea;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Traits\ClearsFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component
{
    use Toast, WithPagination, ClearsFilters;

    public string $busqueda = '';
    public bool $mostrar_filtros = false;
    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    // Cantidad de filtros activos
    public function cantidad_filtros(): ?int
    {
        $filtros = 0;
        if ($this->busqueda) $filtros++;
        return $filtros > 0 ? $filtros : null;
    }

    // Eliminar usuario
    public function eliminar(Linea $unidad)
    {
        $unidad->delete();
        $this->info(__('Line deleted'), position: 'toast-bottom');
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
    public function unidades(): LengthAwarePaginator
    {
        return Linea::query()
            ->when($this->busqueda, function (Builder $query) {
                $query->where('nombre', 'like', "%$this->busqueda%");
            })
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
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
    <x-header :title="__('Lines')" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input :placeholder="__('Search').'...'" wire:model.live.debounce="busqueda" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" :badge="$cantidad_filtros" x-on:click="$wire.mostrar_filtros = true" responsive icon="o-funnel" />
            <x-button :label="__('Create')" link="/lineas/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- Tabla  -->
    <x-card>
        <x-table :headers="$encabezado" :rows="$unidades" :sort-by="$sortBy" link="lineas/{id}/edit" with-pagination>
            @scope('actions', $linea)
            <x-button icon="o-trash" wire:click="eliminar({{ $linea['id'] }})" :wire:confirm="__('Are you sure?')" spinner class="btn-ghost btn-sm text-red-500" />
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
