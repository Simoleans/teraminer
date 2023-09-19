<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,useForm,Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Dropdown from 'primevue/dropdown';
import Swal from 'sweetalert2'



const formData = useForm({
    name: '',
    type : '',
    route: '',
});

const handleStoreProduct = () => {
    formData.post(route("shipments.store"), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => {
            Swal.fire({
                title: 'Creado',
                text: 'Se ha creado el envío correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            })
        }
    });
}

const typeShip = [
    { name: 'Maritimo'},
    { name: 'Aereo'},
];


</script>

<template>
    <Head title="Envíos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Envío</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3 sm:flex gap-[2.75rem] ">
                <div class="mb-4">
                    <span>Crear Envío</span><br>
                    <!-- <small>Data principal de la empresa (Saldra en la factura de encabezado)</small> -->
                </div>
                <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <form @submit.prevent="handleStoreProduct" method="POST">
                            <div class="mt-4">
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" class="block mt-1 w-full" type="text" name="name" v-model="formData.name"  required autofocus/>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="type" value="Tipo" />
                                <!-- <TextInput id="type" class="block mt-1 w-full" type="text" name="type" v-model="formData.type"  required /> -->
                                <Dropdown v-model="formData.type" :options="typeShip" optionLabel="name" placeholder="Selecciona Tipo" class="w-full md:w-14rem" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="route" value="Ruta" />
                                <TextInput id="route" class="block mt-1 w-full" type="text" name="route" v-model="formData.route"  required  />
                                <InputError class="mt-2" :message="formData.errors.route" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('shipments.index')" class="ml-4">
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
    </AuthenticatedLayout>
</template>
