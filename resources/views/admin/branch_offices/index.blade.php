<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Sucursales para el Cliente: ') . $client->client_name }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{ route('admin.client.edit', $client->id) }}" title="{{ __('Clientes') }}"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg></x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>

    <div class="w-full py-2 overflow-hidden">
        <x-admin.add-link href="{{ route('admin.branch_offices.create', $client->id) }}">
            {{ __('Agregar Sucursal') }}
        </x-admin.add-link>
        <div class="min-w-full border-base-200 shadow overflow-x-auto">
            <x-admin.grid.table>
                <x-slot name="head">
                    <tr class="bg-base-200">
                        <x-admin.grid.th>{{ __('Nombre') }}</x-admin.grid.th>
                        <x-admin.grid.th>{{ __('NIT') }}</x-admin.grid.th>
                        <x-admin.grid.th>{{ __('Contacto') }}</x-admin.grid.th>
                        <x-admin.grid.th>{{ __('Ciudad') }}</x-admin.grid.th>
                        @canany(['branch_office edit', 'branch_office delete'])
                        <x-admin.grid.th>{{ __('Acciones') }}</x-admin.grid.th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach($branchOffices as $branchOffice)
                        <tr>
                            <td>{{ $branchOffice->name }}</td>
                            <td>{{ $branchOffice->nit }}</td>
                            <td>{{ $branchOffice->contact }}</td>
                            <td>{{ $branchOffice->delivery_city }}</td>
                            @canany(['branch_office edit', 'branch_office delete'])
                            <td>
                                <a href="{{ route('admin.branch_offices.edit', [$client->id, $branchOffice->id]) }}" class="btn btn-primary mr-4">{{ __('Editar') }}</a>
                                <form action="{{ route('admin.branch_offices.destroy', [$client->id, $branchOffice->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{ __('Eliminar') }}</button>
                                </form>
                            </td>
                            @endcanany
                        </tr>
                    @endforeach
                    @empty($branchOffices)
                        <tr>
                            <td colspan="5">
                                <div class="flex flex-col justify-center items-center py-4 text-lg">
                                    {{ __('No se encontraron resultados') }}
                                </div>
                            </td>
                        </tr>
                    @endempty
                </x-slot>
            </x-admin.grid.table>
        </div>
    </div>
</x-admin.wrapper>
