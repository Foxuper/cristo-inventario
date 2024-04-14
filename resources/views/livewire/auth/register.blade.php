<?php

use App\Models\User;
use Illuminate\View\View;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('components.layouts.empty')]
class extends Component
{
    #[Validate('required')]
    public string $name = '';

    #[Validate('required|email|unique:users')]
    public string $email = '';

    #[Validate('required|confirmed')]
    public string $password = '';

    #[Validate('required')]
    public string $password_confirmation = '';

    public function rendering(View $view): void
    {
        $view->title(__('Register'));
    }

    public function mount()
    {
        if (auth()->user())
            return redirect('/');
    }

    public function register()
    {
        $data = $this->validate();
        auth()->login(User::create($data));
        request()->session()->regenerate();
        return redirect('/');
    }
} ?>

<div class="mx-auto mt-20 md:w-96">
    <div class="mb-10">
        <img src="{{ Vite::asset('resources/img/logo_full.png') }}" alt="Logo" class="mx-auto w-2/3" />
    </div>

    <x-form wire:submit="register">
        <x-input :label="ucfirst(__('validation.attributes.name'))"
            wire:model="name" icon="o-user" inline />
        <x-input :label="ucfirst(__('validation.attributes.email'))"
            wire:model="email" icon="o-envelope" inline />
        <x-input :label="ucfirst(__('validation.attributes.password'))"
            wire:model="password" type="password" icon="o-key" inline />
        <x-input :label="ucfirst(__('validation.attributes.password_confirmation'))"
            wire:model="password_confirmation" type="password" icon="o-key" inline />

        <x-slot:actions>
            <x-button :label="__('auth.already_registered')"
                class="btn-ghost" link="/login" />
            <x-button :label="__('Register')"
                class="btn-primary" type="submit" icon="o-paper-airplane" spinner="register" />
        </x-slot:actions>
    </x-form>
</div>
