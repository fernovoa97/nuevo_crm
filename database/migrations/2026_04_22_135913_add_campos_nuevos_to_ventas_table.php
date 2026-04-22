<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {

            // ================= COMUNES =================
            $table->string('tipo_venta')->nullable();
            $table->string('tipo_ingreso')->nullable();
            $table->string('estado_contrato')->nullable();
            $table->string('campana')->nullable();

            // ================= MOVIL =================
            $table->string('movil_tipo_documento')->nullable();
            $table->string('movil_nro_documento')->nullable();
            $table->string('movil_rrll')->nullable();
            $table->string('movil_correo')->nullable();
            $table->text('movil_geodir')->nullable();
            $table->text('movil_plano')->nullable();
            $table->text('movil_direccion_facturacion')->nullable();
            $table->text('movil_direccion_entrega')->nullable();
            $table->text('movil_referencias')->nullable();
            $table->string('movil_telefono_referencia')->nullable();
            $table->string('movil_plan')->nullable();
            $table->string('movil_operador_cedente')->nullable();
            $table->string('movil_large')->nullable();
            $table->date('movil_fecha_despacho')->nullable();
            $table->string('movil_rango_horario')->nullable();
            $table->string('movil_descuento')->nullable();
            $table->string('movil_wf', 6)->nullable();

            // ================= FIJA =================
            $table->string('fija_correo')->nullable();
            $table->text('fija_coordenadas')->nullable();
            $table->text('fija_plano')->nullable();
            $table->text('fija_direccion_instalacion')->nullable();
            $table->text('fija_referencia')->nullable();
            $table->string('fija_tel_facturacion')->nullable();
            $table->string('fija_tel_sot')->nullable();
            $table->date('fija_fecha_programacion')->nullable();
            $table->string('fija_plan')->nullable();
            $table->decimal('fija_precio', 10, 2)->nullable();
            $table->string('fija_bono')->nullable();
            $table->string('fija_tecnologia')->nullable();
            $table->string('fija_full_claro')->nullable();
            $table->string('fija_numero_full_claro')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_venta',
                'tipo_ingreso',
                'estado_contrato',
                'campana',

                'movil_tipo_documento',
                'movil_nro_documento',
                'movil_rrll',
                'movil_correo',
                'movil_geodir',
                'movil_plano',
                'movil_direccion_facturacion',
                'movil_direccion_entrega',
                'movil_referencias',
                'movil_telefono_referencia',
                'movil_plan',
                'movil_operador_cedente',
                'movil_large',
                'movil_fecha_despacho',
                'movil_rango_horario',
                'movil_descuento',
                'movil_wf',

                'fija_correo',
                'fija_coordenadas',
                'fija_plano',
                'fija_direccion_instalacion',
                'fija_referencia',
                'fija_tel_facturacion',
                'fija_tel_sot',
                'fija_fecha_programacion',
                'fija_plan',
                'fija_precio',
                'fija_bono',
                'fija_tecnologia',
                'fija_full_claro',
                'fija_numero_full_claro',
            ]);
        });
    }
};