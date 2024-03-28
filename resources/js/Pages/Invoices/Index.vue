<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link,router  } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { getMonitor } from "consulta-dolar-venezuela";
import { ref,onMounted } from 'vue';
import ModalShow from '@/Components/Invoice/ModalShow.vue';
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import axios from 'axios';
import Swal from 'sweetalert2'

const bitcoinPrice = ref(0);

const fetchBitcoinPrice = async () => {
  try {
    const response = await axios.get('https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd');
    bitcoinPrice.value = response.data.bitcoin.usd;
  } catch (error) {
    console.error('Error al obtener el precio del Bitcoin:', error);
  }
};

onMounted(fetchBitcoinPrice);



const dolar = ref(false); /*Valor del dólar en EnParaleloVzla*/
const dolarBCV = ref(false); /*Valor del dólar en BCV*/

// get api fetch https://pydolarvenezuela-api.vercel.app/api/v1/dollar/unit/enparalelovzla
fetch('https://pydolarvenezuela-api.vercel.app/api/v1/dollar/unit/enparalelovzla')
    .then(response => response.json())
    .then(data => {
        dolar.value = data.price;
    }); /*Obtener el valor del dólar en EnParaleloVzla*/

//https://pydolarvenezuela-api.vercel.app/api/v1/dollar?page=bcv api
fetch('https://pydolarvenezuela-api.vercel.app/api/v1/dollar?page=bcv')
    .then(response => response.json())
    .then(data => {
        dolarBCV.value = data.monitors.usd.price;
    }); /*Obtener el valor del dólar en BCV*/


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

//total to btc
const totalToBTC = (value) => {
    const total = value / bitcoinPrice.value;

    return total.toFixed(3);
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


const requestDelete = (id) => {
    router.put(route("invoices.update",id), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => console.log("onSuccess"),
    });
};
const swalWithBootstrapButtons = Swal.mixin({
    buttonsStyling: true
})

const handleDelete = (id) => {
    swalWithBootstrapButtons.fire({
        title: '¿Estas seguro que quieres anular esta factura?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, estoy seguro.',
        cancelButtonText: 'No, no quiero.',
        reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            requestDelete(id)
        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
            'Cancelado',
            'No se ha anulado la Factura.',
            'error'
            )
        }
    })
};

</script>

<template>
    <Head title="Facturas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Facturas</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <div>
                            <span>Dolar Paralelo: </span> <strong>{{ formatNumberVzla(dolar) }}</strong><br>
                            <span>Dolar BCV: </span> <strong>{{ formatNumberVzla(dolarBCV) }}</strong><br>
                            <span>Bitcoin: </span> <strong>{{ formatNumber(bitcoinPrice) }}</strong>

                        </div>
                        <Link :href="route('dashboard')" class="px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">
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
                                        <!-- <Link :href="route('invoices.edit',data.id)" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                            <i class="pi pi-pencil"></i>
                                        </Link> -->
                                        <button v-if="data.status == 1" title="Anular Factura"  @click="handleDelete(data.id)" class="text-white bg-red-500 hover:bg-red-500/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2">
                                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <span v-else class="px-4 py-2 text-white bg-red-500 rounded-lg">Anulada</span>
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
                                        <a :href="route('invoices.pdf',data.id)" target="_blank" class="px-4 py-2 font-bold text-white bg-red-400 rounded hover:bg-red-500">
                                            <i class="pi pi-print"></i>
                                        </a>
                                    </div>
                                    <!-- <div class="flex justify-between">
                                        <a :href="route('invoices.garanty',data.id)" target="_blank" class="px-4 py-2 font-bold text-white bg-red-500 rounded hover:bg-red-700">
                                            <i class="pi pi-print"></i>
                                        </a>
                                    </div> -->
                                </template>
                            </Column>
                            <Column field="subtotal" header="Sub-Total">
                                <template #body="{data}">
                                    <span class="text-xl font-bold">{{ formatNumber(data.subtotal) }}</span><br>
                                    <span v-if="dolar">{{ formatNumberVzla(parseFloat(dolar) * parseFloat(data.subtotal)) }}</span><br>
                                </template>
                            </Column>
                            <Column field="discount" header="Descuento">
                                <template #body="{data}">
                                    <span v-if="data.discount > 0" class="text-red-600 font-sm">{{ convertDiscount(data.discount) }}%</span><br>
                                    <span v-if="data.discount > 0" class="font-bold">{{ totalDiscount(data.discount,data.subtotal) }}</span>
                                    <span v-else class="font-bold text-green-600">N/T</span>
                                </template>
                            </Column>
                            <Column field="total" header="Total">
                                <template #body="{data}">
                                    <span class="text-xl font-bold">{{ formatNumber(data.total) }}</span><br>
                                    <span>{{ formatNumberVzla(parseFloat(dolar) * parseFloat(data.total)) }}</span><br>
                                    <span v-if="bitcoinPrice">{{  totalToBTC(data.subtotal)  }} BTC</span>
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
