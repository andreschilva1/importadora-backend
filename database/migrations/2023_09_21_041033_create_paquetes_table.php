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
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->string('photo_path')->nullable();
            $table->string('codigo_rastreo')->nullable();
            $table->string('peso')->nullable();
            $table->foreignId('cliente_id')->constrained('clientes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('empleado_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('almacen_id')->constrained('almacenes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('consolidacion_estado_id')->constrained('consolidacion_estado')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('consolidado_id')->nullable()->constrained('paquetes')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paquetes');
    }
};
