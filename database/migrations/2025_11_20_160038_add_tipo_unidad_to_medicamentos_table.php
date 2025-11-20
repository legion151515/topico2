<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            // Tipo de unidad de medida (unidad, ml, gr, ampolla, sobre, otros)
            $table->string('tipo_unidad')->default('unidad')->after('descripcion');

            // Presentación del medicamento (ej: "1000ml", "500mg", "100 unidades")
            $table->string('presentacion')->nullable()->after('tipo_unidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->dropColumn(['tipo_unidad', 'presentacion']);
        });
    }
};
