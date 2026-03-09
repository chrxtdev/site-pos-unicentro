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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_disciplina_id')->constrained('matricula_disciplinas')->onDelete('cascade');
            
            // 1º Bimestre
            $table->decimal('b1_t1', 4, 2)->nullable();
            $table->decimal('b1_t2', 4, 2)->nullable();
            $table->decimal('b1_t3', 4, 2)->nullable();
            $table->decimal('b1_aval', 4, 2)->nullable();
            $table->decimal('b1_total', 4, 2)->nullable();
            
            // 2º Bimestre
            $table->decimal('b2_t1', 4, 2)->nullable();
            $table->decimal('b2_t2', 4, 2)->nullable();
            $table->decimal('b2_t3', 4, 2)->nullable();
            $table->decimal('b2_aval', 4, 2)->nullable();
            $table->decimal('b2_total', 4, 2)->nullable();
            
            // Resultado
            $table->decimal('media_final', 4, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
