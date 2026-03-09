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
        Schema::create('matricula_disciplinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signin_id')->constrained('signins')->onDelete('cascade'); // Aluno
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
            $table->string('status')->default('cursando'); // cursando, aprovado, reprovado
            $table->timestamps();
            
            $table->unique(['signin_id', 'disciplina_id']); // Evitar matriculas duplicadas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matricula_disciplinas');
    }
};
