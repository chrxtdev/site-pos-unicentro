<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signins', function (Blueprint $table) {
            $table->string('matricula', 20)->nullable()->unique()->after('id')->comment('Registro Acadêmico. Ex: 20261.DIR001');
        });
    }

    public function down(): void
    {
        Schema::table('signins', function (Blueprint $table) {
            $table->dropColumn('matricula');
        });
    }
};
