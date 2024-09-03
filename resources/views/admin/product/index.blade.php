<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Productos') }}
    </x-slot>
    <div class="conten-btn">
        <a href="/admin/products/export" title="Exportar Producto"><svg class="h-8 w-8 text-green-600"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />  <polyline points="15 3 21 3 21 9" />  <line x1="10" y1="14" x2="21" y2="3" /></svg></a>

    </div>

    @can('product create')
        <x-admin.add-link href="{{ route('admin.product.create') }}">
            {{ __('Add Product') }}
        </x-admin.add-link>
    @endcan

    <div class="flex justify-between items-center py-2">
        <div>
            <form method="GET" action="{{ route('admin.product.index') }}">
                <div class="">
                    <input type="text" name="search" placeholder="{{ __('Buscar por codigo o nombre de producto') }}"
                           class="form-input" value="{{ request()->input('search') }}">
                    <select name="client_id" class="form-select">
                        <option value="">{{ __('Select Client') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request()->input('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->client_name }}
                            </option>
                        @endforeach
                    </select>
                    <select id="product-select" name="product_id" class="form-select" style="width: 100%">
                        <option value="">{{ __('Select Product') }}</option>
                    </select>
                    <button type="submit" class="btn btn-primary">{{ __('Buscar') }}</button>
                    <a href="/admin/product" class="btn btn-secondary">{{ __('Reset') }}</a>

                </div>
            </form>
        </div>

    </div>


    
    <div class="py-2">
  

        <div class="min-w-full border-base-200 shadow overflow-x-auto mt-4">
            <x-admin.grid.table>
                <x-slot name="head">
                    <tr class="bg-base-200">
                        @php
                            $sortBy = request()->get('sort_by');
                            $sortOrder = request()->get('sort_order', 'asc');
                        @endphp
                        <x-admin.grid.th>
                            <a href="{{ route('admin.product.index', array_merge(request()->all(), ['sort_by' => 'code', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'code' ? 'desc' : 'asc'])) }}">
                                {{ __('Code') }}
                                @if ($sortBy === 'code')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.product.index', array_merge(request()->all(), ['sort_by' => 'product_name', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'product_name' ? 'desc' : 'asc'])) }}">
                                {{ __('Product Name') }}
                                @if ($sortBy === 'product_name')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.product.index', array_merge(request()->all(), ['sort_by' => 'price', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'price' ? 'desc' : 'asc'])) }}">
                                {{ __('Price') }}
                                @if ($sortBy === 'price')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.product.index', array_merge(request()->all(), ['sort_by' => 'client_id', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'client_id' ? 'desc' : 'asc'])) }}">
                                {{ __('Client') }}
                                @if ($sortBy === 'client_id')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        @canany(['product edit', 'product delete'])
                            <x-admin.grid.th>{{ __('Actions') }}</x-admin.grid.th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->client->client_name }}</td>
                            @canany(['product edit', 'product delete'])
                                <td class="content-action">
                                    <a style="color: #e3ba41;" href="{{ route('admin.product.edit', $product->id) }}">
                                        <svg style="color: #e3ba41;" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24"
                                             stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z"></path>
                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                            <path d="M9 15h3l8.5 -8.5a1.5 1.7 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                            <line x1="16" y1="5" x2="19" y2="8"></line>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
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
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="flex flex-col justify-center items-center py-4 text-lg">
                                    {{ __('No Result Found') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-admin.grid.table>
        </div>
        <div class="mt-4">
            {{ $products->appends(request()->except('page'))->links() }}
        </div>
    </div>

    <script>

$(document).ready(function() {
    $('select').select2({width:200});
    

        // Inicializar Select2
        $('#product-select').select2({
            width:200,
            ajax: {
                url: '/admin/ajax/products', // URL del método AJAX en el backend
                dataType: 'json',
                delay: 250, // Retraso para esperar mientras el usuario escribe
                data: function (params) {
                    return {
                        search: params.term // Término de búsqueda
                    };
                },
                processResults: function (data) {
                    return {
                        results: data // Devuelve los resultados en el formato esperado por Select2
                    };
                },
                cache: true
            },
            placeholder: 'Search for a product', // Texto por defecto
            minimumInputLength: 1, // Número mínimo de caracteres antes de iniciar la búsqueda
        });

        var productId = new URLSearchParams(window.location.search).get('product_id');

        if (productId) {
            $.ajax({
                type: 'GET',
                url: '/admin/ajax/products', // URL del método AJAX en el backend
                data: { search: productId },
                dataType: 'json'
            }).then(function(data) {
                var product = data.find(p => p.id == productId);
                if (product) {
                    var option = new Option(product.text, product.id, true, true);
                    $('#product-select').append(option).trigger('change');
                }
            });
        }
    });


    </script>


</x-admin.wrapper>
