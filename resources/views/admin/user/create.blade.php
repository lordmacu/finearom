<x-admin.wrapper>
    <x-slot name="title">
            {{ __('Users') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{route('admin.user.index')}}" title="{{ __('Create user') }}"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg></x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="w-full py-2 overflow-hidden">

    <form method="POST" action="{{ route('admin.purchase_orders.update', $purchaseOrder->id) }}">
    @csrf
    @method('PUT')

    <div class="py-2">
        <x-admin.form.label for="client_id" class="{{ $errors->has('client_id') ? 'text-red-400' : '' }}">{{ __('Client') }}</x-admin.form.label>
        <select id="client_id" name="client_id" class="input input-bordered w-full {{ $errors->has('client_id') ? 'border-red-400' : '' }}">
            <option value="">{{ __('Select a client') }}</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @if(old('client_id', $purchaseOrder->client_id) == $client->id) selected @endif>{{ $client->client_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="py-2 grid grid-cols-2 gap-4">
        <div>
            <x-admin.form.label for="order_creation_date" class="{{ $errors->has('order_creation_date') ? 'text-red-400' : '' }}">{{ __('Order Creation Date') }}</x-admin.form.label>
            <x-admin.form.input id="order_creation_date" class="{{ $errors->has('order_creation_date') ? 'border-red-400' : '' }}" type="date" name="order_creation_date" value="{{ old('order_creation_date', $purchaseOrder->order_creation_date) }}" />
        </div>
        <div>
            <x-admin.form.label for="required_delivery_date" class="{{ $errors->has('required_delivery_date') ? 'text-red-400' : '' }}">{{ __('Required Delivery Date') }}</x-admin.form.label>
            <x-admin.form.input id="required_delivery_date" class="{{ $errors->has('required_delivery_date') ? 'border-red-400' : '' }}" type="date" name="required_delivery_date" value="{{ old('required_delivery_date', $purchaseOrder->required_delivery_date) }}" />
        </div>
    </div>

    <div class="py-2 grid grid-cols-2 gap-4">
        <div>
            <x-admin.form.label for="order_consecutive" class="{{ $errors->has('order_consecutive') ? 'text-red-400' : '' }}">{{ __('Order Consecutive') }}</x-admin.form.label>
            <x-admin.form.input id="order_consecutive" class="{{ $errors->has('order_consecutive') ? 'border-red-400' : '' }}" type="text" name="order_consecutive" value="{{ old('order_consecutive', $purchaseOrder->order_consecutive) }}" />
        </div>
        <div>
            <x-admin.form.label for="delivery_address" class="{{ $errors->has('delivery_address') ? 'text-red-400' : '' }}">{{ __('Delivery Address') }}</x-admin.form.label>
            <x-admin.form.input id="delivery_address" class="{{ $errors->has('delivery_address') ? 'border-red-400' : '' }}" type="text" name="delivery_address" value="{{ old('delivery_address', $purchaseOrder->delivery_address) }}" />
        </div>
    </div>

    <div class="py-2">
        <x-admin.form.label for="observations" class="{{ $errors->has('observations') ? 'text-red-400' : '' }}">{{ __('Observations') }}</x-admin.form.label>
        <textarea id="observations" class="{{ $errors->has('observations') ? 'border-red-400' : '' }} input input-bordered w-full" name="observations">{{ old('observations', $purchaseOrder->observations) }}</textarea>
    </div>

    <div class="py-4">
        <h3 class="text-lg font-medium">{{ __('Products') }}</h3>
        <div id="products-container" class="border rounded p-4 mt-2">
            @foreach($purchaseOrder->products as $index => $product)
            <div class="grid grid-cols-3 gap-4 mb-4 product-row" data-index="{{ $index }}">
                <div>
                    <x-admin.form.label for="products[{{ $index }}][product_id]" class="{{ $errors->has('products.' . $index . '.product_id') ? 'text-red-400' : '' }}">{{ __('Product') }}</x-admin.form.label>
                    <select id="products[{{ $index }}][product_id]" name="products[{{ $index }}][product_id]" class="input input-bordered w-full {{ $errors->has('products.' . $index . '.product_id') ? 'border-red-400' : '' }}">
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" @if(old('products.' . $index . '.product_id', $product->pivot->product_id) == $prod->id) selected @endif>{{ $prod->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-admin.form.label for="products[{{ $index }}][quantity]" class="{{ $errors->has('products.' . $index . '.quantity') ? 'text-red-400' : '' }}">{{ __('Quantity') }}</x-admin.form.label>
                    <input id="products[{{ $index }}][quantity]" class="{{ $errors->has('products.' . $index . '.quantity') ? 'border-red-400' : '' }} input input-bordered" type="number" name="products[{{ $index }}][quantity]" value="{{ old('products.' . $index . '.quantity', $product->pivot->quantity) }}" min="1" />
                </div>
                <div>
                    <button type="button" class="btn btn-danger remove-product">{{ __('Remove') }}</button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="py-2">
            <button type="button" id="add-product" class="btn btn-secondary">{{ __('Add Product') }}</button>
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <x-admin.form.button>{{ __('Update') }}</x-admin.form.button>
    </div>
</form>

    </div>
</x-admin.wrapper>
