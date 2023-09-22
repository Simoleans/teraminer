<template>

        <Modal :show="modalShow" @close="closeModal" :maxWidth="xl">
            <div class="p-6 mb-8">
                <h2 class="text-lg font-medium text-gray-900 ">
                    Productos de <span class="font-bold">#{{ invoice.correlative }}</span>
                </h2>

                <DataTable :value="invoice.products" tableStyle="min-width: 50rem" paginator :rows="5">
                    <Column field="code_number" header="Codigo"></Column>
                    <Column field="name" header="Nombre"></Column>
                    <Column field="quantity" header="Cantidad"></Column>
                    <Column field="unit_price" header="Precio Unitario">
                        <template #body="slotProps">
                            {{ formatNumber(slotProps.data.unit_price) }}
                        </template>
                    </Column>
                    <Column field="total_product" header="Total">
                        <template #body="slotProps">
                            {{ formatNumber(slotProps.data.total_product) }}
                        </template>
                    </Column>
                    <template #footer> Total : {{ invoice.products ? invoice.products.length : 0 }} Productos. </template>
                </DataTable>

                <div class="mt-3 flex justify-end">
                    <SecondaryButton @click="$emit('close')"> Cerrar </SecondaryButton>
                </div>
            </div>
        </Modal>
</template>

<script setup>
import { defineEmits, ref, watch } from 'vue';
import Dialog from 'primevue/dialog';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

const props = defineProps({
  modalShow: Boolean,
  invoice: Object,
  /* title: String,
  customers : Object,
  sellers : Object,
  products : Object,
  shipments : Object, */
});

 // Imprimirá el objeto como un array


/* const data = ref([]);
const dataProducts = ref([]);

console.log(props.title);

watch(() => props.title, (newValue, oldValue) => {
    data.value = newValue == 'Clientes' ? props.customers :  newValue == 'Vendedores' ? props.sellers : newValue == 'Envios' ? props.shipments : props.products;
    console.log(newValue,data.value);
}); */

const formatNumber = (value) => {
    return new Intl.NumberFormat("es-CL", {
        style: "currency",
        currency: "CLP",
    }).format(value);
};


const closeModal = () => {
  props.modalShow = false;


};

</script>
