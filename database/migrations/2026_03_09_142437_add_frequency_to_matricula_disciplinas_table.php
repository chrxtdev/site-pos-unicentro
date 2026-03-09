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
        Schema::table('matricula_disciplinas', function (Blueprint $table) {
            $table->integer('total_aulas')->default(40)->after('status');
            $table->integer('presencas')->default(0)->after('total_aulas');
            $table->integer('faltas')->default(0)->after('presencas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matricula_disciplinas', function (Blueprint $table) {
            $table->dropColumn(['total_aulas', 'presencas', 'faltas']);
        });
    }
};
