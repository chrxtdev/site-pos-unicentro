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
        Schema::create('processo_seletivos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('numero_etapas');
            $table->integer('numero_ofertas');
            $table->enum('situacao', ['ATIVO', 'INATIVO'])->default('ATIVO');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processo_seletivos');
    }
};
