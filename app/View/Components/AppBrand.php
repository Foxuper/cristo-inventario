<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'HTML'
        <a href="/" wire:navigate>

            <!-- Hidden when collapsed -->
            <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
                <img src="{{ Vite::asset('resources/img/logo_full.png') }}" alt="Logo" class="h-10" />
            </div>

            <!-- Display when collapsed -->
            <div class="display-when-collapsed mt-4 hidden lg:mb-6">
                <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo" class="mx-auto h-8" />
            </div>
        </a>
        HTML;
    }
}
