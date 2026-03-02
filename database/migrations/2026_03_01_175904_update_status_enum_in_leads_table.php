<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->enum('status', [
                'nuevo',
                'asignado',
                'reciclado',
                'trabajado'
            ])->default('nuevo')->change();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->enum('status', [
                'nuevo',
                'asignado',
                'reciclado'
            ])->default('nuevo')->change();
        });
    }
};