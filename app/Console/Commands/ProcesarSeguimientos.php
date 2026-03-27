<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\Notificacion;
use Carbon\Carbon;

class ProcesarSeguimientos extends Command
{
    protected $signature   = 'leads:procesar-seguimientos';
    protected $description = 'Reactiva leads con fecha de seguimiento vencida y genera notificaciones';

    public function handle()
    {
        $ahora = Carbon::now();

        $leads = Lead::where('status', 'seguimiento')
            ->where('tipificacion', 'Volver a llamar')
            ->whereNotNull('fecha_seguimiento')
            ->where('fecha_seguimiento', '<=', $ahora)
            ->get();

        $procesados = 0;

        foreach ($leads as $lead) {

            // Reactivar lead como nuevo
            $lead->status            = 'asignado';
            $lead->tipificacion      = null;
            $lead->fecha_seguimiento = null;
            $lead->save();

            // Crear notificación para el asesor
            Notificacion::create([
                'user_id' => $lead->owner_id,
                'lead_id' => $lead->id,
                'mensaje' => "Es momento de llamar a {$lead->nombre} ({$lead->ruc})",
                'leida'   => false,
            ]);

            $procesados++;
        }

        $this->info("Seguimientos procesados: {$procesados}");
    }
}