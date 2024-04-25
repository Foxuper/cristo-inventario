<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-base-200/50 font-sans antialiased dark:bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="mr-3 lg:hidden">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width :collapse-text="__('Collapse')">

        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <x-app-brand class="p-5 pt-3" />

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                <x-menu-separator />
                <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="!-my-2 -mx-2 rounded">
                    <x-slot:actions>
                        <div class="flex space-x-2">
                            <x-theme-toggle class="pt-0.5" />
                            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" :tooltip-left="__('Logout')" no-wire-navigate link="/logout" />
                        </div>
                    </x-slot:actions>
                </x-list-item>
                <x-menu-separator />
                @endif

                {{-- Menu items --}}
                <x-menu-item :title="__('Home')" icon="o-home" link="/" />
                <x-menu-item :title="__('Users')" icon="o-users" link="/users" />
                <x-menu-item :title="__('Units')" icon="o-cube" link="/unidades" />
                <x-menu-item :title="__('Lines')" icon="o-tag" link="/lineas" />
                <x-menu-item :title="__('Materials')" icon="o-rectangle-group" link="/materiales" />
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{-- TOAST area --}}
    <x-toast />
</body>

</html>
