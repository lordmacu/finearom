<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Menus') }}
    </x-slot>

    @can('menu create')
    <x-admin.add-link href="{{ route('admin.menu.create') }}">
        {{ __('Add Menu') }}
    </x-admin.add-link>
    @endcan

    <div class="py-2 table-permissins">
        <div class="min-w-full  border-base-200 shadow overflow-x-auto">
            <x-admin.grid.search action="{{ route('admin.menu.index') }}" />
            <x-admin.grid.table>
                <x-slot name="head">
                    <tr class="bg-base-200">
                        <x-admin.grid.th>
                            @include('admin.includes.sort-link', ['label' => 'Name', 'attribute' => 'name'])
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            {{ __('Description') }}
                        </x-admin.grid.th>
                        <x-admin.grid.th>
                            {{ __('Machine name') }}
                        </x-admin.grid.th>
                        @canany(['menu edit', 'menu delete'])
                        <x-admin.grid.th>
                            {{ __('Actions') }}
                        </x-admin.grid.th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="body">
                @foreach($menus as $menu)
                    <tr>
                        <x-admin.grid.td>
                            {{ $menu->name }}
                        </x-admin.grid.td>
                        <x-admin.grid.td>
                            {{ $menu->description }}
                        </x-admin.grid.td>
                        <x-admin.grid.td>
                            {{ $menu->machine_name }}
                        </x-admin.grid.td>
                        @canany(['menu edit', 'menu delete', 'menu.item list'])
                        <x-admin.grid.td>
                            <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST">
                                <div>
                                    @can('menu.item list')
                                    <a href="{{route('admin.menu.item.index', $menu->id)}}" class="btn btn-square btn-ghost">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                                        </svg>
                                    </a>
                                    @endcan

                                    @can('menu edit')
                                    <a href="{{route('admin.menu.edit', $menu->id)}}" class="btn btn-square btn-ghost">
                                    <svg style="color: #e3ba41;" class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z"></path>
                                        <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                        <path d="M9 15h3l8.5 -8.5a1.5 1.7 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                        <line x1="16" y1="5" x2="19" y2="8"></line>
                                    </svg>
                                    </a>
                                    @endcan

                                    @can('menu delete')
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-square btn-ghost" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')">
                                        <svg style="
                                                            width: 24px;
                                                            margin-top: 3px;
                                                            color: #bd7676;
                                                        " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0">
                                            </path>
                                        </svg>
                                    </button>
                                    @endcan
                                </div>
                            </form>
                        </x-admin.grid.td>
                        @endcanany
                    </tr>
                    @endforeach
                    @if($menus->isEmpty())
                        <tr>
                            <td colspan="2">
                                <div class="flex flex-col justify-center items-center py-4 text-lg">
                                    {{ __('No Result Found') }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </x-slot>
            </x-admin.grid.table>
        </div>
        <div class="py-8">
            {{ $menus->appends(request()->query())->links() }}
        </div>
    </div>
</x-admin.wrapper>
