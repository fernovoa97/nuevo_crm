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
    Schema::table('leads', function (Blueprint $table) {

        

        // 🔹 Eliminar telefono viejo
        $table->dropColumn('telefono');

        // 🔹 Agregar nuevos teléfonos
        $table->string('telefono1')->nullable()->after('nombre');
        $table->string('telefono2')->nullable();
        $table->string('telefono3')->nullable();
        $table->string('telefono4')->nullable();
        $table->string('telefono5')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
