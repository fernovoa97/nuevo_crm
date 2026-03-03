<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TelefonosAgregadosNotification extends Notification
{
    use Queueable;

    protected $lead;
    protected $telefonos;

    public function __construct($lead, $telefonos)
    {
        $this->lead = $lead;
        $this->telefonos = $telefonos;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'mensaje' => "Se agregaron " . count($this->telefonos) .
                " nuevos teléfonos (" . implode(', ', $this->telefonos) .
                ") al lead {$this->lead->nombre} - RUC {$this->lead->ruc}"
        ];
    }
}