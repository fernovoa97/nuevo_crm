<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'lead_id',
        'asesor_id',
        'supervisor_id',
        'producto',
        'tipo_producto',
        'ruc_empresa',
        'dni_representante',
        'nombre_representante',
        'cargo_fijo',
        'cargo_fijo_sin_igv',
        'lineas_portadas',
        'lineas_nuevas',
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