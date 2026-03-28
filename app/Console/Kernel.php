<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ProcesarSeguimientos;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ProcesarSeguimientos::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('leads:procesar-seguimientos')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}