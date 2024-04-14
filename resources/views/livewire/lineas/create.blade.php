<?php

use App\Models\Linea;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    use Toast;

    #[Validate('required')]
    public string $nombre = '';

    // Crear linea
    public function create(): void
    {
        $datos = $this->validate();
        Linea::create($datos);
        $this->success(__('Line created'), redirectTo: '/lineas', position: 'toast-bottom');
    }
} ?>

<div>
    <x-header :title="__('Create').' '.__('Line')" separator />

    <x-form wire:submit="create">

        {{-- Basico --}}
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header :title="__('Basic')" :subtitle="__('Basic information about the line')" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                <x-input :label="ucfirst(__('validation.attributes.name'))" wire:model="nombre" />
            </div>
        </div>

        {{-- Acciones --}}
        <x-slot:actions>
            <x-button :label="__('Cancel')" link="/lineas" />
            <x-button :label="__('Save')" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
