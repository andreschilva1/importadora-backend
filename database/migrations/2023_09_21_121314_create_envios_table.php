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
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_rastreo')->nullable();
            $table->string('costo');
            //relacion con la tabla paquetes
            $table->foreignId('paquete_id')->constrained('paquetes')->onUpdate('cascade')->onDelete('cascade');
            //relacion con la tabla metodos_envios
            $table->foreignId('metodo_envio_id')->constrained('metodos_envios')->onUpdate('cascade')->onDelete('cascade');
            //relacion con la tabla envios_estados
            $table->foreignId('envio_estado_id')->constrained('envios_estados')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envios');
    }
};
