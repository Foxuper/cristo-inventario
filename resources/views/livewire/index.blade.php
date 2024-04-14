<?php

use App\Models\User;
use App\Models\Linea;
use App\Models\Unidad;
use App\Models\Material;
use Livewire\Volt\Component;

new class extends Component
{
    public function with(): array
    {
        return [
            'usuarios' => User::all(),
            'unidades' => Unidad::all(),
            'lineas' => Linea::all(),
            'materiales' => Material::all(),
        ];
    }
} ?>

<div>
    <!-- Encabezado -->
    <x-header :title="__('Home')" separator />

    <!-- Contenido -->
    <x-card>
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">

            <!-- Cantidad de usuarios -->
            <div class="col-span-1 flex items-center justify-between">
                <div class="flex items-center">
                    <x-icon name="o-users" class="mr-2 text-2xl" />{{ __('Users') }}:
                    <span class="ml-2 text-2xl font-medium">{{ $usuarios->count() }}</span>
                </div>
            </div>

            <!-- Cantidad de unidades -->
            <div class="col-span-1 flex items-center justify-between">
                <div class="flex items-center">
                    <x-icon name="o-cube" class="mr-2 text-2xl" />{{ __('Units') }}:
                    <span class="ml-2 text-2xl font-medium">{{ $unidades->count() }}</span>
                </div>
            </div>

            <!-- Cantidad de lineas -->
            <div class="col-span-1 flex items-center justify-between">
                <div class="flex items-center">
                    <x-icon name="o-tag" class="mr-2 text-2xl" />{{ __('Lines') }}:
                    <span class="ml-2 text-2xl font-medium">{{ $lineas->count() }}</span>
                </div>
            </div>

            <!-- Cantidad de materiales -->
            <div class="col-span-1 flex items-center justify-between">
                <div class="flex items-center">
                    <x-icon name="o-rectangle-group" class="mr-2 text-2xl" />{{ __('Materials') }}:
                    <span class="ml-2 text-2xl font-medium">{{ $materiales->count() }}</span>
                </div>
            </div>
        </div>
    </x-card>
</div>
