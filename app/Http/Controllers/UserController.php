<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $newPassword = Str::random(8);

        $user->password = Hash::make($newPassword);
        $user->save();

        return back()->with('success', 'Nueva contraseña: ' . $newPassword);
    }

    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = User::with('parent')->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'role'      => 'required|in:admin,jefe,supervisor,asesor,mesa_control',
            'parent_id' => 'nullable|exists:users,id'
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'parent_id' => $request->parent_id
        ]);

        return back()->with('success', 'Usuario creado correctamente');
    }

    public function edit(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = User::where('id', '!=', $user->id)->get();

        return view('users.edit', compact('user', 'users'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|min:6',
            'role'      => 'required|in:admin,jefe,supervisor,asesor,mesa_control',
            'parent_id' => 'nullable|exists:users,id'
        ]);

        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->role      = $request->role;
        $user->parent_id = $request->parent_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }
}