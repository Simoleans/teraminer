<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,useForm,Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2'




const props = defineProps({
  customer: {
    type: Object,
    required: true
  }
});

const formData = useForm({
  id: props.customer.id,
  id_card_number: props.customer.id_card_number,
  name: props.customer.name,
  address: props.customer.address,
  phone: props.customer.phone,
  email: props.customer.email,
});

const handleEditCustomer = () => {
    formData.put(route("customers.update",{ customer: props.customer.id }), {
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
        }
    });
}


</script>

<template>
    <Head title="Cliente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cliente</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3 sm:flex gap-[2.75rem] ">
                <div class="mb-4">
                    <span>Editar Cliente</span><br>
                </div>
                <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <form @submit.prevent="handleEditCustomer()" method="POST">

                            <div class="mt-4">
                                <InputLabel for="id_card_number" value="Cédula/RIF" />
                                <TextInput id="id_card_number" class="block mt-1 w-full" type="text" name="id_card_number" v-model="formData.id_card_number"  required autofocus />
                            </div>

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
                                <Link :href="route('customers.index')" class="ml-4">
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
