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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('po');
            $table->string('tipo_incidencia'); // 'Retraso', 'Falla de maquinaria', 'Falta de materia prima', etc.
            $table->text('causa');
            $table->dateTime('nueva_fecha_estimada');
            $table->string('estatus')->default('Abierta'); // 'Abierta', 'Atendida', 'Cerrada'
            $table->timestamps();

            $table->index('po');
            $table->foreign('po')->references('po')->on('pedidos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
