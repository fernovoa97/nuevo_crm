<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('nuevo', 'asignado', 'trabajado', 'seguimiento', 'venta') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('nuevo', 'asignado', 'trabajado', 'seguimiento') NULL");
    }
};