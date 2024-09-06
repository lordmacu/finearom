

<x-admin.layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

   
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                @php
                $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
                $creationDate = request('creation_date'); // Obtener el valor de creation_date
                $dateRange = $creationDate ?? ($startOfMonth . ' - ' . $endOfMonth); // Si existe creation_date, usarlo, si no usar inicio y fin del mes
            @endphp
            
            
            <a href="{{ url('/admin/purchase_orders?creation_date=' . $dateRange) }}" class="block bg-blue-200 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-4 hover:bg-blue-300">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Todas las Ordenes del Mes</h3>
                <p class="mt-1 text-3xl leading-9 font-semibold text-gray-900">{{ $allOrders->count() }}</p>
                
                <p class="mt-4 text-xl leading-7 font-medium text-gray-900">
                    <span class="text-base">Total en Pesos:</span> ${{ number_format($totalPriceAllMonth) }}
                </p>
                
                <p class="mt-1 text-xl leading-7 font-medium text-gray-900">
                    <strong><span class="text-base">Total en USD:</span> &dollar;{{ number_format($totalPriceAllDollars, 2) }}</strong>
                </p>
            </a>
            
            <a href="{{ url('/admin/purchase_orders?status=completed&creation_date=' . $dateRange) }}" class="block bg-green-200 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-4 hover:bg-green-300">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Ordenes Completadas</h3>
                <p class="mt-1 text-3xl leading-9 font-semibold text-gray-900">{{ $completedOrders->count() }}</p>
                
                <p class="mt-4 text-xl leading-7 font-medium text-gray-900">
                    <span class="text-base">Total en Pesos:</span> ${{ number_format($totalPriceCompletedMonth) }}
                </p>
                
                <p class="mt-1 text-xl leading-7 font-medium text-gray-900">
                    <strong><span class="text-base">Total en USD:</span> &dollar;{{ number_format($totalPriceCompletedDollars, 2) }}</strong>
                </p>
            </a>
            
            <a href="{{ url('/admin/purchase_orders?status=pending&creation_date=' . $dateRange) }}" class="block bg-yellow-200 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-4 hover:bg-yellow-300">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Ordenes Pendientes</h3>
                <p class="mt-1 text-3xl leading-9 font-semibold text-gray-900">{{ $pendingOrders->count() }}</p>
                
                <p class="mt-4 text-xl leading-7 font-medium text-gray-900">
                    <span class="text-base">Total en Pesos:</span> ${{ number_format($totalPricePendingMonth) }}
                </p>
                
                <p class="mt-1 text-xl leading-7 font-medium text-gray-900">
                    <strong><span class="text-base">Total en USD:</span> &dollar;{{ number_format($totalPricePendingDollars, 2) }}</strong>
                </p>
            </a>
            
            <a href="{{ url('/admin/purchase_orders?status=processing&creation_date=' . $dateRange) }}" class="block bg-orange-200 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-4 hover:bg-orange-300">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Ordenes en Proceso</h3>
                <p class="mt-1 text-3xl leading-9 font-semibold text-gray-900">{{ $processingOrders->count() }}</p>
                
                <p class="mt-4 text-xl leading-7 font-medium text-gray-900">
                    <span class="text-base">Total en Pesos:</span> ${{ number_format($totalPriceProcessingMonth) }}
                </p>
                
                <p class="mt-1 text-xl leading-7 font-medium text-gray-900">
                   <strong> <span class="text-base">Total en USD:</span> &dollar;{{ number_format($totalPriceProcessingDollars, 2) }}</strong>
                </p>
            </a>
            
                
                
            </div>

            <div class="mb-4">
                <form method="GET" action="{{ url('/admin') }}">
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Filtro por tipo -->
                        <select name="filter_type" id="filter_type" onchange="this.form.submit()" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="" disabled selected>Filtrar por</option>
                            <option value="date" {{ request('filter_type') == 'date' ? 'selected' : '' }}>Fecha</option>
                            <option value="total" {{ request('filter_type') == 'total' ? 'selected' : '' }}>Total</option>
                        </select>
            
                        <!-- Dirección de orden -->
                        <select name="sort_direction" id="sort_direction" onchange="this.form.submit()" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Descendente</option>
                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                        </select>
            
                        <!-- Campo de fecha y botón de filtrar -->
                        <div class="flex items-center">
                            <input type="text" id="creation_date" name="creation_date" value="{{ request('creation_date') }}" class="daterangepicker mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <button type="submit" class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filtrar por fecha
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Latest Orders Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Últimas Ordenes Creadas</h3>
                <div class="relative overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                           
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                             </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($latestOrders as $purchaseOrder)
                            
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ url('/admin/purchase_orders/' . $purchaseOrder->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                        Ver
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $purchaseOrder->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $purchaseOrder->client->client_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $purchaseOrder->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($purchaseOrder->status) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @php
                                        $total = $purchaseOrder->products->sum(function($product) use($purchaseOrder) {
                                            return ($product->price * $product->pivot->quantity) / $purchaseOrder->trm;
                                        });
                                    @endphp
                                    {{ number_format($total, 2, ',', '.') }}
                                </td>
                               
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
               
                <div class="flex justify-end">
                    <a href="/admin/purchase_orders" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Ver Todas las Ordenes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
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
</x-admin.layout>


