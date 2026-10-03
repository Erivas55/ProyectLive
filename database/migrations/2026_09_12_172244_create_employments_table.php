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
        Schema::create('employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('companie_id')->constrained('companies')->onDelete('cascade');
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->string('ubicacion', 255)->nullable();
            $table->enum('modalidad', ['presencial', 'remoto', 'hibrido']);
            $table->enum('tipo_contrato', ['tiempo_completo', 'medio_tiempo', 'temporal', 'practica']);
            $table->decimal('salario', 10, 2)->nullable();
            $table->enum('estado', ['activo', 'cerrado', 'pausado']);
            $table->dateTime('fecha_publicacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employments');
    }
};
