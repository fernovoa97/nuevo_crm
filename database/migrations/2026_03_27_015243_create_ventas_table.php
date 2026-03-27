<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            // Relación con lead y usuarios
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('asesor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');

            // Datos de la venta
            $table->string('producto')->nullable();
            $table->string('tipo_producto')->nullable();
            $table->string('ruc_empresa')->nullable();
            $table->string('dni_representante')->nullable();
            $table->string('nombre_representante')->nullable();
            $table->decimal('cargo_fijo', 10, 2)->nullable();
            $table->decimal('cargo_fijo_sin_igv', 10, 2)->nullable();
            $table->integer('lineas_portadas')->default(0);
            $table->integer('lineas_nuevas')->default(0);

            // Estado y etapa
            $table->enum('estado', ['en_cola', 'en_proceso', 'completada', 'rechazada'])->default('en_cola');
            $table->string('etapa')->nullable(); // combo manual de mesa de control
            $table->text('observaciones')->nullable();

            // Archivos adjuntos (JSON con rutas)
            $table->json('archivos')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};