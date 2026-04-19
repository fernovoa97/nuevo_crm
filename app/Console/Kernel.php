<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ProcesarSeguimientos;
use App\Console\Commands\ReciclarNoInteresados; // 👈 agregar esto
class Kernel extends ConsoleKernel
{
    protected $commands = [
        ProcesarSeguimientos::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('leads:procesar-seguimientos')->everyMinute();
        $schedule->command('leads:reciclar-no-interesados')->everyMinute();

    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }

    protected function scheduleTimezone(): string
    {
        return 'America/Lima';
    }
}