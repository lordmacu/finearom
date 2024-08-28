<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 m-8 pt-8 border border-gray-300 rounded-lg shadow-md mt-0">
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Comprobante de compra</h2>
                <div class="flex pb-8 space-x-5">

                    <div class="relative group">
                        <a href="{{ route('purchase_order.edit', ['id' => $purchaseOrder->id]) }}">
                            <svg class="h-8 w-8 text-gray-500" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                                <line x1="16" y1="5" x2="19" y2="8" />
                            </svg>
                            <div class="absolute bottom-0 flex flex-col items-center hidden mb-6 group-hover:flex">
                                <span
                                    class="relative z-10 p-2 text-xs leading-none text-white whitespace-no-wrap bg-black shadow-lg rounded-md">Editar</span>
                                <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                            </div>
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="{{ route('purchase_order.dashboard') }}">
                            <svg class="h-8 w-8 text-gray-500 cursor-pointer" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                <rect x="7" y="13" width="10" height="8" rx="2" />
                            </svg>
                            <div class="absolute bottom-0 flex flex-col items-center hidden mb-6 group-hover:flex">
                                <span
                                    class="relative z-10 p-2 text-xs leading-none text-white whitespace-no-wrap bg-black shadow-lg rounded-md">Imprimir</span>
                                <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                            </div>
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="{{ route('purchase_order.history') }}">
                            <svg class="h-8 w-8 text-gray-500 cursor-pointer" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <div class="absolute bottom-0 flex flex-col items-center hidden mb-6 group-hover:flex">
                                <span
                                    class="relative z-10 p-2 text-xs leading-none text-white whitespace-no-wrap bg-black shadow-lg rounded-md">Listado</span>
                                <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border border-gray-200 p-6 bg-blue-50 rounded-lg">
                <div class="bg-white rounded-lg pt-8 pb-8 mb-8 border border-gray-200 p-6">
                    @if (session('message'))
                        <div class="bg-green-100 border border-green-300 text-green-700 p-4 rounded relative" role="alert">
                            <span>{{ session('message') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 p-4">
                                <svg class="fill-current text-green-700 h-6 w-6" role="button"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 11-1.414 1.414L10 11.414l-2.934 2.934a1 1 0 11-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z" />
                                </svg>
                            </span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('purchase_order.updateClientPurchase', $purchaseOrder->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col gap-4">
                            <div class="w-full flex justify-between">


                                <div class="w-full flex justify-between">
                                    <div class="flex-1">
                                        <a href="http://127.0.0.1:8000/dashboard"
                                            class="mb-12 flex items-center text-2xl font-bold leading-none capitalize">
                                            <img class="h-10 w-auto" src="{{ asset('images/logo.png') }}" alt="Logo">
                                            <span class="ml-2">Finearom</span>
                                        </a>

                                    </div>
                                    <div class="flex-1 text-right">
                                        <div class="bg-gray-50 px-4 py-5 sm:px-6">
                                            <div class="sm:grid sm:grid-cols-2 sm:gap-4">
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500">
                                                        {{ __('Consecutivo de la Orden') }}
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
                                                        {{ $purchaseOrder->order_consecutive }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500">
                                                        {{ __('Fecha de Entrega Requerida') }}
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
                                                        {{ $purchaseOrder->required_delivery_date }}
                                                    </dd>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <div class="border border-gray-300 bg-white p-4 shadow-sm rounded-md">
                                        <div class="text-lg font-bold">{{$purchaseOrder->client->client_name}}</div>
                                        <div class="py-2">
                                            <dt class="text-sm font-medium text-gray-500">{{ __('Sucursal') }}</dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                {{ $purchaseOrder->branchOffice->name }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">
                                                {{ __('Contacto') }}
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                Contacto
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                                 <div class="w-1/2">
                                        <div
                                            class="border border-gray-300 bg-white p-4 shadow-sm rounded-md text-right">
                                            <div class="text-lg font-bold">Dirección y datos de envio:</div>
                                            <p> {{ $client->email }}</p>
                                            <p> {{ $client->address }}</p>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <div class="py-4">
                            <div id="products-container" class="rounded-lg mt-4">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Producto
                                            </th>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Precio
                                            </th>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Cantidad
                                            </th>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Total
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
                                                    <div class="text-sm text-gray-900">{{ $product->pivot->price }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $product->pivot->quantity }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $product->pivot->price * $product->pivot->quantity }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="w-full flex justify-end">
                            <div class="w-1/2 relative flex justify-end text-right">
                                <div class="bg-white rounded-lg w-3/5">
                                    <div class="text-lg font-bold">Total parcial</div>
                                    <p>Iva:</p>
                                    <p>Retefuente y ReteICA</p>
                                    <div>
                                        <span class="text-lg font-bold">Total</span>

                                    </div>
                                </div>
                                <div class="bg-white rounded-lg w-3/5">
                                    <div class="text-lg font-bold">.</div>
                                    <p>USD 8000.00</p>
                                    <p>USD 0.00</p>
                                    <div>

                                        <span class="text-lg font-bold">USD 8,450.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="py-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Observaciones') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $purchaseOrder->observations }}
                            </dd>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>