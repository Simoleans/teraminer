<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,useForm,Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2'
import { ref } from 'vue'
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});

const formData = useForm({
  id: props.user.id,
  name: props.user.name,
  email: props.user.email,
  rol: props.user.rol,
  password: '',
});

const handleEditUser = () => {
    formData.put(route("users.update",{ user: props.user.id }), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => {
            Swal.fire({
                title: 'Actualizado',
                text: 'Se ha actualizado la información',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            })
            //redirect nack
            this.$inertia.visit(this.$route('users.index'))
        }
    });
}

const changePassword = ref(false);

//validate cheked changePassword formData.possword is value ''
const validateChangePassword = () => {
    if (changePassword.value) {
        formData.password = '';
    }
}



</script>

<template>
    <Head title="Usuario" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Usuario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3 sm:flex gap-[2.75rem] ">
                <div class="mb-4">
                    <span>Editar Usuario </span><br>
                </div>
                <div class="overflow-hidden bg-white shadow-xl grow sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
                        <form @submit.prevent="handleEditUser()" method="POST">

                            <div class="mt-4">
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" class="block w-full mt-1" type="text" name="name" v-model="formData.name"  required autofocus />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Correo" />
                                <TextInput id="email" class="block w-full mt-1" type="email" name="email" v-model="formData.email" required />
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
                                <input type="checkbox" id="changePassword" name="changePassword" @change="validateChangePassword" v-model="changePassword">
                                <label for="changePassword">   Cambiar Contraseña</label>
                            </div>

                            <div class="mt-4" v-show="changePassword">
                                <InputLabel for="password" value="Contraseña" />
                                <TextInput id="password" class="block w-full mt-1" :required="changePassword" type="password" name="password" v-model="formData.password" />
                                <InputError :message="formData.errors.password" />
                            </div>


                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('users.index')" class="ml-4">
                                    Volver
                                </Link>
                                <PrimaryButton class="ml-4">
                                    Actualizar
                                </PrimaryButton>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

