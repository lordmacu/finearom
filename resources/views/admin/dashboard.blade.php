

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
                $dateRange = $startOfMonth . ' - ' . $endOfMonth;
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
</x-admin.layout>


