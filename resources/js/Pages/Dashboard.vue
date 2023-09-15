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

const searchTermSeller = ref('');
const searchTermCustomer = ref('');
const customers = ref([]);
const sellers = ref([]);

const infoSeller = ref(
{
    name : '',
    phone: '',
    address: '',
    email: '',

});

const infoCustomer = ref(
{
    name : '',
    phone: '',
    address: '',
    email: '',

});

watch(searchTermCustomer, (newTerm) => {
    customers.value = [];
    if (newTerm.length < 3) return;
    axios.get('/search-customer', { params: { term: newTerm } })
        .then(response => {
            customers.value = response.data;
        })
        .catch(error => {
            console.log(error);
        });
});

watch(searchTermSeller, (newTerm) => {
    sellers.value = [];
    if (newTerm.length < 3) return;
    axios.get('/search-seller', { params: { term: newTerm } })
        .then(response => {
            sellers.value = response.data;
        })
        .catch(error => {
            console.log(error);
        });
});



const selectCustomer = (customer) => {
    formData.customer_id = customer.id;
    searchTermCustomer.value = '';
    customers.value = [];
    infoCustomer.value = {...customer};
};

const selectSeller = (seller) => {
    formData.seller_id = seller.id;
    searchTermSeller.value = '';
    sellers.value = [];
    infoSeller.value = {...seller};
};


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-4 p-4 flex flex-col md:flex-row gap-2 ">
            <!-- info customer and seller -->
            <div class="max-w-7xl w-full md:lg:w-3/5 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 text-gray-900">Inicio Teraminer</div>
                    <!-- <Link class="inline-flex items-center px-4 py-2 m-5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :href="route('dashboard')">Factura</Link> -->

                    <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-4 sm:px-20 bg-white border-b border-gray-200">
                            <form @submit.prevent="handleInvoiceStore" method="POST">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <InputLabel for="customer_id" value="Cliente" />
                                        <TextInput id="customer_id" class="block mt-1 w-full" type="text" name="customer_id" v-model="searchTermCustomer"  required autofocus autocomplete="false"/>
                                        <InputError class="mt-2" :message="formData.errors.customer_id" />
                                        <div class="flex flex-col">
                                            <ul v-if="customers.length > 0" class="z-10 bg-white mt-2 rounded-md shadow-lg" style="width: 100%; left: 0;">
                                                <li v-for="customer in customers" :key="customer.id" class="p-2 cursor-pointer hover:bg-blue-200" @click="selectCustomer(customer)">{{ customer.name }}</li>
                                            </ul>
                                        </div>

                                    </div>
                                    <div>
                                        <InputLabel for="seller_id" value="Vendedor" />
                                        <TextInput id="seller_id" class="block mt-1 w-full" type="text" name="seller_id" v-model="searchTermSeller"  required autofocus autocomplete="false"/>
                                        <InputError class="mt-2" :message="formData.errors.seller_id" />
                                        <div class="flex flex-col">
                                            <ul v-if="sellers.length > 0" class="z-10 bg-white mt-2 rounded-md shadow-lg" style="width: 100%; left: 0;">
                                                <li v-for="seller in sellers" :key="seller.id" class="p-2 cursor-pointer hover:bg-blue-200" @click="selectSeller(seller)">{{ seller.name }}</li>
                                            </ul>
                                        </div>

                                    </div>
                                    <!-- <div>
                                        <InputLabel for="name" value="Nombre" />
                                        <TextInput id="name" class="block mt-1 w-full" type="text" name="name" v-model="formData.name"  required />
                                    </div> -->
                                </div>


                                    <!-- <div class="grid grid-cols-2 gap-4">
                                        <div class="mt-4">
                                            <InputLabel for="unit_price" value="Precio Unitario ($)" />
                                            <TextInput id="unit_price" class="block mt-1 w-full" type="text" name="unit_price" v-model="formData.unit_price"  required />
                                        </div>
                                    </div> -->

                                <!-- <div class="flex items-center justify-end mt-4">
                                    <Link :href="route('products.index')" class="ml-4">
                                        Volver
                                    </Link>
                                    <PrimaryButton class="ml-4">
                                        Crear
                                    </PrimaryButton>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- info invoice -->
            <div class="max-w-7xl w-full sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 text-gray-900">Inicio Teraminer</div>
                    <!-- <Link class="inline-flex items-center px-4 py-2 m-5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :href="route('dashboard')">Factura</Link> -->

                    <div class="w-full bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-4 sm:px-20 bg-white border-b border-gray-200">
                            <div class="flex justify-between gap-6 items-center">
                                <div>
                                    <span class="bg-[#6A3989] text-md font-bold text-white p-2 rounded-lg">Vendedor</span>
                                    <div class="p-4 mt-4 border-dashed border-2 border-[#6A3989]">
                                        <p class="flex flex-col justify-center">
                                            <span class="font-medium text-md" v-text="infoSeller.name"></span>
                                            <span class="font-medium text-md" v-text="infoSeller.phone"></span>
                                            <span class="font-medium text-md" v-text="infoSeller.address"></span>
                                            <span class="font-medium text-md" v-text="infoSeller.email"></span>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <span class="bg-[#6A3989] text-md font-bold text-white p-2 rounded-lg">Cliente</span>
                                    <div class="p-4 mt-4 border-dashed border-2 border-[#6A3989]">
                                        <p class="flex flex-col justify-center">
                                            <span class="font-medium text-md" v-text="infoCustomer.name"></span>
                                            <span class="font-medium text-md" v-text="infoCustomer.phone"></span>
                                            <span class="font-medium text-md" v-text="infoCustomer.address"></span>
                                            <span class="font-medium text-md" v-text="infoCustomer.email"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
