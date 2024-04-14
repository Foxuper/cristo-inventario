<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

// Ruta de inicio de sesión
Volt::route('/login', 'auth.login')->name('login');

// Ruta de registro
Volt::route('/register', 'auth.register');

// Ruta de cierre de sesión
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Volt::route('/', 'index');
    Volt::route('/users', 'users.index');
    Volt::route('/users/create', 'users.create');
    Volt::route('/users/{user}/edit', 'users.edit');

    // Unidad
    Volt::route('/unidades', 'unidades.index');
    Volt::route('/unidades/create', 'unidades.create');
    Volt::route('/unidades/{unidad}/edit', 'unidades.edit');

    // Linea
    Volt::route('/lineas', 'lineas.index');
    Volt::route('/lineas/create', 'lineas.create');
    Volt::route('/lineas/{linea}/edit', 'lineas.edit');
});
