<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-800">
            Dashboard Asesor
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Panel Superior -->
            <div class="flex flex-col md:flex-row gap-6">

                <!-- Bienvenida -->
                <div class="flex-1 bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-slate-700 text-lg">
                        Bienvenido, <strong>{{ auth()->user()->name }}</strong>
                    </p>
                    <p class="text-sm text-slate-500 mt-1">
                        Panel de gestión personal
                    </p>
                </div>

                

            </div>

            <!-- Total -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 text-center">
    <div class="grid grid-cols-3 gap-4">
        <div>
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Trabajados</p>
            <p class="text-2xl font-bold text-gray-800">{{ $trabajados }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Pendientes</p>
            <p class="text-2xl font-bold text-gray-800">{{ $pendientes }}</p>
        </div>
    </div>
</div>

            <!-- ================= NUEVOS ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-4">
                    Leads Nuevos ({{ $leadsNuevos->total() }})
                </p>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Segmento</th>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">DNI</th>
                                <th class="p-3 text-left">Teléfono 1</th>
                                <th class="p-3 text-left">Teléfono 2</th>
                                <th class="p-3 text-left">Teléfono 3</th>
                                <th class="p-3 text-left">Teléfono 4</th>
                                <th class="p-3 text-left">Teléfono 5</th>
                                <th class="p-3 text-left">Tipificar</th>
                                <th class="p-3 text-left">Correo</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($leadsNuevos as $lead)
                                <tr class="hover:bg-slate-50 transition">

                                    <td class="p-3">{{ $lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->razon_social ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->segmento ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->nombre ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->dni ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono1 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono2 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono3 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono4 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono5 ?? '-' }}</td>
                                    <td class="p-3">
                                        <form method="POST"
                                              action="{{ route('leads.tipificar', $lead->id) }}"
                                              class="flex gap-2">

                                            @csrf

                                            <select name="tipificacion"
                                                    required
                                                    class="border border-slate-200 rounded-xl px-3 py-1 text-xs focus:ring-2 focus:ring-slate-300 outline-none">
                                                <option value="">Seleccionar</option>
                                                <option value="Propuesta enviada">Propuesta enviada</option>
                                                <option value="No interesado">No interesado</option>
                                                <option value="Número incorrecto">Número incorrecto</option>
                                                <option value="Volver a llamar">Volver a llamar</option>
                                            </select>

                                            <button type="submit"
                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded-xl text-xs transition">
                                                Guardar
                                            </button>

                                        </form>
                                    </td>
                                    <td class="p-3">{{ $lead->email ?? '-' }}</td>

                                    

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $leadsNuevos->links() }}
                </div>

            </div>

            <!-- ================= TRABAJADOS ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-4">
                    Leads Trabajados ({{ $leadsTrabajados->total() }})
                </p>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Segmento</th>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">DNI</th>
                                <th class="p-3 text-left">Teléfono 1</th>
                                <th class="p-3 text-left">Teléfono 2</th>
                                <th class="p-3 text-left">Teléfono 3</th>
                                <th class="p-3 text-left">Teléfono 4</th>
                                <th class="p-3 text-left">Teléfono 5</th>
                                <th class="p-3 text-left">Correo</th>
                                <th class="p-3 text-left">Tipificación</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($leadsTrabajados as $lead)
                                <tr class="hover:bg-slate-50 transition">

                                    <td class="p-3">{{ $lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->razon_social ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->segmento ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->nombre ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->dni ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono1 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono2 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono3 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono4 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->telefono5 ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->email ?? '-' }}</td>

                                    <td class="p-3 font-semibold">
                                        {{ $lead->tipificacion }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $leadsTrabajados->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>