<?php

use App\Models\Linea;
use App\Models\Unidad;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Material', function (Blueprint $table) {
            $table->id();

            $table->string('descripcion');
            $table->integer('cantidad');
            $table->string('codigo')->nullable();
            $table->string('clave_sat')->nullable();
            $table->boolean('activo')->default(true);

            // Relaciones
            $table->foreignIdFor(Linea::class)
                ->constrained('Linea')->cascadeOnDelete();
            $table->foreignIdFor(Unidad::class, 'unidad_medida_id')
                ->constrained('Unidad')->cascadeOnDelete();
            $table->foreignIdFor(Unidad::class, 'unidad_compra_id')
                ->constrained('Unidad')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Material');
    }
};
