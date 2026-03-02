<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Asesor
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Bienvenida -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                Bienvenido, {{ auth()->user()->name }}
            </div>

            <!-- Total -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 text-center">
                <h3 class="text-lg font-semibold">
                    Total Leads: {{ $total }}
                </h3>
            </div>

            <!-- ================= NUEVOS ================= -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-8">

                <h3 class="text-lg font-semibold mb-4">
                    Leads Nuevos ({{ $leadsNuevos->total() }})
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">Nombre</th>
                                <th class="border p-2">Teléfono</th>
                                <th class="border p-2">Correo</th>
                                <th class="border p-2">Tipificar</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($leadsNuevos as $lead)
                                <tr class="text-center">
                                    <td class="border p-2">{{ $lead->nombre }}</td>
                                    <td class="border p-2">{{ $lead->telefono }}</td>
                                    <td class="border p-2">{{ $lead->email }}</td>
                                    <td class="border p-2">
                                        <form method="POST" action="{{ route('leads.tipificar', $lead->id) }}" class="flex gap-2 justify-center">
                                            @csrf
                                            <select name="tipificacion" required class="border rounded p-1">
                                                <option value="">Seleccionar</option>
                                                <option value="Propuesta enviada">Propuesta enviada</option>
                                                <option value="No interesado">No interesado</option>
                                                <option value="Número incorrecto">Número incorrecto</option>
                                                <option value="Volver a llamar">Volver a llamar</option>
                                            </select>
                                            <button type="submit"
                                                    class="bg-green-600 text-white px-3 py-1 rounded">
                                                Guardar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN NUEVOS -->
                <div class="mt-6">
                    {{ $leadsNuevos->links() }}
                </div>

            </div>

            <!-- ================= TRABAJADOS ================= -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">
                    Leads Trabajados ({{ $leadsTrabajados->total() }})
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">Nombre</th>
                                <th class="border p-2">Teléfono</th>
                                <th class="border p-2">Correo</th>
                                <th class="border p-2">Tipificación</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($leadsTrabajados as $lead)
                                <tr class="text-center">
                                    <td class="border p-2">{{ $lead->nombre }}</td>
                                    <td class="border p-2">{{ $lead->telefono }}</td>
                                    <td class="border p-2">{{ $lead->email }}</td>
                                    <td class="border p-2 font-semibold">
                                        {{ $lead->tipificacion }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN TRABAJADOS -->
                <div class="mt-6">
                    {{ $leadsTrabajados->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>