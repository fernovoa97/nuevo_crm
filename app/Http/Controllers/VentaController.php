<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'producto'       => 'required|in:movil,fija',

            'tipo_venta'     => 'required|string|max:100',
            'tipo_ingreso'   => 'required|string|max:100',

            'estado_contrato'=> 'nullable|string|max:100',

            // generales
            'ruc'                => 'nullable|string|max:20',
            'razon_social'       => 'nullable|string|max:255',
            'nombre_representante' => 'nullable|string|max:255',
            'numero_documento'   => 'nullable|string|max:20',

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
            'supervisor_id'  => $request->supervisor_id ?? null,

            'tipo_producto'  => $request->producto,
            'tipo_venta'     => $request->tipo_venta,
            'tipo_ingreso'   => $request->tipo_ingreso,
            'estado_contrato'=> $request->estado_contrato,

            'ruc_empresa'    => $request->ruc,
            'razon_social'   => $request->razon_social,
            'nombre_representante' => $request->nombre_representante,
            'dni_representante'    => $request->numero_documento,

            'estado'         => 'en_cola',
            'archivos'       => !empty($archivos) ? $archivos : null,
        ];

        // ================= MOVIL =================
        if ($request->producto === 'movil') {
            $data = array_merge($data, [

                'movil_tipo_documento' => $request->tipo_documento,
                'movil_nro_documento'  => $request->numero_documento,
                'movil_correo'         => $request->correo,

                'movil_coordenadas'    => $request->coordenadas,
                'movil_plano'          => $request->plano,
                'movil_direccion_facturacion' => $request->direccion_facturacion,
                'movil_direccion_entrega'     => $request->direccion_entrega,
                'movil_referencias'    => $request->referencias,
                'movil_telefono_referencia' => $request->telefono_referencia,

                'movil_plan'           => $request->plan,
                'movil_operador_cedente' => $request->operador,
                'movil_large'          => $request->large,

                'movil_fecha_despacho' => $request->fecha_despacho,
                'movil_rango_horario'  => $request->rango_horario,

                'movil_descuento'      => $request->descuento,
                'movil_wf'             => $request->nro_wf,
            ]);
        }

        // ================= FIJA =================
        if ($request->producto === 'fija') {
            $data = array_merge($data, [

                'fija_correo'          => $request->correo,
                'fija_coordenadas'     => $request->coordenadas_factibilidad,
                'fija_plano'           => $request->plano_factibilidad,

                'fija_direccion'       => $request->direccion_instalacion,
                'fija_referencia'      => $request->referencia_direccion,

                'fija_tel_sot'         => $request->telefono_sot,
                'fija_fecha_programacion' => $request->fecha_programacion,

                'fija_plan'            => $request->plan_fija,
                'fija_precio'          => $request->precio,

                'fija_tecnologia'      => $request->tecnologia,
                'fija_full_claro'      => $request->full_claro,
                'fija_numero_full_claro' => $request->numero_fullclaro,
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

        // archivos nuevos
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

        // notificación
        if ($request->filled('observaciones')) {
            Notificacion::create([
                'user_id' => $venta->asesor_id,
                'lead_id' => $venta->lead_id,
                'mensaje' => "Tu venta tiene observaciones: {$request->observaciones}",
                'leida'   => false,
            ]);
        }

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