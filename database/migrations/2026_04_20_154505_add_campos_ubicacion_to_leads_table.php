<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('giro')->nullable()->after('razon_social');
            $table->string('dpto')->nullable()->after('giro');
            $table->string('prov')->nullable()->after('dpto');
            $table->string('dist')->nullable()->after('prov');
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['giro', 'dpto', 'prov', 'dist']);
        });
    }
};