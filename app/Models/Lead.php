<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Lead extends Model
{
    protected $fillable = [
'ruc',
'razon_social',
'nombre',
'dni',
'segmento',

'telefono1',
'telefono2',
'telefono3',
'telefono4',
'telefono5',

'email',
'comentarios',

'status',
'tipificacion',

'owner_id',
'created_by',
'root_id',
'parent_id',

'fecha_asignacion',
'fecha_tipificacion'
];

    // Relación con el asesor
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}