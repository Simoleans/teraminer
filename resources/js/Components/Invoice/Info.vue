<template>
    <!-- info invoice -->
    <div class="max-w-7xl w-full sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 text-gray-900">Informaciòn</div>
            <!-- <Link class="inline-flex items-center px-4 py-2 m-5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :href="route('dashboard')">Factura</Link> -->
            <div class="w-full bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 sm:px-20 bg-white border-b border-gray-200">
                    <div class="flex justify-between gap-6 items-center">
                        <div>
                            <span class="bg-[#6A3989] text-md font-bold text-white p-2 rounded-lg">Cliente</span>
                            <div class="p-4 mt-4 border-dashed border-2 border-[#6A3989]">
                                <p class="flex flex-col justify-center">
                                    <span class="font-medium text-md" v-text="infoCustomer.id_card_number"></span>
                                    <span class="font-medium text-md" v-text="infoCustomer.name"></span>
                                    <span class="font-medium text-md" v-text="infoCustomer.phone"></span>
                                    <span class="font-medium text-md" v-text="infoCustomer.address"></span>
                                    <span class="font-medium text-md" v-text="infoCustomer.email"></span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <span class="bg-[#6A3989] text-md font-bold text-white p-2 rounded-lg">Vendedor</span>
                            <div class="p-4 mt-4 border-dashed border-2 border-[#6A3989]">
                                <p class="flex flex-col justify-center">
                                    <span class="font-medium text-md" v-text="infoSeller.id_card_number"></span>
                                    <span class="font-medium text-md" v-text="infoSeller.name"></span>
                                    <span class="font-medium text-md" v-text="infoSeller.phone"></span>
                                    <span class="font-medium text-md" v-text="infoSeller.address"></span>
                                    <span class="font-medium text-md" v-text="infoSeller.email"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    infoCustomer: Object,
    infoSeller: Object,
});

</script>
