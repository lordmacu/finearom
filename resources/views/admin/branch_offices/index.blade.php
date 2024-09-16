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
                                <button type="button" onclick="confirmDelete('{{ route('admin.branch_offices.destroy', [$client->id, $branchOffice->id]) }}')" class="btn btn-danger">
                                    {{ __('Eliminar') }}
                                </button>
                                
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
                            ¿Estás seguro de que deseas eliminar esta sucursal? Esta acción no se puede deshacer.
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

</x-admin.wrapper>


<script>
    function confirmDelete(actionUrl) {
        const form = document.getElementById('deleteForm');
        form.action = actionUrl;
        document.getElementById('deleteConfirmationModal').classList.remove('hidden');
    }

    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteConfirmationModal').classList.add('hidden');
    });
</script>
