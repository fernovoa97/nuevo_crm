<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // 👈 agrega esta línea

class Lead extends Model
{
 protected $fillable = [
    'nombre',
    'telefono',
    'email',   // 👈 ESTO debe existir
    'status',
    'owner_id',
    'parent_id',
    'root_id',
    'created_by'
];

public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}
}
