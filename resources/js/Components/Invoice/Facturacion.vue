<template>
    <div class="p-10 grow">
            <div class="bg-white shadow-lg rounded-lg p-6 border-dashed border-2 border-[#6A3989]">
                <h2 class="text-2xl font-bold mb-4">Factura</h2>
                <div class="flex justify-between mb-4">
                    <span class="font-bold">Sub: </span>
                    <span v-text="invoice.subTotalGeneral"></span>
                </div>
                <div v-if="invoice.discount != 0" class="flex justify-between mb-4">
                    <span class="font-bold">Descuento: </span>
                    <span v-text="invoice.discountFormat"></span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="font-bold">Total: </span><br>
                    <span class="font-bold text-2xl" v-text="totalInvoiceGeneral"></span>
                </div>
            </div>
        </div>
</template>

<script setup>

const props = defineProps({
    subTotalGeneral: {
        type: String,
        required: true
    },
    discount: {
        type: Number,
        required: true
    },
    totalInvoiceGeneral: {
        type: String,
        required: true
    },
    invoice : {
        type: Object,
        required: true
    },
})
</script>
