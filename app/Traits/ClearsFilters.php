<?php

namespace App\Traits;

trait ClearsFilters
{
    // Reinicia la paginación al cambiar los filtros
    public function updated($property): void
    {
        if (!is_array($property) && $property != "")
            $this->resetPage();
    }

    // Limpiar filtros
    public function limpiar(): void
    {
        $this->reset();
        $this->resetPage();
        $this->success(__('Filters cleared'), position: 'toast-bottom');
    }
}
