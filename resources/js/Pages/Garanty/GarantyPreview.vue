<template>
    <div class="m-10">
        <div class="flex flex-col items-end justify-end text-[#6A3989] mb-8">
            <span class="text-3xl font-bold">TERAMINER</span>
            <span class="text-2xl font-black">CERTIFICADO DE GARANTIA ACIS</span>
            <span class="text-2xl font-bold"><pre>{{ garanty.invoice.products[0].name.toUpperCase() }}</pre></span>
        </div>

        <div class="flex justify-center mb-5">
            Certificado que se otorga a {{ garanty.invoice.customer.name }} titular de cédula de identidad número
            {{ garanty.invoice.customer.id_card_number }}, residenciado en {{ garanty.invoice.customer.address }},
            por la compra de ({{ garanty.garanty_products.length }}) equipos ({{ garanty.invoice.products[0].name.toUpperCase() }}) en condición de “nuevo”,
            cuya especificación es la siguiente: algoritmo de minado SHA256, capacidad de minado 102TH.
        </div>

        <div class="flex justify-center text-[#6A3989] font-bold mb-2">
            CONDICIONES GENERALES DE GARANTÍA
        </div>
        <ol class="pl-5 font-normal list-decimal">
            <li class="mb-2">Tendrá una vigencia de 6 meses a partir de la fecha de entrega del equipo.</li>
            <li class="mb-2">Esta garantía comprende que, si durante el plazo de vigencia de la misma, se produjeran defectos imputables a la calidad del producto suministrado por la empresa Teraminer, estaría obligada al reemplazo del equipo.</li>
            <li class="mb-2">Esta garantía comprende que, si durante el plazo de vigencia de la misma, se produjeran defectos imputables al rendimiento del producto suministrado por la empresa Teraminer, estaría obligada al reemplazo del equipo.</li>
            <li class="mb-2">La garantía no tendrá validez si el beneficiario no cumple con los requerimientos mínimos de seguridad física y lógica para los equipos.</li>
            <li class="mb-2">En el caso de producirse discrepancias en las condiciones de minado establecidas por el fabricante de los equipos en la ficha técnica de los mismos, la compañía Teraminer velará por la revisión y posterior comprobación del rendimiento de los equipos antes mencionados.</li>
            <li>Esta garantía no tendrá validez si los equipos mencionados sufren alteraciones a nivel de hardware o software, es decir si son destapados, golpeados, por riesgos catastróficos de fuerza mayor o si sufrieron alguna modificación a nivel de software contrarias a las establecidas originalmente por el fabricante.</li>
        </ol>

        <div class="flex justify-center text-[#6A3989] font-bold mb-2">
            RIESGOS EXCLUIDOS
        </div>
        <ol class="pl-5 font-normal list-decimal">
            <!-- Aquí va la lista de riesgos excluidos. -->
        </ol>

        <div class="flex justify-center text-[#6A3989] font-bold mb-2">
            SERIALES DE EQUIPOS
        </div>
        <ul class="pl-5 mb-5 font-normal list-disc">
            <li v-for="product in garanty.garanty_products" :key="product.id" class="mb-2 font-bold">
                {{ product.serial }}
            </li>
        </ul>

        <div class="flex items-center justify-left mt-4">
            <div class="w-full font-normal text-left">
                <p class="mb-5">En Maracay, a los _______________ días del mes de _______________ de _______.</p>
                <p>Recibe conforme: _______________________</p>
            </div>
        </div>

        <div class="flex justify-end">
            <button @click="printPage" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Imprimir</button>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    garanty: {
        type: Object,
        required: true,
    },
});

const printPage = () => {
    window.print();
};
</script>

<style scoped>
@media print {
    button {
        display: none;
    }
}
</style>
