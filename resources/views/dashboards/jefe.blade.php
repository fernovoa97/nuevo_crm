<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Jefe
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Bienvenida -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                Bienvenido, {{ auth()->user()->name }}
            </div>

            <!-- Contadores -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <h3>Total: {{ $total }}</h3>
                    <h3>Asignados: {{ $asignados }}</h3>
                    <h3>Libres: {{ $libres }}</h3>
                </div>
            </div>

            <!-- Formulario Asignación -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">Asignar Leads</h3>

                <form method="POST" action="{{ route('leads.asignar') }}" class="flex gap-4 items-center">
                    @csrf

                    <select name="user_id" required class="border rounded p-2">
                        @foreach(auth()->user()->children as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }} ({{ $u->role }})
                            </option>
                        @endforeach
                    </select>

                    <input type="number"
                           name="cantidad"
                           required
                           min="1"
                           placeholder="Cantidad"
                           class="border rounded p-2 w-32">

                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded">
                        Asignar
                    </button>
                </form>

            </div>


            <!-- Tabla Leads -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">

                <h3 class="text-lg font-semibold mb-4">Leads del Equipo</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">Nombre</th>
                                <th class="border p-2">Teléfono</th>
                                <th class="border p-2">Correo</th>
                                <th class="border p-2">Status</th>
                                <th class="border p-2">Asignado a</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($leads as $lead)
                                <tr class="text-center">
                                    <td class="border p-2">{{ $lead->nombre }}</td>
                                    <td class="border p-2">{{ $lead->telefono }}</td>
                                    <td class="border p-2">{{ $lead->email }}</td>
                                    <td class="border p-2">{{ $lead->status }}</td>
                                    <td class="border p-2">
                                        {{ $lead->owner?->name ?? 'Sin asignar' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN -->
                <div class="mt-6">
                    {{ $leads->links() }}
                </div>

            </div>

            
        </div>
    </div>
</x-app-layout>