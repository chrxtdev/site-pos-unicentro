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
        Schema::create('signins', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->date('data_nascimento');
            $table->string('sexo');
            $table->string('estado_civil');
            $table->string('ensino_medio');
            $table->string('cor_raca');
            $table->string('endereco');
            $table->string('bairro');
            $table->string('cep');
            $table->string('telefone_celular');
            $table->string('login')->unique();
            $table->string('senha');
            $table->string('tipo_aluno');
            $table->decimal('valor_mensalidade', 8, 2);
            $table->string('pos_graduacao');
            $table->timestamps();
;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signins');
    }
};
