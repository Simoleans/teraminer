<template>
    <!-- info invoice -->
    <div class="max-w-7xl w-full lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 text-gray-900">Informaciòn</div>
            <div class="w-full bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 sm:px-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between gap-2 items-center md:lg:flex-nowrap flex-wrap">
                        <div>
                            <span class="bg-[#6A3989] text-md font-bold text-white p-2 rounded-lg">Cliente</span>
                            <div class="p-4 mt-4 border-dashed border-2 border-[#6A3989]">
                                <p class="flex flex-col justify-center max-w-full whitespace-normal">
                                    <span class="font-medium text-md break-all" v-text="infoCustomer.id_card_number"></span>
                                    <span class="font-medium text-md break-all" v-text="infoCustomer.name"></span>
                                    <span class="font-medium text-md break-all" v-text="infoCustomer.phone"></span>
                                    <span class="font-medium text-md break-all" v-text="infoCustomer.address"></span>
                                    <span class="font-medium text-md break-all" v-text="infoCustomer.email"></span>
                                </p>
                            </div>

                        </div>
                        <div class="mt-2 md:lg:mt-0">
                            <span class="bg-[#6A3989] text-md font-bold text-white p-2 rounded-lg">Vendedor</span>
                            <div class="p-4 mt-4 border-dashed border-2 border-[#6A3989]">
                                <p class="flex flex-col justify-center max-w-full">
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
