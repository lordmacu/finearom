<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Órdenes de compra') }}
    </x-slot>

    @can('purchase_order create')
        <x-admin.add-link href="{{ route('admin.purchase_orders.create') }}">
            {{ __('Agregar orden de compra') }}
        </x-admin.add-link>
    @endcan

    <div class="py-2">
        <form method="GET" action="{{ route('admin.purchase_orders.index') }}">
            <div class="flex-table space-x-4 mb-4">
                <!-- Cliente Dropdown -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700">{{ __('Cliente') }}</label>
                    <select id="client_id" name="client_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">{{ __('Selección de Cliente') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->client_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Fecha de Creación -->
                <div>
                    <label for="creation_date" class="block text-sm font-medium text-gray-700">{{ __('Fecha de Creación') }}</label>
                    <input type="text" id="creation_date" name="creation_date" value="{{ request('creation_date') }}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base daterangepicker border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                </div>
                <!-- Fecha de Entrega -->
                <div>
                    <label for="delivery_date" class="block text-sm font-medium text-gray-700">{{ __('Fecha de Entrega') }}</label>
                    <input type="text" id="delivery_date" name="delivery_date" value="{{ request('delivery_date') }}" class="mt-1 block w-full pl-3 pr-10 py-2 daterangepicker text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                </div>
                <!-- Consecutivo -->
                <div>
                    <label for="order_consecutive" class="block text-sm font-medium text-gray-700">{{ __('Consecutivo') }}</label>
                    <input type="text" id="order_consecutive" name="order_consecutive" value="{{ request('order_consecutive') }}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                </div>
                <!-- Estado de la Orden -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Estado') }}</label>
                    <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">{{ __('Seleccionar Estado') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pendiente') }}</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('En Proceso') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completado') }}</option>
                        <option value="parcial_status" {{ request('status') == 'parcial_status' ? 'selected' : '' }}>{{ __('Parcial') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                    </select>
                </div>
                <!-- Botón de Búsqueda -->
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Buscar') }}
                    </button>
                </div>
            </div>
        </form>

        <div class="min-w-full border-base-200 shadow overflow-x-auto">
            <x-admin.grid.table>
                <x-slot name="head">
                    <tr class="bg-base-200">
                        @php
                            $sortBy = request()->get('sort_by');
                            $sortOrder = request()->get('sort_order', 'asc');
                        @endphp
                        <x-admin.grid.th>
                            <a href="{{ route('admin.purchase_orders.index', array_merge(request()->all(), ['sort_by' => 'order_consecutive', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'order_consecutive' ? 'desc' : 'asc'])) }}">
                                {{ __('Consecutivo') }}
                                @if ($sortBy === 'order_consecutive')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.purchase_orders.index', array_merge(request()->all(), ['sort_by' => 'client_id', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'client_id' ? 'desc' : 'asc'])) }}">
                                {{ __('Cliente') }}
                                @if ($sortBy === 'client_id')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.purchase_orders.index', array_merge(request()->all(), ['sort_by' => 'required_delivery_date', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'required_delivery_date' ? 'desc' : 'asc'])) }}">
                                {{ __('Fecha de Entrega') }}
                                @if ($sortBy === 'required_delivery_date')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.purchase_orders.index', array_merge(request()->all(), ['sort_by' => 'total', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'total' ? 'desc' : 'asc'])) }}">
                                {{ __('Total') }}
                                @if ($sortBy === 'total')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.purchase_orders.index', array_merge(request()->all(), ['sort_by' => 'status', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'status' ? 'desc' : 'asc'])) }}">
                                {{ __('Estado') }}
                                @if ($sortBy === 'status')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            {{ __('Comportamiento') }}
                        </x-admin.grid.th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @forelse($purchaseOrders as $purchaseOrder)
                        <tr>
                            <td>{{ $purchaseOrder->order_consecutive }}</td>
                            <td>{{ $purchaseOrder->client->client_name }}</td>
                            <td>{{ $purchaseOrder->required_delivery_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @php
                                    $total = $purchaseOrder->products->sum(function($product) use($purchaseOrder) {
                                        return ($product->price * $product->pivot->quantity);
                                    });
                                @endphp
                                {{ number_format($total, 2, ',', '.') }}
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <span class="status-indicator {{ $purchaseOrder->status }}"></span>
                                    <select style="width: 100px" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                        onchange="handleStatusChange(this, '{{ $purchaseOrder->id }}', '{{ $purchaseOrder->order_consecutive }}', '{{ $purchaseOrder->status }}')">
                                        <option value="pending" {{ $purchaseOrder->status == 'pending' ? 'selected' : '' }}>{{ __('Pendiente') }}</option>
                                        <option value="processing" {{ $purchaseOrder->status == 'processing' ? 'selected' : '' }}>{{ __('En Proceso') }}</option>
                                        <option value="completed" {{ $purchaseOrder->status == 'completed' ? 'selected' : '' }}>{{ __('Completado') }}</option>
                                        <option value="parcial_status" {{ $purchaseOrder->status == 'parcial_status' ? 'selected' : '' }}>{{ __('Parcial') }}</option>
                                        <option value="cancelled" {{ $purchaseOrder->status == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                                    </select>
                                </div>
                            </td>
                            <td class="icon-btn">
                                <a href="#"
                                onclick="openObservationsModal(
                                    {{ $purchaseOrder->id }},
                                    '{{ $purchaseOrder->order_consecutive }}',
                                    `{!! nl2br(e($purchaseOrder->observations_extra)) !!}`
                                )"
                             >
                                 <svg xmlns="http://www.w3.org/2000/svg"
                                      class="w-6 h-6 {{ !empty($purchaseOrder->observations_extra) ? 'text-green-500' : 'text-gray-500' }}"
                                      fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                     <path stroke-linecap="round"
                                           stroke-linejoin="round"
                                           stroke-width="2"
                                           d="M8 10h.01M12 10h.01M16 10h.01M21 14a2 2 0 01-2 2H7l-4 4V6a2 2 0 012-2h12a2 2 0 012 2v8z"/>
                                 </svg>
                             </a>
                                <a class="pt-2" href="{{ route('admin.purchase_orders.show', $purchaseOrder->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="text-blue-500">
                                        <path fill="currentColor" d="M12 4.5C7 4.5 2.73 8 1 12c1.73 4 6 7.5 11 7.5s9.27-3.5 11-7.5c-1.73-4-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.purchase_orders.edit', $purchaseOrder->id) }}">
                                    <svg style="color: #e3ba41;" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z"></path>
                                        <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                        <path d="M9 15h3l8.5 -8.5a1.5 1.7 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                        <line x1="16" y1="5" x2="19" y2="8"></line>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.purchase-order.pdf', $purchaseOrder->id) }}" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v4h16v-4M12 4v12m0 0l-4-4m4 4l4-4" />
                                    </svg>
                                </a>
                                
                                <form action="{{ route('admin.purchase_orders.destroy', $purchaseOrder->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        <svg style="width: 24px; margin-top: 3px; color: #bd7676;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="flex flex-col justify-center items-center py-4 text-lg">
                                    {{ __('No se encontraron resultados') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-admin.grid.table>
        </div>
        <div class="mt-4">
            {{ $purchaseOrders->appends(request()->except('page'))->links() }}
        </div>
    </div>

<!-- Modal de Observaciones -->
<div id="observationsModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
        <!-- Fondo oscuro -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Contenido del modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="{{ route('admin.purchase_orders.addObservation') }}" id="observationsForm">
                @csrf
                @method('POST')

                <!-- Campo oculto para el ID de la orden -->
                <input type="hidden" id="purchaseOrderId" name="purchase_order_id" value="">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Observaciones de la Orden <span id="orderConsecutive"></span>
                    </h3>
                    <div class="mt-2">
                        <div class="text-sm text-gray-500" id="currentObservations" style="white-space: pre-wrap;">
                            <!-- Aquí se mostrarán las observaciones actuales -->
                        </div>
                        <div class="mt-4">
                            <label for="newObservation" class="block text-sm font-medium text-gray-700">Agregar Nueva Observación</label>
                            <textarea id="newObservation" name="new_observation" rows="3" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="saveObservation" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar
                    </button>
                    <button type="button" id="cancelObservation" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal -->
<div id="confirmationModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" id="statusChangeForm">
                @csrf
                @method('PUT')

                <!-- Campo oculto para el estado seleccionado -->
                <input type="hidden" id="status_row" name="status" value="">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 12h.01M12 6h.01M3 21h18"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirmación de Cambio de Estado
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿Estás seguro de que deseas cambiar el estado a <span id="statusToConfirm"></span>?
                                </p>
                                <div class="mt-4">
                                    <label for="invoice_number" class="block text-sm font-medium text-gray-700">Número de Factura</label>
                                    <input type="text" id="invoice_number" name="invoice_number" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                </div>
                                <div class="mt-4">
                                    <label for="dispatch_date" class="block text-sm font-medium text-gray-700">Fecha de Despacho</label>
                                    <input type="date" id="dispatch_date" name="dispatch_date" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                </div>
                                <div class="mt-4">
                                    <label for="tracking_number" class="block text-sm font-medium text-gray-700">Número de Guía</label>
                                    <input type="text" id="tracking_number" name="tracking_number" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                               <input type="hidden" id="status"/>
                                </div>
                                <div class="mt-4">
                                    <label for="observations" class="block text-sm font-medium text-gray-700">Observaciones</label>
                                    <textarea id="observations" name="observations" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="confirmStatusChange" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmar
                    </button>
                    <button type="button" id="cancelStatusChange" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



</x-admin.wrapper>

<script>

document.addEventListener('DOMContentLoaded', function() {

    window.openObservationsModal = function(purchaseOrderId, orderConsecutive, observationsExtra) {
        document.getElementById('purchaseOrderId').value = purchaseOrderId;
        document.getElementById('orderConsecutive').textContent = orderConsecutive;
        document.getElementById('currentObservations').innerHTML = observationsExtra || 'Sin observaciones';
        document.getElementById('newObservation').value = '';
        document.getElementById('observationsModal').classList.remove('hidden');
    }

    // Cerrar el modal al hacer clic en "Cancelar"
    document.getElementById('cancelObservation').addEventListener('click', function() {
        document.getElementById('observationsModal').classList.add('hidden');
    });

    const selects = document.querySelectorAll('select[name="status"]');
    const modal = document.getElementById('confirmationModal');
    document.getElementById('cancelStatusChange').addEventListener('click', function() {
        modal.classList.add('hidden');
    });
})

function handleStatusChange(selectElement, orderId, orderConsecutive, currentStatus) {
    const selectedValue = selectElement.value;

    if (selectedValue === 'completed' || selectedValue === 'parcial_status') {
        // Mostrar el modal
        const modal = document.getElementById('confirmationModal');
        const statusToConfirm = document.getElementById('statusToConfirm');
        const form = document.getElementById('statusChangeForm');
        const statusField = document.getElementById('status_row');

        // Configurar el título del modal
        statusToConfirm.textContent = selectedValue.charAt(0).toUpperCase() + selectedValue.slice(1);

        // Configurar la acción del formulario
        form.action = `/admin/purchase_orders/${orderId}/update-status`;

        // Establecer el valor del estado en el campo oculto
        statusField.value = selectedValue;

        // Mostrar el modal
        modal.classList.remove('hidden');
    } else {
        // Si el estado no es 'completed' o 'parcial', enviar el formulario automáticamente
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/purchase_orders/${orderId}/update-status`;

        // Añadir los campos ocultos necesarios
        const csrfField = document.createElement('input');
        csrfField.type = 'hidden';
        csrfField.name = '_token';
        csrfField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfField);

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        form.appendChild(methodField);

        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = selectedValue;
        form.appendChild(statusField);

        // Enviar el formulario
        document.body.appendChild(form);
        form.submit();
    }
}


    $(function() {
        document.querySelectorAll('.daterangepicker').forEach(element => {
            const picker = new easepick.create({
                element: element,
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
                zIndex: 10,
                plugins: ['RangePlugin'],
                lang: 'es-Es',
                autoApply: false,
                RangePlugin: {
                    tooltipNumber(num) {
                        return num - 1;
                    },
                    locale: {
                        one: 'día',
                        other: 'días',
                    },
                },
            });

            const ampPlugin = picker.PluginManager.addInstance('AmpPlugin');
            ampPlugin.options.resetButton = true;
        });
    });
</script>

<style>
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
    }
    .status-indicator.pending {
        background-color: yellow;
    }
    .status-indicator.processing {
        background-color: orange;
    }
    .status-indicator.completed {
        background-color: green;
    }
    .status-indicator.cancelled {
        background-color: red;
    }
    .status-indicator.parcial_status {
        background-color: blue;
    }
</style>
