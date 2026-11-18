
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('orden_compra', 20);
            $table->enum('tipo_incidencia', [
                'Retraso',
                'Falla de maquinaria',
                'Falta de materia prima'
            ]);
            $table->text('causa');
            $table->dateTime('nueva_fecha_estimada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
