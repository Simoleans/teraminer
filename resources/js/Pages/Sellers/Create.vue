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
  name : '',
  address: '',
  phone: '',
  email: '',
  website: ''
});

const handleStoreData = () => {
    formData.post(route("sellers.store"), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => {
            Swal.fire({
                title: 'Creado',
                text: 'Se ha creado el vendedor',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            })
        }
    });
}


</script>

<template>
    <Head title="Data" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3 sm:flex gap-[2.75rem] ">
                <div class="mb-4">
                    <span>Crear Vendedor</span><br>
                    <!-- <small>Data principal de la empresa (Saldra en la factura de encabezado)</small> -->
                </div>
                <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <form @submit.prevent="handleStoreData" method="POST">
                            <div class="mt-4">
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" class="block mt-1 w-full" type="text" name="name" v-model="formData.name"  required autofocus />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="address" value="Dirección" />
                                <TextInput id="address" class="block mt-1 w-full" type="text" name="address" v-model="formData.address"  required />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="phone" value="Teléfono" />
                                <TextInput id="phone" class="block mt-1 w-full" type="text" name="phone" v-model="formData.phone"  required />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" class="block mt-1 w-full" type="email" name="email" v-model="formData.email"  required />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('sellers.index')" class="ml-4">
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
