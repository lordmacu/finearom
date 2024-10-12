<x-admin.wrapper>
    <x-slot name="title">
        {{ __('Configuración de Administración') }}
    </x-slot>

    <div id="app" class="w-full overflow-hidden">
        <config-form
        :initial-rows="{{ $processes->toJson() }}"
        :message="{{ json_encode(session('message')) }}"
        ></config-form>

        <!-- Dropdown para seleccionar el archivo de backup -->
        <form method="POST" action="{{ route('admin.config.restoreBackup') }}">
            @csrf
            <div class="mb-4">
                <label for="backup" class="block text-sm font-medium text-gray-700">Seleccionar Backup</label>
                <select id="backup" name="backup" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    @foreach($backups as $backup)
                        <option value="{{ $backup }}">{{ $backup }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botón para restaurar -->
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" onclick="return confirm('¿Estás seguro de que deseas restaurar la base de datos desde este backup? Esta acción sobrescribirá todos los datos de las últimas 2 horas.')">
                Restaurar Backup
            </button>
        </form>

        <form method="POST" action="{{ route('admin.config.createBackup') }}" class="mt-4">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                Crear Backup
            </button>
        </form>
    </div>
</x-admin.wrapper>

@vite(['resources/js/app.js'])
