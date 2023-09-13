<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,useForm,Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import Swal from 'sweetalert2'



const formData = useForm({
    code_number: '',
    name : '',
    unit_price: '',
});

const handleStoreProduct = () => {
    formData.post(route("products.store"), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => {
            Swal.fire({
                title: 'Creado',
                text: 'Se ha creado el producto',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            })
        }
    });
}


</script>

<template>
    <Head title="Productos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Producto</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3 sm:flex gap-[2.75rem] ">
                <div class="mb-4">
                    <span>Crear Producto</span><br>
                    <!-- <small>Data principal de la empresa (Saldra en la factura de encabezado)</small> -->
                </div>
                <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <form @submit.prevent="handleStoreProduct" method="POST">
                            <div class="mt-4">
                                <InputLabel for="code_number" value="Codigo" />
                                <TextInput id="code_number" class="block mt-1 w-full" type="text" name="code_number" v-model="formData.code_number"  required autofocus />
                                <InputError class="mt-2" :message="formData.errors.code_number" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" class="block mt-1 w-full" type="text" name="name" v-model="formData.name"  required />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="unit_price" value="Precio Unitario ($)" />
                                <TextInput id="unit_price" class="block mt-1 w-full" type="text" name="unit_price" v-model="formData.unit_price"  required />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('products.index')" class="ml-4">
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
