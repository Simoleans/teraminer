<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,Link,useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, watch,computed } from 'vue';
import axios from 'axios';
import Info from '@/Components/Invoice/Info.vue';
import Facturacion from '@/Components/Invoice/Facturacion.vue';
import TablaFacturacion from '@/Components/Invoice/TablaFacturacion.vue';


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
const searchProduct = ref('');

const customers = ref([]);
const sellers = ref([]);
const products = ref('');
/* const productsArray = ref([]);


const totalInvoice = ref('');
const subTotal = ref('');
const subTotalFormat = ref('');
const totalProduct = ref(''); */

const invoice = ref({
    subTotalFormat: '',
    discountFormat: '',
    totalProduct: '',
    totalInvoice: '',
    discount: '',
    subTotal: '',
    productsArray: [],
})

//const discount = ref(0);
//const discountFormat = ref();






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


watch(searchProduct, (newTerm) => {
    customers.value = [];
    if (newTerm.length < 3) return;
    axios.get('/search-product', { params: { term: newTerm } })
        .then(response => {
            products.value = response.data;
        })
        .catch(error => {
            console.log(error);
        });
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
  infoCustomer.value = { ...customer };

};

const selectSeller = (seller) => {
  formData.seller_id = seller.id;
  searchTermSeller.value = '';
  sellers.value = [];
  infoSeller.value = { ...seller };
};

//productos
const addProduct = (product) => {
  const index = invoice.value.productsArray.findIndex((p) => p.name === product.name);

  if (index >= 0) {
    invoice.value.productsArray[index].quantity += 1;
    //console.log(invoice.value.productsArray[index].quantity,product,product.quantity);
    invoice.value.productsArray[index].total_product = parseInt(invoice.value.productsArray[index].quantity)  * parseInt(product.unit_price);
  } else {
    product.quantity = 1;
    product.total_product = parseInt(product.unit_price);
    invoice.value.productsArray.push(product);
  }

  searchProduct.value = '';
  products.value = [];
  suma();
};

const suma = () => {
    let total = parseInt(0);
    let sub = parseInt(0);
    invoice.value.productsArray.forEach((product) => {
        total += product.total_product - (product.total_product *  invoice.value.discount / 100);
        sub += product.total_product;

    });

    invoice.value.totalInvoice = total;
    invoice.value.subTotal = sub;
    invoice.value.subTotalFormat = '$'+sub;
    invoice.value.discountFormat = '- $'+sub * invoice.value.discount / 100;


};

//watch discount
watch(invoice.value, (newTerm) => {
    suma();
});


//computed total
const totalInvoiceGeneral = computed(() => {
    let total = parseInt(0);
    invoice.value.productsArray.forEach((product) => {
        total += product.total_product - (product.total_product * invoice.value.discount / 100);
    });

    return '$'+total;
});

//computed subtotal
const subTotalGeneral = computed(() => {
    let total = parseInt(0);
    invoice.value.productsArray.forEach((product) => {
        total += product.total_product;
    });

    return '$'+total;
});


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
                    <div class="p-4 text-gray-900">Cliente/Vendedor</div>
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
                                                <li v-for="customer in customers" :key="customer.id" class="p-2 cursor-pointer hover:bg-blue-200" @click="selectCustomer(customer)">{{ customer.name }} - {{ customer.id_card_number}}</li>
                                            </ul>
                                        </div>

                                    </div>
                                    <div>
                                        <InputLabel for="seller_id" value="Vendedor" />
                                        <TextInput id="seller_id" class="block mt-1 w-full" type="text" name="seller_id" v-model="searchTermSeller"  required autocomplete="false"/>
                                        <InputError class="mt-2" :message="formData.errors.seller_id" />
                                        <div class="flex flex-col">
                                            <ul v-if="sellers.length > 0" class="z-10 bg-white mt-2 rounded-md shadow-lg" style="width: 100%; left: 0;">
                                                <li v-for="seller in sellers" :key="seller.id" class="p-2 cursor-pointer hover:bg-blue-200" @click="selectSeller(seller)">{{ seller.name }} - {{ seller.id_card_number }}</li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
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
            <Info :infoSeller="infoSeller" :infoCustomer="infoCustomer"/>
        </div>

        <div class="py-4 p-4 w-full flex flex-col md:flex-row gap-2 ">
            <!-- info customer and seller -->
            <div class="w-full sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 text-gray-900">Productos</div>
                    <div class="flex  md:lg:flex-row justify-between flex-col-reverse">
                        <div class="p-10 grow">
                            <InputLabel for="product" value="Producto" />
                            <TextInput id="product" class="block mt-1 w-full" type="text" name="product" v-model="searchProduct"  required autocomplete="false"/>
                            <div class="flex flex-col">
                                <ul v-if="products.length > 0" class="z-10 bg-white mt-2 rounded-md shadow-lg" style="width: 100%; left: 0;">
                                    <li v-for="product in products" :key="product.id" class="p-2 cursor-pointer hover:bg-blue-200" @click="addProduct(product)">{{ product.name }} - {{ product.code_number}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="p-10">
                            <InputLabel for="discount" value="Descuento (%)" />
                            <TextInput id="discount" class="block mt-1 w-full" type="text" name="discount" v-model="invoice.discount"  required autocomplete="false"/>
                        </div>
                        <Facturacion :subTotalGeneral="subTotalGeneral" :totalInvoiceGeneral="totalInvoiceGeneral" :discount="discount" :invoice="invoice" />
                    </div>
                    <TablaFacturacion :productsArray="invoice.productsArray" :invoice="invoice" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
