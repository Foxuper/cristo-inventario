<?php

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Illuminate\Validation\Rule;

new class extends Component
{
    use Toast;
    public User $user;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $this->fill($this->user);
    }

    // Reglas de validación
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => [
                'required', 'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'password' => 'nullable|confirmed',
        ];
    }

    // Editar usuario
    public function edit(): void
    {
        $datos = $this->validate();
        $this->user->update($datos);
        $this->success(__('User updated'), redirectTo: '/users', position: 'toast-bottom');
    }
} ?>

<div>
    <x-header :title="__('Edit').' '.$user->name" separator />

    <x-form wire:submit="edit">

        {{-- Basico --}}
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header :title="__('Basic')" :subtitle="__('Basic information about the user')" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                <x-input :label="ucfirst(__('validation.attributes.name'))" wire:model="name" />
                <x-input :label="ucfirst(__('validation.attributes.email'))" wire:model="email" />
            </div>
        </div>
        <x-menu-separator />

        {{-- Seguridad --}}
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header :title="__('Security')" :subtitle="__('Security information about the user')" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                <x-input :label="ucfirst(__('validation.attributes.password'))" wire:model="password" type="password" />
                <x-input :label="ucfirst(__('validation.attributes.password_confirmation'))" wire:model="password_confirmation" type="password" />
            </div>
        </div>

        {{-- Acciones --}}
        <x-slot:actions>
            <x-button :label="__('Cancel')" link="/users" />
            <x-button :label="__('Save')" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
