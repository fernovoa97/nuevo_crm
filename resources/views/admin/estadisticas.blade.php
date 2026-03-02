<x-app-layout>
    <x-slot name="header">
        <h2>Estadísticas de Asesores</h2>
    </x-slot>

    <div class="p-6">

        <table border="1" width="100%">
            <tr>
                <th>Asesor</th>
                <th>Asignados</th>
                <th>Trabajados</th>
                <th>Productividad</th>
            </tr>

            @foreach($estadisticas as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->total_asignados }}</td>
                <td>{{ $item->total_trabajados }}</td>
                <td>
                    @if($item->total_asignados > 0)
                        {{ round(($item->total_trabajados / $item->total_asignados) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
            </tr>
            @endforeach
        </table>

    </div>
</x-app-layout>