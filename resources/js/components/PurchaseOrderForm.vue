<template>
    <div ref="formContainer">
        <div class="w-full overflow-hidden">
            <div class="border border-gray-200 bg-blue-50 rounded-lg">
                <div class="bg-white rounded-lg pt-8 pb-8 mb-8 border border-gray-200 p-6">
                    <form @submit.prevent="submitForm">
                        <div class="flex flex-col gap-4">
                            <div class="w-full flex justify-between">
                                <div>
                                    <div style="margin-bottom: 20px;">
                                        <input v-model="trmCalculated" placeholder="Trm Orden"
                                            class="border rounded px-2" />

                                        <button type="button" @click="setActualTrm"
                                            class="bg-blue-500 text-white px-4 py-2 rounded ml-2">
                                            Trm Actual
                                        </button>
                                    </div>
                                    <div>
                                        <label :class="errors.order_consecutive ? 'text-red-500' : ''">
                                            Consecutivo de la Orden
                                        </label>
                                        <input v-model="orderConsecutive" type="text" :disabled="purchaseOrder === null"
                                            :class="[errors.order_consecutive ? 'border-red-500' : 'border-gray-300', 'rounded', 'px-2', 'ml-2']" />
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <label :class="errors.required_delivery_date ? 'text-red-500' : ''">
                                            Fecha de Entrega Requerida 
                                        </label>


                                        <VueDatePicker v-model="requiredDeliveryDate" model-type="yyyy-MM-dd"
                                            :enable-time-picker="false"
                                            :class="[errors.required_delivery_date ? 'border-red-500' : 'border-gray-300', 'rounded', 'px-2']" />

                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <div class="border border-gray-300 bg-white p-4 shadow-sm rounded-md">
                                        <label :class="errors.client_id ? 'text-red-500' : ''">
                                            Clientes
                                        </label>
                                        <select v-model="clientId" @change="onClientChange" class="w-full border"
                                            :class="[errors.client_id ? 'border-red-500' : 'border-gray-300', 'rounded']">
                                            <option value="">Selecciona un cliente</option>
                                            <option v-for="client in clients" :key="client.id" :value="client.id">
                                                {{ client.client_name }}
                                            </option>
                                        </select>

                                        <div class="py-2">
                                            <label :class="errors.status ? 'text-red-500' : ''">
                                                Estado de la Orden
                                            </label>
                                            <select v-model="status" class="w-full border"
                                                :class="[errors.status ? 'border-red-500' : 'border-gray-300', 'rounded']">
                                                <option value="pending">Pendiente</option>
                                                <option value="processing">En Proceso</option>
                                                <option value="completed">Completada</option>
                                                <option value="cancelled">Cancelada</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="w-1/2">
                                    <div class="border border-gray-300 bg-white p-4 shadow-sm rounded-md">
                                        <div class="py-2">
                                            <label :class="errors.contact ? 'text-red-500' : ''">
                                                Contacto
                                            </label>

                                            <input v-model="contact" type="text"
                                                :class="[errors.contact ? 'border-red-500' : 'border-gray-300', 'rounded', 'w-full']" />

                                        </div>
                                        <div class="py-2">
                                            <label :class="errors.phone ? 'text-red-500' : ''">
                                                Teléfono
                                            </label>
                                            <input v-model="phone" type="text"
                                                :class="[errors.phone ? 'border-red-500' : 'border-gray-300', 'rounded', 'w-full']" />

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="py-4">
                            <div id="products-container" class="rounded-lg mt-4">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Producto</th>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Precio (USD)</th>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Cantidad</th>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Total (USD)</th>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Sucursal</th>
                                            <th
                                                class="py-2 px-4 border-b-2 bg-gray-100 text-left text-xs text-gray-500 uppercase">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(product, index) in products" :key="index" class="product-row">
                                            <td class="py-2 px-4 border-b">

                                                <select v-model="product.product_id"
                                                    @change="updateProductPrice(product)"
                                                    class="w-full border border-gray-300 rounded">
                                                    <option value="">Selecciona un producto</option>
                                                    <option v-for="p in availableProducts" :key="p.id" :value="p.id"
                                                        :data-price="p.price">
                                                        {{ p.product_name }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="py-2 px-4 border-b product-price" :data-price="product.price">

                                                {{ (product.price) }}
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <input v-model.number="product.quantity" type="number" min="1"
                                                    @change="updateProductTotal(product)"
                                                    class="w-full border border-gray-300 rounded product-quantity" />
                                            </td>
                                            <td class="py-2 px-4 border-b product-total" :data-price="product.price"
                                                :data-quantity="product.quantity">
                                                {{ ((product.price * product.quantity)).toFixed(2) }}
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <select v-model="product.branch_office_id"
                                                    class="w-full border border-gray-300 rounded branch-office-select">
                                                    <option value="">Selecciona una sucursal</option>
                                                    <option v-for="branch in branchOffices" :key="branch.id"
                                                        :value="branch.id">
                                                        {{ branch.name }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <button type="button" @click="removeProduct(index)"
                                                    class="bg-red-600 text-white px-4 py-2 rounded">
                                                    X
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="py-2">
                                <button type="button" @click="addProductRow"
                                    class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Agregar Producto +
                                </button>
                            </div>
                        </div>

                        <div class="w-full flex justify-end">
                            <div class="w-1/2 relative flex justify-end text-right">
                                <div class="bg-white rounded-lg w-3/5">
                                    <div class="text-lg font-bold">Total parcial: <span>{{ subtotal.toFixed(2) }}</span>
                                        USD</div>
                                    <!--  <div>Iva: <span>{{ iva.toFixed(2) }}</span> USD</div>
                                    <div>ReteICA: <span>{{ reteica.toFixed(2) }}</span> USD</div> -->
                                    <div>
                                        <span class="text-lg font-bold">Total: <span>{{ total.toFixed(2) }}</span>
                                            USD</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="py-2">
                                <label :class="errors.observations ? 'text-red-500' : ''">
                                    Observaciones
                                </label>
                                <textarea v-model="observations" class="w-full border"
                                    :class="[errors.observations ? 'border-red-500' : 'border-gray-300', 'rounded']"></textarea>

                            </div>
                        </div>

                        <div class="w-full">
                            <label :class="errors.attachment ? 'text-red-500' : ''">Adjuntar PDF</label>
                            <input type="file" @change="onFileChange" class="w-full border border-gray-300 rounded" />

                            <div v-if="attachmentUrl" class="mt-2">
                                <p>Archivo adjunto actual:</p>
                                <a :href="attachmentUrl" target="_blank" class="text-blue-500">Ver PDF</a>
                                <button v-if="purchaseOrder" @click.prevent="removeAttachment"
                                    class="text-red-500 ml-2">Eliminar</button>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 space-x-4" v-if="canEditPurchaseOrder">
                            <div>
                                <button v-if="purchaseOrder == null" type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded">
                                    Crear
                                </button>
                                <button v-if="purchaseOrder" type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>export default {
    props: {
        canEditPurchaseOrder: Boolean,
        clients: {
            type: Array,
            required: true
        },
        exchange: {
            type: Number,
            required: true
        },
        purchaseOrderProducts: {
            type: Array,
            required: false
        },
        purchaseOrder: {
            type: Object,
            required: false
        },
        productsAvailable: {
            type: Array,
            required: false
        },
        branchOfficesOrder: {
            type: Array,
            required: false
        }
    },
    data() {
        return {
            trmActual: this.exchange,
            orderConsecutive: '',
            requiredDeliveryDate: '',
            clientId: null,
            status: 'pending',
            contact: '',
            phone: '',
            products: [],
            availableProducts: [],
            branchOffices: [],
            observations: '',
            errors: {},
            trmCalculated: 0,
            fullPage: false,
            trmSaved: 0,
            attachment: null,
            attachmentUrl: this.purchaseOrder?.attachment ? `/storage/${this.purchaseOrder.attachment}` : null,
            errors: {},
            trmInitial: 0

        };
    },
    computed: {

        minDeliveryDate() {
            return new Date().toISOString().split('T')[0];
        },
        subtotal() {
            return this.products.reduce((sum, product) => sum + (product.price * product.quantity), 0);
        },
        iva() {
            return this.subtotal * 0.19;
        },
        reteica() {
            return this.subtotal * 0.00966;
        },
        total() {
            return this.subtotal; // + this.iva + this.reteica;
        },
        canAddProduct() {
            return true;
        },
    },
    methods: {
        setCustomTrm() {
            if (this.trmActual) {
                this.trmActual = parseFloat(this.trmActual);
                this.updateAllProductTotals();
            }
        },
        setActualTrm() {
            this.trmCalculated = Math.round(1 / this.exchange);
            this.updateAllProductTotals();
        },
        updateProductPrice(product) {
            const selectedProduct = this.availableProducts.find(p => p.id === product.product_id);
            if (selectedProduct) {
                product.price = selectedProduct.price;
                this.updateProductTotal(product);
            }
        },
        updateProductTotal(product) {
            product.total = product.price * product.quantity;
        },
        updateAllProductTotals() {
            this.products.forEach(product => {
                this.updateProductPrice(product);
            });
        },

        addProductRow() {
            let branchOfficeId = '';
            let productId = '';
            let productPrice = 0;

            if (this.branchOffices?.length > 0) {
                branchOfficeId = this.branchOffices[0].id;
            }

            if (this.availableProducts?.length > 0) {
                productId = this.availableProducts[0].id || '';
                productPrice = this.availableProducts[0].price || 0;
            }

            this.products.push({
                product_id: productId || '',
                price: productPrice || 0,
                quantity: 1,
                total: 0,
                branch_office_id: branchOfficeId || '',
            });
        },

        removeProduct(index) {
            this.products.splice(index, 1);
        },
        onClientChange() {
            this.clearAllFields();
            this.fetchClientBranchOffices(this.clientId);
            this.fetchClientProducts(this.clientId);
            this.fetchClient(this.clientId);
        },
        fetchClientBranchOffices(clientId) {
            let loader = this.$loading.show({
                container: this.fullPage ? null : this.$refs.formContainer,
            });
            axios.get(`/admin/purchase_orders/getClientBranchOffices/${clientId}`)
                .then(response => {
                    this.branchOffices = response.data;
                    loader.hide();
                })
                .catch(error => {
                    console.error('Error fetching branch offices:', error);
                    loader.hide();

                });
        },
        fetchClientProducts(clientId) {

            let loader = this.$loading.show({
                container: this.fullPage ? null : this.$refs.formContainer,
            });
            axios.get(`/admin/purchase_orders/getClientProducts/${clientId}`)
                .then(response => {
                    this.availableProducts = response.data;
                    loader.hide();

                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    loader.hide();

                });
        },
        fetchClient(clientId) {

            let loader = this.$loading.show({
                container: this.fullPage ? null : this.$refs.formContainer,
            });
            axios.get(`/admin/client/${clientId}`)
                .then(response => {
                    // this.availableProducts = response.data;
                    var data= response.data;
                    this.phone = data.phone;
                    this.contact=data.accounting_contact
                    this.observations =data.commercial_terms
                    loader.hide();

                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    loader.hide();

                });
        },
        clearAllFields() {
            this.products = [];
            this.status = 'pending';
            this.contact = '';
            this.phone = '';
            this.updateAllProductTotals();
        },
        onFileChange(event) {
            this.attachment = event.target.files[0];
        },

        // Handle attachment removal
        removeAttachment() {
            if (confirm('¿Estás seguro de que deseas eliminar este archivo adjunto?')) {
                const purchaseOrderId = this.purchaseOrder.id;  // Assuming you have the purchase order ID available

                axios.delete(`/admin/purchase-orders/${purchaseOrderId}/attachment`)
                    .then(response => {
                        this.attachment = null;
                        this.attachmentUrl = null;
                        this.$swal('Success', 'The attachment has been deleted successfully.', 'success');
                    })
                    .catch(error => {
                        console.error('Error deleting attachment:', error);
                        this.$swal('Error', 'Failed to delete the attachment.', 'error');
                    });
            }
        },
        async submitForm() {
            if (this.validateForm()) {
                let formData = new FormData();

                let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                });

                formData.append('order_consecutive', this.orderConsecutive);
                formData.append('required_delivery_date', this.requiredDeliveryDate);
                formData.append('client_id', this.clientId);
                formData.append('status', this.status);
                formData.append('contact', this.contact);
                formData.append('phone', this.phone);
                formData.append('observations', this.observations);
                formData.append('trm', this.trmCalculated);
                formData.append('trm_initial', this.trmSaved);
                //   formData.append('products', JSON.stringify(this.products));
                this.products.forEach((product, index) => {
                    formData.append(`products[${index}][product_id]`, product.product_id);
                    formData.append(`products[${index}][quantity]`, product.quantity);
                    formData.append(`products[${index}][branch_office_id]`, product.branch_office_id);
                });
                if (this.attachment) {
                    formData.append('attachment', this.attachment);
                }

                let urlToSend = '/admin/purchase_orders/store';
                if (this.purchaseOrder) {
                    urlToSend = `/admin/purchase_orders/${this.purchaseOrder.id}/update`;
                }

                try {
                    const response = await axios.post(urlToSend, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    });

                    const purchaseOrderId = response.data.purchase_order_id;
                    if (this.purchaseOrder == null) {
                        this.$swal('Se ha creado la orden de compra con éxito!', { icon: 'success' });

                        window.location.href = `/admin/purchase_orders/${purchaseOrderId}/edit`;
                    } else {
                        this.$swal('Se ha actualizado la orden de compra con éxito!', { icon: 'success' });
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                    if (error.response && error.response.data.errors) {
                        this.errors = error.response.data.errors;

                        let errorMessage = '<ul>';
                        for (const [field, messages] of Object.entries(this.errors)) {
                            messages.forEach(message => {
                                errorMessage += `<li>${message}</li>`;
                            });
                        }
                        errorMessage += '</ul>';

                        this.$swal({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
                        });
                    }
                }

                loader.hide();
            }
        },

        validateForm() {
            this.errors = {};
            let isValid = true;

            if (!this.orderConsecutive) {
                this.errors.order_consecutive = 'Este campo es requerido.';
                isValid = false;
            }

            if (!this.contact) {
                this.errors.contact = 'Este campo es requerido.';
                isValid = false;
            }

            if (!this.phone) {
                this.errors.phone = 'Este campo es requerido.';
                isValid = false;
            }

            if (!this.requiredDeliveryDate) {
                this.errors.required_delivery_date = 'Este campo es requerido.';
                isValid = false;
            }

            if (this.products.length === 0 || !this.products.some(product => product.product_id)) {
                alert('Debe agregar al menos un producto.');
                isValid = false;
            }

            return isValid;
        },
    },
    mounted() {
        this.trmCalculated = Math.round(1 / this.exchange);

        if (this.purchaseOrder) {

            this.orderConsecutive = this.purchaseOrder.order_consecutive;
            this.requiredDeliveryDate = this.purchaseOrder.required_delivery_date;
            this.clientId = this.purchaseOrder.client_id;
            this.status = this.purchaseOrder.status;
            this.contact = this.purchaseOrder.contact;
            this.phone = this.purchaseOrder.phone;
            this.availableProducts = this.productsAvailable;
            this.observations = this.purchaseOrder.observations;
            this.products = this.purchaseOrderProducts;
            this.branchOffices = this.branchOfficesOrder;
            this.trmCalculated = this.purchaseOrder.trm;

            this.products.forEach(element => {
                const selectedProduct = this.availableProducts.find(p => p.id === element.product_id);
                if (selectedProduct) {
                    element.price = selectedProduct.price;
                    this.updateProductTotal(element);
                }
            });
        }
        this.trmSaved = this.trmCalculated;
    },
};


</script>