<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Venta;
use App\Models\Lead;
use App\Models\Notificacion;
use App\Models\User;

class VentaController extends Controller
{
    // =========================
    // ASESOR — CREAR VENTA
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'lead_id'              => 'required|exists:leads,id',
            'supervisor_id'        => 'nullable|exists:users,id',
            'producto'             => 'required|string|max:255',
            'tipo_producto'        => 'nullable|string|max:255',
            'ruc_empresa'          => 'nullable|string|max:20',
            'dni_representante'    => 'nullable|string|max:20',
            'nombre_representante' => 'nullable|string|max:255',
            'cargo_fijo'           => 'nullable|numeric|min:0',
            'cargo_fijo_sin_igv'   => 'nullable|numeric|min:0',
            'lineas_portadas'      => 'nullable|integer|min:0',
            'lineas_nuevas'        => 'nullable|integer|min:0',
            'archivos.*'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();

        // Subir archivos
        $archivos = [];
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('ventas', 'public');
                $archivos[] = [
                    'nombre' => $archivo->getClientOriginalName(),
                    'ruta'   => $path,
                    'tipo'   => $archivo->getClientMimeType(),
                ];
            }
        }

        $venta = Venta::create([
            'lead_id'              => $request->lead_id,
            'asesor_id'            => $user->id,
            'supervisor_id'        => $request->supervisor_id,
            'producto'             => $request->producto,
            'tipo_producto'        => $request->tipo_producto,
            'ruc_empresa'          => $request->ruc_empresa,
            'dni_representante'    => $request->dni_representante,
            'nombre_representante' => $request->nombre_representante,
            'cargo_fijo'           => $request->cargo_fijo,
            'cargo_fijo_sin_igv'   => $request->cargo_fijo_sin_igv,
            'lineas_portadas'      => $request->lineas_portadas ?? 0,
            'lineas_nuevas'        => $request->lineas_nuevas   ?? 0,
            'estado'               => 'en_cola',
            'archivos'             => !empty($archivos) ? $archivos : null,
        ]);

        // Actualizar status del lead
        $lead = Lead::findOrFail($request->lead_id);
        $lead->status = 'venta';
        $lead->save();

        return back()->with('success', 'Venta enviada a mesa de control correctamente');
    }

    // =========================
    // MESA DE CONTROL — VER VENTAS
    // =========================
    public function index()
    {
        $ventas = Venta::with(['lead', 'asesor', 'supervisor'])
            ->orderByRaw("FIELD(estado, 'en_cola', 'en_proceso', 'completada', 'rechazada')")
            ->paginate(15);

        return view('dashboards.mesa_control', compact('ventas'));
    }

    // =========================
    // MESA DE CONTROL — ACTUALIZAR VENTA
    // =========================
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'mesa_control') {
            abort(403);
        }

        $request->validate([
            'estado'        => 'required|in:en_cola,en_proceso,completada,rechazada',
            'etapa'         => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
            'archivos.*'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $venta = Venta::findOrFail($id);

        // Subir archivos adicionales si los hay
        if ($request->hasFile('archivos')) {
            $archivosActuales = $venta->archivos ?? [];
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('ventas', 'public');
                $archivosActuales[] = [
                    'nombre' => $archivo->getClientOriginalName(),
                    'ruta'   => $path,
                    'tipo'   => $archivo->getClientMimeType(),
                ];
            }
            $venta->archivos = $archivosActuales;
        }

        $venta->estado        = $request->estado;
        $venta->etapa         = $request->etapa;
        $venta->observaciones = $request->observaciones;
        $venta->save();

        // Notificar al asesor si hay observaciones
        if ($request->filled('observaciones')) {
            Notificacion::create([
                'user_id' => $venta->asesor_id,
                'lead_id' => $venta->lead_id,
                'mensaje' => "Tu venta tiene una observación: {$request->observaciones}",
                'leida'   => false,
            ]);
        }

        // Notificar al asesor si fue completada o rechazada
        if (in_array($request->estado, ['completada', 'rechazada'])) {
            $label = $request->estado === 'completada' ? '✅ aprobada' : '❌ rechazada';
            Notificacion::create([
                'user_id' => $venta->asesor_id,
                'lead_id' => $venta->lead_id,
                'mensaje' => "Tu venta de {$venta->lead->razon_social} fue {$label}",
                'leida'   => false,
            ]);
        }

        return back()->with('success', 'Venta actualizada correctamente');
    }

    // =========================
    // ASESOR — VER SUS VENTAS
    // =========================
    public function misVentas()
    {
        $user   = Auth::user();
        $ventas = Venta::with(['lead'])
            ->where('asesor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('ventas.mis_ventas', compact('ventas'));
    }
}