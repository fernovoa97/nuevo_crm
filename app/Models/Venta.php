<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'lead_id',
        'asesor_id',
        'supervisor_id',

        // generales
        'tipo_producto',
        'tipo_venta',
        'tipo_ingreso',
        'estado_contrato',
        'campana',

        'ruc_empresa',
        'razon_social',
        'nombre_representante',
        'dni_representante',

        // ================= MOVIL =================
        'movil_tipo_documento',
        'movil_nro_documento',
        'movil_rrll',
        'movil_correo',
        'movil_coordenadas',
        'movil_plano',
        'movil_direccion_facturacion',
        'movil_direccion_entrega',
        'movil_referencias',
        'movil_telefono_referencia',
        'movil_plan',
        'movil_operador_cedente',
        'movil_campana',
        'movil_large',
        'movil_fecha_despacho',
        'movil_rango_horario',
        'movil_descuento',
        'movil_wf',

        // ================= FIJA =================
        'fija_correo',
        'fija_coordenadas',
        'fija_plano',
        'fija_direccion',
        'fija_referencia',
        'fija_tel_facturacion',
        'fija_tel_sot',
        'fija_fecha_programacion',
        'fija_plan',
        'fija_precio',
        'fija_campana',
        'fija_bono',
        'fija_tecnologia',
        'fija_full_claro',
        'fija_numero_full_claro',

        // sistema
        'estado',
        'etapa',
        'observaciones',
        'archivos',
    ];

    protected $casts = [
        'archivos'        => 'array',
        'cargo_fijo'      => 'decimal:2',
        'cargo_fijo_sin_igv' => 'decimal:2',
    ];

    // ================= RELACIONES =================

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function asesor()
    {
        return $this->belongsTo(User::class, 'asesor_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // ================= HELPERS =================

    public function estadoBadge(): string
    {
        return match($this->estado) {
            'en_cola'    => 'bg-slate-100 text-slate-600',
            'en_proceso' => 'bg-amber-100 text-amber-600',
            'completada' => 'bg-emerald-100 text-emerald-700',
            'rechazada'  => 'bg-red-100 text-red-600',
            default      => 'bg-slate-100 text-slate-600',
        };
    }

    public function estadoLabel(): string
    {
        return match($this->estado) {
            'en_cola'    => 'En cola',
            'en_proceso' => 'En proceso',
            'completada' => 'Completada',
            'rechazada'  => 'Rechazada',
            default      => '-',
        };
    }
}