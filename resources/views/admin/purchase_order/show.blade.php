<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Ver Orden de Compra') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{ route('admin.purchase_orders.index') }}"
            title="{{ __('Detalles de la Orden de Compra ') }}">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 5H1m0 0 4 4M1 5l4-4" />
            </svg>
        </x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="w-full py-2 pt-10 overflow-hidden">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">

            <div>
                <div class="flex flex-col gap-4">
                    <div class="w-full flex justify-between">
                        <div class="flex-1">
                            <a href="#" class="mb-12 flex items-center text-2xl font-bold leading-none capitalize">
                                <span class="ml-2">Finearom</span>
                            </a>
                        </div>
                        <div class="flex-1 text-right">
                            <div class="bg-gray-50 px-4 py-5 sm:px-6 rounded-lg">
                                <div class="sm:grid sm:grid-cols-2 sm:gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Consecutivo de la Orden</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
                                            {{ $purchaseOrder->order_consecutive }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Fecha de Entrega Requerida</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
                                            {{ $purchaseOrder->required_delivery_date }}</dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <div class="border border-gray-300 bg-white p-4 shadow-sm rounded-md">
                                <div class="text-lg font-bold">
                                    {{ $purchaseOrder->client->client_name }}
                                </div>
                               
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Contacto</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Contacto Ejemplo</dd>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="border border-gray-300 bg-white p-4 shadow-sm rounded-md text-right">
                                <div class="text-lg font-bold">Dirección y datos de envío:</div>
                           

                                <p id="contact"  class="flex justify-between"><strong class="title text-left">Contacto:</strong> <span class="value text-right">{{$purchaseOrder->contact}}</span></p>
                                <p id="phone"  class="flex justify-between"><strong class="title text-left">Telefono:</strong> <span class="value text-right">{{$purchaseOrder->phone}}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-4">
                    <div id="products-container" class="rounded-lg mt-4">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                        Producto
                                    </th>
                                    <th class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                        Precio (USD)
                                    </th>
                                    <th class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                        Cantidad
                                    </th>
                                    <th class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                        Total (USD)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($purchaseOrder->products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $product->product_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($product->price * $exchange, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $product->pivot->quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ number_format($product->price * $exchange * $product->pivot->quantity, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="w-full flex justify-end">
                    <div class="relative flex justify-end text-right space-x-8">
                        <div class="bg-white rounded-lg  p-4">
                            <div class="text-lg font-bold">Total parcial: <span id="subtotal">0</span> USD</div>
                            <div>Iva: <span id="iva">0</span> USD</div>
                            <div>Retefuente y ReteICA: <span id="reteica">0</span> USD</div>
                            <div><span class="text-lg font-bold">Total: <span id="total">0</span> USD</span></div>
                        </div>
                    </div>
                </div>
                @if($purchaseOrder->observations)
                <div class="py-2">
                    <dt class="text-sm font-medium text-gray-500">Observaciones</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Observaciones Ejemplo  {{$purchaseOrder->observations}}</dd>
                </div>
                @endif

            </div>

        </div>
    </div>
</x-admin.wrapper>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const exchangeRate = {{ $exchange }};
        updateTotals();

        function updateTotals() {
            let subtotal = 0;
            document.querySelectorAll('#products-container tbody tr').forEach(row => {
                const total = parseFloat(row.querySelector('td:nth-child(4)').textContent) || 0;
                subtotal += total;
            });

            const iva = subtotal * 0.19; // Supongamos que el IVA es 19%
            const reteica = subtotal * 0.00966; // Supongamos que ReteICA es 0.966%
            const total = subtotal + iva + reteica;

            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('iva').textContent = iva.toFixed(2);
            document.getElementById('reteica').textContent = reteica.toFixed(2);
            document.getElementById('total').textContent = total.toFixed(2);
        }
    });
</script>
