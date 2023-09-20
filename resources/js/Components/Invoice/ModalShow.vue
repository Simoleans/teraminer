<template>

        <Modal :show="modalShow" @close="closeModal" :maxWidth="xl">
            <div class="p-6 mb-8">
                <h2 class="text-lg font-medium text-gray-900 ">
                    {{ title }}
                </h2>

                <DataTable :value="data" tableStyle="min-width: 50rem" paginator :rows="5" v-if="title == 'Clientes' || title == 'Vendedores'">
                    <Column field="id_card_number" header="Cédula/RIF"></Column>
                    <Column field="name" header="Nombre"></Column>
                    <Column field="address" header="Direcciòn"></Column>
                    <Column field="phone" header="Telefono"></Column>
                    <Column field="email" header="Email"></Column>
                    <template #footer> Total : {{ data ? data.length : 0 }} {{ title }} </template>
                </DataTable>

                <DataTable :value="data" tableStyle="min-width: 50rem" :rows="5" v-if="title == 'Productos'">
                    <Column field="code_number" header="Codigo"></Column>
                    <Column field="name" header="Nombre"></Column>
                    <Column field="unit_price" header="Precio Unitario"></Column>
                    <template #footer> Total : {{ props.products ? props.products.length : 0 }} Productos. </template>
                </DataTable>

                <DataTable :value="data" :rows="5" tableStyle="min-width: 50rem" v-if="title == 'Envios'">
                    <Column field="name" header="Nombre"></Column>
                    <Column field="type" header="Tipo"></Column>
                    <Column field="route" header="Ruta"></Column>
                    <template #footer> Total : {{ props.shipments ? props.shipments.length : 0 }} Datos. </template>
                </DataTable>

                <div class="mt-6 flex justify-end">
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
  title: String,
  customers : Object,
  sellers : Object,
  products : Object,
  shipments : Object,
});

const data = ref([]);
const dataProducts = ref([]);

console.log(props.title);

watch(() => props.title, (newValue, oldValue) => {
    data.value = newValue == 'Clientes' ? props.customers :  newValue == 'Vendedores' ? props.sellers : newValue == 'Envios' ? props.shipments : props.products;
    console.log(newValue,data.value);
});




const closeModal = () => {
  props.modalShow = false;


};

</script>
