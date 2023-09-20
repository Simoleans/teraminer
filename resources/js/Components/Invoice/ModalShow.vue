<template>

        <Modal :show="modalShow" @close="closeModal" :maxWidth="xl">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ title }}
                </h2>

                <DataTable :value="props.customers" tableStyle="min-width: 50rem" paginator :rows="5" v-if="props.title == 'Clientes'">
                            <Column field="id_card_number" header="Cédula/RIF"></Column>
                            <Column field="name" header="Nombre"></Column>
                            <Column field="address" header="Direcciòn"></Column>
                            <Column field="phone" header="Telefono"></Column>
                            <Column field="email" header="Email"></Column>
                            <template #footer> Total : {{ props.customers ? props.customers.length : 0 }} Clientes. </template>
                        </DataTable>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />


                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="$emit('close')"> Cerrar </SecondaryButton>
                </div>
            </div>
        </Modal>
</template>

<script setup>
import { defineEmits, ref } from 'vue';
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
});


const closeModal = () => {
  props.modalShow = false;


};

</script>
