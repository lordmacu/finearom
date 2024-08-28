<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Client Observations') }}
    </x-slot>

    <div class="d-print-none with-border">
        <x-admin.breadcrumb href="{{ route('admin.client.index') }}" title="{{ __('Client Observations') }}"><svg
                class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 5H1m0 0 4 4M1 5l4-4" />
            </svg></x-admin.breadcrumb>
        aquii
    </div>

    <div class="py-2">
        <div class="min-w-full border-base-200 shadow overflow-x-auto">
            <x-admin.grid.table>
                <x-slot name="head">
                    <tr class="bg-base-200">
                        <x-admin.grid.th>{{ __('Requires Physical Invoice') }}</x-admin.grid.th>
                        <x-admin.grid.th>{{ __('Packaging Unit') }}</x-admin.grid.th>
                        <x-admin.grid.th>{{ __('Requires Appointment') }}</x-admin.grid.th>
                        <x-admin.grid.th>{{ __('Additional Observations') }}</x-admin.grid.th>
                        @canany(['client_observation edit', 'client_observation delete'])
                            <x-admin.grid.th>{{ __('Actions') }}</x-admin.grid.th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach($observations as $observation)
                        <tr>
                            <td>{{ $observation->requires_physical_invoice ? 'Yes' : 'No' }}</td>
                            <td>{{ $observation->packaging_unit }}</td>
                            <td>{{ $observation->requires_appointment ? 'Yes' : 'No' }}</td>
                            <td>{{ $observation->additional_observations }}</td>
                            @canany(['client_observation edit', 'client_observation delete'])
                                <td>
                                    <a href="{{ route('admin.client_observations.edit', [$client->id, $observation->id]) }}"><svg
                                            class="h-8 w-8 text-blue-500" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z"></path>
                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                            <path d="M9 15h3l8.5 -8.5a1.5 1.7 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                            <line x1="16" y1="5" x2="19" y2="8"></line>
                                        </svg></a>
                                    <form
                                        action="{{ route('admin.client_observations.destroy', [$client->id, $observation->id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"><svg style="
                                                        width: 24px;
                                                        margin-top: 3px;
                                                        color: #bd7676;
                                                    " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="w-5">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0">
                                                                                            </path>
                                                                                        </svg></button>
                                    </form>
                                </td>
                            @endcanany
                        </tr>
                    @endforeach
                    @empty($observations)
                        <tr>
                            <td colspan="5">
                                <div class="flex flex-col justify-center items-center py-4 text-lg">
                                    {{ __('No Observations Found') }}
                                </div>
                            </td>
                        </tr>
                    @endempty
                </x-slot>
            </x-admin.grid.table>
        </div>
    </div>
</x-admin.wrapper>