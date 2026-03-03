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
                            <label class="block text-sm font-medium mb-2 text-slate-600">
                                Nombre
                            </label>
                            <input name="name"
                                   required
                                   class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm focus:ring-2 focus:ring-slate-300 outline-none"
                                   placeholder="Nombre completo">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-600">
                                Email
                            </label>
                            <input name="email"
                                   type="email"
                                   required
                                   class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm focus:ring-2 focus:ring-slate-300 outline-none"
                                   placeholder="correo@empresa.com">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-600">
                                Contraseña
                            </label>

                            <div class="relative">
                                <input id="password"
                                       name="password"
                                       type="password"
                                       required
                                       class="border border-slate-200 rounded-xl px-4 py-2.5 w-full pr-12 text-sm focus:ring-2 focus:ring-slate-300 outline-none"
                                       placeholder="Contraseña">

                                <button type="button"
                                        onclick="togglePassword()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">
                                    👁
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-600">
                                Rol
                            </label>
                            <select name="role"
                                    required
                                    class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                                <option value="jefe">Jefe</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="asesor">Asesor</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-2 text-slate-600">
        Superior
    </label>
    <select name="parent_id"
            class="border border-slate-200 rounded-xl px-4 py-2.5 w-full text-sm focus:ring-2 focus:ring-slate-300 outline-none">
        <option value="">Sin superior</option>

        @foreach(\App\Models\User::whereIn('role', ['admin','jefe','supervisor'])->get() as $u)
            <option value="{{ $u->id }}">
                {{ $u->name }} ({{ $u->role }})
            </option>
        @endforeach

    </select>
</div>

                    </div>

                    <div class="mt-8">
                        <button type="submit"
                                class="bg-slate-800 hover:bg-slate-900 
                                       text-white px-6 py-2.5 
                                       rounded-xl text-sm font-semibold 
                                       transition duration-200 shadow-sm">
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
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">Correo</th>
                                <th class="p-3 text-left">Rol</th>
                                <th class="p-3 text-left">Superior</th>
                                <th class="p-3 text-left">Contraseña</th>
                                <th class="p-3 text-left">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $u)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3 font-medium">
                                        {{ $u->name }}
                                    </td>

                                    <td class="p-3">
                                        {{ $u->email }}
                                    </td>

                                    <td class="p-3 capitalize">
                                        {{ $u->role }}
                                    </td>

                                    <td class="p-3">
                                        {{ $u->parent?->name ?? 'Ninguno' }}
                                    </td>

                                    <!-- Contraseña (segura) -->
                                    <td class="p-3">
                                        <span class="tracking-widest">••••••••</span>

                                        <form method="POST"
                                              action="{{ route('users.resetPassword', $u->id) }}"
                                              class="inline-block ml-2">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded-lg transition">
                                                Reset
                                            </button>
                                        </form>
                                    </td>

                                    <td class="p-3">
                                        <a href="{{ route('users.edit', $u->id) }}"
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs transition">
                                            Editar
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</x-app-layout>