<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'Unidad';

    protected $fillable = [
        'nombre'
    ];

    public function materiales_medida()
    {
        return $this->hasMany(Material::class, 'unidad_medida_id');
    }

    public function materiales_compra()
    {
        return $this->hasMany(Material::class, 'unidad_compra_id');
    }
}
