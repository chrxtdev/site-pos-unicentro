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
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); // Chave estrangeira para cursos
            $table->foreignId('processo_seletivo_id')->constrained()->onDelete('cascade'); // Outra chave estrangeira
            $table->string('turno');
            $table->integer('quantidade_vagas');
            $table->string('locais_prova');
            $table->decimal('valor_taxa', 8, 2)->nullable();
            $table->date('data_vencimento_taxa')->nullable();
            $table->string('conta_recebimento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
