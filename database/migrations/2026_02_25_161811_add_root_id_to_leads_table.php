<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            // Nueva columna root_id
            $table->unsignedBigInteger('root_id')
                  ->nullable()
                  ->after('parent_id');

            // Foreign key opcional (recomendado)
            $table->foreign('root_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            // Primero eliminar foreign key
            $table->dropForeign(['root_id']);

            // Luego eliminar columna
            $table->dropColumn('root_id');
        });
    }
};