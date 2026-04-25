<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-800">
            Gestión de Usuarios
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-3 rounded-xl mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- CREAR USUARIO -->
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <h3 class="text-lg font-semibold text-slate-800 mb-6">
                    Crear Nuevo Usuario
                </h3>

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-600">Nombre</label>
                            <input name="name" required
                                   class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm"
                                   placeholder="Nombre completo">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-600">Email</label>
                            <input name="email" type="email" required
                                   class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm"
                                   placeholder="correo@empresa.com">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-600">Contraseña</label>
                            <input name="password" type="password" required
                                   class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm"
                                   placeholder="Contraseña">
                        </div>

                        <!-- 🔥 CORREGIDO AQUÍ -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-600">Rol</label>
                            <select name="role" required
                                    class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm">

                                <option value="jefe">Jefe</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="asesor">Asesor</option>
                                <option value="mesa_control">Mesa de control</option>

                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-2 text-slate-600">Superior</label>
                            <select name="parent_id"
                                    class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm">

                                <option value="">Sin superior</option>

                                @foreach(\App\Models\User::whereIn('role', ['admin','jefe','supervisor','mesa_control'])->get() as $u)
                                    <option value="{{ $u->id }}">
                                        {{ $u->name }} ({{ $u->role }})
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>

                    <div class="mt-8">
                        <button type="submit"
                                class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl text-sm font-semibold">
                            Crear Usuario
                        </button>
                    </div>

                </form>
            </div>

            <!-- LISTA DE USUARIOS -->
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <h3 class="text-lg font-semibold text-slate-800 mb-6">
                    Lista de Usuarios
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">Correo</th>
                                <th class="p-3 text-left">Rol</th>
                                <th class="p-3 text-left">Superior</th>
                                <th class="p-3 text-left">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach($users as $u)
                                <tr>
                                    <td class="p-3 font-medium">{{ $u->name }}</td>
                                    <td class="p-3">{{ $u->email }}</td>
                                    <td class="p-3 capitalize">{{ $u->role }}</td>
                                    <td class="p-3">{{ $u->parent?->name ?? 'Ninguno' }}</td>

                                    <td class="p-3 flex gap-2">

                                        <!-- EDITAR -->
                                        <a href="{{ route('users.edit', $u->id) }}"
                                           class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs">
                                            Editar
                                        </a>

                                        <!-- RESET PASSWORD -->
                                        <form method="POST"
                                              action="{{ route('users.resetPassword', $u->id) }}">
                                            @csrf
                                            <button class="bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs">
                                                Reset
                                            </button>
                                        </form>

                                        <!-- 🔥 ELIMINAR -->
                                        <form method="POST"
                                              action="{{ route('users.destroy', $u->id) }}"
                                              onsubmit="return confirm('¿Eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs">
                                                Eliminar
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>