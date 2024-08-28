<x-app-layout>
    <x-slot name="header">
        <p class="views-text"
            style="text-transform: capitalize;color: #565968;display:flex;column-gap: 10px;letter-spacing: 0px;">
            @php
                $urlParts = explode('.', $currentRouteName);
            @endphp
            @foreach($urlParts as $part)
                <svg class="icon-arrow" style="width: 15px;" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg> {{ $part }}
            @endforeach
        </p>

        <div class="flex" style="justify-content: space-between;">
            <h2 class="py-4" style=" font-size: 29px; font-weight: bolder;">
                {{ __('Historial de Órdenes de Compra') }}
            </h2>
            <div class="flex justify-end mb-4">
                <a href="{{ route('purchase_order.dashboard') }}" class="btn btn-primary">
                    {{ __('Agregar Nueva Orden de Compra') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="contain">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="tabla-border">
                <div class="flex justify-between bg-base-100" style=" padding: 25px; border-radius: 13px;">

                    <div class="flex items-center">
                        <span class="mr-2">Mostrar:</span>
                        <select class="select select-bordered" onchange="location = this.value;">
                            @foreach([5, 10, 25, 50, 100] as $size)
                                <option
                                    value="{{ route('purchase_order.history', array_merge(request()->query(), ['paginate' => $size])) }}"
                                    {{ request('paginate') == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center">
                        <form method="GET" action="{{ route('purchase_order.history') }}">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar..."
                                class="input input-bordered">

                        </form>
                    </div>
                </div>
                <x-admin.grid.table>
                    <x-slot name="head">
                        <tr style="background-color: #2563eb0a;">
                            <x-admin.grid.th>{{ __('Consecutivo') }}</x-admin.grid.th>
                            <x-admin.grid.th>{{ __('Cliente') }}</x-admin.grid.th>
                            <x-admin.grid.th>{{ __('Fecha de Creación') }}</x-admin.grid.th>
                            <x-admin.grid.th>{{ __('Fecha de Entrega') }}</x-admin.grid.th>
                            <x-admin.grid.th>{{ __('Dirección') }}</x-admin.grid.th>
                            <x-admin.grid.th>{{ __('Acciones') }}</x-admin.grid.th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($purchaseOrders as $purchaseOrder)
                            <tr class="bg-base-100">
                                <td>{{ $purchaseOrder->order_consecutive }}</td>
                                <td>{{ $purchaseOrder->client->client_name }}</td>
                                <td>{{ $purchaseOrder->order_creation_date }}</td>
                                <td>{{ $purchaseOrder->required_delivery_date }}</td>
                                <td>{{ $purchaseOrder->delivery_address }}</td>
                                <td class="icon-btn" style=" display: flex; column-gap: 18px; ">
                                    <a class="pt-2" href="{{ route('purchase_order.show', $purchaseOrder->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                            class="text-blue-500">
                                            <path fill="currentColor"
                                                d="M12 4.5C7 4.5 2.73 8 1 12c1.73 4 6 7.5 11 7.5s9.27-3.5 11-7.5c-1.73-4-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('purchase_order.edit', $purchaseOrder->id) }}">
                                        <svg style="color: #e3ba41;" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z"></path>
                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                            <path d="M9 15h3l8.5 -8.5a1.5 1.7 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                            <line x1="16" y1="5" x2="19" y2="8"></line>
                                        </svg>
                                    </a>
                                    <form action="{{ route('purchase_order.destroy', $purchaseOrder->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <svg style="width: 24px; margin-top: 3px; color: #bd7676;"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="w-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @empty($purchaseOrders)
                            <tr>
                                <td colspan="6">
                                    <div class="flex flex-col justify-center items-center py-4 text-lg">
                                        {{ __('No Result Found') }}
                                    </div>
                                </td>
                            </tr>
                        @endempty
                    </x-slot>
                </x-admin.grid.table>
                <div class="mt-4">
                    {{ $purchaseOrders->appends(['search' => $search, 'paginate' => $paginate])->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .tabla-border {
        border: #ebebeb solid 1px;
        border-radius: 8px;
        box-shadow: 0px 0px 14px 0px #8080802b;
        background: white;
        padding: 13px;
    }
</style>