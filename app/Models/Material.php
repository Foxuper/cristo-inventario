<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;
    protected $table = 'Material';

    protected $fillable = [
        'descripcion',
        'cantidad',
        'precio',
        'codigo',
        'clave_sat',
        'activo',
        'linea_id',
        'unidad_medida_id',
        'unidad_compra_id',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'float',
        'activo' => 'boolean',
    ];

    public function linea()
    {
        return $this->belongsTo(Linea::class);
    }

    public function unidad_medida()
    {
        return $this->belongsTo(Unidad::class, 'unidad_medida_id');
    }

    public function unidad_compra()
    {
        return $this->belongsTo(Unidad::class, 'unidad_compra_id');
    }
}
