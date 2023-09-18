<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
//import ColumnGroup from 'primevue/columngroup';   // optional
//import Row from 'primevue/row';
import { Link,useForm,router  } from '@inertiajs/vue3';
import Swal from 'sweetalert2'



const props = defineProps({
  sellers: {
    type: Object,
    required: true
  }
});

//const form = useForm(null);
const requestDelete = (id) => {
    router.delete(route("sellers.destroy",id), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => console.log("onSuccess"),
    });
};
const swalWithBootstrapButtons = Swal.mixin({
    buttonsStyling: true
})

const handleDelete = (id) => {
    swalWithBootstrapButtons.fire({
        title: '¿Estas seguro que quieres eliminar este vendedor?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, estoy seguro.',
        cancelButtonText: 'No, no quiero.',
        reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            requestDelete(id)
        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
            'Cancelado',
            'No se ha eliminado el vendedor.',
            'error'
            )
        }
    })
};

</script>

<template>
    <Head title="Vendedores" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Vendedores</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-end p-4">
                        <Link :href="route('sellers.create')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Crear
                        </Link>
                    </div>
                    <div class="p-6 text-gray-900">
                        <DataTable :value="props.sellers" tableStyle="min-width: 50rem">
                            <Column field="id_card_number" header="Cédula/RIF"></Column>
                            <Column field="name" header="Nombre"></Column>
                            <Column field="address" header="Direcciòn"></Column>
                            <Column field="phone" header="Telefono"></Column>
                            <Column field="email" header="Email"></Column>
                            <Column style="min-width:8rem">
                                <template #body="{data}">
                                    <div class="flex justify-between">
                                        <Link :href="route('sellers.edit',data.id)" class="inline-block px-6 py-2 border-2 border-blue-600 text-blue-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                                            <i class="pi pi-pencil" />
                                        </Link>
                                        <button type="button" @click="handleDelete(data.id)" class="inline-block px-6 py-2 border-2 border-red-600 text-red-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                                            <i class="pi pi-trash" />
                                        </button>
                                    </div>

                                </template>
                            </Column>
                            <template #footer> Total : {{ props.sellers ? props.sellers.length : 0 }} Vendedores. </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
