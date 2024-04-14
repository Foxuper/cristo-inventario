<?php

use Illuminate\View\View;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('components.layouts.empty')] #[Title('Login')]
class extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function rendering(View $view): void
    {
        $view->title(__('Login'));
    }

    public function mount()
    {
        if (auth()->user())
            return redirect('/');
    }

    public function login()
    {
        $credenciales = $this->validate();

        if (auth()->attempt($credenciales, $this->remember)) {
            request()->session()->regenerate();
            return redirect()->intended('/');
        }

        $this->addError('email', __('auth.failed'));
    }
} ?>

<div class="mx-auto mt-20 md:w-96">
    <div class="mb-10">
        <img src="{{ Vite::asset('resources/img/logo_full.png') }}" alt="Logo" class="mx-auto w-2/3" />
    </div>

    <x-form wire:submit="login">
        <x-input :label="ucfirst(__('validation.attributes.email'))"
            wire:model="email" icon="o-envelope" inline />
        <x-input :label="ucfirst(__('validation.attributes.password'))"
            wire:model="password" type="password" icon="o-key" inline />
        <x-checkbox :label="ucfirst(__('auth.remember_me'))"
            wire:model="remember" />

        <x-slot:actions>
            <x-button :label="ucfirst(__('auth.create_account'))"
                class="btn-ghost" link="/register" />
            <x-button :label="__('Login')"
                class="btn-primary" type="submit" icon="o-paper-airplane"  spinner="login" />
        </x-slot:actions>
    </x-form>
</div>
