<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link  } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { getMonitor } from "consulta-dolar-venezuela";
import { ref } from 'vue';
import ModalShow from '@/Components/Invoice/ModalShow.vue';
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';



const dolar = ref(false); /*Valor del dólar en EnParaleloVzla*/

getMonitor("EnParaleloVzla", "price", false).then($ =>{
    dolar.value = $.enparalelovzla.price;
}); /*Obtener el valor del dólar en EnParaleloVzla*/


const props = defineProps({
  invoices: {
    type: Object,
    required: true
  }
});

const modalShow = ref(false);
const dataInvoice = ref([]);


//number format 2000 => $2.000
const formatNumber = (value) => {
    return new Intl.NumberFormat("es-CL", {
        style: "currency",
        currency: "CLP",
    }).format(value);
};

//format number venezuelan
const formatNumberVzla = (value) => {
    return new Intl.NumberFormat("es-CL", {
        style: "currency",
        currency: "VES",
    }).format(value);
};

//function convert discount
const convertDiscount = (value) => {
    if (Number.isInteger(parseFloat(value))) {
    return parseInt(value);
  } else {
    return parseFloat(value);
  }
};

//function total discount
const totalDiscount = (discount,subtotal) => {
    return formatNumber(subtotal * discount / 100);

};

const openModal = (invoice) => {
    modalShow.value = true;
    dataInvoice.value = invoice;
};


const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

</script>

<template>
    <Head title="Facturas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Facturas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-end p-4">
                        <Link :href="route('dashboard')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Crear
                        </Link>
                    </div>
                    <div class="p-6 text-gray-900">
                        <DataTable v-model:filters="filters" paginator :rows="5" :value="props.invoices" tableStyle="min-width: 50rem" filterDisplay="col"
                        :globalFilterFields="['correlative', 'total']" sortField="correlative" :sortOrder="-1" >
                            <template #header>
                                <div class="flex justify-content-end">
                                    <span class="p-input-icon-left">
                                        <i class="pi pi-search" />
                                        <InputText v-model="filters['global'].value" placeholder="Buscar (Correlativo)" />
                                    </span>
                                </div>
                            </template>
                            <Column style="min-width:8rem" header="Acción">
                                <template #body="{data}">
                                    <div class="flex justify-between">
                                        <!-- edit button -->
                                        <Link :href="route('invoices.edit',data.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            <!-- icon pencil -->
                                            <i class="pi pi-pencil"></i>
                                        </Link>
                                    </div>
                                </template>
                            </Column>
                            <Column field="correlative" header="Nùmero">
                                <template #body="{data}">
                                    <span>#{{ data.correlative }}</span>
                                </template>
                            </Column>
                            <Column style="min-width:8rem" header="Imprimir">
                                <template #body="{data}">
                                    <div class="flex justify-between">
                                        <!-- edit button -->
                                        <a :href="route('invoices.pdf',data.id)" target="_blank" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            <!-- icon print -->
                                            <i class="pi pi-print"></i>
                                        </a>
                                    </div>
                                    <div class="flex justify-between">
                                        <!-- edit button -->
                                        <a :href="route('invoices.garanty',data.id)" target="_blank" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            <!-- icon print -->
                                            <i class="pi pi-print"></i>
                                        </a>
                                    </div>
                                </template>
                            </Column>
                            <Column field="subtotal" header="Sub-Total">
                                <template #body="{data}">
                                    <span class="text-xl font-bold">{{ formatNumber(data.subtotal) }}</span><br>
                                    <span v-if="dolar">{{ formatNumberVzla(parseFloat(dolar) * parseFloat(data.subtotal)) }}</span>
                                </template>
                            </Column>
                            <Column field="discount" header="Descuento">
                                <template #body="{data}">
                                    <span v-if="data.discount > 0" class="text-red-600 font-sm">{{ convertDiscount(data.discount) }}%</span><br>
                                    <span v-if="data.discount > 0" class="font-bold">{{ totalDiscount(data.discount,data.subtotal) }}</span>
                                    <span v-else class="text-green-600 font-bold">N/T</span>
                                </template>
                            </Column>
                            <Column field="total" header="Total">
                                <template #body="{data}">
                                    <span class="text-xl font-bold">{{ formatNumber(data.total) }}</span><br>
                                    <span>{{ formatNumberVzla(parseFloat(dolar) * parseFloat(data.total)) }}</span>
                                </template>
                            </Column>
                            <Column style="min-width:8rem" header="Productos">
                                <template #body="{data}">
                                    <div class="flex justify-between">
                                        <PrimaryButton class="mt-4" @click="openModal(data)" title="Ver Productos asociados esta factura" ><i class="pi pi-eye" /></PrimaryButton>
                                    </div>
                                </template>
                            </Column>
                            <Column field="customer_id" header="Cliente">
                                <template #body="{data}">
                                    <span>{{ data.customer.name }}</span>
                                </template>
                            </Column>
                            <Column field="seller_id" header="Vendedor">
                                <template #body="{data}">
                                    <span>{{ data.seller.name }}</span>
                                </template>
                            </Column>
                            <Column field="shipment_id" header="Tipo de envio">
                                <template #body="{data}">
                                    <span>{{ data.shipment?.name ?? 'N/T' }}</span>
                                </template>
                            </Column>
                            <template #footer> Total : {{ props.invoices ? props.invoices.length : 0 }} Facturas. </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>

        <ModalShow
            :modalShow="modalShow"
            @close="modalShow = false"
            :invoice="dataInvoice"
        />
    </AuthenticatedLayout>
</template>
