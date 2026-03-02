<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-800">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Panel Superior -->
<div class="flex flex-col md:flex-row gap-6">

    <!-- Saludo -->
    <div class="flex-1 bg-white rounded-2xl shadow-sm p-6">
        <p class="text-slate-700 text-lg">
            Bienvenido, <strong>{{ auth()->user()->name }}</strong>
        </p>
        <p class="text-sm text-slate-500 mt-1">
            Panel de administración general
        </p>
    </div>

    <!-- Administración -->
    <div class="flex-1 bg-white rounded-2xl shadow-sm p-6 flex flex-col justify-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">
            Administración
        </p>

        <a href="{{ route('users.index') }}"
           class="bg-slate-800 hover:bg-slate-900 
                  text-white px-5 py-2.5 
                  rounded-xl text-sm font-semibold 
                  transition duration-200 shadow-sm w-fit">
            Gestionar Usuarios
        </a>
    </div>

    <!-- Cargar datos -->
    <div class="flex-1 bg-white rounded-2xl shadow-sm p-6">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">
            Cargar datos
        </p>

        <form method="POST"
              action="{{ route('leads.importar') }}"
              enctype="multipart/form-data"
              class="flex items-center gap-3 flex-wrap">

            @csrf

            <input type="file"
                   name="archivo"
                   required
                   class="block text-sm text-slate-600
                          file:py-2 file:px-4
                          file:rounded-xl file:border-0
                          file:text-sm file:font-semibold
                          file:bg-slate-100 file:text-slate-700
                          hover:file:bg-slate-200 transition" />

            <button type="submit"
                    class="bg-slate-700 hover:bg-slate-800
                           text-white px-4 py-2
                           rounded-xl text-sm font-semibold
                           transition duration-200 shadow-sm">
                Subir
            </button>

        </form>
    </div>

</div>
            <!-- Contadores -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">Total Leads</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $total }}</p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">Asignados</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $asignados }}</p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">Libres</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $libres }}</p>
                    </div>

                </div>
            </div>

            <!-- Formulario Asignación -->
            
            <div class="bg-white rounded-2xl shadow-sm p-6">
                 <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">
            Asignación
        </p>
                <form method="POST"
                      action="{{ route('leads.asignar') }}"
                      class="flex flex-wrap items-center gap-4">

                    @csrf

                    <select name="user_id"
                            required
                            class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                        @foreach(\App\Models\User::where('role', '!=', 'admin')->get() as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>

                    <input type="number"
                           name="cantidad"
                           placeholder="Cantidad"
                           required
                           min="1"
                           class="border border-slate-200 rounded-xl px-4 py-2.5 w-32 text-sm focus:ring-2 focus:ring-slate-300 outline-none">

                    <button type="submit"
                            class="bg-slate-800 hover:bg-slate-900 
                                   text-white px-5 py-2.5 
                                   rounded-xl text-sm font-semibold 
                                   transition duration-200 shadow-sm">
                        Asignar Leads
                    </button>
                </form>
            </div>

            <!-- Tabla Leads -->
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <h3 class="text-lg font-semibold text-slate-800 mb-4">
                    Leads Totales
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">Teléfono</th>
                                <th class="p-3 text-left">Correo</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-left">Asignado a</th>
                                <th class="p-3 text-left">Creado por</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($leads as $lead)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3">{{ $lead->nombre }}</td>
                                    <td class="p-3">{{ $lead->telefono }}</td>
                                    <td class="p-3">{{ $lead->email }}</td>
                                    <td class="p-3">{{ $lead->status }}</td>
                                    <td class="p-3">{{ $lead->owner?->name ?? 'Sin asignar' }}</td>
                                    <td class="p-3">
                                        {{ \App\Models\User::find($lead->created_by)?->name ?? 'Sistema' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $leads->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>