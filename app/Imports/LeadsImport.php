<?php

namespace App\Imports;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToCollection, WithHeadingRow
{
    public $creados = 0;
    public $actualizados = 0;
    public $sinEspacio = 0;

    public function collection(Collection $rows)
    {
        $user = Auth::user();

        foreach ($rows as $row) {

            $ruc = trim($row['ruc'] ?? '');

            if (!$ruc) {
                continue;
            }

            $leadExistente = Lead::where('ruc', $ruc)->first();

            // =========================
            // CREAR NUEVO
            // =========================
            if (!$leadExistente) {

                Lead::create([

                    'ruc'           => $ruc,
                    'razon_social'  => $row['razon_social'] ?? null,
                    'nombre'        => $row['nombre'] ?? null,
                    'dni'           => $row['dni'] ?? null,
                    'segmento'      => $row['segmento'] ?? null,

                    'telefono1'     => $row['telefono1'] ?? null,
                    'telefono2'     => $row['telefono2'] ?? null,
                    'telefono3'     => $row['telefono3'] ?? null,
                    'telefono4'     => $row['telefono4'] ?? null,
                    'telefono5'     => $row['telefono5'] ?? null,

                    'email'         => $row['email'] ?? null,
                    'comentarios'   => $row['comentarios'] ?? null,

                    'status'        => 'nuevo',
                    'tipificacion'  => null,

                    'owner_id'      => $user->id,
                    'parent_id'     => $user->id,
                    'root_id'       => $user->role === 'admin' ? null : $user->id,
                    'created_by'    => $user->id,
                ]);

                $this->creados++;
            }

            // =========================
            // ACTUALIZAR TELÉFONOS
            // =========================
            else {

                $telefonosExcel = [
                    $row['telefono1'] ?? null,
                    $row['telefono2'] ?? null,
                    $row['telefono3'] ?? null,
                    $row['telefono4'] ?? null,
                    $row['telefono5'] ?? null,
                ];

                $telefonosExcel = array_filter($telefonosExcel);

                $telefonosActuales = [
                    $leadExistente->telefono1,
                    $leadExistente->telefono2,
                    $leadExistente->telefono3,
                    $leadExistente->telefono4,
                    $leadExistente->telefono5,
                ];

                $actualizado = false;
                $espacioDisponible = false;

                foreach ($telefonosExcel as $telefonoNuevo) {

                    if (!in_array($telefonoNuevo, $telefonosActuales)) {

                        for ($i = 1; $i <= 5; $i++) {
                            $campo = "telefono$i";

                            if (!$leadExistente->$campo) {
                                $leadExistente->$campo = $telefonoNuevo;
                                $actualizado = true;
                                $espacioDisponible = true;
                                break;
                            }
                        }

                        if (!$espacioDisponible) {
                            $this->sinEspacio++;
                        }
                    }
                }

                if ($actualizado) {
                    $leadExistente->save();
                    $this->actualizados++;
                }
            }
        }
    }
}