<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
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

        // ================= FILTROS =================
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $jefe = $request->input('jefe');
        $supervisor = $request->input('supervisor');

        $desde = $desde ? Carbon::parse($desde)->startOfDay() : null;
        $hasta = $hasta ? Carbon::parse($hasta)->endOfDay() : null;

        // ================= BASE DE USUARIOS SEGÚN ROL =================
        $queryUsuarios = User::where('role', 'asesor');

        if ($user->role === 'jefe') {
            // asesores de sus supervisores
            $queryUsuarios = $queryUsuarios->whereIn('parent_id', function ($q) use ($user) {
                $q->select('id')
                    ->from('users')
                    ->where('parent_id', $user->id);
            });
        }

        if ($user->role === 'supervisor') {
            // solo sus asesores directos
            $queryUsuarios = $queryUsuarios->where('parent_id', $user->id);
        }

        // filtro por jefe (solo admin)
        if ($jefe && $user->role === 'admin') {
            $queryUsuarios = $queryUsuarios->whereIn('parent_id', function ($q) use ($jefe) {
                $q->select('id')
                    ->from('users')
                    ->where('parent_id', $jefe);
            });
        }

        // filtro por supervisor (solo jefe)
       if ($supervisor) {
    $queryUsuarios = $queryUsuarios->where('parent_id', $supervisor);
}

        $usuariosIds = $queryUsuarios->pluck('id');

        // ================= ESTADÍSTICAS =================
      $estadisticas = User::whereIn('users.id', $usuariosIds)

    ->leftJoin('leads', function ($join) use ($desde, $hasta) {

        $join->on('users.id', '=', 'leads.owner_id');

        if ($desde) {
            $join->where('leads.fecha_asignacion', '>=', $desde);
        }

        if ($hasta) {
            $join->where('leads.fecha_asignacion', '<=', $hasta);
        }

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
                    THEN 1 
                    ELSE 0 
                END
            ) as cantidad_ultima
        ")

    )

    ->groupBy('users.id', 'users.name')
    ->get();

        // ================= LISTAS PARA FILTROS =================
        $jefes = collect();
        $supervisores = collect();

        if ($user->role === 'admin') {
            $jefes = User::where('role', 'jefe')->get();
            $supervisores = User::where('role', 'supervisor')->get();
        }

        if ($user->role === 'jefe') {
            $supervisores = User::where('role', 'supervisor')
                ->where('parent_id', $user->id)
                ->get();
        }

        return view('estadisticas.index', compact(
            'estadisticas',
            'jefes',
            'supervisores',
            'desde',
            'hasta',
            'jefe',
            'supervisor'
        ));
    }

    // ================= DASHBOARD PRINCIPAL =================
   // ================= DASHBOARD PRINCIPAL =================
public function index()
{
    $user = Auth::user();

    // ================= ADMIN =================
    if ($user->role === 'admin') {

        $leads = Lead::with('owner')->paginate(15);

        $total = Lead::count();

        $libres = Lead::where('owner_id', $user->id)->count();

        $asignados = Lead::where('owner_id', '!=', $user->id)->count();

        return view('dashboards.admin', compact('leads', 'total', 'asignados', 'libres'));
    }

    // ================= JEFE =================
    if ($user->role === 'jefe') {

        $leads = Lead::where('root_id', $user->id)
            ->with('owner')
            ->paginate(15);

        $total = Lead::where('root_id', $user->id)->count();

        $asignados = Lead::where('root_id', $user->id)
            ->where('owner_id', '!=', $user->id)
            ->whereNotNull('owner_id')
            ->count();

        $libres = Lead::where('root_id', $user->id)
            ->where('owner_id', $user->id)
            ->count();

        return view('dashboards.jefe', compact('leads', 'total', 'asignados', 'libres'));
    }

    // ================= SUPERVISOR =================
    if ($user->role === 'supervisor') {

        $leads = Lead::where(function ($query) use ($user) {
            $query->where('parent_id', $user->id)
                ->orWhere('owner_id', $user->id);
        })
        ->with('owner')
        ->paginate(15);

        $total = Lead::where(function ($query) use ($user) {
            $query->where('parent_id', $user->id)
                ->orWhere('owner_id', $user->id);
        })->count();

        $libres = Lead::where('owner_id', $user->id)->count();

        $asignados = Lead::where(function ($query) use ($user) {
            $query->where('parent_id', $user->id)
                ->orWhere('owner_id', $user->id);
        })
        ->where('owner_id', '!=', $user->id)
        ->count();

        return view('dashboards.supervisor', compact('leads', 'total', 'asignados', 'libres'));
    }

    // ================= ASESOR =================
if ($user->role === 'asesor') {

    $baseQuery = Lead::where('owner_id', $user->id);

    // Leads nuevos (sin tipificar)
    $leadsNuevos = (clone $baseQuery)
        ->whereNull('tipificacion')
        ->paginate(10, ['*'], 'nuevos');

    // Leads trabajados (tipificados)
    $leadsTrabajados = (clone $baseQuery)
        ->whereNotNull('tipificacion')
        ->paginate(10, ['*'], 'trabajados');

    // Contadores para el dashboard
    $total = (clone $baseQuery)->count();

    $trabajados = (clone $baseQuery)
        ->where('status', 'trabajado')
        ->count();

    $pendientes = (clone $baseQuery)
        ->where(function ($q) {
            $q->whereNull('status')
              ->orWhere('status', 'nuevo')
              ->orWhere('status', 'asignado');
        })
        ->count();

    return view('dashboards.asesor', compact(
        'leadsNuevos',
        'leadsTrabajados',
        'total',
        'trabajados',
        'pendientes'
    ));
}

    abort(403);
}
}