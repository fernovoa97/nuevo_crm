<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Venta;
use App\Models\Lead;
use App\Models\Notificacion;

class VentaController extends Controller
{
    // =========================
    // ASESOR — CREAR VENTA
    // =========================
    public function store(Request $request)
    {
        // ================= VALIDACIÓN =================
        $request->validate([
            'lead_id'        => 'required|exists:leads,id',
            'tipo_producto'  => 'required|in:movil,fija',

            'tipo_venta'     => 'required|string|max:100',
            'tipo_ingreso'   => 'required|string|max:100',

            'estado_contrato' => 'nullable|string|max:100',

            // comunes
            'ruc_empresa'    => 'nullable|string|max:20',
            'nombre_representante' => 'nullable|string|max:255',

            // archivos
            'archivos.*'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();

        // ================= ARCHIVOS =================
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

        // ================= DATA BASE =================
        $data = [
            'lead_id'        => $request->lead_id,
            'asesor_id'      => $user->id,
            'supervisor_id'  => $request->supervisor_id,

            'tipo_producto'  => $request->tipo_producto,
            'tipo_venta'     => $request->tipo_venta,
            'tipo_ingreso'   => $request->tipo_ingreso,
            'estado_contrato'=> $request->estado_contrato,

            'ruc_empresa'    => $request->ruc_empresa,
            'nombre_representante' => $request->nombre_representante,

            'estado'         => 'en_cola',
            'archivos'       => !empty($archivos) ? $archivos : null,
        ];

        // ================= DATOS MOVIL =================
        if ($request->tipo_producto === 'movil') {
            $data = array_merge($data, [

                'movil_tipo_documento' => $request->movil_tipo_documento,
                'movil_nro_documento'  => $request->movil_nro_documento,
                'movil_rrll'           => $request->movil_rrll,
                'movil_correo'         => $request->movil_correo,

                'movil_coordenadas'    => $request->movil_coordenadas,
                'movil_plano'          => $request->movil_plano,
                'movil_direccion_facturacion' => $request->movil_direccion_facturacion,
                'movil_direccion_entrega'     => $request->movil_direccion_entrega,
                'movil_referencias'    => $request->movil_referencias,
                'movil_telefono_referencia' => $request->movil_telefono_referencia,

                'movil_plan'           => $request->movil_plan,
                'movil_operador_cedente' => $request->movil_operador_cedente,
                'movil_campana'        => $request->movil_campana,
                'movil_large'          => $request->movil_large,

                'movil_fecha_despacho' => $request->movil_fecha_despacho,
                'movil_rango_horario'  => $request->movil_rango_horario,

                'movil_descuento'      => $request->movil_descuento,
                'movil_wf'             => $request->movil_wf,
            ]);
        }

        // ================= DATOS FIJA =================
        if ($request->tipo_producto === 'fija') {
            $data = array_merge($data, [

                'fija_correo'          => $request->fija_correo,
                'fija_coordenadas'     => $request->fija_coordenadas,
                'fija_plano'           => $request->fija_plano,

                'fija_direccion'       => $request->fija_direccion,
                'fija_referencia'      => $request->fija_referencia,

                'fija_tel_facturacion' => $request->fija_tel_facturacion,
                'fija_tel_sot'         => $request->fija_tel_sot,

                'fija_fecha_programacion' => $request->fija_fecha_programacion,

                'fija_plan'            => $request->fija_plan,
                'fija_precio'          => $request->fija_precio,
                'fija_campana'         => $request->fija_campana,
                'fija_bono'            => $request->fija_bono,

                'fija_tecnologia'      => $request->fija_tecnologia,
                'fija_full_claro'      => $request->fija_full_claro,
                'fija_numero_full_claro' => $request->fija_numero_full_claro,
            ]);
        }

        // ================= GUARDAR =================
        $venta = Venta::create($data);

        // ================= ACTUALIZAR LEAD =================
        $lead = Lead::findOrFail($request->lead_id);
        $lead->status = 'venta';
        $lead->save();

        return back()->with('success', 'Venta registrada correctamente 🚀');
    }

    // =========================
    // MESA DE CONTROL — VER VENTAS
    // =========================
    public function index()
    {
        $ventas = Venta::with(['lead', 'asesor', 'supervisor'])
            ->orderByRaw("FIELD(estado, 'en_cola', 'en_proceso', 'completada', 'rechazada')")
            ->latest()
            ->paginate(15);

        return view('dashboards.mesa_control', compact('ventas'));
    }

    // =========================
    // MESA DE CONTROL — ACTUALIZAR
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

        // subir archivos adicionales
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

        // notificación observaciones
        if ($request->filled('observaciones')) {
            Notificacion::create([
                'user_id' => $venta->asesor_id,
                'lead_id' => $venta->lead_id,
                'mensaje' => "Tu venta tiene observaciones: {$request->observaciones}",
                'leida'   => false,
            ]);
        }

        // notificación estado final
        if (in_array($request->estado, ['completada', 'rechazada'])) {
            $label = $request->estado === 'completada' ? '✅ aprobada' : '❌ rechazada';

            Notificacion::create([
                'user_id' => $venta->asesor_id,
                'lead_id' => $venta->lead_id,
                'mensaje' => "Tu venta fue {$label}",
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
        $ventas = Venta::with('lead')
            ->where('asesor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('ventas.mis_ventas', compact('ventas'));
    }
}