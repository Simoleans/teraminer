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
    email: '',
    name : '',
    rol: 2,
    password: '',
    password_confirmation: ''
});

const handleStoreUser = () => {
    formData.post(route("users.store"), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => {
            Swal.fire({
                title: 'Creado',
                text: 'Se ha creado el Usuario',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            })
        }
    });
}


</script>

<template>
    <Head title="Usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Usuario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3 sm:flex gap-[2.75rem] ">
                <div class="mb-4">
                    <span>Crear Usuario</span><br>
                    <!-- <small>Data principal de la empresa (Saldra en la factura de encabezado)</small> -->
                </div>
                <div class="overflow-hidden bg-white shadow-xl grow sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
                        <form @submit.prevent="handleStoreUser" method="POST">
                            <div class="mt-4">
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" class="block w-full mt-1" type="text" name="name" v-model="formData.name"  required />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" class="block w-full mt-1" type="email" name="email" v-model="formData.email"  required />
                                <InputError :message="formData.errors.email" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="rol" value="Rol" />
                                <select id="rol" class="block w-full mt-1" name="rol" v-model="formData.rol" required>
                                    <option value="1">Administrador</option>
                                    <option value="2">Cajero</option>
                                    <option value="3">Tecnico</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="password" value="Contraseña" />
                                <TextInput id="password" class="block w-full mt-1" type="password" name="password" v-model="formData.password"  required />
                                <InputError :message="formData.errors.password" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                                <TextInput id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" v-model="formData.password_confirmation"  required />
                                <InputError :message="formData.errors.password_confirmation" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('users.index')" class="ml-4">
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
