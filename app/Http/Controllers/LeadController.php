<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\User;
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

       $lead->tipificacion = $request->tipificacion;
$lead->status = 'trabajado';
$lead->fecha_tipificacion = now();
$lead->save();

        return back()->with('success', 'Tipificado');
    }

    // ⚠️ Solo por si en el futuro quieres crear manual
    public function store(Request $request)
    {
        $request->validate([
            'ruc' => 'required|string|unique:leads,ruc',
            'nombre' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        Lead::create([
            'ruc'        => $request->ruc,
            'razon_social' => $request->razon_social,
            'nombre'     => $request->nombre,
            'dni'        => $request->dni,
            'segmento'   => $request->segmento,

            'telefono1'  => $request->telefono1,
            'telefono2'  => $request->telefono2,
            'telefono3'  => $request->telefono3,
            'telefono4'  => $request->telefono4,
            'telefono5'  => $request->telefono5,

            'email'      => $request->email,
            'comentarios'=> $request->comentarios,

            'status'     => 'nuevo',

            'owner_id'   => $user->id,
            'created_by' => $user->id
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
            'user_id' => 'required|exists:users,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $destino = User::findOrFail($request->user_id);

        $leads = Lead::where('owner_id', $user->id)
            ->limit($request->cantidad)
            ->get();

      foreach ($leads as $lead) {

    if ($user->role === 'admin' && $lead->root_id === null) {
        $lead->root_id = $destino->id;
        $lead->parent_id = $destino->id;
    }

    if ($user->role === 'jefe') {
        $lead->parent_id = $destino->id;
    }

    $lead->owner_id = $destino->id;
    $lead->status = 'asignado';

    $lead->fecha_asignacion = now(); // 👈 ESTA LINEA ES LA CLAVE

    $lead->save();
}

        return redirect()->route('dashboard')
            ->with('success', 'Leads asignados correctamente');
    }
}