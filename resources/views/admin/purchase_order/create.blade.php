<!-- resources/views/admin/purchase_order.blade.php -->
<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Orden de Compra') }}
    </x-slot>

    <div id="app" class="w-full overflow-hidden">
        <purchase-order-form  :exchange="{{ $exchange }}" :clients="{{ json_encode($clients) }}"></purchase-order-form>
    </div>
</x-admin.wrapper>

@vite(['resources/js/app.js'])
