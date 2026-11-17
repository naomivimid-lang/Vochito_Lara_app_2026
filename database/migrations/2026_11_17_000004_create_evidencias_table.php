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
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id();
            $table->string('po');
            $table->string('recibe');
            $table->dateTime('fecha_entrega');
            $table->string('foto_path')->nullable();
            $table->text('comentarios')->nullable();
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
        Schema::dropIfExists('evidencias');
    }
};
