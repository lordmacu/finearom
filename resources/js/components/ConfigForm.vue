<template>
    <div>
        <!-- Mensaje de éxito -->
        <div v-if="message" class="bg-green-500 text-white p-3 rounded mb-4">
            {{ message }}
        </div>

        <!-- Formulario de subida de archivo 
        <form @submit.prevent="submitUploadForm" class="mb-4">
            <div class="mb-4">
                <label for="fileUpload" class="block text-gray-700 text-sm font-bold mb-2">Subir Archivo Excel</label>
                <input type="file" id="fileUpload" ref="fileUpload" @change="handleFileUpload" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept=".xlsx,.xls">
            </div>
            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Subir Archivo
                </button>
            </div>
        </form>
-->
        <!-- Filtro de procesos -->
        <div class="mb-4">
            <label for="filterProcess" class="block text-gray-700 text-sm font-bold mb-2">Filtrar por Proceso</label>
            <select v-model="filterProcess" id="filterProcess" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Todos</option>
                <option value="orden_de_compra">1 Orden de Compra a planta</option>
                <option value="confirmacion_despacho">2 Confirmación Despacho a cliente</option>
                <option value="pedido">3 Recepción orden de compra</option>
            </select>
        </div>

        <!-- Formulario para la configuración -->
        <form @submit.prevent="submitForm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proceso</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(row, index) in filteredRows" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" v-model="row.name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Nombre">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="email" v-model="row.email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Correo Electrónico">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select v-model="row.process_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="orden_de_compra">Orden de Compra</option>
                                <option value="confirmacion_despacho">Confirmación Despacho</option>
                                <option value="pedido">Pedido</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="button" @click="removeRow(row.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex items-center justify-end mt-4">
                <button type="button" @click="addRow" class="bg-indigo-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Agregar Fila</button>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        initialRows: {
            type: Array,
            default: () => [{ name: '', email: '', process_type: 'orden_de_compra' }],
        },
        message: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            rows: [...this.initialRows],
            filterProcess: 'orden_de_compra',
            file: null // Agregar archivo
        }
    },
    computed: {
        filteredRows() {
            if (this.filterProcess === '') {
                return this.rows;
            }
            return this.rows.filter(row => row.process_type === this.filterProcess);
        }
    },
    methods: {
        addRow() {
            const processType = this.filterProcess !== '' ? this.filterProcess : '';
            const uniqueId = Date.now();

            this.rows.push({ id: uniqueId, name: '', email: '', process_type: processType });
        },
        removeRow(rowId) {
            const originalIndex = this.rows.findIndex(row => row.id === rowId);
            if (originalIndex !== -1) {
                this.rows.splice(originalIndex, 1);
            }
        },
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        async submitUploadForm() {
            try {
                const formData = new FormData();
                formData.append('file', this.file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                const response = await axios.post('/admin/config/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    window.location.href = '/admin/config';
                }
            } catch (error) {
                console.error(error.response.data);
            }
        },
        async submitForm() {
            try {
                const response = await axios.post('/admin/config', {
                    rows: this.rows,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                });

                if (response.data.success) {
                    window.location.href = '/admin/config';
                }
            } catch (error) {
                console.error(error.response.data);
            }
        }
    }
}
</script>
