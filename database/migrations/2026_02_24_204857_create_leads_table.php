<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('telefono');
            $table->string('email')->nullable();

            $table->foreignId('owner_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('cascade');

            // 🔥 CAMBIADO A STRING (mejor que enum)
            $table->string('status')->default('nuevo');

            $table->string('tipificacion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};