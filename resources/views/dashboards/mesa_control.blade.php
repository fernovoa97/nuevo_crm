<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-800">
            Dashboard Mesa de Control
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Panel Superior -->
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1 bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-slate-700 text-lg">
                        Bienvenido, <strong>{{ auth()->user()->name }}</strong>
                    </p>
                    <p class="text-sm text-slate-500 mt-1">Panel de mesa de control</p>
                </div>
                <div class="flex-1 bg-white rounded-2xl shadow-sm p-6 flex gap-6 items-center justify-center">
                    <div class="text-center">
                        <p class="text-sm text-slate-500">En cola</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalCola }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-slate-500">En proceso</p>
                        <p class="text-3xl font-bold text-amber-600">{{ $totalProceso }}</p>
                    </div>
                </div>
            </div>

            <!-- ================= EN COLA ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-slate-400">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-4">
                    🕐 En Cola ({{ $enCola->total() }})
                </p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">Acciones</th>
                                <th class="p-3 text-left">Asesor</th>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Producto</th>
                                <th class="p-3 text-left">Líneas</th>
                                <th class="p-3 text-left">Cargo Fijo</th>
                                <th class="p-3 text-left">Archivos</th>
                                <th class="p-3 text-left">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($enCola as $venta)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3">
                                        <button onclick="abrirModalGestion({{ $venta->id }}, '{{ addslashes($venta->lead->razon_social) }}', '{{ $venta->estado }}', '{{ addslashes($venta->etapa ?? '') }}', '{{ addslashes($venta->observaciones ?? '') }}')"
                                            class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-1 rounded-lg text-xs font-semibold transition">
                                            Gestionar
                                        </button>
                                    </td>
                                    <td class="p-3 font-medium">{{ $venta->asesor->name ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->ruc_empresa ?? $venta->lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->lead->razon_social ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->producto ?? '-' }}</td>
                                    <td class="p-3 text-xs">
                                        @if($venta->lineas_portadas) <span class="bg-blue-100 text-blue-600 px-1 rounded">P: {{ $venta->lineas_portadas }}</span> @endif
                                        @if($venta->lineas_nuevas) <span class="bg-emerald-100 text-emerald-600 px-1 rounded">N: {{ $venta->lineas_nuevas }}</span> @endif
                                    </td>
                                    <td class="p-3">S/ {{ number_format($venta->cargo_fijo, 2) }}</td>
                                    <td class="p-3">
                                        @if($venta->archivos)
                                            @foreach($venta->archivos as $archivo)
                                                <a href="{{ Storage::url($archivo['ruta']) }}" target="_blank"
                                                   class="block text-xs text-blue-500 hover:underline truncate max-w-[120px]">
                                                    📎 {{ $archivo['nombre'] }}
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="text-slate-400 text-xs">Sin archivos</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-xs text-slate-500">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $enCola->links() }}</div>
            </div>

            <!-- ================= EN PROCESO ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-amber-400">
                <p class="text-xs font-semibold text-amber-500 uppercase tracking-wide mb-4">
                    ⚙ En Proceso ({{ $enProceso->total() }})
                </p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-amber-50 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">Acciones</th>
                                <th class="p-3 text-left">Asesor</th>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Producto</th>
                                <th class="p-3 text-left">Etapa actual</th>
                                <th class="p-3 text-left">Observaciones</th>
                                <th class="p-3 text-left">Archivos</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($enProceso as $venta)
                                <tr class="hover:bg-amber-50 transition">
                                    <td class="p-3">
                                        <button onclick="abrirModalGestion({{ $venta->id }}, '{{ addslashes($venta->lead->razon_social) }}', '{{ $venta->estado }}', '{{ addslashes($venta->etapa ?? '') }}', '{{ addslashes($venta->observaciones ?? '') }}')"
                                            class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-lg text-xs font-semibold transition">
                                            Actualizar
                                        </button>
                                    </td>
                                    <td class="p-3 font-medium">{{ $venta->asesor->name ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->ruc_empresa ?? $venta->lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->lead->razon_social ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->producto ?? '-' }}</td>
                                    <td class="p-3 text-xs font-semibold text-amber-600">{{ $venta->etapa ?? '-' }}</td>
                                    <td class="p-3 text-xs text-red-500">{{ $venta->observaciones ?? '-' }}</td>
                                    <td class="p-3">
                                        @if($venta->archivos)
                                            @foreach($venta->archivos as $archivo)
                                                <a href="{{ Storage::url($archivo['ruta']) }}" target="_blank"
                                                   class="block text-xs text-blue-500 hover:underline truncate max-w-[120px]">
                                                    📎 {{ $archivo['nombre'] }}
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="text-slate-400 text-xs">Sin archivos</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $enProceso->links() }}</div>
            </div>

            <!-- ================= COMPLETADAS ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-400">
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-4">
                    ✅ Completadas ({{ $completadas->total() }})
                </p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-emerald-50 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">Asesor</th>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Producto</th>
                                <th class="p-3 text-left">Cargo Fijo</th>
                                <th class="p-3 text-left">Líneas</th>
                                <th class="p-3 text-left">Completada</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($completadas as $venta)
                                <tr class="hover:bg-emerald-50 transition">
                                    <td class="p-3 font-medium">{{ $venta->asesor->name ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->ruc_empresa ?? $venta->lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->lead->razon_social ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->producto ?? '-' }}</td>
                                    <td class="p-3">S/ {{ number_format($venta->cargo_fijo, 2) }}</td>
                                    <td class="p-3 text-xs">
                                        @if($venta->lineas_portadas) <span class="bg-blue-100 text-blue-600 px-1 rounded">P: {{ $venta->lineas_portadas }}</span> @endif
                                        @if($venta->lineas_nuevas) <span class="bg-emerald-100 text-emerald-600 px-1 rounded">N: {{ $venta->lineas_nuevas }}</span> @endif
                                    </td>
                                    <td class="p-3 text-xs text-slate-500">{{ $venta->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $completadas->links() }}</div>
            </div>

            <!-- ================= RECHAZADAS ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-red-400">
                <p class="text-xs font-semibold text-red-500 uppercase tracking-wide mb-4">
                    ❌ Rechazadas ({{ $rechazadas->total() }})
                </p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-red-50 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">Asesor</th>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Producto</th>
                                <th class="p-3 text-left">Motivo rechazo</th>
                                <th class="p-3 text-left">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rechazadas as $venta)
                                <tr class="hover:bg-red-50 transition">
                                    <td class="p-3 font-medium">{{ $venta->asesor->name ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->ruc_empresa ?? $venta->lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->lead->razon_social ?? '-' }}</td>
                                    <td class="p-3">{{ $venta->producto ?? '-' }}</td>
                                    <td class="p-3 text-xs text-red-500">{{ $venta->observaciones ?? '-' }}</td>
                                    <td class="p-3 text-xs text-slate-500">{{ $venta->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $rechazadas->links() }}</div>
            </div>

        </div>
    </div>

    <!-- ================= MODAL GESTIONAR VENTA ================= -->
    <div id="modal-gestion" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-lg mx-4">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Gestionar Venta</h3>
            <p id="gestion-razon-social" class="text-sm text-slate-500 mb-6"></p>

            <form id="form-gestion" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Estado</label>
                    <select name="estado" id="gestion-estado" required
                            class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                        <option value="en_cola">En cola</option>
                        <option value="en_proceso">En proceso</option>
                        <option value="completada">Completada</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Etapa actual</label>
                    <select name="etapa" id="gestion-etapa"
                            class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                        <option value="">Seleccionar etapa</option>
                        <option value="Verificación de carpeta">Verificación de carpeta</option>
                        <option value="Registro de evaluación">Registro de evaluación</option>
                        <option value="Derivado a créditos">Derivado a créditos</option>
                        <option value="Consulta previa">Consulta previa</option>
                        <option value="Programación de pedido">Programación de pedido</option>
                        <option value="Confirmación de pedido">Confirmación de pedido</option>
                        <option value="Pedido entregado">Pedido entregado</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Observaciones</label>
                    <textarea name="observaciones" id="gestion-observaciones" rows="3"
                              placeholder="Escribe aquí si hay algo que el asesor debe corregir o saber..."
                              class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none resize-none"></textarea>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Adjuntar archivos adicionales</label>
                    <input type="file" name="archivos[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                           class="mt-1 w-full text-sm text-slate-600 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="cerrarModalGestion()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-xl text-sm font-semibold transition">Cancelar</button>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalGestion(id, razonSocial, estado, etapa, observaciones) {
            document.getElementById('form-gestion').action        = `/ventas/${id}`;
            document.getElementById('gestion-razon-social').textContent = razonSocial;
            document.getElementById('gestion-estado').value       = estado;
            document.getElementById('gestion-etapa').value        = etapa;
            document.getElementById('gestion-observaciones').value = observaciones;
            document.getElementById('modal-gestion').classList.remove('hidden');
        }

        function cerrarModalGestion() {
            document.getElementById('modal-gestion').classList.add('hidden');
        }

        document.getElementById('modal-gestion').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalGestion();
        });
    </script>

</x-app-layout>