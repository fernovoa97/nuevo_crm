<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('telefono1_estado')->nullable()->after('telefono1');
            $table->string('telefono2_estado')->nullable()->after('telefono2');
            $table->string('telefono3_estado')->nullable()->after('telefono3');
            $table->string('telefono4_estado')->nullable()->after('telefono4');
            $table->string('telefono5_estado')->nullable()->after('telefono5');
            $table->boolean('sin_numero_valido')->default(false)->after('telefono5_estado');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'telefono1_estado',
                'telefono2_estado',
                'telefono3_estado',
                'telefono4_estado',
                'telefono5_estado',
                'sin_numero_valido',
            ]);
        });
    }
};