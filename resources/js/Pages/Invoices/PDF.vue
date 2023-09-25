<template>
    <div class="bg-white p-4 mb-8">
    <div class="flex justify-between items-center">
        <img src="/img/teraminer.png" alt="" class="w-20">
        <h1 class="text-2xl font-bold text-[#6A3989]">TERAMINER FACTURA <br> #{{ invoice.correlative }}</h1>
    </div>
    <div class="mt-4">
        <p class="text-[#6A3989]">{{ data.name }}</p>
        <p class="text-[#6A3989]">{{ data.phone }}</p>
        <p class="text-[#6A3989]">{{ data.email }}</p>
        <!-- <p class="text-[#6A3989]">RIF de la Empresa</p> -->
        <p class="text-[#6A3989]">{{ data.website }}</p>
    </div>
    <div class="flex justify-between">
        <div class="mt-4">
            <h2 class="text-2xl font-bold bg-[#6A3989] p-2 rounded-md text-white">VENDEDOR</h2>
            <p >{{ invoice.seller.name }}</p>
            <p >{{ invoice.seller.phone }}</p>
            <p >{{ invoice.seller.email }}</p>
            <p >{{ invoice.seller.address }}</p>
            <p >{{ invoice.seller.id_card_number }}</p>
        </div>
        <div class="mt-4">
            <h2 class="text-2xl font-bold bg-[#6A3989] p-2 rounded-md text-white">CLIENTE</h2>
            <p >{{ invoice.customer.name }}</p>
            <p >{{ invoice.customer.phone }}</p>
            <p >{{ invoice.customer.email }}</p>
            <p >{{ invoice.customer.address }}</p>
            <p >{{ invoice.customer.id_card_number }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-2xl font-bold text-[#6A3989] uppercase">ENVíO</h2>
        <table class="w-full border border-black text-center">
        <thead>
            <tr class="bg-[#6A3989] text-white">
            <th class="p-2">Nombre</th>
            <th class="p-2">Tipo</th>
            <th class="p-2">Ruta</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td class="p-2">{{ invoice.shipment.name }}</td>
            <td class="p-2">{{ invoice.shipment.type }}</td>
            <td class="p-2">{{ invoice.shipment.route }}</td>
            </tr>
        </tbody>
        </table>
    </div>

    <div class="mt-8">
        <h2 class="text-2xl font-bold text-[#6A3989]">PRODUCTOS</h2>
        <table class="w-full border border-black text-center mb-15">
        <thead>
            <tr class="bg-[#6A3989] text-white">
            <th class="p-2">Producto</th>
            <th class="p-2">Cantidad</th>
            <th class="p-2">Precio Unit</th>
            <th class="p-2">Total</th>
            </tr>
        </thead>
        <!-- v-for invoice.products in table -->
        <tbody v-for="product in invoice.products" :key="product.id">
            <tr>
            <td class="p-2">{{ product.name }}</td>
            <td class="p-2">{{ product.quantity }}</td>
            <td class="p-2">{{ formatNumber(product.unit_price) }}</td>
            <td class="p-2">{{ formatNumber(product.total_product) }}</td>
            </tr>

        </tbody>
        </table>
    </div>
    <div class="flex justify-end">
        <div class="mt-8">
        <p class="text-xl font-bold">Subtotal: $95</p>
        <p class="text-xl font-bold" v-if="invoice.discount">Descuento: -{{ invoice.discount }}% / {{ totalDiscount(invoice.discount,invoice.subtotal) }}</p>
        <p class="text-xl font-bold">Total: {{ formatNumber(invoice.total) }}</p>
        </div>
    </div>
</div>

<div class="footer">
    If you have any questions about this purchase order, please contact<br />
    [Caleb Betancourt, (412) 895-0309, payments@teraminer.com.ve - cbetancourt@teraminer.com.ve]
</div>

</template>
<script setup>
import { ref } from 'vue';
import { onMounted } from 'vue';

const props = defineProps({
    invoice: {
    type: Object,
    required: true,
    },
    data: {
        type: Object,
        required: true,
    },
});

const formatNumber = (value) => {
    return new Intl.NumberFormat("es-CL", {
        style: "currency",
        currency: "CLP",
    }).format(value);
};

const totalDiscount = (discount,subtotal) => {
    return formatNumber(subtotal * discount / 100);

};

onMounted(() => {
  window.print();
});

const closeWindow = () => {
  window.close();
};

// Ejemplo de una acción que cierra la ventana
const handleClick = () => {
  // Realiza la acción deseada
  // ...

  // Cierra la ventana
  closeWindow();
};
</script>

<style scoped>
@media print {
    .footer {
      position: relative;
      bottom: 0;
      left: 0;
      width: 100%;
      color: black;
      /* background-color: #6A3989; */
      /* color: white; */
      padding: 10px;
      text-align: center;
    }
  }</style>
