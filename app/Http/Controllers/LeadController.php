<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\User;
use App\Imports\LeadsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

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

    $lead->tipificacion = $request->tipificacion;
    $lead->status = 'trabajado';
    $lead->save();

    return back()->with('success', 'Tipificado');
}

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email'
        ]);

        $user = Auth::user();

        Lead::create([
            'nombre'     => $request->nombre,
            'telefono'   => $request->telefono,
            'email'      => $request->email,
            'status'     => 'nuevo',

            'owner_id'   => $user->id,
            'parent_id'  => $user->id,
            'root_id'    => $user->role === 'admin' ? null : $user->id,
            'created_by' => $user->id
        ]);

        return back()->with('success', 'Lead creado correctamente');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new LeadsImport, $request->file('archivo'));

        return back()->with('success', 'Leads importados correctamente');
    }

    public function asignar(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'cantidad' => 'required|integer|min:1'
    ]);

    $user = Auth::user();
    $destino = User::findOrFail($request->user_id);

    // 🔥 ADMIN ahora asigna los que él posee
    if ($user->role === 'admin') {
        $leads = Lead::where('owner_id', $user->id)
            ->limit($request->cantidad)
            ->get();
    } else {
        // JEFE o SUPERVISOR asignan los que poseen
        $leads = Lead::where('owner_id', $user->id)
            ->limit($request->cantidad)
            ->get();
    }

    foreach ($leads as $lead) {

    if ($user->role === 'admin' && $lead->root_id === null) {
        $lead->root_id = $destino->id;
        $lead->parent_id = $destino->id;
    }

    // 🔥 SOLO el JEFE puede cambiar parent_id
    if ($user->role === 'jefe') {
        $lead->parent_id = $destino->id;
    }

    // 🔥 supervisor SOLO cambia owner
    $lead->owner_id = $destino->id;
    $lead->status = 'asignado';
    $lead->save();
}

    return redirect()->route('dashboard')
        ->with('success', 'Leads asignados correctamente');
}
}