<div id="modal-venta" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-4xl mx-4 max-h-screen overflow-y-auto">

        <h3 class="text-lg font-bold text-black mb-4">💼 Registrar Venta</h3>

        <p id="venta-razon-social" class="text-sm text-gray-600 mb-4"></p>

        <form method="POST" action="{{ route('ventas.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <input type="hidden" name="lead_id" id="venta-lead-id">

            <!-- ================= PRODUCTO ================= -->
            <div>
                <label class="text-xs font-bold text-gray-600">📌 Producto (Tipo de servicio a vender)</label>
                <select name="producto" id="producto"
                    class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                    <option value="">Seleccionar</option>
                    <option value="movil">Móvil</option>
                    <option value="fija">Fija</option>
                </select>
            </div>

            <!-- ================= CAMPOS GENERALES ================= -->
            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="text-xs font-bold text-gray-600">📌 Tipo de Venta (Cómo se genera la venta)</label>
                    <select name="tipo_venta" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                        <option>PORTABILIDAD</option>
                        <option>ALTA NUEVA</option>
                        <option>RENOVACION</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">📌 Tipo de Ingreso (Origen de la venta)</label>
                    <select name="tipo_ingreso"
                            onchange="toggleContrato()"
                            class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                        <option>PDV</option>
                        <option>CENTRALIZADO</option>
                        <option>ALMACEN PROPIO</option>
                    </select>
                </div>

            </div>

            <!-- ================= ESTADO CONTRATO ================= -->
            <div id="campo-contrato" class="hidden">
                <label class="text-xs font-bold text-gray-600">📌 Estado del Contrato (Seguimiento interno)</label>
                <select name="estado_contrato" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                    <option>PENDIENTE DE LOTEO</option>
                    <option>PENDIENTE DE RESPUESTA EN SIGEX</option>
                    <option>CONFORME</option>
                    <option>NO CONFORME</option>
                </select>
            </div>

            <!-- ================= DATOS EMPRESA ================= -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600">🏢 RUC de la empresa cliente</label>
                    <input type="text" name="ruc" id="venta-ruc"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">🏢 Razón Social (Nombre de la empresa)</label>
                    <input type="text" name="razon_social"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>
            </div>

            <!-- ================= REPRESENTANTE ================= -->
            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="text-xs font-bold text-gray-600">👤 Tipo de Documento</label>
                    <select name="tipo_documento" class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                        <option>DNI</option>
                        <option>CE</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">👤 Número de Documento</label>
                    <input type="text" name="numero_documento" id="venta-dni"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>

                <div class="col-span-2">
                    <label class="text-xs font-bold text-gray-600">👤 Nombre del Representante</label>
                    <input type="text" name="nombre_representante" id="venta-nombre"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>

            </div>

            <!-- ================= CONTACTO ================= -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600">📧 Correo de contacto</label>
                    <input type="text" name="correo"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">📞 Teléfono de referencia</label>
                    <input type="text" name="telefono_referencia"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>
            </div>

            <!-- ================= DIRECCIONES ================= -->
            <div class="space-y-2">
                <div>
                    <label class="text-xs font-bold text-gray-600">📍 Dirección de Facturación</label>
                    <input type="text" name="direccion_facturacion"
                           class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">📍 Dirección de Instalación / Entrega</label>
                    <input type="text" name="direccion_entrega"
                           class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">📍 Referencias de ubicación</label>
                    <input type="text" name="referencias"
                           class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                </div>
            </div>

            <!-- ================= GEO ================= -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600">🌎 Coordenadas (Google Maps)</label>
                    <input type="text" name="coordenadas"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">🗺 Plano / Croquis</label>
                    <input type="text" name="plano"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>
            </div>

            <!-- ================= BLOQUE MOVIL ================= -->
            <div id="bloque-movil" class="hidden space-y-4 border-t pt-4">

                <h4 class="text-sm font-bold text-[#00AEEF]">📱 Datos Móvil</h4>

                <div>
                    <label class="text-xs font-bold text-gray-600">📱 Plan(es) solicitados</label>
                    <input type="text" name="plan"
                           class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">📱 Operador actual</label>
                    <select name="operador" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                        <option>ENTEL</option>
                        <option>MOVISTAR</option>
                        <option>BITEL</option>
                        <option>OTROS</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">📱 Large (código interno)</label>
                    <input type="text" name="large"
                           class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-600">📅 Fecha de despacho</label>
                        <input type="date" name="fecha_despacho"
                               class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600">⏰ Rango horario</label>
                        <select name="rango_horario" class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                            <option>SLA 3H</option>
                            <option>AM1</option>
                            <option>AM2</option>
                            <option>PM1</option>
                            <option>PM2</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-600">💰 Tipo de descuento</label>
                    <select name="descuento"
                            onchange="toggleDescuento()"
                            class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                        <option>NO APLICA</option>
                        <option>50%</option>
                        <option>DCTO BAJO PLANTILLA</option>
                    </select>
                </div>

                <div id="campo-wf" class="hidden">
                    <label class="text-xs font-bold text-gray-600">📄 Número WF</label>
                    <input type="text" name="nro_wf"
                           class="mt-1 border rounded-xl px-3 py-2 text-sm w-full">
                </div>

            </div>

            <!-- ================= BLOQUE FIJA ================= -->
            <div id="bloque-fija" class="hidden space-y-4 border-t pt-4">

                <h4 class="text-sm font-bold text-black">🏠 Datos Fija</h4>

                <label class="text-xs font-bold text-gray-600">📍 Coordenadas de factibilidad</label>
                <input type="text" name="coordenadas_factibilidad" class="w-full border rounded-xl px-3 py-2 text-sm">

                <label class="text-xs font-bold text-gray-600">🗺 Plano de factibilidad</label>
                <input type="text" name="plano_factibilidad" class="w-full border rounded-xl px-3 py-2 text-sm">

                <label class="text-xs font-bold text-gray-600">📍 Dirección de instalación</label>
                <input type="text" name="direccion_instalacion" class="w-full border rounded-xl px-3 py-2 text-sm">

                <label class="text-xs font-bold text-gray-600">📍 Referencia de dirección</label>
                <input type="text" name="referencia_direccion" class="w-full border rounded-xl px-3 py-2 text-sm">

                <label class="text-xs font-bold text-gray-600">📞 Teléfono SOT</label>
                <input type="text" name="telefono_sot" class="w-full border rounded-xl px-3 py-2 text-sm">

                <label class="text-xs font-bold text-gray-600">📅 Fecha de programación</label>
                <input type="date" name="fecha_programacion" class="w-full border rounded-xl px-3 py-2 text-sm">

                <label class="text-xs font-bold text-gray-600">📦 Plan contratado</label>
                <input type="text" name="plan_fija" class="w-full border rounded-xl px-3 py-2 text-sm">

                <label class="text-xs font-bold text-gray-600">💰 Precio del servicio</label>
                <input type="text" name="precio" class="w-full border rounded-xl px-3 py-2 text-sm">

                <label class="text-xs font-bold text-gray-600">🔌 Tecnología</label>
                <select name="tecnologia" class="w-full border rounded-xl px-3 py-2 text-sm">
                    <option>HFC</option>
                    <option>FTTH</option>
                </select>

                <label class="text-xs font-bold text-gray-600">📦 Full Claro</label>
                <select name="full_claro" onchange="toggleFullClaro()" class="w-full border rounded-xl px-3 py-2 text-sm">
                    <option>NO APLICA</option>
                    <option>APLICA</option>
                </select>

                <div id="campo-fullclaro" class="hidden">
                    <label class="text-xs font-bold text-gray-600">📞 Número Full Claro</label>
                    <input type="text" name="numero_fullclaro" class="w-full border rounded-xl px-3 py-2 text-sm">
                </div>

            </div>

            <!-- ================= ARCHIVOS ================= -->
            <div>
                <label class="text-xs font-bold text-gray-600">📎 Adjuntar archivos (contratos, fotos, etc.)</label>
                <input type="file" name="archivos[]" multiple class="w-full text-sm mt-1">
            </div>

            <!-- ================= BOTONES ================= -->
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="cerrarModalVenta()"
                    class="bg-gray-100 px-5 py-2 rounded-xl text-sm">
                    Cancelar
                </button>

                <button type="submit"
                    class="bg-[#00AEEF] text-white px-5 py-2 rounded-xl text-sm">
                    Enviar
                </button>
            </div>

        </form>
    </div>
</div>