<template>
    <DataTable :value="productsArray" :class="p-datatable-lg" tableStyle="min-width: 50rem" class="p-10">
            <Column field="name" header="Nombre"></Column>
            <Column field="code_number" header="Codigo"></Column>
            <Column field="price" header="Precio"></Column>
            <Column field="quantity" header="Cantidad" :showFilterMenu="false" :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
                <template #body="{ data }">
                    <TextInput id="quantity" class="block mt-1 w-full" type="number" name="quantity" @change="updateTotal(data,$event)" v-model="data.quantity" required autofocus autocomplete="false"/>
                </template>
            </Column>
            <Column field="total_product" header="Total" :showFilterMenu="false" :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
                <template #body="{ data }">
                    <span v-text="data.total_product"></span>
                </template>
            </Column>
            <Column field="total_product" header="Total" :showFilterMenu="false" :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
                <template #body="{ data }">
                    <!-- button delete -->
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="productsArray.splice(productsArray.indexOf(data), 1)">Eliminar</button>
                </template>
            </Column>
        </DataTable>
</template>

<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
/* import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'; */
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    productsArray: {
        type: Array,
        required: true
    },
    invoice: {
        type: Object,
        required: true
    }
});

const updateTotal = (data,event) => {
    if(event.target.value < 1){
        event.target.value = 1;
    }
    props.invoice.totalProduct = event.target.value * data.unit_price;
    data.total_product = event.target.value * data.unit_price;
    //suma()
}

const suma = () => {
    let total = parseInt(0);
    let sub = parseInt(0);
    invoice.productsArray.forEach((product) => {
        total += product.total_product - (product.total_product * discount / 100);
        sub += product.total_product;

    });

    invoice.totalInvoice = total;
    invoice.subTotal = sub;
    invoice.subTotalFormat = '$'+sub;
    discountFormat = '- $'+sub * discount / 100;

};


</script>
