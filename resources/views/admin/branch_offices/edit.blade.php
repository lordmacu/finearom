<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Editar Sucursal para el Cliente: ') . $client->client_name }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{ route('admin.branch_offices.index', $client->id) }}"
            title="{{ __('Sucursales') }}"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg></x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>

    <div class="w-full py-2 overflow-hidden">
        <form method="POST" action="{{ route('admin.branch_offices.update', [$client->id, $branchOffice->id]) }}">
            @csrf
            @method('PUT')

            <div class="cont-form">
                <div class="flex gap-4">
                    <div class="w-1/2">
                        <x-admin.form.label for="name"
                            class="{{ $errors->has('name') ? 'text-red-400' : '' }}">{{ __('Nombre') }}</x-admin.form.label>
                        <x-admin.form.input id="name" class="{{ $errors->has('name') ? 'border-red-400' : '' }}"
                            type="text" name="name" value="{{ old('name', $branchOffice->name) }}" />

                    </div>
                    <div class="w-1/2">
                        <x-admin.form.label for="nit"
                            class="{{ $errors->has('nit') ? 'text-red-400' : '' }}">{{ __('NIT') }}</x-admin.form.label>
                        <x-admin.form.input id="nit" class="{{ $errors->has('nit') ? 'border-red-400' : '' }}"
                            type="text" name="nit" value="{{ old('nit', $branchOffice->nit) }}" />
                    </div>
                </div>



                <div class="flex gap-4">
                    <div class="w-1/2">
                        <x-admin.form.label for="contact"
                            class="{{ $errors->has('contact') ? 'text-red-400' : '' }}">{{ __('Contacto') }}</x-admin.form.label>
                        <x-admin.form.input id="contact" class="{{ $errors->has('contact') ? 'border-red-400' : '' }}"
                            type="text" name="contact" value="{{ old('contact', $branchOffice->contact) }}" />
                    </div>
                    <div class="w-1/2">
                        <x-admin.form.label for="billing_contact"
                            class="{{ $errors->has('billing_contact') ? 'text-red-400' : '' }}">{{ __('Contacto Cartera') }}</x-admin.form.label>
                        <x-admin.form.input id="billing_contact"
                            class="{{ $errors->has('billing_contact') ? 'border-red-400' : '' }}" type="text"
                            name="billing_contact"
                            value="{{ old('billing_contact', $branchOffice->billing_contact) }}" />
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-1/2">


                        <x-admin.form.label for="delivery_address"
                            class="{{ $errors->has('delivery_address') ? 'text-red-400' : '' }}">{{ __('Dirección de Entrega') }}</x-admin.form.label>
                        <x-admin.form.input id="delivery_address"
                            class="{{ $errors->has('delivery_address') ? 'border-red-400' : '' }}" type="text"
                            name="delivery_address"
                            value="{{ old('delivery_address', $branchOffice->delivery_address) }}" />
                    </div>

                    <div class="w-1/2">
                        <x-admin.form.label for="delivery_city"
                            class="{{ $errors->has('delivery_city') ? 'text-red-400' : '' }}">{{ __('Ciudad de Entrega') }}</x-admin.form.label>
                        <x-admin.form.input id="delivery_city"
                            class="{{ $errors->has('delivery_city') ? 'border-red-400' : '' }}" type="text"
                            name="delivery_city" value="{{ old('delivery_city', $branchOffice->delivery_city) }}" />
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-1/2">
                        <x-admin.form.label for="billing_address"
                            class="{{ $errors->has('billing_address') ? 'text-red-400' : '' }}">{{ __('Dirección de Radicación') }}</x-admin.form.label>
                        <x-admin.form.input id="billing_address"
                            class="{{ $errors->has('billing_address') ? 'border-red-400' : '' }}" type="text"
                            name="billing_address"
                            value="{{ old('billing_address', $branchOffice->billing_address) }}" />
                    </div>

                    <div class="w-1/2">
                        <x-admin.form.label for="billing_city"
                            class="{{ $errors->has('billing_city') ? 'text-red-400' : '' }}">{{ __('Ciudad de Radicación') }}</x-admin.form.label>
                        <x-admin.form.input id="billing_city"
                            class="{{ $errors->has('billing_city') ? 'border-red-400' : '' }}" type="text"
                            name="billing_city" value="{{ old('billing_city', $branchOffice->billing_city) }}" />
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-1/2">
                        <x-admin.form.label for="phone"
                            class="{{ $errors->has('phone') ? 'text-red-400' : '' }}">{{ __('Teléfono') }}</x-admin.form.label>
                        <x-admin.form.input id="phone" class="{{ $errors->has('phone') ? 'border-red-400' : '' }}"
                            type="text" name="phone" value="{{ old('phone', $branchOffice->phone) }}" />
                    </div>

                    <div class="w-1/2">
                        <x-admin.form.label for="shipping_observations"
                            class="{{ $errors->has('shipping_observations') ? 'text-red-400' : '' }}">{{ __('Observaciones de Envío') }}</x-admin.form.label>
                        <x-admin.form.input id="shipping_observations"
                            class="{{ $errors->has('shipping_observations') ? 'border-red-400' : '' }}" type="text"
                            name="shipping_observations"
                            value="{{ old('shipping_observations', $branchOffice->shipping_observations) }}" />
                    </div>
                </div>

                <div class="py-2">
                    <x-admin.form.label for="general_observations"
                        class="{{ $errors->has('general_observations') ? 'text-red-400' : '' }}">{{ __('Observaciones Generales') }}</x-admin.form.label>
                    <x-admin.form.input id="general_observations"
                        class="{{ $errors->has('general_observations') ? 'border-red-400' : '' }}" type="text"
                        name="general_observations"
                        value="{{ old('general_observations', $branchOffice->general_observations) }}" />
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <x-admin.form.button>{{ __('Actualizar') }}</x-admin.form.button>
            </div>
        </form>
    </div>
</x-admin.wrapper>