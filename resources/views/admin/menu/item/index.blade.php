<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Menus') }}
    </x-slot>
    
    <div class="d-print-none with-border">
        <x-admin.breadcrumb href="{{route('admin.menu.index')}}" title="{{ __('Menu Items') }}"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
</svg></x-admin.breadcrumb> 
    </div>
    <div class="w-full py-2">
        <div class="min-w-full border-base-200 shadow">
            <table class="table">
                <tbody>
                    <tr>
                        <td>{{ __('Name') }}</td>
                        <td>{{$menu->name}}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Machine name') }}</td>
                        <td>{{$menu->machine_name}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @can('menu create')
    <x-admin.add-link href="{{ route('admin.menu.item.create', $menu->id) }}">
        {{ __('Add Menu Item') }}
    </x-admin.add-link>
    @endcan
    
    <div class="py-2">
        <div class="min-w-full  border-base-200 shadow overflow-x-auto">
            <x-admin.grid.table>
                <x-slot name="head">
                    <tr class="bg-base-200">
                        <x-admin.grid.th>
                        {{ __('Name') }}
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            {{ __('Enabled') }}
                        </x-admin.grid.th>
                        @canany(['menu.item edit', 'menu.item delete'])
                        <x-admin.grid.th>
                            {{ __('Actions') }}
                        </x-admin.grid.th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach($items as $item)
                        <x-admin.grid.index-menu-item :item="$item" :menu="$menu" level="0"/>
                    @endforeach
                    @empty($items)
                        <tr>
                            <td colspan="2">
                                <div class="flex flex-col justify-center items-center py-4 text-lg">
                                    {{ __('No Result Found') }}
                                </div>
                            </td>
                        </tr>
                    @endempty
                </x-slot>
            </x-admin.grid.table>
        </div>
    </div>
</x-admin.wrapper>
