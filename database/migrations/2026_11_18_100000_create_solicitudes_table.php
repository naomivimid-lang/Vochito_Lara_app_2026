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
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->nullOnDelete();
            $table->string('tipo', 20); // 'cancelacion' o 'modificacion'
            $table->string('po', 30);
            $table->string('email', 150);
            $table->string('motivo', 150)->nullable();
            $table->text('comentarios');
            $table->string('estado', 20)->default('Pendiente'); // 'Pendiente', 'Aprobada', 'Rechazada'
            $table->text('respuesta_admin')->nullable();
            $table->timestamp('fecha_resolucion')->nullable();
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
