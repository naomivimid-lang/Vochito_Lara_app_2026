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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('po')->unique();
            $table->string('cliente');
            $table->string('producto');
            $table->integer('cantidad');
            $table->dateTime('entrega_estimada');
            $table->dateTime('entrega_real')->nullable();
            $table->string('estatus')->default('Orden recibida');
            $table->string('desempeno')->default('pendiente'); // 'a_tiempo', 'retrasado', 'cancelado', 'pendiente'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
