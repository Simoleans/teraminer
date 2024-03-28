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

const blankValidation = ref('_blank');



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
  }else{
    blankValidation.value = '_self';
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
                        :globalFilterFields="['correlative', 'total']" sortField="correlative" :sortOrder="-1" >
                            <template #header>
                                <div class="flex justify-content-end">
                                    <span class="p-input-icon-left">
                                        <i class="pi pi-search" />
                                        <InputText v-model="filters['global'].value" placeholder="Buscar (Correlativo)" />
                                    </span>
                                </div>
                            </template>
                            <Column field="correlative" header="# Factura">
                                <template #body="{data}">
                                    <span>#{{ data.invoice.correlative }}</span>
                                </template>
                            </Column>
                            <Column style="min-width:8rem" header="Imprimir Garantia">
                                <template #body="{data}">
                                    <div class="flex justify-center">
                                        <a :href="route('garanty.pdf',data.id)" :target="blankValidation" class="flex justify-center w-full px-4 py-2 font-bold text-white bg-red-400 rounded hover:bg-red-500">
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
