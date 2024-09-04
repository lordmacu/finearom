<x-admin.wrapper>
    <x-slot name="title">
            {{ __('Elementos del Menú') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{route('admin.menu.item.index', $menu->id)}}" title="{{ __('Actualizar Elemento del Menú') }}">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg>
        </x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="w-full py-2 overflow-hidden cont-form">

        <form method="POST" action="{{ route('admin.menu.item.update', ['menu' => $menu->id, 'item' => $item->id]) }}">
        @csrf
        @method('PUT')

            <div class="py-2">
                <x-admin.form.label for="name" class="{{$errors->has('name') ? 'text-red-400' : ''}}">{{ __('Nombre') }}</x-admin.form.label>

                <x-admin.form.input id="name" class="{{$errors->has('name') ? 'border-red-400' : ''}}"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $item->name) }}"
                                    />
            </div>

            <div class="py-2">
                <x-admin.form.label for="uri" class="{{$errors->has('uri') ? 'text-red-400' : ''}}">{{ __('Enlace') }}</x-admin.form.label>

                <x-admin.form.input id="uri" class="{{$errors->has('uri') ? 'border-red-400' : ''}}"
                                    type="text"
                                    name="uri"
                                    value="{{ old('uri', $item->uri) }}"
                                    />
                <div class="item-list">
                    También puedes ingresar una ruta interna como <em class="placeholder">/home</em> o una URL externa como <em class="placeholder">http://example.com</em>. 
                    Añade el prefijo <em class="placeholder">&lt;admin&gt;</em> para enlazar una página de administración. Ingresa <em class="placeholder">&lt;nolink&gt;</em> para mostrar solo el texto del enlace.
                </div>
            </div>

            <div class="py-2">
                <x-admin.form.label for="description" class="{{$errors->has('description') ? 'text-red-400' : ''}}">{{ __('Descripción') }}</x-admin.form.label>

                <x-admin.form.input id="description" class="{{$errors->has('description') ? 'border-red-400' : ''}}"
                                    type="text"
                                    name="description"
                                    value="{{ old('description', $item->description) }}"
                                    />
            </div>

            <div class="p-2">
                <label for="enabled" class="inline-flex items-center">
                    <input id="enabled" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="enabled" value="1" {{ old('enabled', $item->enabled) ? 'checked="checked"' : '' }}>
                    <span class="ml-2">{{ __('Habilitado') }}</span>
                </label>
            </div>

            <div class="py">
                <x-admin.form.label for="parent_id" class="{{$errors->has('parent_id') ? 'text-red-400' : ''}}">{{ __('Elemento Padre') }}</x-admin.form.label>

                <select name="parent_id" class="input input-bordered w-full">
                    <option value=''>-RAÍZ-</option>
                    @foreach ($item_options as $key => $name)
                    <option value="{{ $key }}" @selected(old('parent_id', $item['parent_id']) == $key)>
                        {!! $name !!}
                    </option>
                    @endforeach
                </select>

                <div>
                    La profundidad máxima para un enlace y todos sus hijos es fija. Algunos enlaces de menú pueden no estar disponibles como padres si seleccionarlos excede este límite.
                </div>
            </div>

            <div class="py-2 w-40">
                <x-admin.form.label for="weight" class="{{$errors->has('weight') ? 'text-red-400' : ''}}">{{ __('Peso') }}</x-admin.form.label>

                <x-admin.form.input id="weight" class="{{$errors->has('weight') ? 'border-red-400' : ''}}"
                                    type="number"
                                    name="weight"
                                    value="{{ old('weight', $item->weight) }}"
                                    />
            </div>

            <div class="py-2">
                <x-admin.form.label for="icon" class="{{$errors->has('icon') ? 'text-red-400' : ''}}">{{ __('Ícono') }}</x-admin.form.label>

                <textarea name="icon" id="icon" class="{{$errors->has('icon') ? 'border-red-400' : ''}} textarea input-bordered w-full">{{ old('icon', $item->icon) }}</textarea>
            </div>

            <div class="py-2">
                <h3 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight py-4 block sm:inline-block flex">Roles</h3>
                <div class="grid grid-cols-4 gap-4">
                    @forelse ($roles as $role)
                        <div class="col-span-4 sm:col-span-2 md:col-span-1">
                            <label class="form-check-label">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->id, $itemHasRoles) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                {{ $role->name }}
                            </label>
                        </div>
                    @empty
                        ----
                    @endforelse
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <x-admin.form.button>{{ __('Actualizar') }}</x-admin.form.button>
            </div>
        </form>
    </div>
</x-admin.wrapper>
