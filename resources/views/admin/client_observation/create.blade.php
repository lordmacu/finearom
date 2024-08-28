<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Add Client Observation') }}
    </x-slot>

    <div>
        <x-admin.breadcrumb href="{{ route('admin.client.index') }}" title="{{ __('Editar Cliente') }}"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
            </svg></x-admin.breadcrumb>
        <x-admin.form.errors />
    </div>
    <div class="w-full py-2 overflow-hidden">
    <form method="POST" action="{{ route('admin.clients.observations.store', $client->id) }}">
    @csrf

    <div class="py-2 flex items-center">
        <input id="requires_physical_invoice" type="checkbox" name="requires_physical_invoice" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2" value="1" {{ old('requires_physical_invoice', $observation->requires_physical_invoice ?? false) ? 'checked' : '' }}>
        <x-admin.form.label for="requires_physical_invoice" class="{{ $errors->has('requires_physical_invoice') ? 'text-red-400' : '' }}">{{ __('Requires Physical Invoice') }}</x-admin.form.label>
    </div>

    <div class="py-2 grid grid-cols-2 gap-4">
        <div>
            <x-admin.form.label for="packaging_unit" class="{{ $errors->has('packaging_unit') ? 'text-red-400' : '' }}">{{ __('Packaging Unit') }}</x-admin.form.label>
            <x-admin.form.input id="packaging_unit" class="{{ $errors->has('packaging_unit') ? 'border-red-400' : '' }}" type="number" name="packaging_unit" value="{{ old('packaging_unit', $observation->packaging_unit ?? '') }}" />
        </div>
        <div>
            <x-admin.form.label for="billing_closure_date" class="{{ $errors->has('billing_closure_date') ? 'text-red-400' : '' }}">{{ __('Billing Closure Date') }}</x-admin.form.label>
            <x-admin.form.input id="billing_closure_date" class="{{ $errors->has('billing_closure_date') ? 'border-red-400' : '' }}" type="text" name="billing_closure_date" value="{{ old('billing_closure_date', $observation->billing_closure_date ?? '') }}" />
        </div>
    </div>

    <div class="py-2 flex items-center">
        <input id="requires_appointment" type="checkbox" name="requires_appointment" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2" value="1" {{ old('requires_appointment', $observation->requires_appointment ?? false) ? 'checked' : '' }}>
        <x-admin.form.label for="requires_appointment" class="{{ $errors->has('requires_appointment') ? 'text-red-400' : '' }}">{{ __('Requires Appointment') }}</x-admin.form.label>
    </div>

    <div class="py-2 flex items-center">
        <input id="is_in_free_zone" type="checkbox" name="is_in_free_zone" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2" value="1" {{ old('is_in_free_zone', $observation->is_in_free_zone ?? false) ? 'checked' : '' }}>
        <x-admin.form.label for="is_in_free_zone" class="{{ $errors->has('is_in_free_zone') ? 'text-red-400' : '' }}">{{ __('Is in Free Zone') }}</x-admin.form.label>
    </div>

    <div class="py-2 grid grid-cols-2 gap-4">
        <div>
            <x-admin.form.label for="reteica" class="{{ $errors->has('reteica') ? 'text-red-400' : '' }}">{{ __('Reteica') }}</x-admin.form.label>
            <x-admin.form.input id="reteica" class="{{ $errors->has('reteica') ? 'border-red-400' : '' }}" type="number" name="reteica" value="{{ old('reteica', $observation->reteica ?? 0) }}" />
        </div>
        <div>
            <x-admin.form.label for="retefuente" class="{{ $errors->has('retefuente') ? 'text-red-400' : '' }}">{{ __('Retefuente') }}</x-admin.form.label>
            <x-admin.form.input id="retefuente" class="{{ $errors->has('retefuente') ? 'border-red-400' : '' }}" type="number" name="retefuente" value="{{ old('retefuente', $observation->retefuente ?? 0) }}" />
        </div>
    </div>

    <div class="py-2 grid grid-cols-2 gap-4">
        <div>
            <x-admin.form.label for="reteiva" class="{{ $errors->has('reteiva') ? 'text-red-400' : '' }}">{{ __('Reteiva') }}</x-admin.form.label>
            <x-admin.form.input id="reteiva" class="{{ $errors->has('reteiva') ? 'border-red-400' : '' }}" type="number" name="reteiva" value="{{ old('reteiva', $observation->reteiva ?? 0) }}" />
        </div>
        <div>
            <x-admin.form.label for="additional_observations" class="{{ $errors->has('additional_observations') ? 'text-red-400' : '' }}">{{ __('Additional Observations') }}</x-admin.form.label>
            <textarea id="additional_observations" name="additional_observations" class="input input-bordered w-full {{ $errors->has('additional_observations') ? 'border-red-400' : '' }}">{{ old('additional_observations', $observation->additional_observations ?? '') }}</textarea>
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <x-admin.form.button>{{ __('Add') }}</x-admin.form.button>
    </div>
</form>

    </div>
</x-admin.wrapper>
