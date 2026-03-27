<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->integer('movistar')->nullable()->default(0)->after('telefono5');
            $table->integer('entel')->nullable()->default(0)->after('movistar');
            $table->integer('claro')->nullable()->default(0)->after('entel');
            $table->integer('bitel')->nullable()->default(0)->after('claro');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['movistar', 'entel', 'claro', 'bitel']);
        });
    }
};