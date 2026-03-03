<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            $table->string('ruc')->nullable()->after('email');
            $table->string('razon_social')->nullable()->after('ruc');
            $table->string('dni')->nullable()->after('razon_social');
            $table->string('segmento')->nullable()->after('dni');
            $table->text('comentarios')->nullable()->after('segmento');

        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            $table->dropColumn([
                'ruc',
                'razon_social',
                'dni',
                'segmento',
                'comentarios'
            ]);

        });
    }
};