<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Configuración de Administración') }}
    </x-slot>

    <div id="app" class="w-full overflow-hidden">
        <config-form
        :initial-rows="{{ $processes->toJson() }}"
        :message="{{ json_encode(session('message')) }}"
        ></config-form>
    </div>
</x-admin.wrapper>

@vite(['resources/js/app.js'])

