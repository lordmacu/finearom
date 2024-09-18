<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Clientes') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{ route('admin.client.index') }}" title="{{ __('Editar Cliente') }}">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg>
        </x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.branch_offices.index', $client->id) }}" class="btn btn-secondary">
            Agregar sucursal
        </a>
    </div>
    <div class="w-full py-2 overflow-hidden cont-form">
        <form method="POST" action="{{ route('admin.client.update', $client->id) }}">
            @csrf
            @method('PUT')
            <div class="py-2 grid grid-cols-2 gap-4">
                <div class="py-2">
                    <div>
                        <x-admin.form.label for="client_name" class="{{ $errors->has('client_name') ? 'text-red-400' : '' }}">{{ __('Nombre del Cliente') }}</x-admin.form.label>
                        <x-admin.form.input id="client_name" class="{{ $errors->has('client_name') ? 'border-red-400' : '' }}" type="text" name="client_name" value="{{ old('client_name', $client->client_name) }}" />
                    </div>
                </div>
                <div class="py-2">
                    <x-admin.form.label for="nit" class="{{ $errors->has('nit') ? 'text-red-400' : '' }}">{{ __('Nit') }}</x-admin.form.label>
                    <x-admin.form.input id="nit" class="{{ $errors->has('nit') ? 'border-red-400' : '' }}" type="text" name="nit" value="{{ old('nit', $client->nit) }}" />
                </div>
            </div>
            <div class="py-2">
                <x-admin.form.label for="address" class="{{ $errors->has('address') ? 'text-red-400' : '' }}">{{ __('Direccion') }}</x-admin.form.label>
                <x-admin.form.input id="address" class="{{ $errors->has('address') ? 'border-red-400' : '' }}" type="text" name="address" value="{{ old('address', $client->address) }}" />
            </div>

            <div class="py-2 grid grid-cols-2 gap-4">
                <div class="py-2">
                    <x-admin.form.label for="phone" class="{{ $errors->has('phone') ? 'text-red-400' : '' }}">{{ __('Telefono') }}</x-admin.form.label>
                    <x-admin.form.input id="phone" class="{{ $errors->has('phone') ? 'border-red-400' : '' }}" type="text" name="phone" value="{{ old('phone', $client->phone) }}" />
                </div>

                <div class="py-2">
                    <x-admin.form.label for="executive" class="{{ $errors->has('executive') ? 'text-red-400' : '' }}">{{ __('Correo Ejecutivo') }}</x-admin.form.label>
                    <x-admin.form.input id="executive" class="{{ $errors->has('executive') ? 'border-red-400' : '' }}" type="email" name="executive" value="{{ old('executive', $client->executive) }}" />
                </div>
            </div>

            <div class="py-2 grid grid-cols-2 gap-4">
                <div>
                    <x-admin.form.label for="client_type" class="{{ $errors->has('client_type') ? 'text-red-400' : '' }}">{{ __('Tipo de Cliente') }}</x-admin.form.label>
                    <select id="client_type" name="client_type" class="input input-bordered w-full {{ $errors->has('client_type') ? 'border-red-400' : '' }}">
                        <option value="pareto" {{ old('client_type', $client->client_type) == 'pareto' ? 'selected' : '' }}>Pareto</option>
                        <option value="balance" {{ old('client_type', $client->client_type) == 'balance' ? 'selected' : '' }}>Balance</option>
                    </select>
                </div>
                <div class="py-2">
                    <x-admin.form.label for="email" class="{{ $errors->has('email') ? 'text-red-400' : '' }}">{{ __('Correo Electrónico') }}</x-admin.form.label>
                    <x-admin.form.input id="email" class="{{ $errors->has('email') ? 'border-red-400' : '' }}" type="email" name="email" value="{{ old('email', $client->email) }}" />
                </div>
            </div>

   

            <div class="py-2 grid grid-cols-2 gap-4">
                <div class="py-2">
                    <x-admin.form.label for="dispatch_confirmation_email" class="{{ $errors->has('dispatch_confirmation_email') ? 'text-red-400' : '' }}">{{ __('Correo Confirmación Despacho') }}</x-admin.form.label>
                    <x-admin.form.input id="dispatch_confirmation_email" class="{{ $errors->has('dispatch_confirmation_email') ? 'border-red-400' : '' }}" type="email" name="dispatch_confirmation_email" value="{{ old('dispatch_confirmation_email', $client->dispatch_confirmation_email) }}" />
                </div>
                <div class="py-2">
                    <x-admin.form.label for="accounting_contact_email" class="{{ $errors->has('accounting_contact_email') ? 'text-red-400' : '' }}">{{ __('Correo Contacto Contabilidad') }}</x-admin.form.label>
                    <x-admin.form.input id="accounting_contact_email" class="{{ $errors->has('accounting_contact_email') ? 'border-red-400' : '' }}" type="email" name="accounting_contact_email" value="{{ old('accounting_contact_email', $client->accounting_contact_email) }}" />
                </div>
            </div>

            <div class="py-2 grid grid-cols-2 gap-4">
                <div>
                    <x-admin.form.label for="registration_address" class="{{ $errors->has('registration_address') ? 'text-red-400' : '' }}">{{ __('Dirección de Radicación') }}</x-admin.form.label>
                    <x-admin.form.input id="registration_address" class="{{ $errors->has('registration_address') ? 'border-red-400' : '' }}" type="text" name="registration_address" value="{{ old('registration_address', $client->registration_address) }}" />
                </div>
                <div>
                    <x-admin.form.label for="registration_city" class="{{ $errors->has('registration_city') ? 'text-red-400' : '' }}">{{ __('Ciudad de Radicación') }}</x-admin.form.label>
                    <x-admin.form.input id="registration_city" class="{{ $errors->has('registration_city') ? 'border-red-400' : '' }}" type="text" name="registration_city" value="{{ old('registration_city', $client->registration_city) }}" />
                </div>
            </div>
            <div class="py-2 grid grid-cols-2 gap-4">
                <div>
                    <x-admin.form.label for="proforma_invoice" class="{{ $errors->has('proforma_invoice') ? 'text-red-400' : '' }}">{{ __('Factura Proforma') }}</x-admin.form.label>
                    <select id="proforma_invoice" name="proforma_invoice" class="input input-bordered w-full {{ $errors->has('proforma_invoice') ? 'border-red-400' : '' }}">
                        <option value="0" {{ old('proforma_invoice', $client->proforma_invoice) == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('proforma_invoice', $client->proforma_invoice) == '1' ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
                <div>
                    <x-admin.form.label for="payment_method" class="{{ $errors->has('payment_method') ? 'text-red-400' : '' }}">{{ __('Forma de Pago') }}</x-admin.form.label>
                    <select id="payment_method" name="payment_method" class="input input-bordered w-full {{ $errors->has('payment_method') ? 'border-red-400' : '' }}">
                        <option value="1" {{ old('payment_method', $client->payment_method) == '1' ? 'selected' : '' }}>Contado</option>
                        <option value="2" {{ old('payment_method', $client->payment_method) == '2' ? 'selected' : '' }}>Credito</option>
                    </select>
                </div>
            </div>

            <div class="py-2 grid grid-cols-2 gap-4">
                <div>
                    <x-admin.form.label for="payment_day" class="{{ $errors->has('payment_day') ? 'text-red-400' : '' }}">{{ __('Día de Pago') }}</x-admin.form.label>
                    <x-admin.form.input id="payment_day" class="{{ $errors->has('payment_day') ? 'border-red-400' : '' }}" type="number" name="payment_day" value="{{ old('payment_day', $client->payment_day) }}" />
                </div>
                <div>
                    <x-admin.form.label for="status" class="{{ $errors->has('status') ? 'text-red-400' : '' }}">{{ __('Estado') }}</x-admin.form.label>
                    <select id="status" name="status" class="input input-bordered w-full {{ $errors->has('status') ? 'border-red-400' : '' }}">
                        <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="py-2">
                <x-admin.form.label for="commercial_terms" class="{{ $errors->has('commercial_terms') ? 'text-red-400' : '' }}">{{ __('Condiciones Comerciales') }}</x-admin.form.label>
                <textarea id="commercial_terms" name="commercial_terms" class="input input-bordered w-full {{ $errors->has('commercial_terms') ? 'border-red-400' : '' }}">{{ old('commercial_terms', $client->commercial_terms) }}</textarea>
            </div>

            <div class="flex justify-between mt-4">
                <x-admin.form.button>{{ __('Actualizar') }}</x-admin.form.button>
                <a href="{{ route('admin.branch_offices.index', $client->id) }}" class="btn btn-secondary">{{ __('Ver sucursales') }}</a>
            </div>
        </form>
    </div>
</x-admin.wrapper>
