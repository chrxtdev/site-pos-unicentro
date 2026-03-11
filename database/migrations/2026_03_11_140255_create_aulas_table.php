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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplina_id')->constrained()->onDelete('cascade');
            $table->date('data');
            $table->time('hora')->nullable();
            $table->text('conteudo')->nullable();
            $table->integer('qtd_aulas')->default(1); // Quantas horas/aulas vale essa sessão
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
