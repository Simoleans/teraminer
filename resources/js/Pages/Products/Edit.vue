<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,useForm,Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2'




const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

const formData = useForm({
  id: props.product.id,
  code_number: props.product.code_number,
  name: props.product.name,
  unit_price: props.product.unit_price,
});

const handleEditProduct = () => {
    formData.put(route("products.update",{ product: props.product.id }), {
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
    <Head title="Producto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Producto</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3 sm:flex gap-[2.75rem] ">
                <div class="mb-4">
                    <span>Editar Producto</span><br>
                </div>
                <div class="grow bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <form @submit.prevent="handleEditProduct()" method="POST">

                            <div class="mt-4">
                                <InputLabel for="code_number" value="Cédula" />
                                <TextInput id="code_number" class="block mt-1 w-full" type="text" name="code_number" v-model="formData.code_number"  required autofocus />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" class="block mt-1 w-full" type="text" name="name" v-model="formData.name"  required autofocus />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="unit_price" value="Dirección" />
                                <TextInput id="unit_price" class="block mt-1 w-full" type="text" name="unit_price" v-model="formData.unit_price"  required />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('products.index')" class="ml-4">
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
