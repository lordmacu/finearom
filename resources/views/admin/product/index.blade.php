<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Productos') }}
    </x-slot>

    @can('product create')
        <x-admin.add-link href="{{ route('admin.product.create') }}">
            {{ __('Add Product') }}
        </x-admin.add-link>
    @endcan

    <div class="py-2">
        <form method="GET" action="{{ route('admin.product.index') }}">
            <div class="flex items-center space-x-2">
                <input type="text" name="search" placeholder="{{ __('Search by code or product name') }}"
                       class="form-input" value="{{ request()->input('search') }}">
                <select name="client_id" class="form-select">
                    <option value="">{{ __('Select Client') }}</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request()->input('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->client_name }}
                        </option>
                    @endforeach
                </select>
                <select name="product_id" class="form-select">
                    <option value="">{{ __('Select Product') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request()->input('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
            </div>
        </form>

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
</x-admin.wrapper>
