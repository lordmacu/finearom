<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Clientes') }}
    </x-slot>

    <div class="flex justify-between items-center py-2">
        <div>
            <form method="GET" action="{{ route('admin.client.index') }}" class="flex items-center">
                <x-admin.form.input id="search" name="search" placeholder="Buscar cliente o NIT..." class="mr-2" value="{{ request('search') }}" />
                <x-admin.form.button>{{ __('Buscar') }}</x-admin.form.button>
                @if(request('search'))
                    <a href="{{ route('admin.client.index') }}" class="ml-2 btn btn-secondary">{{ __('Resetear') }}</a>
                @endif
            </form>
        </div>
        @can('client create')
            <x-admin.add-link href="{{ route('admin.client.create') }}">
                {{ __('Agregar Cliente') }}
            </x-admin.add-link>
        @endcan
    </div>

    <div class="py-2">
        <div class="min-w-full border-base-200 shadow overflow-x-auto">
            <x-admin.grid.table>
                <x-slot name="head">
                    <tr class="bg-base-200">
                        @php
                            $sortBy = request()->get('sort_by');
                            $sortOrder = request()->get('sort_order', 'asc');
                        @endphp
                        <x-admin.grid.th>
                            <a href="{{ route('admin.client.index', array_merge(request()->all(), ['sort_by' => 'client_name', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'client_name' ? 'desc' : 'asc'])) }}">
                                {{ __('Nombre del Cliente') }}
                                @if ($sortBy === 'client_name')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.client.index', array_merge(request()->all(), ['sort_by' => 'nit', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'nit' ? 'desc' : 'asc'])) }}">
                                {{ __('NIT') }}
                                @if ($sortBy === 'nit')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            <a href="{{ route('admin.client.index', array_merge(request()->all(), ['sort_by' => 'email', 'sort_order' => $sortOrder === 'asc' && $sortBy === 'email' ? 'desc' : 'asc'])) }}">
                                {{ __('Correo Electrónico') }}
                                @if ($sortBy === 'email')
                                    @if ($sortOrder === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </x-admin.grid.th>
                        @canany(['client edit', 'client delete'])
                            <x-admin.grid.th>{{ __('Acciones') }}</x-admin.grid.th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->client_name }}</td>
                            <td>{{ $client->nit }}</td>
                            <td>{{ $client->email }}</td>
                            @canany(['client edit', 'client delete'])
                                <td class="content-action">
                                    <a href="{{ route('admin.client.edit', $client->id) }}">
                                        <svg style="color: #e3ba41;" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" />
                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                            <path d="M9 15h3l8.5 -8.5a1.5 1.7 0 0 0 -3 -3l-8.5 8.5v3" />
                                            <line x1="16" y1="5" x2="19" y2="8" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.branch_offices.index', $client->id) }}">
                                        <svg style="color: #4caf50;" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" />
                                            <circle cx="12" cy="12" r="2" />
                                            <path d="M22 12a10 10 0 1 0 -20 0a10 10 0 0 0 20 0z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.client.destroy', $client->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar este cliente?');">
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
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
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
            {{ $clients->appends(request()->except('page'))->links() }}
        </div>
    </div>
</x-admin.wrapper>
