<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\User;
use App\Models\Notificacion;
use App\Imports\LeadsImport;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $leads = Lead::all();
        } else {
            $leads = Lead::where('owner_id', $user->id)->get();
        }

        return view('leads.index', compact('leads'));
    }

    public function tipificar(Request $request, $id)
    {
        $request->validate([
            'tipificacion' => 'required'
        ]);

        $lead = Lead::findOrFail($id);

        $lead->tipificacion      = $request->tipificacion;
        $lead->status            = 'trabajado';
        $lead->fecha_tipificacion = now();

        if ($request->tipificacion === 'Número incorrecto') {
            $lead->sin_numero_valido = true;
        }

        if ($request->tipificacion === 'Volver a llamar') {
            $lead->status = 'seguimiento';
        }

        if ($request->tipificacion === 'Propuesta enviada') {
            $lead->status = 'venta';
        }

        $lead->save();

        return back()->with('success', 'Tipificado correctamente');
    }

    // =========================
    // AGENDAR SEGUIMIENTO
    // =========================
    public function agendarSeguimiento(Request $request, $id)
    {
        $request->validate([
            'fecha_seguimiento' => 'required|date|after:now',
        ]);

        $lead = Lead::findOrFail($id);

        if ($lead->owner_id !== Auth::id()) {
            abort(403);
        }

        $lead->tipificacion      = 'Volver a llamar';
        $lead->status            = 'seguimiento';
        $lead->fecha_seguimiento = $request->fecha_seguimiento;
        $lead->fecha_tipificacion = now();
        $lead->save();

        return back()->with('success', 'Seguimiento agendado para ' . \Carbon\Carbon::parse($request->fecha_seguimiento)->format('d/m/Y H:i'));
    }

    // ⚠️ Solo por si en el futuro quieres crear manual
    public function store(Request $request)
    {
        $request->validate([
            'ruc'      => 'required|string|unique:leads,ruc',
            'nombre'   => 'required|string|max:255',

            'movistar' => 'nullable|integer|min:0',
            'entel'    => 'nullable|integer|min:0',
            'claro'    => 'nullable|integer|min:0',
            'bitel'    => 'nullable|integer|min:0',
        ]);

        $user = Auth::user();

        Lead::create([
            'ruc'          => $request->ruc,
            'razon_social' => $request->razon_social,
            'nombre'       => $request->nombre,
            'dni'          => $request->dni,
            'segmento'     => $request->segmento,

            'telefono1'    => $request->telefono1,
            'telefono2'    => $request->telefono2,
            'telefono3'    => $request->telefono3,
            'telefono4'    => $request->telefono4,
            'telefono5'    => $request->telefono5,

            'email'        => $request->email,
            'comentarios'  => $request->comentarios,

            'movistar'     => $request->movistar ?? 0,
            'entel'        => $request->entel    ?? 0,
            'claro'        => $request->claro    ?? 0,
            'bitel'        => $request->bitel    ?? 0,

            'status'       => 'nuevo',

            'owner_id'     => $user->id,
            'created_by'   => $user->id
        ]);

        return back()->with('success', 'Lead creado correctamente');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new \App\Imports\LeadsImport;

        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('archivo'));

        return back()->with('success',
            "Importación finalizada: 
            {$import->creados} creados, 
            {$import->actualizados} actualizados, 
            {$import->sinEspacio} sin espacio para teléfonos."
        );
    }

    public function asignar(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $user    = Auth::user();
        $destino = User::findOrFail($request->user_id);

        $leads = Lead::where('owner_id', $user->id)
            ->limit($request->cantidad)
            ->get();

        foreach ($leads as $lead) {

            if ($user->role === 'admin' && $lead->root_id === null) {
                $lead->root_id   = $destino->id;
                $lead->parent_id = $destino->id;
            }

            if ($user->role === 'jefe') {
                $lead->parent_id = $destino->id;
            }

            $lead->owner_id        = $destino->id;
            $lead->status          = 'asignado';
            $lead->fecha_asignacion = now();
            $lead->save();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Leads asignados correctamente');
    }

    // =========================
    // MARCAR ESTADO DE TELÉFONO
    // =========================
    public function marcarTelefono(Request $request, $id)
    {
        $request->validate([
            'numero' => 'required|integer|between:1,5',
            'estado' => 'required|in:exitoso,incorrecto,null',
        ]);

        $lead = Lead::findOrFail($id);

        if ($lead->owner_id !== Auth::id()) {
            abort(403);
        }

        $campo        = 'telefono' . $request->numero . '_estado';
        $lead->$campo = $request->estado === 'null' ? null : $request->estado;

        $lead->sin_numero_valido = $lead->todosNumerosIncorrectos();

        $lead->save();

        return response()->json([
            'success'           => true,
            'sin_numero_valido' => $lead->sin_numero_valido,
        ]);
    }

    // =========================
    // BANDEJA ADMIN - ACTUALIZAR TELÉFONOS
    // =========================
    public function actualizarTelefonos(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'telefono1' => 'nullable|string|max:20',
            'telefono2' => 'nullable|string|max:20',
            'telefono3' => 'nullable|string|max:20',
            'telefono4' => 'nullable|string|max:20',
            'telefono5' => 'nullable|string|max:20',
        ]);

        $lead = Lead::findOrFail($id);

        for ($i = 1; $i <= 5; $i++) {
            $campo       = "telefono$i";
            $campoEstado = "telefono{$i}_estado";

            $lead->$campo       = $request->$campo;
            $lead->$campoEstado = null;
        }

        $lead->sin_numero_valido = false;
        $lead->tipificacion      = null;
        $lead->status            = 'asignado';

        $lead->save();

        return back()->with('success', 'Teléfonos actualizados correctamente');
    }

    // =========================
    // EDITAR LEAD (ASESOR)
    // =========================
    public function editarLead(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        if ($lead->owner_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nombre'      => 'nullable|string|max:255',
            'dni'         => 'nullable|string|max:20',
            'segmento'    => 'nullable|string|max:100',
            'telefono1'   => 'nullable|string|max:20',
            'telefono2'   => 'nullable|string|max:20',
            'telefono3'   => 'nullable|string|max:20',
            'telefono4'   => 'nullable|string|max:20',
            'telefono5'   => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'comentarios' => 'nullable|string|max:500',
            'movistar'    => 'nullable|integer|min:0',
            'entel'       => 'nullable|integer|min:0',
            'claro'       => 'nullable|integer|min:0',
            'bitel'       => 'nullable|integer|min:0',
        ]);

        $lead->nombre      = $request->nombre;
        $lead->dni         = $request->dni;
        $lead->segmento    = $request->segmento;
        $lead->telefono1   = $request->telefono1;
        $lead->telefono2   = $request->telefono2;
        $lead->telefono3   = $request->telefono3;
        $lead->telefono4   = $request->telefono4;
        $lead->telefono5   = $request->telefono5;
        $lead->email       = $request->email;
        $lead->comentarios = $request->comentarios;
        $lead->movistar    = $request->movistar;
        $lead->entel       = $request->entel;
        $lead->claro       = $request->claro;
        $lead->bitel       = $request->bitel;

        $lead->save();

        return back()->with('success', 'Lead actualizado correctamente');
    }
}