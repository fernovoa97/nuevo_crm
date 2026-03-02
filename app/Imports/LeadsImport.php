<?php

namespace App\Imports;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $user = Auth::user();

        return new Lead([
            'nombre'     => $row['nombre'],
            'telefono'   => $row['telefono'],
            'email'      => $row['email'] ?? null,
            'status'     => 'nuevo',

            'owner_id'   => $user->id,
            'parent_id'  => $user->id,
            'root_id'    => $user->role === 'admin' ? null : $user->id,
            'created_by' => $user->id
        ]);
    }
}