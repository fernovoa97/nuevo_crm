<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use Carbon\Carbon;

class ReciclarNoInteresados extends Command
{
    protected $signature   = 'leads:reciclar-no-interesados';
    protected $description = 'Regresa al admin los leads No interesados después de 30 días';

    public function handle()
    {
        \Log::info('Job reciclar ejecutado: ' . Carbon::now());
            
            $leads = Lead::where('tipificacion', 'No interesado')
                ->where('fecha_tipificacion', '<=', Carbon::now()->subMinutes(1))
                ->get();

            \Log::info('Leads encontrados: ' . $leads->count());
            // ...

        $leads = Lead::where('tipificacion', 'No interesado')
            ->where('fecha_tipificacion', '<=', Carbon::now()->subMinutes(1))
            ->get();

        foreach ($leads as $lead) {
            $lead->owner_id = User::where('role', 'admin')->first()->id;
            $lead->parent_id         = null;
            $lead->root_id           = null;
            $lead->status            = 'nuevo';
            $lead->tipificacion      = null;
            $lead->fecha_tipificacion = null;
            $lead->save();
        }

        $this->info("Reciclados: {$leads->count()} leads");
    }
}