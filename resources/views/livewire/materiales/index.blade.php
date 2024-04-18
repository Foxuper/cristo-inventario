<?php

use App\Models\Linea;
use App\Models\Unidad;
use Mary\Traits\Toast;
use App\Models\Material;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

new class extends Component
{
    use Toast;
    public int $linea_id = 0;
    public int $unidad_medida_id = 0;
    public int $unidad_compra_id = 0;

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
        if ($this->linea_id > 0) $filtros++;
        if ($this->unidad_medida_id > 0) $filtros++;
        if ($this->unidad_compra_id > 0) $filtros++;
        return $filtros > 0 ? $filtros : null;
    }

    // Agregar cantidad
    public function agregar(int $material_id)
    {
        $material = Material::find($material_id);
        $material->update(['cantidad' => $material->cantidad + 1]);
    }

    // Quitar cantidad
    public function quitar(int $material_id)
    {
        $material = Material::find($material_id);
        $material->update(['cantidad' => $material->cantidad - 1]);
    }

    // Eliminar material
    public function eliminar(Material $material)
    {
        $material->delete();
        $this->info(__('Material deleted'), position: 'toast-bottom');
    }

    // Encabezado de la tabla
    public function encabezado(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'descripcion', 'label' => ucfirst(__('validation.attributes.description'))],
            ['key' => 'linea_nombre', 'label' => __('Line')],
            ['key' => 'unidad_medida_nombre', 'label' => __('Unit of measure')],
            ['key' => 'unidad_compra_nombre', 'label' => __('Unit of purchase')],
            ['key' => 'clave_sat', 'label' => __('SAT key')],
            ['key' => 'codigo', 'label' => __('Code')],
            ['key' => 'cantidad', 'label' => __('Quantity')],
            ['key' => 'precio', 'label' => __('Price')],
            ['key' => 'activo', 'label' => __('Active'), 'class' => 'w-1'],
        ];
    }

    // Consulta de materiales
    public function materiales(): Collection
    {
        return Material::query()
            ->withAggregate('linea', 'nombre')
            ->withAggregate('unidad_medida', 'nombre')
            ->withAggregate('unidad_compra', 'nombre')
            ->when($this->busqueda, function (Builder $query) {
                $query->where('descripcion', 'like', "%$this->busqueda%")
                    ->orWhere('clave_sat', 'like', "%$this->busqueda%")
                    ->orWhere('codigo', 'like', "%$this->busqueda%")
                    ->orWhere('cantidad', 'like', "%$this->busqueda%")
                    ->orWhere('precio', 'like', "%$this->busqueda%");
            })
            ->when($this->linea_id > 0, fn (Builder $query)
            => $query->where('linea_id', $this->linea_id))
            ->when($this->unidad_medida_id > 0, fn (Builder $query)
            => $query->where('unidad_medida_id', $this->unidad_medida_id))
            ->when($this->unidad_compra_id > 0, fn (Builder $query)
            => $query->where('unidad_compra_id', $this->unidad_compra_id))
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    // Exportar a CSV
    public function exportar()
    {
        $materiales = $this->materiales();
        $encabezado = array_map(fn ($columna) => $columna['key'], $this->encabezado());

        $archivo = fopen('php://temp', 'w');
        fputcsv($archivo, $encabezado);

        foreach ($materiales as $material)
            fputcsv($archivo, $material->only($encabezado));

        rewind($archivo);
        $csv = stream_get_contents($archivo);

        fclose($archivo);
        return response()->streamDownload(fn () => print($csv), 'materiales.csv');
    }

    public function with(): array
    {
        return [
            'lineas' => Linea::all(),
            'unidades' => Unidad::all(),
            'materiales' => $this->materiales(),
            'encabezado' => $this->encabezado(),
            'cantidad_filtros' => $this->cantidad_filtros(),
        ];
    }
}; ?>

<div>
    <!-- Encabezado -->
    <x-header :title="__('Materials')" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input :placeholder="__('Search').'...'" wire:model.live.debounce="busqueda" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" :badge="$cantidad_filtros" x-on:click="$wire.mostrar_filtros = true" responsive icon="o-funnel" />
            <x-button :label="__('Create')" link="/materiales/create" responsive icon="o-plus" class="btn-primary" />
            <x-button :label="__('Export')" icon="o-arrow-down-tray" class="btn-primary" wire:click="exportar" />
        </x-slot:actions>
    </x-header>

    <!-- Tabla  -->
    <x-card>
        <x-table :headers="$encabezado" :rows="$materiales" :sort-by="$sortBy" link="materiales/{id}/edit">
            @scope('cell_activo', $material)
            <x-badge :value="$material['activo'] ? __('Yes') : __('No')" :class="$material['activo'] ? 'badge-primary' : 'badge-danger'" />
            @endscope
            @scope('cell_precio', $material)
            ${{ number_format($material['precio'], 2) }}
            @endscope
            @scope('actions', $material)
            <div class="flex">
                <x-button icon="o-plus" wire:click="agregar({{ $material['id'] }})" class="btn-ghost btn-sm" />
                <x-button icon="o-minus" wire:click="quitar({{ $material['id'] }})" class="btn-ghost btn-sm" />
                <x-button icon="o-trash" wire:click="eliminar({{ $material['id'] }})" :wire:confirm="__('Are you sure?')" spinner class="btn-ghost btn-sm text-red-500" />
            </div>
            @endscope
        </x-table>
    </x-card>

    <!-- Filtros -->
    <x-drawer wire:model="mostrar_filtros" :title="__('Filters')" right separator with-close-button class="lg:w-1/3">
        <div class="grid gap-5">
            <x-input :placeholder="__('Search').'...'" wire:model.live.debounce="busqueda" icon="o-magnifying-glass" x-on:keydown.enter="$wire.mostrar_filtros = false" />
            <x-select :label="__('Line')" placeholder=" " wire:model.live="linea_id" :options="$lineas" icon="o-tag" option-label="nombre" placeholder-value="0" />
            <x-select :label="__('Unit of measure')" placeholder=" " wire:model.live="unidad_medida_id" :options="$unidades" icon="o-cube" option-label="nombre" placeholder-value="0" />
            <x-select :label="__('Unit of purchase')" placeholder=" " wire:model.live="unidad_compra_id" :options="$unidades" icon="o-cube" option-label="nombre" placeholder-value="0" />
        </div>

        <x-slot:actions>
            <x-button :label="__('Reset')" icon="o-x-mark" wire:click="limpiar" spinner />
            <x-button :label="__('Accept')" icon="o-check" class="btn-primary" x-on:click="$wire.mostrar_filtros = false" />
        </x-slot:actions>
    </x-drawer>
</div>
