<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'ruc',
        'razon_social',
        'giro',      // ← nuevo
        'dpto',      // ← nuevo
        'prov',      // ← nuevo
        'dist',      // ← nuevo
        'nombre',
        'dni',
        'segmento',

        'telefono1',
        'telefono1_estado',
        'telefono2',
        'telefono2_estado',
        'telefono3',
        'telefono3_estado',
        'telefono4',
        'telefono4_estado',
        'telefono5',
        'telefono5_estado',

        'sin_numero_valido',

        'movistar',
        'entel',
        'claro',
        'bitel',

        'email',
        'comentarios',

        'status',
        'tipificacion',

        'owner_id',
        'created_by',
        'root_id',
        'parent_id',

        'fecha_asignacion',
        'fecha_tipificacion',
        'fecha_seguimiento',
    ];

    protected $casts = [
        'fecha_seguimiento' => 'datetime',
        'fecha_asignacion'  => 'datetime',
        'fecha_tipificacion' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function todosNumerosIncorrectos(): bool
    {
        $tienePorLoMenosUno = false;

        for ($i = 1; $i <= 5; $i++) {
            $telefono = $this->{"telefono$i"};
            $estado   = $this->{"telefono{$i}_estado"};

            if ($telefono) {
                $tienePorLoMenosUno = true;
                if ($estado !== 'incorrecto') {
                    return false;
                }
            }
        }

        return $tienePorLoMenosUno;
    }
}