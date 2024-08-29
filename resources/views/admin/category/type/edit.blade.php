<x-admin.wrapper>
    <x-slot name="title">
            {{ __('Tipos de Categoría') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{route('admin.category.type.index')}}" title="{{ __('Actualizar Tipo de Categoría') }}"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg></x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="w-full py-2 overflow-hidden cont-form">

        <form method="POST" action="{{ route('admin.category.type.update', $type->id) }}">
        @csrf
        @method('PUT')

            <div class="py-2">
            <x-admin.form.label for="name" class="{{$errors->has('name') ? 'text-red-400' : ''}}">{{ __('Nombre') }}</x-admin.form.label>

            <x-admin.form.input id="name" class="{{$errors->has('name') ? 'border-red-400' : ''}}"
                                type="text"
                                name="name"
                                value="{{ old('name', $type->name) }}"
                                />
            </div>

            <div class="py-2">
                <x-admin.form.label for="machine_name">{{ __('Nombre de Máquina:') }} {{ $type->machine_name }}</x-admin.form.label>
            </div>

            <div class="py-2">
            <x-admin.form.label for="description" class="{{$errors->has('description') ? 'text-red-400' : ''}}">{{ __('Descripción') }}</x-admin.form.label>

            <x-admin.form.input id="description" class="{{$errors->has('description') ? 'border-red-400' : ''}}"
                                type="text"
                                name="description"
                                value="{{ old('description', $type->description) }}"
                                />
            </div>

            <div class="p-2">
                <label for="is_flat" class="inline-flex items-center">
                    <input id="is_flat" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_flat" value="1" {{ old('is_flat', $type->is_flat) ? 'checked="checked"' : '' }}>
                    <span class="ml-2">{{ __('Usar Categoría Plana') }}</span>
                </label>
            </div>

            <div class="flex justify-end mt-4">
                <x-admin.form.button>{{ __('Actualizar') }}</x-admin.form.button>
            </div>
        </form>
    </div>
</x-admin.wrapper>
