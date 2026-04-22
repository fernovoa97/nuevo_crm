<div id="modal-venta" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-4xl mx-4 max-h-screen overflow-y-auto">

        <h3 class="text-lg font-bold text-black mb-4">💼 Registrar Venta</h3>

        <form method="POST" action="{{ route('ventas.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <input type="hidden" name="lead_id" id="venta-lead-id">

            <!-- ================= PRODUCTO ================= -->
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase">Producto</label>
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
                    <label class="text-xs font-semibold">Tipo de Venta</label>
                    <select name="tipo_venta" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                        <option>PORTABILIDAD</option>
                        <option>ALTA NUEVA</option>
                        <option>RENOVACION</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold">Tipo de Ingreso</label>
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
                <label class="text-xs font-semibold">Estado de Contrato</label>
                <select name="estado_contrato" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                    <option>PENDIENTE DE LOTEO</option>
                    <option>PENDIENTE DE RESPUESTA EN SIGEX</option>
                    <option>CONFORME</option>
                    <option>NO CONFORME</option>
                </select>
            </div>

            <!-- ================= DATOS EMPRESA ================= -->
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="ruc" placeholder="RUC"
                       class="border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="razon_social" placeholder="Razón Social"
                       class="border rounded-xl px-3 py-2 text-sm">
            </div>

            <!-- ================= REPRESENTANTE ================= -->
            <div class="grid grid-cols-2 gap-4">

                <select name="tipo_documento" class="border rounded-xl px-3 py-2 text-sm">
                    <option>DNI</option>
                    <option>CE</option>
                </select>

                <input type="text" name="numero_documento" placeholder="Nro Documento"
                       class="border rounded-xl px-3 py-2 text-sm">

                <div class="col-span-2">
                    <input type="text" name="nombre_representante" placeholder="Nombre Representante"
                           class="border rounded-xl px-3 py-2 text-sm w-full">
                </div>

            </div>

            <!-- ================= CONTACTO ================= -->
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="correo" placeholder="Correo"
                       class="border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="telefono_referencia" placeholder="Teléfono"
                       class="border rounded-xl px-3 py-2 text-sm">
            </div>

            <!-- ================= DIRECCIONES ================= -->
            <div class="space-y-2">
                <input type="text" name="direccion_facturacion" placeholder="Dirección Facturación"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="direccion_entrega" placeholder="Dirección Entrega / Instalación"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="referencias" placeholder="Referencias"
                       class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

            <!-- ================= GEO ================= -->
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="coordenadas" placeholder="Coordenadas"
                       class="border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="plano" placeholder="Plano"
                       class="border rounded-xl px-3 py-2 text-sm">
            </div>

            <!-- ================= BLOQUE MOVIL ================= -->
            <div id="bloque-movil" class="hidden space-y-4 border-t pt-4">

                <h4 class="text-sm font-bold text-[#00AEEF]">📱 Datos Móvil</h4>

                <input type="text" name="plan" placeholder="Plan(es) solicitados"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <select name="operador" class="w-full border rounded-xl px-3 py-2 text-sm">
                    <option>ENTEL</option>
                    <option>MOVISTAR</option>
                    <option>BITEL</option>
                    <option>OTROS</option>
                </select>

                <input type="text" name="large" placeholder="Large"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <div class="grid grid-cols-2 gap-4">
                    <input type="date" name="fecha_despacho"
                           class="border rounded-xl px-3 py-2 text-sm">

                    <select name="rango_horario" class="border rounded-xl px-3 py-2 text-sm">
                        <option>SLA 3H</option>
                        <option>AM1</option>
                        <option>AM2</option>
                        <option>PM1</option>
                        <option>PM2</option>
                    </select>
                </div>

                <select name="descuento"
                        onchange="toggleDescuento()"
                        class="border rounded-xl px-3 py-2 text-sm">
                    <option>NO APLICA</option>
                    <option>50%</option>
                    <option>DCTO BAJO PLANTILLA</option>
                </select>

                <div id="campo-wf" class="hidden">
                    <input type="text" name="nro_wf" placeholder="Nro WF"
                           class="border rounded-xl px-3 py-2 text-sm w-full">
                </div>

            </div>

            <!-- ================= BLOQUE FIJA ================= -->
            <div id="bloque-fija" class="hidden space-y-4 border-t pt-4">

                <h4 class="text-sm font-bold text-black">🏠 Datos Fija</h4>

                <input type="text" name="coordenadas_factibilidad" placeholder="Coordenadas Factibilidad"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="plano_factibilidad" placeholder="Plano Factibilidad"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="direccion_instalacion" placeholder="Dirección Instalación"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="referencia_direccion" placeholder="Referencia Dirección"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="telefono_sot" placeholder="Teléfono SOT"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="date" name="fecha_programacion"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="plan_fija" placeholder="Plan elegido"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <input type="text" name="precio" placeholder="Precio servicio"
                       class="w-full border rounded-xl px-3 py-2 text-sm">

                <select name="tecnologia" class="w-full border rounded-xl px-3 py-2 text-sm">
                    <option>HFC</option>
                    <option>FTTH</option>
                </select>

                <select name="full_claro" onchange="toggleFullClaro()" class="w-full border rounded-xl px-3 py-2 text-sm">
                    <option>NO APLICA</option>
                    <option>APLICA</option>
                </select>

                <div id="campo-fullclaro" class="hidden">
                    <input type="text" name="numero_fullclaro" placeholder="Número Full Claro"
                           class="w-full border rounded-xl px-3 py-2 text-sm">
                </div>

            </div>

            <!-- ================= ARCHIVOS ================= -->
            <div>
                <input type="file" name="archivos[]" multiple class="w-full text-sm">
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