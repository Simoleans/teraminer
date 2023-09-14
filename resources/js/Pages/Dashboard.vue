<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,Link,useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, watch } from 'vue';
import axios from 'axios';

const handleInvoiceStore = () => {
    Swal.fire({
        title: 'Creado',
        text: 'Se ha creado la factura',
        icon: 'success',
        confirmButtonText: 'Aceptar'
    })
}

const formData = useForm({
    customer_id: '',
    name : '',
    unit_price: '',
});

const searchTerm = ref('');
const customers = ref([]);
//let shouldExecuteWatch = true;

watch(searchTerm, (newTerm) => {
    customers.value = [];
    if (newTerm.length < 3) return;
    //if(formData.customer_id) return false;
  axios.get('/search-customer', { params: { term: newTerm } })
    .then(response => {
      customers.value = response.data;
      shouldExecuteWatch = false;
      //console.log(response.data);
    })
    .catch(error => {
      console.log(error);
    });
});




const selectCustomer = (customer) => {
  // Desactivar el watch si la variable de control es falsa
  //shouldExecuteWatch = false;

    formData.customer_id = customer.id;
    searchTerm.value = customer.name;
    customers.value = [];
    console.log(formData)
    //return false;
};


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Inicio Teraminer</div>
                    <!-- <Link class="inline-flex items-center px-4 py-2 m-5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :href="route('dashboard')">Factura</Link> -->

                    <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-8 sm:px-20 bg-white border-b border-gray-200">
                            <form @submit.prevent="handleInvoiceStore" method="POST">
                                <div class="grid grid-cols-2 gap-10">
                                    <div class="mt-4">
                                        <InputLabel for="customer_id" value="Cliente" />
                                        <TextInput id="customer_id" class="block mt-1 w-full" type="text" name="customer_id" v-model="searchTerm"  required autofocus />
                                        <InputError class="mt-2" :message="formData.errors.customer_id" />
                                        <div class="flex flex-col">
                                            <ul v-if="customers.length > 0" class="z-10 bg-white mt-2 rounded-md shadow-lg" style="width: 100%; left: 0;">
                                                <li v-for="customer in customers" :key="customer.id" class="p-2 cursor-pointer hover:bg-blue-200" @click="selectCustomer(customer)">{{ customer.name }}</li>
                                            </ul>
                                        </div>

                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="name" value="Nombre" />
                                        <TextInput id="name" class="block mt-1 w-full" type="text" name="name" v-model="formData.name"  required />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="unit_price" value="Precio Unitario ($)" />
                                        <TextInput id="unit_price" class="block mt-1 w-full" type="text" name="unit_price" v-model="formData.unit_price"  required />
                                    </div>
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <Link :href="route('products.index')" class="ml-4">
                                        Volver
                                    </Link>
                                    <PrimaryButton class="ml-4">
                                        Crear
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
