<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Linea extends Model
{
    protected $table = 'Linea';

    protected $fillable = [
        'nombre'
    ];

    public function materiales()
    {
        return $this->hasMany(Material::class);
    }
}
