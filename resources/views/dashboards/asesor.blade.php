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
                <div class="flex-1 bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-slate-700 text-lg">
                        Bienvenido, <strong>{{ auth()->user()->name }}</strong>
                    </p>
                    <p class="text-sm text-slate-500 mt-1">Panel de gestión personal</p>
                </div>
            </div>

            <!-- Contadores -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
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

            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ================= NUEVOS ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-4">
                    Leads Nuevos ({{ $leadsNuevos->total() }})
                </p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-slate-100 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">Acciones</th>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Segmento</th>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">DNI</th>
                                <th class="p-3 text-left">Teléfonos</th>
                                <th class="p-3 text-left">Movistar</th>
                                <th class="p-3 text-left">Entel</th>
                                <th class="p-3 text-left">Claro</th>
                                <th class="p-3 text-left">Bitel</th>
                                <th class="p-3 text-left">Tipificar</th>
                                <th class="p-3 text-left">Correo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($leadsNuevos as $lead)
                                <tr class="hover:bg-slate-50 transition" id="lead-row-{{ $lead->id }}">
                                    <td class="p-3">
                                        <button onclick="abrirModal({{ $lead->id }}, '{{ addslashes($lead->nombre) }}', '{{ $lead->dni }}', '{{ $lead->segmento }}', '{{ $lead->telefono1 }}', '{{ $lead->telefono2 }}', '{{ $lead->telefono3 }}', '{{ $lead->telefono4 }}', '{{ $lead->telefono5 }}', '{{ $lead->email }}', '{{ addslashes($lead->comentarios) }}', {{ $lead->movistar ?? 0 }}, {{ $lead->entel ?? 0 }}, {{ $lead->claro ?? 0 }}, {{ $lead->bitel ?? 0 }})"
                                            class="bg-slate-600 hover:bg-slate-700 text-white px-2 py-1 rounded-lg text-xs font-semibold transition">✏</button>
                                    </td>
                                    <td class="p-3">{{ $lead->ruc ?? '-' }}</td>
                                    <td class="p-3 whitespace-nowrap max-w-[250px] truncate">{{ $lead->razon_social ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->segmento ?? '-' }}</td>
                                    <td class="p-3 whitespace-nowrap max-w-[250px] truncate">{{ $lead->nombre ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->dni ?? '-' }}</td>
                                    <td class="p-3">
                                        <div class="flex flex-col gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @php $tel = $lead->{"telefono$i"}; $estado = $lead->{"telefono{$i}_estado"}; @endphp
                                                @if($tel)
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-xs font-mono px-2 py-0.5 rounded-lg
                                                            {{ $estado === 'exitoso' ? 'bg-emerald-100 text-emerald-700 font-bold' : '' }}
                                                            {{ $estado === 'incorrecto' ? 'bg-red-100 text-red-400 line-through' : '' }}
                                                            {{ !$estado ? 'bg-slate-100 text-slate-700' : '' }}">{{ $tel }}</span>
                                                        <button onclick="marcarTelefono({{ $lead->id }}, {{ $i }}, 'exitoso')" class="text-emerald-500 hover:text-emerald-700 transition">✔</button>
                                                        <button onclick="marcarTelefono({{ $lead->id }}, {{ $i }}, 'incorrecto')" class="text-red-400 hover:text-red-600 transition">✘</button>
                                                        @if($estado)
                                                            <button onclick="marcarTelefono({{ $lead->id }}, {{ $i }}, 'null')" class="text-slate-400 hover:text-slate-600 transition text-xs">↺</button>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="p-3">{{ $lead->movistar ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->entel ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->claro ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->bitel ?? '-' }}</td>
                                    <td class="p-3">
                                        <form method="POST"
                                              action="{{ route('leads.tipificar', $lead->id) }}"
                                              class="flex gap-2 form-tipificacion"
                                              data-lead-id="{{ $lead->id }}">
                                            @csrf
                                            <select name="tipificacion" required
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
                <div class="mt-6">{{ $leadsNuevos->links() }}</div>
            </div>

            <!-- ================= SEGUIMIENTO ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-amber-400">
                <p class="text-xs font-semibold text-amber-500 uppercase tracking-wide mb-4">
                    📅 Volver a Llamar ({{ $leadsSeguimiento->total() }})
                </p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-amber-50 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">Teléfonos</th>
                                <th class="p-3 text-left">Fecha seguimiento</th>
                                <th class="p-3 text-left">Correo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($leadsSeguimiento as $lead)
                                <tr class="hover:bg-amber-50 transition">
                                    <td class="p-3">{{ $lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->razon_social ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->nombre ?? '-' }}</td>
                                    <td class="p-3">
                                        <div class="flex flex-col gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($lead->{"telefono$i"})
                                                    <span class="text-xs font-mono px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700">{{ $lead->{"telefono$i"} }}</span>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="p-3 font-semibold text-amber-600">
                                        {{ $lead->fecha_seguimiento?->format('d/m/Y H:i') ?? '-' }}
                                    </td>
                                    <td class="p-3">{{ $lead->email ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $leadsSeguimiento->links() }}</div>
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
                                <th class="p-3 text-left">Acciones</th>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Segmento</th>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">DNI</th>
                                <th class="p-3 text-left">Teléfonos</th>
                                <th class="p-3 text-left">M</th>
                                <th class="p-3 text-left">E</th>
                                <th class="p-3 text-left">C</th>
                                <th class="p-3 text-left">B</th>
                                <th class="p-3 text-left">Correo</th>
                                <th class="p-3 text-left">Tipificación</th>
                                <th class="p-3 text-left">Retipificar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($leadsTrabajados as $lead)
                                <tr class="hover:bg-slate-50 transition {{ $lead->tipificacion === 'No interesado' ? 'bg-red-50' : '' }}">
                                    <td class="p-3">
                                        <button onclick="abrirModal({{ $lead->id }}, '{{ addslashes($lead->nombre) }}', '{{ $lead->dni }}', '{{ $lead->segmento }}', '{{ $lead->telefono1 }}', '{{ $lead->telefono2 }}', '{{ $lead->telefono3 }}', '{{ $lead->telefono4 }}', '{{ $lead->telefono5 }}', '{{ $lead->email }}', '{{ addslashes($lead->comentarios) }}', {{ $lead->movistar ?? 0 }}, {{ $lead->entel ?? 0 }}, {{ $lead->claro ?? 0 }}, {{ $lead->bitel ?? 0 }})"
                                            class="bg-slate-600 hover:bg-slate-700 text-white px-2 py-1 rounded-lg text-xs font-semibold transition">✏</button>
                                    </td>
                                    <td class="p-3">{{ $lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->razon_social ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->segmento ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->nombre ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->dni ?? '-' }}</td>
                                    <td class="p-3">
                                        <div class="flex flex-col gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @php $tel = $lead->{"telefono$i"}; $estado = $lead->{"telefono{$i}_estado"}; @endphp
                                                @if($tel)
                                                    <span class="text-xs font-mono px-2 py-0.5 rounded-lg
                                                        {{ $estado === 'exitoso' ? 'bg-emerald-100 text-emerald-700 font-bold' : '' }}
                                                        {{ $estado === 'incorrecto' ? 'bg-red-100 text-red-400 line-through' : '' }}
                                                        {{ !$estado ? 'bg-slate-100 text-slate-700' : '' }}">{{ $tel }}</span>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="p-3">{{ $lead->movistar ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->entel ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->claro ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->bitel ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->email ?? '-' }}</td>
                                    <td class="p-3">
                                        @if($lead->tipificacion === 'No interesado')
                                            <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-600">
                                                No interesado
                                            </span>
                                            @php
                                                $dias = now()->diffInDays($lead->fecha_tipificacion);
                                                $diasRestantes = 30 - $dias;
                                            @endphp
                                            <p class="text-xs text-slate-400 mt-1">
                                                ⏳ Recicla en {{ $diasRestantes }} día(s)
                                            </p>
                                        @else
                                            <span class="font-semibold">{{ $lead->tipificacion }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        @if($lead->tipificacion === 'No interesado')
                                            <form method="POST"
                                                  action="{{ route('leads.tipificar', $lead->id) }}"
                                                  class="flex gap-2 form-tipificacion"
                                                  data-lead-id="{{ $lead->id }}">
                                                @csrf
                                                <select name="tipificacion" required
                                                        class="border border-slate-200 rounded-xl px-3 py-1 text-xs focus:ring-2 focus:ring-slate-300 outline-none">
                                                    <option value="">Retipificar</option>
                                                    <option value="Propuesta enviada">Propuesta enviada</option>
                                                    <option value="Número incorrecto">Número incorrecto</option>
                                                    <option value="Volver a llamar">Volver a llamar</option>
                                                </select>
                                                <button type="submit"
                                                        class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-xl text-xs transition">
                                                    Cambiar
                                                </button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $leadsTrabajados->links() }}</div>
            </div>

            <!-- ================= PROPUESTAS / VENTAS ================= -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-400">
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-4">
                    💼 Propuestas Enviadas ({{ $leadsVenta->total() }})
                </p>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-slate-700">
                        <thead class="bg-emerald-50 text-slate-600">
                            <tr>
                                <th class="p-3 text-left">Acciones</th>
                                <th class="p-3 text-left">RUC</th>
                                <th class="p-3 text-left">Razón Social</th>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">Estado venta</th>
                                <th class="p-3 text-left">Etapa</th>
                                <th class="p-3 text-left">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($leadsVenta as $lead)
                                @php $venta = $lead->ventas->first(); @endphp
                                <tr class="hover:bg-emerald-50 transition">
                                    <td class="p-3">
                                        <button onclick="abrirModalVenta({{ $lead->id }}, '{{ addslashes($lead->razon_social) }}', '{{ $lead->ruc }}')"
                                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-1 rounded-lg text-xs font-semibold transition">
                                            💼 Enviar venta
                                        </button>
                                    </td>
                                    <td class="p-3">{{ $lead->ruc ?? '-' }}</td>
                                    <td class="p-3">{{ $lead->razon_social ?? '-' }}</td>
                                    <td class="p-3 font-medium">{{ $lead->nombre ?? '-' }}</td>
                                    <td class="p-3">
                                        @if($venta)
                                            <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $venta->estadoBadge() }}">
                                                {{ $venta->estadoLabel() }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-lg text-xs bg-slate-100 text-slate-500">Sin venta</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-xs">{{ $venta?->etapa ?? '-' }}</td>
                                    <td class="p-3 text-xs text-red-500">{{ $venta?->observaciones ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $leadsVenta->links() }}</div>
            </div>

        </div>
    </div>

    <!-- ================= MODAL EDITAR ================= -->
    <div id="modal-editar" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-lg mx-4">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Editar Lead</h3>
            <form id="form-editar" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Nombre</label>
                        <input type="text" name="nombre" id="edit-nombre" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">DNI</label>
                        <input type="text" name="dni" id="edit-dni" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Segmento</label>
                        <input type="text" name="segmento" id="edit-segmento" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Email</label>
                        <input type="email" name="email" id="edit-email" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @for($i = 1; $i <= 5; $i++)
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Teléfono {{ $i }}</label>
                            <input type="text" name="telefono{{ $i }}" id="edit-telefono{{ $i }}" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                        </div>
                    @endfor
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Comentarios</label>
                    <textarea name="comentarios" id="edit-comentarios" rows="3" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none resize-none"></textarea>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Movistar</label>
                        <input type="number" name="movistar" id="edit-movistar" min="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Entel</label>
                        <input type="number" name="entel" id="edit-entel" min="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Claro</label>
                        <input type="number" name="claro" id="edit-claro" min="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Bitel</label>
                        <input type="number" name="bitel" id="edit-bitel" min="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="cerrarModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-xl text-sm font-semibold transition">Cancelar</button>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL SEGUIMIENTO ================= -->
    <div id="modal-seguimiento" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-sm mx-4">
            <h3 class="text-lg font-bold text-slate-800 mb-6">📅 Agendar Seguimiento</h3>
            <form id="form-seguimiento" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Fecha y hora</label>
                    <input type="datetime-local" name="fecha_seguimiento" id="input-fecha-seguimiento" required
                           class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="cerrarModalSeguimiento()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-xl text-sm font-semibold transition">Cancelar</button>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">Agendar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL VENTA ================= -->
    <div id="modal-venta" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto">
            <h3 class="text-lg font-bold text-slate-800 mb-2">💼 Registrar Venta</h3>
            <p id="venta-razon-social" class="text-sm text-slate-500 mb-6"></p>

            <form id="form-venta" method="POST" action="{{ route('ventas.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="lead_id" id="venta-lead-id">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Producto</label>
                        <input type="text" name="producto" required class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Tipo de producto</label>
                        <input type="text" name="tipo_producto" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">RUC Empresa</label>
                        <input type="text" name="ruc_empresa" id="venta-ruc" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">DNI Representante</label>
                        <input type="text" name="dni_representante" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-semibold text-slate-500 uppercase">Nombre Representante</label>
                        <input type="text" name="nombre_representante" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Cargo Fijo (S/)</label>
                        <input type="number" name="cargo_fijo" step="0.01" min="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Cargo Fijo sin IGV (S/)</label>
                        <input type="number" name="cargo_fijo_sin_igv" step="0.01" min="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Líneas Portadas</label>
                        <input type="number" name="lineas_portadas" min="0" value="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Líneas Nuevas</label>
                        <input type="number" name="lineas_nuevas" min="0" value="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Supervisor</label>
                        <select name="supervisor_id" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-slate-300 outline-none">
                            <option value="">Sin supervisor</option>
                            @foreach(\App\Models\User::where('role', 'supervisor')->get() as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase">Adjuntar archivos (fotos o PDFs)</label>
                    <input type="file" name="archivos[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                           class="mt-1 w-full text-sm text-slate-600 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition">
                    <p class="text-xs text-slate-400 mt-1">Máximo 5MB por archivo. Formatos: JPG, PNG, PDF</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="cerrarModalVenta()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-xl text-sm font-semibold transition">Cancelar</button>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">Enviar a Mesa de Control</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= JAVASCRIPT ================= -->
    <script>
        function marcarTelefono(leadId, numero, estado) {
            fetch(`/leads/${leadId}/marcar-telefono`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ numero, estado })
            })
            .then(res => res.json())
            .then(data => { if (data.success) location.reload(); })
            .catch(err => console.error('Error:', err));
        }

        function abrirModal(id, nombre, dni, segmento, tel1, tel2, tel3, tel4, tel5, email, comentarios, movistar, entel, claro, bitel) {
            document.getElementById('form-editar').action = `/leads/${id}/editar`;
            document.getElementById('edit-nombre').value      = nombre;
            document.getElementById('edit-dni').value         = dni;
            document.getElementById('edit-segmento').value    = segmento;
            document.getElementById('edit-telefono1').value   = tel1;
            document.getElementById('edit-telefono2').value   = tel2;
            document.getElementById('edit-telefono3').value   = tel3;
            document.getElementById('edit-telefono4').value   = tel4;
            document.getElementById('edit-telefono5').value   = tel5;
            document.getElementById('edit-email').value       = email;
            document.getElementById('edit-comentarios').value = comentarios;
            document.getElementById('edit-movistar').value    = movistar;
            document.getElementById('edit-entel').value       = entel;
            document.getElementById('edit-claro').value       = claro;
            document.getElementById('edit-bitel').value       = bitel;
            document.getElementById('modal-editar').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modal-editar').classList.add('hidden');
        }

        document.getElementById('modal-editar').addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });

        function abrirModalSeguimiento(leadId) {
            document.getElementById('form-seguimiento').action = `/leads/${leadId}/agendar-seguimiento`;
            document.getElementById('input-fecha-seguimiento').value = '';
            document.getElementById('modal-seguimiento').classList.remove('hidden');
        }

        function cerrarModalSeguimiento() {
            document.getElementById('modal-seguimiento').classList.add('hidden');
        }

        document.getElementById('modal-seguimiento').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalSeguimiento();
        });

        function abrirModalVenta(leadId, razonSocial, ruc) {
            document.getElementById('venta-lead-id').value           = leadId;
            document.getElementById('venta-razon-social').textContent = razonSocial + ' — RUC: ' + ruc;
            document.getElementById('venta-ruc').value               = ruc;
            document.getElementById('modal-venta').classList.remove('hidden');
        }

        function cerrarModalVenta() {
            document.getElementById('modal-venta').classList.add('hidden');
        }

        document.getElementById('modal-venta').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalVenta();
        });

        // Interceptar "Volver a llamar" → abrir modal de seguimiento en lugar de submit directo
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-tipificacion').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    const select = form.querySelector('select[name="tipificacion"]');
                    if (select.value === 'Volver a llamar') {
                        e.preventDefault();
                        const leadId = form.getAttribute('data-lead-id');
                        abrirModalSeguimiento(leadId);
                    }
                    // Cualquier otra tipificación se envía normalmente
                });
            });
        });
    </script>

</x-app-layout>