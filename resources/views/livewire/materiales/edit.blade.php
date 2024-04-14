<?php

use App\Models\Linea;
use App\Models\Unidad;
use Mary\Traits\Toast;
use App\Models\Material;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    use Toast;
    public Material $material;

    #[Validate('required')]
    public string $descripcion = '';

    #[Validate('required|exists:Linea,id')]
    public ?int $linea_id = null;

    #[Validate('required|exists:Unidad,id')]
    public ?int $unidad_medida_id = null;

    #[Validate('required|exists:Unidad,id')]
    public ?int $unidad_compra_id = null;

    #[Validate('nullable')]
    public ?string $clave_sat = null;

    #[Validate('nullable')]
    public ?string $codigo = null;

    #[Validate('required|integer|min:0')]
    public int $cantidad = 0;

    #[Validate('required|numeric|min:0')]
    public float $precio = 0;

    #[Validate('boolean')]
    public bool $activo = true;

    public function mount(): void
    {
        $this->fill($this->material);
    }

    // Editar material
    public function edit(): void
    {
        $datos = $this->validate();
        $this->material->update($datos);
        $this->success(__('Material updated'), redirectTo: '/materiales', position: 'toast-bottom');
    }

    public function with(): array
    {
        return [
            'lineas' => Linea::all(),
            'unidades' => Unidad::all(),
        ];
    }
} ?>

<div>
    <x-header :title="__('Edit').' '.$material->descripcion" separator />

    <x-form wire:submit="edit">

        {{-- Basico --}}
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header :title="__('Basic')" :subtitle="__('Basic information about the material')" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                <x-input :label="ucfirst(__('validation.attributes.description'))" wire:model="descripcion" />
                <x-select :label="__('Line')" placeholder=" " wire:model.live="linea_id" :options="$lineas" icon="o-tag" option-label="nombre" :placeholder-value="null" />
                <x-select :label="__('Unit of measure')" placeholder=" " wire:model.live="unidad_medida_id" :options="$unidades" icon="o-cube" option-label="nombre" :placeholder-value="null" />
                <x-select :label="__('Unit of purchase')" placeholder=" " wire:model.live="unidad_compra_id" :options="$unidades" icon="o-cube" option-label="nombre" :placeholder-value="null" />
                <x-input :label="__('Price')" wire:model="precio" prefix="$" />
            </div>
        </div>
        <x-menu-separator />

        {{-- Identificación --}}
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header :title="__('Identification')" :subtitle="__('Identification information about the material')" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                <x-input :label="__('SAT key')" wire:model="clave_sat" />
                <x-input :label="__('Code')" wire:model="codigo" />
            </div>
        </div>
        <x-menu-separator />

        {{-- Estado --}}
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header :title="__('Status')" :subtitle="__('Status information about the material')" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                <x-input :label="__('Quantity')" wire:model="cantidad" />
                <div class="h-2"></div>
                <x-checkbox :label="__('Active')" wire:model="activo" />
            </div>
        </div>

        {{-- Acciones --}}
        <x-slot:actions>
            <x-button :label="__('Cancel')" link="/materiales" />
            <x-button :label="__('Save')" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
