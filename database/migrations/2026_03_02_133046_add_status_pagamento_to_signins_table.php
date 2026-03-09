<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('signins', function (Blueprint $table) {
            $table->string('status_pagamento', 50)->default('pendente')->after('asaas_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signins', function (Blueprint $table) {
            $table->dropColumn('status_pagamento');
        });
    }
};