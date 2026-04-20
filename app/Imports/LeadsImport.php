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
                    'ruc'          => $ruc,
                    'razon_social' => $row['razon_social'] ?? null,
                    'giro'         => $row['giro'] ?? null,
                    'dpto'         => $row['dpto'] ?? null,
                    'prov'         => $row['prov'] ?? null,
                    'dist'         => $row['dist'] ?? null,
                    'nombre'       => $row['nombre'] ?? null,
                    'dni'          => $row['dni'] ?? null,
                    'segmento'     => $row['segmento'] ?? null,

                    'telefono1'    => $row['telefono1'] ?? null,
                    'telefono2'    => $row['telefono2'] ?? null,
                    'telefono3'    => $row['telefono3'] ?? null,
                    'telefono4'    => $row['telefono4'] ?? null,
                    'telefono5'    => $row['telefono5'] ?? null,

                    'email'        => $row['email'] ?? null,
                    'comentarios'  => $row['comentarios'] ?? null,

                    'movistar'     => $row['movistar'] ?? 0,
                    'entel'        => $row['entel']    ?? 0,
                    'claro'        => $row['claro']    ?? 0,
                    'bitel'        => $row['bitel']    ?? 0,

                    'status'       => 'nuevo',
                    'tipificacion' => null,

                    'owner_id'     => $user->id,
                    'parent_id'    => $user->id,
                    'root_id'      => $user->role === 'admin' ? null : $user->id,
                    'created_by'   => $user->id,
                ]);

                $this->creados++;

            // =========================
            // ACTUALIZAR
            // =========================
            } else {

                $leadExistente->update([
                    'razon_social' => $row['razon_social'] ?? $leadExistente->razon_social,
                    'giro'         => $row['giro'] ?? $leadExistente->giro,
                    'dpto'         => $row['dpto'] ?? $leadExistente->dpto,
                    'prov'         => $row['prov'] ?? $leadExistente->prov,
                    'dist'         => $row['dist'] ?? $leadExistente->dist,
                    'nombre'       => $row['nombre']       ?? $leadExistente->nombre,
                    'dni'          => $row['dni']           ?? $leadExistente->dni,
                    'segmento'     => $row['segmento']      ?? $leadExistente->segmento,

                    'telefono1'    => $row['telefono1']     ?? $leadExistente->telefono1,
                    'telefono2'    => $row['telefono2']     ?? $leadExistente->telefono2,
                    'telefono3'    => $row['telefono3']     ?? $leadExistente->telefono3,
                    'telefono4'    => $row['telefono4']     ?? $leadExistente->telefono4,
                    'telefono5'    => $row['telefono5']     ?? $leadExistente->telefono5,

                    'email'        => $row['email']         ?? $leadExistente->email,
                    'comentarios'  => $row['comentarios']   ?? $leadExistente->comentarios,

                    'movistar'     => $row['movistar']      ?? $leadExistente->movistar,
                    'entel'        => $row['entel']         ?? $leadExistente->entel,
                    'claro'        => $row['claro']         ?? $leadExistente->claro,
                    'bitel'        => $row['bitel']         ?? $leadExistente->bitel,
                ]);

                $this->actualizados++;
            }
        }
    }
}