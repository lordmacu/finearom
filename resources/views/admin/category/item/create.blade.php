<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Categorías') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{route('admin.category.type.item.index', $type->id)}}" title="{{ __('Agregar Categoría') }}">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg>
        </x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="w-full py-2 overflow-hidden">

        <form method="POST" action="{{ route('admin.category.type.item.store', ['type' => $type->id]) }}">
        @csrf

            <div class="py-2">
                <x-admin.form.label for="name" class="{{$errors->has('name') ? 'text-red-400' : ''}}">{{ __('Nombre') }}</x-admin.form.label>

                <x-admin.form.input id="name" class="{{$errors->has('name') ? 'border-red-400' : ''}}"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    />
            </div>

            <div class="py-2">
                <x-admin.form.label for="slug" class="{{$errors->has('slug') ? 'text-red-400' : ''}}">{{ __('Slug') }}</x-admin.form.label>

                <x-admin.form.input id="slug" class="{{$errors->has('slug') ? 'border-red-400' : ''}}"
                                    type="text"
                                    name="slug"
                                    value="{{ old('slug') }}"
                                    />
                <div>
                    El “slug” es la versión amigable para URL del nombre. Generalmente está en minúsculas y solo contiene letras, números y guiones.
                </div>
            </div>

            <div class="py-2">
                <x-admin.form.label for="description" class="{{$errors->has('description') ? 'text-red-400' : ''}}">{{ __('Descripción') }}</x-admin.form.label>

                <x-admin.form.input id="description" class="{{$errors->has('description') ? 'border-red-400' : ''}}"
                                    type="text"
                                    name="description"
                                    value="{{ old('description') }}"
                                    />
            </div>

            <div class="p-2">
                <label for="enabled" class="inline-flex items-center">
                    <input id="enabled" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="enabled" value="1" {{ old('enabled', 1) ? 'checked="checked"' : '' }}>
                    <span class="ml-2">{{ __('Habilitado') }}</span>
                </label>
            </div>

            @if (!$type->is_flat)
            <div class="py">
                <x-admin.form.label for="parent_id" class="{{$errors->has('parent_id') ? 'text-red-400' : ''}}">{{ __('Elemento Padre') }}</x-admin.form.label>

                <select name="parent_id" class="input input-bordered w-full ">
                    <option value=''>-RAÍZ-</option>
                    @foreach ($item_options as $key => $name)
                    <option value="{{ $key }}" @selected(old('parent_id') == $key)>
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
                                    value="{{ old('weight', 0) }}"
                                    />
            </div>
            @endif

            <div class="flex justify-end mt-4">
                <x-admin.form.button>{{ __('Crear') }}</x-admin.form.button>
            </div>
        </form>
    </div>
</x-admin.wrapper>
