<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link,router  } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref,onMounted } from 'vue';
import ModalGaranty from '@/Pages/Garanty/ModalGaranty.vue';
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Swal from 'sweetalert2'



const props = defineProps({
  garanties: {
    type: Object,
    required: true
  },
  flash: {
    type: String,
  },
  productsData: {
    type: Object,
  },
});


onMounted(() => {
  if (props.flash.error) {
    Swal.fire({
      title: 'Error',
      text: props.flash.error,
      icon: 'error',
      confirmButtonText: 'Aceptar'
    }).then(() => {
       openModal(props.productsData);
    });
  }
});

const modalShow = ref(false);
const dataGaranty = ref([]);

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

const openModal = (garantyProducts) => {
    modalShow.value = true;
    dataGaranty.value = garantyProducts;
};


const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS},
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

//number format 2000 => $2.000
const formatNumber = (value) => {
    return new Intl.NumberFormat("es-CL", {
        style: "currency",
        currency: "CLP",
    }).format(value);
};

const targetHREF = (products) => {

    //search json products all key serial is null return _self else _blank
    const isBlank = products.some((product) => product.serial === null);

    return isBlank ? "_self" : "_blank";

};

const previewGaranty = (garantyData) => {
    const url = route('garanty.preview', garantyData.id); // Asegúrate de tener una ruta definida para esto
    window.open(url, 'Preview', 'width=794,height=900');
};


</script>

<template>
    <Head title="Facturas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Garantías</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <DataTable v-model:filters="filters" paginator :rows="5" :value="props.garanties" tableStyle="min-width: 50rem" filterDisplay="col"
                        :globalFilterFields="['invoice.correlative', 'invoice.customer.name']" sortField="correlative" :sortOrder="-1" >
                            <template #header>
                                <div class="flex justify-content-end">
                                    <span class="p-input-icon-left">
                                        <i class="pi pi-search" />
                                        <InputText v-model="filters['global'].value" placeholder="Buscar (Correlativo,Cliente)" />
                                    </span>
                                </div>
                            </template>
                            <Column field="correlative" header="# Factura">
                                <template #body="{data}">
                                    <span>#{{ data.invoice.correlative }}</span>
                                </template>
                            </Column>
                            <Column field="correlative" header="Cliente">
                                <template #body="{data}">
                                    <strong>{{ data.invoice.customer.name }}</strong>
                                </template>
                            </Column>
                            <Column style="min-width:8rem" header="Previsualizar Garantía">
                                <template #body="{data}">
                                    <div class="flex justify-center">
                                        <PrimaryButton @click="previewGaranty(data)" title="Previsualizar Garantía">
                                            <i class="pi pi-eye"></i>
                                        </PrimaryButton>
                                    </div>
                                </template>
                            </Column>
                            <Column style="min-width:8rem" header="Imprimir Garantia">
                                <template #body="{data}">
                                    <div class="flex justify-center">
                                        <a :href="route('garanty.pdf',data.id)" :target="targetHREF(data.garanty_products)" class="flex justify-center w-full px-4 py-2 font-bold text-white bg-red-400 rounded hover:bg-red-500">
                                            <i class="pi pi-print"></i>
                                        </a>
                                    </div>
                                </template>
                            </Column>
                            <Column field="total" header="Total">
                                <template #body="{data}">
                                    <span class="text-xl font-bold">{{ formatNumber(data.invoice.total) }}</span><br>
                                </template>
                            </Column>
                            <Column style="min-width:8rem" header="Productos">
                                <template #body="{data}">
                                    <div class="flex justify-between">
                                        <PrimaryButton class="mt-4" @click="openModal(data.garanty_products)" title="Ver Productos asociados esta factura" ><i class="pi pi-eye" /></PrimaryButton>
                                    </div>
                                </template>
                            </Column>
                            <!-- <template #footer> Total : {{ props.invoices ? props.invoices.length : 0 }} Facturas. </template> -->
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>

        <ModalGaranty
            :modalShow="modalShow"
            @close="modalShow = false"
            :garantyProduct="dataGaranty"
        />
    </AuthenticatedLayout>
</template>
