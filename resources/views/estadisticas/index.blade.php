<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Estadísticas del Equipo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- FILTROS -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">

                <form method="GET" action="{{ route('estadisticas') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                        <div>
                            <label class="block text-sm font-medium mb-1">Desde</label>
                            <input type="date"
                                   name="desde"
                                   value="{{ $desde }}"
                                   class="border rounded p-2 w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Hasta</label>
                            <input type="date"
                                   name="hasta"
                                   value="{{ $hasta }}"
                                   class="border rounded p-2 w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Jefe</label>
                            <select name="jefe" class="border rounded p-2 w-full">
                                <option value="">Todos</option>
                                @foreach($jefes as $j)
                                    <option value="{{ $j->id }}"
                                        {{ $j->id == $jefe ? 'selected' : '' }}>
                                        {{ $j->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Supervisor</label>
                            <select name="supervisor" class="border rounded p-2 w-full">
                                <option value="">Todos</option>
                                @foreach($supervisores as $s)
                                    <option value="{{ $s->id }}"
                                        {{ $s->id == $supervisor ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <button type="submit"
                                    class="bg-teal-950 text-white px-4 py-2 rounded w-full">
                                Filtrar
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <!-- TABLA -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">
                    Rendimiento de Asesores
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">Asesor</th>
                                <th class="border p-2">Asignados</th>
                                <th class="border p-2">Trabajados</th>
                                <th class="border p-2">Última asignación</th>
<th class="border p-2">Cantidad última</th>
                                <th class="border p-2">Avance</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($estadisticas as $item)
                                <tr class="text-center">
                                    <td class="border p-2 font-medium">
                                        {{ $item->name }}
                                    </td>

                                    <td class="border p-2">
                                        {{ $item->total_asignados }}
                                    </td>

                                    <td class="border p-2">
                                        {{ $item->total_trabajados }}
                                    </td>
                                    <td class="border p-2">
    {{ $item->ultima_asignacion 
        ? \Carbon\Carbon::parse($item->ultima_asignacion)->format('d-m-Y') 
        : '-' }}
</td>

<td class="border p-2">
    {{ $item->cantidad_ultima ?? 0 }}
</td>

                                    <td class="border p-2">
                                        
    @php
        $porcentaje = $item->total_asignados > 0
            ? round(($item->total_trabajados / $item->total_asignados) * 100, 1)
            : 0;

        if ($porcentaje <= 25) {
            $color = 'bg-teal-500';
        } elseif ($porcentaje <= 50) {
            $color = 'bg-teal-600';
        } elseif ($porcentaje <= 75) {
            $color = 'bg-teal-700';
        } else {
            $color = 'bg-teal-800';
        }
    @endphp

    <div class="w-full bg-gray-200 rounded-full h-5">
        <div class="{{ $color }} h-5 rounded-full text-xs text-white font-bold flex items-center justify-center"
             style="width: {{ $porcentaje }}%">
            {{ $porcentaje }}%
        </div>
    </div>
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