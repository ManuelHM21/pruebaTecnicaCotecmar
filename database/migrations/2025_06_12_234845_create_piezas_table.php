<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiezasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('piezas', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nombre');
            $table->float('peso_teorico');
            $table->float('peso_real')->nullable();
            $table->enum('estado', ['Pendiente', 'Fabricado'])->default('Pendiente');
            $table->string('bloque_id');
            $table->foreign('bloque_id')->references('id')->on('bloques')->onDelete('cascade');
            $table->timestamp('fecha_registro')->nullable();
            $table->string('registrado_por')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piezas');
    }
};
