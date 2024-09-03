<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Categorías') }}
    </x-slot>
    
    <div class="d-print-none with-border">
        <x-admin.breadcrumb href="{{route('admin.category.type.index')}}" title="{{ __('Categorías') }}">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
            </svg>
        </x-admin.breadcrumb> 
    </div>
    <div class="w-full py-2">
        <div class="min-w-full border-base-200 shadow relative overflow-x-auto">
            <table class="table">
                <tbody>
                    <tr>
                        <td>{{ __('Nombre') }}</td>
                        <td>{{$type->name}}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Nombre de máquina') }}</td>
                        <td>{{$type->machine_name}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @can('category create')
    <x-admin.add-link href="{{ route('admin.category.type.item.create', $type->id) }}">
        {{ __('Agregar Categoría') }}
    </x-admin.add-link>
    @endcan
    
    <div class="py-2 relative overflow-x-auto">
        <div class="min-w-full border-base-200 shadow overflow-x-auto">
            <x-admin.grid.table>
                <x-slot name="head">
                    <tr class="bg-base-200">
                        <x-admin.grid.th>
                        {{ __('Nombre') }}
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                        {{ __('Slug') }}
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            {{ __('Habilitado') }}
                        </x-admin.grid.th>
                        @canany(['category edit', 'category delete'])
                        <x-admin.grid.th>
                            {{ __('Acciones') }}
                        </x-admin.grid.th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach($items as $item)
                        <x-admin.grid.index-category-item :item="$item" :type="$type" level="0"/>
                    @endforeach
                    @empty($items)
                        <tr>
                            <td colspan="2">
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
