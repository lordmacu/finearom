<x-app-layout>
    <x-slot name="header"></x-slot>
    <div class="contain">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 m-8 pt-8 border border-gray-300 rounded-lg shadow-md mt-0">
                <div class="flex justify-between">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar orden de compra</h2>
                    <div class="flex pb-8 space-x-5">
                        <div class="relative group">
                            <a href="{{ route('purchase_order.show', ['id' => $purchaseOrder->id]) }}">
                                <svg class="h-8 w-8 text-gray-500 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <div class="absolute bottom-0 flex flex-col items-center hidden mb-6 group-hover:flex">
                                    <span class="relative z-10 p-2 text-xs leading-none text-white whitespace-no-wrap bg-black shadow-lg rounded-md">Ver Orden</span>
                                    <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                                </div>
                            </a>
                        </div>

                        <div class="relative group">
                            <a href="{{ route('purchase_order.dashboard') }}">
                                <svg class="h-8 w-8 text-gray-500 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                    <rect x="7" y="13" width="10" height="8" rx="2" />
                                </svg>
                                <div class="absolute bottom-0 flex flex-col items-center hidden mb-6 group-hover:flex">
                                    <span class="relative z-10 p-2 text-xs leading-none text-white whitespace-no-wrap bg-black shadow-lg rounded-md">Imprimir</span>
                                    <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                                </div>
                            </a>
                        </div>
                        <div class="relative group">
                            <a href="{{ route('purchase_order.history') }}">
                                <svg class="h-8 w-8 text-gray-500 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <div class="absolute bottom-0 flex flex-col items-center hidden mb-6 group-hover:flex">
                                    <span class="relative z-10 p-2 text-xs leading-none text-white whitespace-no-wrap bg-black shadow-lg rounded-md">Listado</span>
                                    <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 p-6 bg-blue-50 rounded-lg">
                    <div class="bg-white rounded-lg pt-8 pb-8 mb-8 border border-gray-200 p-6">
                        @if (session('message'))
                            <div class="bg-green-100 border border-green-300 text-green-700 p-4 rounded relative" role="alert">
                                <span>{{ session('message') }}</span>
                                <span class="absolute top-0 bottom-0 right-0 p-4">
                                    <svg class="fill-current text-green-700 h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <title>Close</title>
                                        <path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 11-1.414 1.414L10 11.414l-2.934 2.934a1 1 0 11-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z" />
                                    </svg>
                                </span>
                            </div>
                        @endif

                        <form id="purchase-order-form" method="POST" action="{{ route('purchase_order.updateClientPurchase', $purchaseOrder->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="flex flex-col gap-4">
                                <div class="w-full flex justify-between">
                                    <div>
                                        <a href="http://127.0.0.1:8000/dashboard" class="mb-12 flex text-2xl font-bold leading-none capitalize">
                                            <img class="h-10 w-auto" src="{{ asset('images/logo.png') }}" alt="Logo">
                                            <p class="text-logo">Finearom</p>
                                        </a>
                                        <div>
                                            <label for="order_consecutive" class="{{ $errors->has('order_consecutive') ? 'text-red-400' : '' }}">
                                                {{ __('Consecutivo de la Orden') }} {{$purchaseOrder->order_consecutive}}
                                            </label>
                                           
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <label for="required_delivery_date" class="{{ $errors->has('required_delivery_date') ? 'text-red-400' : '' }}">
                                                {{ __('Fecha de Entrega Requerida') }}
                                            </label>
                                            <input id="required_delivery_date" type="date" name="required_delivery_date" value="{{ old('required_delivery_date', $purchaseOrder->required_delivery_date) }}"
                                                min="{{ now()->toDateString('Y-m-d') }}"
                                                class="border {{ $errors->has('required_delivery_date') ? 'border-red-400' : '' }} rounded px-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-1/2">
                                        <div class="border border-gray-300 bg-white p-4 shadow-sm rounded-md">
                                            <div class="text-lg font-bold">{{$purchaseOrder->client->client_name}}</div>
                                            <div class="py-2">
                                                <label for="branch_office_id" class="{{ $errors->has('branch_office_id') ? 'text-red-400' : '' }}">
                                                    {{ __('Sucursal') }}
                                                </label>
                                                <select id="branch_office_id" name="branch_office_id" class="w-full border {{ $errors->has('branch_office_id') ? 'border-red-400' : 'border-gray-300' }} rounded">
                                                    <option value="">{{ __('Selecciona una sucursal') }}</option>
                                                    @foreach($branchOffices as $branchOffice)
                                                    <option   data-phone="{{$branchOffice->phone}}"  data-contact="{{$branchOffice->contact}}"  data-deliverycity="{{$branchOffice->delivery_city}}" data-deliveryaddress="{{$branchOffice->delivery_address}}"  value="{{ $branchOffice->id }}" @if(old('branch_office_id', $purchaseOrder->branch_office_id) == $branchOffice->id) selected @endif>
                                                        {{ $branchOffice->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="delivery_address" class="{{ $errors->has('delivery_address') ? 'text-red-400' : '' }}">
                                                    {{ __('Dirección de Entrega') }}
                                                </label>
                                                <input id="delivery_address" type="text" name="delivery_address" value="{{ old('delivery_address', $purchaseOrder->delivery_address) }}"
                                                    class="w-full border {{ $errors->has('delivery_address') ? 'border-red-400' : 'border-gray-300' }} rounded" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-1/2">
                                        <div class="border border-gray-300 bg-white p-4 shadow-sm rounded-md">
                                            <div class="text-lg font-bold">Dirección y datos de envio:</div>
        
        
                                            <p id="deliveryaddress"  class="flex justify-between"><strong class="title text-left">Dirección:</strong> <span class="value text-right">{{$purchaseOrder->delivery_address}}</span></p>
                                            <p id="deliveryCity"  class="flex justify-between"><strong class="title text-left">Ciudad:</strong> <span class="value text-right">{{$purchaseOrder->branchOffice->delivery_city}}</span></p>
                                            <p id="contact"  class="flex justify-between"><strong class="title text-left">Contacto:</strong> <span class="value text-right">{{$purchaseOrder->branchOffice->contact}}</span></p>
                                            <p id="phone"  class="flex justify-between"><strong class="title text-left">Telefono:</strong> <span class="value text-right">{{$purchaseOrder->branchOffice->phone}}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="py-4">
                                <div id="products-container" class="rounded-lg mt-4">
                                    <table class="min-w-full bg-white">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">Producto</th>
                                                <th class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">Precio (USD)</th>
                                                <th class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">Cantidad</th>
                                                <th class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($purchaseOrder->products as $index => $product)
                                                <tr class="product-row">
                                                    <td class="py-2 px-4 border-b">
                                                        <div>
                                                            <select id="products[{{ $index }}][product_id]" name="products[{{ $index }}][product_id]" class="w-full border border-gray-300 rounded product-select">
                                                                <option value="">{{ __('Selecciona un producto') }}</option>
                                                                @foreach($products as $prod)
                                                                    <option value="{{ $prod->id }}" data-price="{{ $prod->price }}" @if(old('products.' . $index . '.product_id', $product->pivot->product_id) == $prod->id) selected @endif>
                                                                        {{ $prod->product_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-4 border-b product-price">
                                                        {{ number_format( $product->price * $exchange, 2) }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b">
                                                        <div>
                                                            <input id="products[{{ $index }}][quantity]" class="w-full border border-gray-300 rounded product-quantity" type="number" name="products[{{ $index }}][quantity]"
                                                                value="{{ old('products.' . $index . '.quantity', $product->pivot->quantity) }}" min="1" />
                                                        </div>
                                                    </td>

                                                    <td class="py-2 px-4 border-b product-price">
                                                        {{$product->quantity}}
                                                        {{ number_format( ($product->quantity*$product->price) * $exchange, 2) }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b">
                                                        <button type="button" class="remove-product bg-red-600 text-white px-4 py-2 rounded">
                                                            {{ __('X') }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="py-2">
                                    <button type="button" id="add-product" class="bg-blue-500 text-white px-4 py-2 rounded">
                                        {{ __('Agregar Producto +') }}
                                    </button>
                                </div>
                            </div>
                            <div class="w-full flex justify-end">
                                <div class="w-1/2 relative flex justify-end text-right">
                                    <div class="bg-white rounded-lg w-3/5">
                                        <div class="text-lg font-bold">Total parcial: <span id="subtotal">0</span> USD</div>
                                        <div>
                                            <span class="text-lg font-bold">Total: <span id="total">0</span> USD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="py-2">
                                <label for="observations" class="{{ $errors->has('observations') ? 'text-red-400' : '' }}">
                                    {{ __('Observaciones') }}
                                </label>
                                <textarea id="observations" name="observations" class="w-full border {{ $errors->has('observations') ? 'border-red-400' : 'border-gray-300' }} rounded">{{ old('observations', $purchaseOrder->observations) }}</textarea>
                            </div>
                            <div class="flex justify-end mt-4 space-x-4">
                                <div>
                                    <button type="submit" class="bg-green-600 border-0 text-white px-4 py-2 rounded" id="submit-btn">{{ __('Actualizar') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const products = JSON.parse('{!! json_encode($products) !!}');
        const exchangeRate = {{ $exchange }};

        function updateAddProductButtonState() {
            const selectedProducts = [];
            document.querySelectorAll('#products-container select[name*="[product_id]"]').forEach(select => {
                selectedProducts.push(select.value);
            });

            const addProductButton = document.getElementById('add-product');
            if (selectedProducts.length >= products.length) {
                addProductButton.disabled = true;
            } else {
                addProductButton.disabled = false;
            }
        }

        function updateProductPrice(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const priceInPesos = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const priceInUSD = priceInPesos * exchangeRate; // Multiplicando por la tasa de cambio para convertir a USD
    const priceCell = selectElement.closest('tr').querySelector('.product-price');
    priceCell.textContent = priceInUSD.toFixed(2);
    updateTotals();
}

function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.product-row').forEach(row => {
        const price = parseFloat(row.querySelector('.product-price').textContent) || 0;
        const quantity = parseFloat(row.querySelector('.product-quantity').value) || 0;
        subtotal += price * quantity;
    });

    const iva = subtotal * 0.19; // Supongamos que el IVA es 19%
    const reteica = subtotal * 0.00966; // Supongamos que ReteICA es 0.966%
    const total = subtotal;// + iva + reteica;

    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('iva').textContent = iva.toFixed(2);

}


        document.getElementById('branch_office_id').addEventListener('change', function () {

            const clientId = $('#branch_office_id option:selected').data('client');
        const phone = $('#branch_office_id option:selected').data('phone');
        const deliveryCity = $('#branch_office_id option:selected').data('deliverycity');
        const deliveryaddress = $('#branch_office_id option:selected').data('deliveryaddress');
        const contact = $('#branch_office_id option:selected').data('contact');

        $('#phone .value').html(phone);
        $('#deliveryaddress .value').html(deliveryaddress);
        $('#deliveryCity .value').html(deliveryCity);
        $('#contact .value').html(contact);
        $("#delivery_address").val(deliveryaddress);

            const branchOfficeId = this.value;

            const productsSection = document.getElementById('products-container');
            if (!branchOfficeId) {
                productsSection.style.display = 'none';
                document.getElementById('add-product').disabled = true;
                return;
            }

            productsSection.style.display = 'block';
            updateAddProductButtonState();
        });

        document.getElementById('add-product').addEventListener('click', function () {
            addProductRow();
        });

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-product')) {
                event.target.closest('.product-row').remove();
                updateAddProductButtonState();
                updateTotals();
            }
        });

        document.addEventListener('change', function (event) {
            if (event.target.matches('select[name*="[product_id]"]')) {
                const productRow = event.target.closest('tr');
                const quantityInput = productRow.querySelector('.product-quantity');
                quantityInput.value = 1; // Poner la cantidad en 1 por defecto
                updateProductPrice(event.target);
                updateAddProductButtonState();
            } else if (event.target.matches('.product-quantity')) {
                updateTotals();
            }
        });

        document.getElementById('submit-btn').addEventListener('click', function (event) {
            event.preventDefault();
            if (validateForm()) {
                document.getElementById('purchase-order-form').submit();
            }
        });

        function validateForm() {
            let isValid = true;

            const orderConsecutive = document.getElementById('order_consecutive');
            const branchOffice = document.getElementById('branch_office_id');
            const deliveryAddress = document.getElementById('delivery_address');
            const requiredDeliveryDate = document.getElementById('required_delivery_date');
            const productRows = document.querySelectorAll('.product-row');

            clearValidationErrors();

            if (!orderConsecutive.value) {
                showValidationError(orderConsecutive, 'Este campo es requerido.');
                isValid = false;
            }

            if (!branchOffice.value) {
                showValidationError(branchOffice, 'Este campo es requerido.');
                isValid = false;
            }

            if (!deliveryAddress.value) {
                showValidationError(deliveryAddress, 'Este campo es requerido.');
                isValid = false;
            }

            if (!requiredDeliveryDate.value) {
                showValidationError(requiredDeliveryDate, 'Este campo es requerido.');
                isValid = false;
            }

            if (productRows.length === 0 || !Array.from(productRows).some(row => row.querySelector('.product-select').value)) {
                alert('Debe agregar al menos un producto.');
                isValid = false;
            }

            return isValid;
        }

        function showValidationError(element, message) {
            const errorDiv = document.createElement('div');
            errorDiv.classList.add('text-red-500', 'mt-2');
            errorDiv.textContent = message;
            element.closest('div').appendChild(errorDiv);
        }

        function clearValidationErrors() {
            document.querySelectorAll('.text-red-500').forEach(error => error.remove());
        }


        $('#delivery_address').on('change', function(element) {

$('#deliveryaddress').html(`<strong>Dirección:</strong> ${$('#delivery_address').val()}`);

})

        function addProductRow() {
            const container = document.getElementById('products-container');
            const index = container.querySelectorAll('.product-row').length;

            const selectedProducts = [];
            container.querySelectorAll('select[name*="[product_id]"]').forEach(select => {
                selectedProducts.push(select.value);
            });

            const productRowDiv = document.createElement('tr');
            productRowDiv.classList.add('product-row');
            productRowDiv.innerHTML = `
                <td class="py-2 px-4 border-b">
                    <div>
                        <select name="products[${index}][product_id]" class="w-full border border-gray-300 rounded product-select">
                            <option value="">{{ __('Selecciona un producto') }}</option>
                        </select>
                    </div>
                </td>
                <td class="py-2 px-4 border-b product-price">
                    0
                </td>
                <td class="py-2 px-4 border-b">
                    <div>
                        <input type="number" min="1" name="products[${index}][quantity]" class="w-full border border-gray-300 rounded product-quantity" value="1" />
                    </div>
                </td>
                <td class="py-2 px-4 border-b">
                    <button type="button" class="remove-product bg-red-600 text-white px-4 py-2 rounded">
                        {{ __('X') }}
                    </button>
                </td>
            `;

            const productSelect = productRowDiv.querySelector(`select[name="products[${index}][product_id]"]`);
            products.forEach(product => {
                if (!selectedProducts.includes(product.id.toString())) {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.product_name;
                    option.setAttribute('data-price', product.price);
                    productSelect.appendChild(option);
                }
            });

            container.querySelector('tbody').appendChild(productRowDiv);
            updateAddProductButtonState();
        }

        // Initial state check
        updateAddProductButtonState();
        updateTotals();
    });
</script>
<style>
    p.text-logo {
        margin-top: 9px;
        padding-left: 6px;
    }
    .button-x {
        position: relative;
        bottom: -8px;
    }
</style>
