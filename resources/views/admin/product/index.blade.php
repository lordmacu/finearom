<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Productos') }}
    </x-slot>
    <div class="conten-btn">
        <a href="/admin/products/export" title="Exportar Producto">
            <svg class="h-8 w-8 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                <polyline points="15 3 21 3 21 9" />
                <line x1="10" y1="14" x2="21" y2="3" />
            </svg>
        </a>
    </div>

    @can('product create')
        <x-admin.add-link href="{{ route('admin.product.create') }}">
            {{ __('Añadir Producto') }}
        </x-admin.add-link>
    @endcan

    <div class="flex justify-between items-center py-2">
        <div>
            <form method="GET" action="{{ route('admin.product.index') }}">
                <div class="">
                    <input type="text" name="search" placeholder="{{ __('Buscar por código o nombre de producto') }}" class="form-input" value="{{ request()->input('search') }}">
                    <select name="client_id" class="form-select">
                        <option value="">{{ __('Seleccionar Cliente') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request()->input('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->client_name }}
                            </option>
                        @endforeach
                    </select>
                    <select id="product-select" name="product_id" class="form-select" style="width: 100%">
                        <option value="">{{ __('Seleccionar Producto') }}</option>
                    </select>
                    <button type="submit" class="btn btn-primary">{{ __('Buscar') }}</button>
                    <a href="/admin/product" class="btn btn-secondary">{{ __('Restablecer') }}</a>
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
                                {{ __('Código') }}
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
                                {{ __('Nombre del Producto') }}
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
                                {{ __('Precio') }}
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
                        @canany(['product edit', 'product delete'])
                            <x-admin.grid.th>{{ __('Acciones') }}</x-admin.grid.th>
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
                                        <svg style="color: #e3ba41;" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z"></path>
                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                            <path d="M9 15h3l8.5 -8.5a1.5 1.7 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                            <line x1="16" y1="5" x2="19" y2="8"></line>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="confirmDelete('{{ route('admin.product.destroy', $product->id) }}')" class="btn btn-danger">
                                        <svg style="width: 24px; margin-top: 3px; color: #bd7676;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0">
                                            </path>
                                        </svg>
                                    </button>
                                    
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
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
            {{ $products->appends(request()->except('page'))->links() }}
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
<div id="deleteConfirmationModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
        <!-- Fondo oscuro -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Contenido del modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Confirmación de Eliminación
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            ¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Eliminar
                    </button>
                    <button type="button" id="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


    <script>

    function confirmDelete(actionUrl) {
        const form = document.getElementById('deleteForm');
        form.action = actionUrl;
        document.getElementById('deleteConfirmationModal').classList.remove('hidden');
    }



        $(document).ready(function() {

            document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteConfirmationModal').classList.add('hidden');
    });
            $('select').select2({width:200});
            
            // Inicializar Select2
            $('#product-select').select2({
                width: 200,
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
                placeholder: 'Buscar producto', // Texto por defecto
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