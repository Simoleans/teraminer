<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,Link,useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, watch,computed } from 'vue';
import Info from '@/Components/Invoice/Info.vue';
import Facturacion from '@/Components/Invoice/Facturacion.vue';
import TablaFacturacion from '@/Components/Invoice/TablaFacturacion.vue';
import Dropdown from 'primevue/dropdown';
import Swal from 'sweetalert2'


const props = defineProps({
    shipments: {
        type: Object,
        required: true
    },
    customers: {
        type: Object,
        required: true
    },
    sellers: {
        type: Object,
        required: true
    },
    products: {
        type: Object,
        required: true
    },
    correlative: {
        type: String,
    }
});

const customers = ref([]);
const sellers = ref([]);
const products = ref('');

const modalShow = ref(false);
const titleModal = ref('');

const invoice = ref({
    subTotalFormat: '',
    discountFormat: '',
    totalProduct: '',
    totalInvoice: '',
    discount: '',
    subTotal: '',
    productsArray: [],
    shipment_id: '',
    customer_id: '',
    seller_id: '',
});

const formData = useForm({...invoice.value});


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


const handleInvoiceStore = () => {
    formData.post(route("invoices.store"), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => {
            Swal.fire({
                title: 'Creado',
                text: 'Se ha creado la factura #'+ props.correlative,
                icon: 'success',
                confirmButtonText: 'Ver Facturas',
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            location.href = '/invoices';
                        }
                    })
        }
    });
}

const selectCustomer = (customer) => {
  formData.customer_id = customer.id;

  customers.value = [];
  infoCustomer.value = { ...customer };

};

const selectSeller = (seller) => {
  formData.seller_id = seller.id;

  sellers.value = [];
  infoSeller.value = { ...seller };
};

//productos
const addProduct = (product) => {
  const index = formData.productsArray.findIndex((p) => p.name === product.name);

  if (index >= 0) {
    formData.productsArray[index].quantity += 1;
    formData.productsArray[index].total_product = parseInt(formData.productsArray[index].quantity)  * parseInt(product.unit_price);
  } else {
    product.quantity = 1;
    product.total_product = parseInt(product.unit_price);
    formData.productsArray.push(product);
  }


  products.value = [];
  suma();
};

const suma = () => {
    let total = parseInt(0);
    let sub = parseInt(0);
    formData.productsArray.forEach((product) => {
        total += product.total_product - (product.total_product *  formData.discount / 100);
        sub += product.total_product;

    });

    formData.totalInvoice = total;
    formData.subTotal = sub;
    formData.subTotalFormat = '$'+sub;
    invoice.value.discountFormat = '- $'+sub * formData.discount / 100;
};

//watch discount
watch(formData, (newTerm) => {
    suma();
});


//computed total
const totalInvoiceGeneral = computed(() => {
    let total = parseInt(0);
    formData.productsArray.forEach((product) => {
        total += product.total_product - (product.total_product * formData.discount / 100);
    });

    return '$'+total;
});

//computed subtotal
const subTotalGeneral = computed(() => {
    let total = parseInt(0);
    formData.productsArray.forEach((product) => {
        total += product.total_product;
    });

    return '$'+total;
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inicio</h2>
                <!-- <Link :href="route('invoices.index')" class="text-sm text-gray-700 underline">Ver Facturas</Link> -->
            </div>

        </template>

        <div class="py-4 p-4 flex flex-col md:flex-row gap-2 ">
            <!-- info customer and seller -->
            <div class="max-w-7xl w-full md:lg:w-3/5  lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 text-gray-900">Cliente/Vendedor</div>
                    <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-4 sm:px-8 bg-white border-b border-gray-200">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <InputLabel for="customer_id" value="Cliente" />
                                        <Dropdown editable v-model="formData.customer_id" :options="props.customers" optionLabel="name" optionValue="id" placeholder="Selecciona Cliente" class="w-full md:w-14rem" >
                                            <template #option="slotProps">
                                                <div class="flex align-items-center" @click="selectCustomer(slotProps.option)">
                                                    <div>{{ slotProps.option.name }} - {{ slotProps.option.id_card_number}}</div>
                                                </div>
                                            </template>
                                        </Dropdown>
                                        <InputError class="mt-2" :message="formData.errors.customer_id" />
                                    </div>
                                    <div>
                                        <InputLabel for="seller_id" value="Vendedor" />
                                        <Dropdown editable v-model="formData.seller_id" :options="props.sellers" optionLabel="name" optionValue="id" placeholder="Selecciona Vendedor" class="w-full md:w-14rem">
                                            <template #option="slotProps">
                                                <div class="flex align-items-center" @click="selectSeller(slotProps.option)">
                                                    <div>{{ slotProps.option.name }} - {{ slotProps.option.id_card_number}}</div>
                                                </div>
                                            </template>
                                        </Dropdown>
                                        <InputError class="mt-2" :message="formData.errors.seller_id" />
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <Info :infoSeller="infoSeller" :infoCustomer="infoCustomer"/>
            <div class="max-w-7xl w-full md:lg:w-3/5 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 text-gray-900">Envío</div>
                    <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-4 sm:px-8 bg-white border-b border-gray-200">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <InputLabel for="shipment_id" value="Envio" />
                                        <Dropdown editable v-model="formData.shipment_id" :options="props.shipments" optionLabel="name" optionValue="id" placeholder="Selecciona Tipo" class="w-full md:w-14rem" />
                                        <InputError class="mt-2" :message="formData.errors.shipment_id" />
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4 p-4 w-full flex flex-col md:flex-row gap-2 ">
            <!-- info customer and seller -->
            <div class="w-full sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 text-gray-900">Productos</div>
                    <div class="flex  md:lg:flex-row justify-between flex-col-reverse">
                        <div class="p-10 grow">
                            <InputLabel for="product" value="Producto" />
                            <Dropdown editable v-model="formData.product_id" :options="props.products" optionLabel="name" optionValue="id" placeholder="Selecciona Producto" class="w-full md:w-14rem">

                                <template #option="slotProps">
                                    <div class="flex align-items-center" @click="addProduct(slotProps.option)">
                                        <div>{{ slotProps.option.name}}</div>
                                    </div>
                                </template>
                            </Dropdown>
                            <InputError class="mt-2" :message="formData.errors.productsArray" />
                        </div>
                        <div class="p-10">
                            <InputLabel for="discount" value="Descuento (%)" />
                            <TextInput id="discount" class="block mt-1 w-full" type="text" name="discount" v-model="formData.discount"  required autocomplete="false"/>
                            <InputError class="mt-2" :message="formData.errors.discount" />
                        </div>
                        <Facturacion
                            :subTotalGeneral="subTotalGeneral"
                            :totalInvoiceGeneral="totalInvoiceGeneral"
                            :discount="discount"
                            :invoice="invoice"
                            :formData="formData"
                        />

                    </div>

                    <TablaFacturacion
                        :productsArray="formData.productsArray"
                        :invoice="invoice"
                    />

                    <!-- button save -->
                    <div class="flex justify-end p-5">
                        <PrimaryButton class="mt-4" @click="handleInvoiceStore" >Guardar</PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
