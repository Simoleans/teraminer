<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link,router  } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { getMonitor } from "consulta-dolar-venezuela";
import { ref } from 'vue';
import ModalShow from '@/Components/Invoice/ModalShow.vue';


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


/* const requestDelete = (id) => {
    router.delete(route("invoices.destroy",id), {
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
        title: '¿Estas seguro que quieres eliminar este producto?',
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
            'No se ha eliminado el producto.',
            'error'
            )
        }
    })
}; */

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
                        <DataTable :value="props.invoices" tableStyle="min-width: 50rem">
                            <Column field="correlative" header="Nùmero">
                                <template #body="{data}">
                                    <span>#{{ data.correlative }}</span>
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
                                       <!--  <Link :href="route('invoices.edit',data.id)" class="inline-block px-6 py-2 border-2 border-blue-600 text-blue-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                                            <i class="pi pi-pencil" />
                                        </Link> -->
                                        <!-- //button primary show products -->

                                        <PrimaryButton class="mt-4" @click="openModal(data)" title="Ver Productos asociados esta factura" ><i class="pi pi-eye" /></PrimaryButton>

                                       <!--  <button type="button" @click="handleDelete(data.id)" class="inline-block px-6 py-2 border-2 border-red-600 text-red-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                                            <i class="pi pi-trash" />
                                        </button> -->
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
