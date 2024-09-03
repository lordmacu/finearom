<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Productos') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{ route('admin.product.index') }}" title="{{ __('Editar Producto') }}">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg>
        </x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="w-full py-2 overflow-hidden cont-form">
    <form method="POST" action="{{ route('admin.product.update', $product->id) }}">
    @csrf
    @method('PUT')

    <div class="py-2">
        <x-admin.form.label for="code" class="{{ $errors->has('code') ? 'text-red-400' : '' }}">{{ __('Código') }}</x-admin.form.label>
        <x-admin.form.input id="code" class="{{ $errors->has('code') ? 'border-red-400' : '' }}" type="text" name="code" value="{{ old('code', $product->code) }}" />
    </div>

    <div class="py-2">
        <x-admin.form.label for="product_name" class="{{ $errors->has('product_name') ? 'text-red-400' : '' }}">{{ __('Nombre del Producto') }}</x-admin.form.label>
        <x-admin.form.input id="product_name" class="{{ $errors->has('product_name') ? 'border-red-400' : '' }}" type="text" name="product_name" value="{{ old('product_name', $product->product_name) }}" />
    </div>

    <div class="py-2 grid grid-cols-2 gap-4">
        <div>
            <x-admin.form.label for="price" class="{{ $errors->has('price') ? 'text-red-400' : '' }}">{{ __('Precio') }}</x-admin.form.label>
            <x-admin.form.input id="price" class="{{ $errors->has('price') ? 'border-red-400' : '' }}" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" />
        </div>
        <div>
            <x-admin.form.label for="client_id" class="{{ $errors->has('client_id') ? 'text-red-400' : '' }}">{{ __('Cliente') }}</x-admin.form.label>
            <select id="client_id" name="client_id" class="input input-bordered w-full {{ $errors->has('client_id') ? 'border-red-400' : '' }}">
                <option value="">{{ __('Seleccionar un cliente') }}</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @if(old('client_id', $product->client_id) == $client->id) selected @endif>{{ $client->client_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <x-admin.form.button>{{ __('Actualizar') }}</x-admin.form.button>
    </div>
</form>

    </div>
</x-admin.wrapper>
