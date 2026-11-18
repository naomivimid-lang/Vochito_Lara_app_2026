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
       Schema::create('notificaciones', function (Blueprint $table) {
        $table->id();
        $table->string('orden_compra', 20);
        $table->string('titulo');
        $table->text('mensaje');
        $table->string('tipo', 30);
        $table->boolean('leida')->default(false);
        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacions');
    }
};
