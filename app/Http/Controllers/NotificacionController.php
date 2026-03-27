<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    // Devuelve notificaciones no leídas del usuario
    public function index()
    {
        $notificaciones = Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->with('lead')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notificaciones);
    }

    // Marcar una notificación como leída
    public function marcarLeida($id)
    {
        $notificacion = Notificacion::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notificacion->leida = true;
        $notificacion->save();

        return response()->json(['success' => true]);
    }

    // Marcar todas como leídas
    public function marcarTodasLeidas()
    {
        Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['success' => true]);
    }
}