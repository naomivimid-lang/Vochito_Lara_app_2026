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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // 'cancelacion' o 'modificacion'
            $table->string('po');
            $table->string('email');
            $table->string('motivo')->nullable();
            $table->text('comentarios');
            $table->string('estado')->default('Pendiente'); // 'Pendiente', 'Aprobada', 'Rechazada'
            $table->text('respuesta_admin')->nullable();
            $table->dateTime('fecha_resolucion')->nullable();
            $table->timestamps();

            $table->index('po');
            $table->index('tipo');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
