<template>
<div class="p-2 mb-2 bg-white">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <img src="/img/teraminer.png" alt="" class="w-20">
            <span class="text-2xl font-bold text-[#6A3989] ml-2">TERAMINER</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-[#6A3989]">INVOICE</h1>
            <div class="flex flex-col items-center justify-start">
                <table class="w-full text-center border border-black divide-x">
                    <tbody>
                        <tr>
                            <td class="p-2">Fecha</td>
                            <td class="p-2">{{ invoice.created_at }}</td>
                        </tr>
                        <tr>
                            <td class="p-2">PO #</td>
                            <td class="p-2">{{ invoice.correlative }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="mt-1">
        <p class="text-[#6A3989]">{{ data.name }}</p>
        <p class="text-[#6A3989]">{{ data.phone }}</p>
        <p class="text-[#6A3989]">{{ data.email }}</p>
        <!-- <p class="text-[#6A3989]">RIF de la Empresa</p> -->
        <p class="text-[#6A3989]">{{ data.website }}</p>
    </div>
    <div class="flex justify-between">
        <div class="mt-2">
            <h2 class="text-2xl font-bold bg-[#6A3989] p-1 rounded-md text-white">VENDEDOR</h2>
            <p >{{ invoice.seller.name }}</p>
            <p >{{ invoice.seller.phone }}</p>
            <p >{{ invoice.seller.email }}</p>
            <p >{{ invoice.seller.address }}</p>
            <p >{{ invoice.seller.id_card_number }}</p>
        </div>
        <div class="mt-2">
            <h2 class="text-2xl font-bold bg-[#6A3989] p-1 rounded-md text-white">CLIENTE</h2>
            <p >{{ invoice.customer.name }}</p>
            <p >{{ invoice.customer.phone }}</p>
            <p >{{ invoice.customer.email }}</p>
            <p >{{ invoice.customer.address }}</p>
            <p >{{ invoice.customer.id_card_number }}</p>
        </div>
    </div>

    <div class="mt-2">
        <table class="w-full text-center border border-black">
            <thead>
                <tr class="bg-[#6A3989] text-white">
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Ruta</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ invoice.shipment.name }}</td>
                    <td>{{ invoice.shipment.type }}</td>
                    <td>{{ invoice.shipment.route }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        <!-- <h2 class="text-2xl font-bold text-[#6A3989]">PRODUCTOS</h2> -->
        <table class="w-full text-center border border-black mb-15">
            <thead>
                <tr class="bg-[#6A3989] text-white">
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unit</th>
                    <th>Total</th>
                </tr>
            </thead>
            <!-- v-for invoice.products in table -->
            <tbody v-for="product in invoice.products" :key="product.id">
                <tr>
                    <td>{{ product.name }}</td>
                    <td>{{ product.quantity }}</td>
                    <td>{{ formatNumber(product.unit_price) }}</td>
                    <td>{{ formatNumber(product.total_product) }}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="flex justify-between gap-12 mt-6">
        <table class="w-full text-sm text-center border border-black mb-15">
            <thead>
                <tr class="bg-[#6A3989] text-white">
                    <th class="p-2">Instrucciones Especiales - Comentarios</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td >
                        Envío a ser realizado desde el almacen principal de Teraminer en, Zhongyi International Express Co., Ltd, RM103, No. 9 Xinhe Street, Bantian, LongGang District, Shenzhen China (518129) - Houston Texas 30 Esplanade Blvd, Suite 100 <br>
                        <br>
                        Pioneer Road, Nanshan District, Shenzhen City. Fecha estimada 31 días previa reserva (aerea), maritimo 62 días según fecha de liberación por las autoridades de sanidad de la aduana en China (COVID)
                    </td>
                </tr>

            </tbody>
        </table>
        <div class="mt-8 ">
            <table class="w-full text-center border border-black divide-x">
                    <tbody>
                        <tr>
                            <td class="p-2">Sub-Total</td>
                            <td class="p-2 text-xl font-bold">{{ formatNumber(invoice.subtotal) }}</td>
                        </tr>
                        <tr v-if="invoice.discount">
                            <td class="p-2">Descuento</td>
                            <td class="p-2"><span class="font-bold text-md"> -{{ invoice.discount }}% /</span><small>{{ totalDiscount(invoice.discount,invoice.subtotal) }}</small></td>
                        </tr>
                        <tr>
                            <td class="p-2">Total</td>
                            <td class="p-2 text-xl font-bold">{{ formatNumber(invoice.total) }}</td>
                        </tr>
                    </tbody>
                </table>
        </div>
    </div>
</div>

<div class="footer">
    If you have any questions about this purchase order, please contact<br />
    [Caleb Betancourt, (412) 895-0309, payments@teraminer.com.ve - cbetancourt@teraminer.com.ve]
</div>

</template>
<script setup>
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
