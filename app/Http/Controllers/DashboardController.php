<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\Venta;
use App\Models\Notificacion;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // ================= ESTADÍSTICAS =================
    public function estadisticas(Request $request)
    {
        $ultimaAsignacion = Lead::select(
            'owner_id',
            DB::raw('MAX(fecha_asignacion) as ultima_fecha')
        )
        ->groupBy('owner_id');

        $user = auth()->user();

        $desde      = $request->input('desde');
        $hasta      = $request->input('hasta');
        $jefe       = $request->input('jefe');
        $supervisor = $request->input('supervisor');

        $desde = $desde ? Carbon::parse($desde)->startOfDay() : null;
        $hasta = $hasta ? Carbon::parse($hasta)->endOfDay()   : null;

        $queryUsuarios = User::where('role', 'asesor');

        if ($user->role === 'jefe') {
            $queryUsuarios = $queryUsuarios->whereIn('parent_id', function ($q) use ($user) {
                $q->select('id')->from('users')->where('parent_id', $user->id);
            });
        }

        if ($user->role === 'supervisor') {
            $queryUsuarios = $queryUsuarios->where('parent_id', $user->id);
        }

        if ($jefe && $user->role === 'admin') {
            $queryUsuarios = $queryUsuarios->whereIn('parent_id', function ($q) use ($jefe) {
                $q->select('id')->from('users')->where('parent_id', $jefe);
            });
        }

        if ($supervisor) {
            $queryUsuarios = $queryUsuarios->where('parent_id', $supervisor);
        }

        $usuariosIds = $queryUsuarios->pluck('id');

        $estadisticas = User::whereIn('users.id', $usuariosIds)

            ->leftJoin('leads', function ($join) use ($desde, $hasta) {
                $join->on('users.id', '=', 'leads.owner_id');
                if ($desde) $join->where('leads.fecha_asignacion', '>=', $desde);
                if ($hasta) $join->where('leads.fecha_asignacion', '<=', $hasta);
            })

            ->leftJoinSub($ultimaAsignacion, 'ua', function ($join) {
                $join->on('users.id', '=', 'ua.owner_id');
            })

            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(leads.id) as total_asignados'),
                DB::raw("SUM(CASE WHEN leads.tipificacion IS NOT NULL THEN 1 ELSE 0 END) as total_trabajados"),
                DB::raw('MAX(ua.ultima_fecha) as ultima_asignacion'),
                DB::raw("
                    SUM(
                        CASE
                            WHEN leads.fecha_asignacion = ua.ultima_fecha
                            THEN 1 ELSE 0
                        END
                    ) as cantidad_ultima
                ")
            )
            ->groupBy('users.id', 'users.name')
            ->get();

        $jefes        = collect();
        $supervisores = collect();

        if ($user->role === 'admin') {
            $jefes        = User::where('role', 'jefe')->get();
            $supervisores = User::where('role', 'supervisor')->get();
        }

        if ($user->role === 'jefe') {
            $supervisores = User::where('role', 'supervisor')
                ->where('parent_id', $user->id)
                ->get();
        }

        return view('estadisticas.index', compact(
            'estadisticas', 'jefes', 'supervisores',
            'desde', 'hasta', 'jefe', 'supervisor'
        ));
    }

    // ================= DASHBOARD PRINCIPAL =================
    public function index()
    {
        $user = Auth::user();

        // ================= ADMIN =================
        if ($user->role === 'admin') {

            $leads          = Lead::with('owner')->paginate(15);
            $total          = Lead::count();
            $libres         = Lead::where('owner_id', $user->id)->count();
            $asignados      = Lead::where('owner_id', '!=', $user->id)->count();
            $leadsSinNumero = Lead::where('sin_numero_valido', true)->get();

            return view('dashboards.admin', compact(
                'leads', 'total', 'asignados', 'libres', 'leadsSinNumero'
            ));
        }

        // ================= JEFE =================
        if ($user->role === 'jefe') {

            $leads    = Lead::where('root_id', $user->id)->with('owner')->paginate(15);
            $total    = Lead::where('root_id', $user->id)->count();
            $asignados = Lead::where('root_id', $user->id)
                ->where('owner_id', '!=', $user->id)
                ->whereNotNull('owner_id')
                ->count();
            $libres   = Lead::where('root_id', $user->id)
                ->where('owner_id', $user->id)
                ->count();

            return view('dashboards.jefe', compact('leads', 'total', 'asignados', 'libres'));
        }

        // ================= SUPERVISOR =================
        if ($user->role === 'supervisor') {

            $leads = Lead::where(function ($query) use ($user) {
                $query->where('parent_id', $user->id)
                      ->orWhere('owner_id', $user->id);
            })->with('owner')->paginate(15);

            $total = Lead::where(function ($query) use ($user) {
                $query->where('parent_id', $user->id)
                      ->orWhere('owner_id', $user->id);
            })->count();

            $libres   = Lead::where('owner_id', $user->id)->count();
            $asignados = Lead::where(function ($query) use ($user) {
                $query->where('parent_id', $user->id)
                      ->orWhere('owner_id', $user->id);
            })->where('owner_id', '!=', $user->id)->count();

            return view('dashboards.supervisor', compact('leads', 'total', 'asignados', 'libres'));
        }

        // ================= ASESOR =================
        if ($user->role === 'asesor') {

            $baseQuery = Lead::where('owner_id', $user->id);

            $leadsNuevos = (clone $baseQuery)
                ->whereNull('tipificacion')
                ->paginate(10, ['*'], 'nuevos');

            $leadsSeguimiento = (clone $baseQuery)
                ->where('status', 'seguimiento')
                ->where('tipificacion', 'Volver a llamar')
                ->orderBy('fecha_seguimiento', 'asc')
                ->paginate(10, ['*'], 'seguimiento');

            $leadsTrabajados = (clone $baseQuery)
                ->whereNotNull('tipificacion')
                ->where('status', '!=', 'seguimiento')
                ->where('status', '!=', 'venta')
                ->paginate(10, ['*'], 'trabajados');

            $leadsVenta = (clone $baseQuery)
                ->where('status', 'venta')
                ->with('ventas')
                ->paginate(10, ['*'], 'ventas');

            $total      = (clone $baseQuery)->count();
            $trabajados = (clone $baseQuery)
                ->where(function ($q) {
                    $q->whereNotNull('tipificacion')
                    ->orWhere('status', 'seguimiento')
                    ->orWhere('status', 'venta');
                })
                ->count();
            $pendientes = (clone $baseQuery)->where(function ($q) {
                $q->whereNull('status')
                  ->orWhere('status', 'nuevo')
                  ->orWhere('status', 'asignado');
            })->count();

            $notificaciones = Notificacion::where('user_id', $user->id)
                ->where('leida', false)
                ->count();

            return view('dashboards.asesor', compact(
                'leadsNuevos',
                'leadsSeguimiento',
                'leadsTrabajados',
                'leadsVenta',
                'total',
                'trabajados',
                'pendientes',
                'notificaciones'
            ));
        }

        // ================= MESA DE CONTROL =================
        if ($user->role === 'mesa_control') {

            $enCola     = Venta::with(['lead', 'asesor'])
                ->where('estado', 'en_cola')
                ->orderBy('created_at', 'asc')
                ->paginate(10, ['*'], 'cola');

            $enProceso  = Venta::with(['lead', 'asesor'])
                ->where('estado', 'en_proceso')
                ->orderBy('created_at', 'asc')
                ->paginate(10, ['*'], 'proceso');

            $completadas = Venta::with(['lead', 'asesor'])
                ->where('estado', 'completada')
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'completadas');

            $rechazadas  = Venta::with(['lead', 'asesor'])
                ->where('estado', 'rechazada')
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'rechazadas');

            $totalCola    = Venta::where('estado', 'en_cola')->count();
            $totalProceso = Venta::where('estado', 'en_proceso')->count();

            return view('dashboards.mesa_control', compact(
                'enCola',
                'enProceso',
                'completadas',
                'rechazadas',
                'totalCola',
                'totalProceso'
            ));
        }

        abort(403);
    }
}