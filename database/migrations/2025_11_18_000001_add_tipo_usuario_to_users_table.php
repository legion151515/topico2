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
        Schema::table('users', function (Blueprint $table) {
            // Agregar columna para tipo de usuario
            $table->enum('tipo_usuario', ['admin', 'medico', 'enfermero', 'recepcionista', 'estudiante'])
                  ->default('estudiante')
                  ->after('email');

            // Agregar DNI para vincular estudiantes con pacientes
            $table->string('dni', 8)->nullable()->after('tipo_usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo_usuario', 'dni']);
        });
    }
};
