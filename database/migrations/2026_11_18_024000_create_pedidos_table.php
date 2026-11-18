
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            $table->string('po')->unique();
            $table->string('cliente');
            $table->string('producto');
            $table->integer('cantidad');

            $table->dateTime('entrega');

            $table->string('estatus')
                  ->default('Orden recibida');

            $table->json('incidencias')->nullable();

            $table->timestamps();

            $table->index('estatus');
            $table->index('entrega');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
