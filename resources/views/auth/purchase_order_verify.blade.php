<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('purchase_order.verify_code') }}">
            @csrf

            <!-- Verification Code -->
            <div>
                <x-input-label for="code" :value="__('Verification Code')" />
                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required autofocus />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-3">
                    {{ __('Verify') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
