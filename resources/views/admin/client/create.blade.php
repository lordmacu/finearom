<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Agregar Cliente') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{ route('admin.client.index') }}" title="{{ __('Agregar Cliente') }}">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg>
        </x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="w-full py-2 overflow-hidden cont-form">
        <form method="POST" action="{{ route('admin.client.store') }}">
            @csrf

            <div class="py-2">
                <x-admin.form.label for="client_name" class="{{ $errors->has('client_name') ? 'text-red-400' : '' }}">{{ __('Nombre del Cliente') }}</x-admin.form.label>
                <x-admin.form.input id="client_name" class="{{ $errors->has('client_name') ? 'border-red-400' : '' }}" type="text" name="client_name" value="{{ old('client_name') }}" />
            </div>

            <div class="py-2 grid grid-cols-2 gap-4">
                <div>
                    <x-admin.form.label for="client_type" class="{{ $errors->has('client_type') ? 'text-red-400' : '' }}">{{ __('Tipo de Cliente') }}</x-admin.form.label>
                    <select id="client_type" name="client_type" class="input input-bordered w-full {{ $errors->has('client_type') ? 'border-red-400' : '' }}">
                        <option value="pareto" {{ old('client_type') == 'pareto' ? 'selected' : '' }}>Pareto</option>
                        <option value="balance" {{ old('client_type') == 'balance' ? 'selected' : '' }}>Balance</option>
                    </select>
                </div>
            </div>

            <div class="py-2 grid grid-cols-2 gap-4">
                <div class="py-2">
                    <x-admin.form.label for="email" class="{{ $errors->has('email') ? 'text-red-400' : '' }}">{{ __('Correo Electrónico') }}</x-admin.form.label>
                    <x-admin.form.input id="email" class="{{ $errors->has('email') ? 'border-red-400' : '' }}" type="email" name="email" value="{{ old('email') }}" />
                </div>
            </div>

            <div class="py-2">
                <x-admin.form.label for="billing_closure" class="{{ $errors->has('billing_closure') ? 'text-red-400' : '' }}">{{ __('Cierre de Facturación') }}</x-admin.form.label>
                <x-admin.form.input id="billing_closure" class="{{ $errors->has('billing_closure') ? 'border-red-400' : '' }}" type="text" name="billing_closure" value="{{ old('billing_closure') }}" />
            </div>

            <div class="py-2 grid grid-cols-2 gap-4">
                <div>
                    <x-admin.form.label for="proforma_invoice" class="{{ $errors->has('proforma_invoice') ? 'text-red-400' : '' }}">{{ __('Factura Proforma') }}</x-admin.form.label>
                    <select id="proforma_invoice" name="proforma_invoice" class="input input-bordered w-full {{ $errors->has('proforma_invoice') ? 'border-red-400' : '' }}">
                        <option value="0" {{ old('proforma_invoice') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('proforma_invoice') == '1' ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
                <div>
                    <x-admin.form.label for="payment_method" class="{{ $errors->has('payment_method') ? 'text-red-400' : '' }}">{{ __('Forma de Pago') }}</x-admin.form.label>
                    <select id="payment_method" name="payment_method" class="input input-bordered w-full {{ $errors->has('payment_method') ? 'border-red-400' : '' }}">
                        <option value="1" {{ old('payment_method') == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ old('payment_method') == '2' ? 'selected' : '' }}>2</option>
                    </select>
                </div>
            </div>

            <div class="py-2 grid grid-cols-2 gap-4">
                <div>
                    <x-admin.form.label for="payment_day" class="{{ $errors->has('payment_day') ? 'text-red-400' : '' }}">{{ __('Día de Pago') }}</x-admin.form.label>
                    <x-admin.form.input id="payment_day" class="{{ $errors->has('payment_day') ? 'border-red-400' : '' }}" type="number" name="payment_day" value="{{ old('payment_day') }}" />
                </div>
                <div>
                    <x-admin.form.label for="status" class="{{ $errors->has('status') ? 'text-red-400' : '' }}">{{ __('Estado') }}</x-admin.form.label>
                    <select id="status" name="status" class="input input-bordered w-full {{ $errors->has('status') ? 'border-red-400' : '' }}">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            
            <div class="py-2">
                <x-admin.form.label for="commercial_conditions" class="{{ $errors->has('commercial_conditions') ? 'text-red-400' : '' }}">{{ __('Condiciones Comerciales') }}</x-admin.form.label>
                <textarea id="commercial_conditions" name="commercial_conditions" class="input input-bordered w-full {{ $errors->has('commercial_conditions') ? 'border-red-400' : '' }}">{{ old('commercial_conditions') }}</textarea>
            </div>

            <div class="flex justify-end mt-4">
                <x-admin.form.button>{{ __('Crear') }}</x-admin.form.button>
            </div>
        </form>
    </div>
</x-admin.wrapper>
