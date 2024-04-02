<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link,router  } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref,onMounted,watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    flash: {
        type: String,
    },
});

watch(() => {
  if (props.flash.alert) {
    Swal.fire({
      title: 'Error',
      text: props.flash.alert,
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
  }
});

const requestDelete = (id) => {
    router.put(route("users.updateStatus",id), {
        onStart: () => console.log("start"),
        onFinish: () => console.log("finish"),
        onError: (error) => console.log(error),
        onSuccess: () => console.log("onSuccess"),
    });
};

const requestRestore = (id) => {
    router.put(route("users.restoreStatus",id), {
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
        title: '¿Estas seguro que quieres suspender a este usuario?',
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
            'No se ha suspendido este usuario.',
            'error'
            )
        }
    })
};

const handleRestore = (id) => {
    swalWithBootstrapButtons.fire({
        title: '¿Estas seguro que quieres activar a este usuario?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, estoy seguro.',
        cancelButtonText: 'No, no quiero.',
        reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            requestRestore(id)
        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
            'Cancelado',
            'No se ha activado este usuario.',
            'error'
            )
        }
    })
};

//rol swith 1= administrador, 2= cajero,3=tecnico
const rolName = (rol) => {
    switch (rol) {
        case 1:
            return 'Administrador';
        case 2:
            return 'Cajero';
        case 3:
            return 'Tecnico';
        default:
            return 'Sin Rol';
    }
};

const statusName = (status) => {
    switch (status) {
        case 1:
            return 'Activo';
        case 0:
            return 'Suspendido';
        default:
            return 'Sin Estado';
    }
};


</script>

<template>
    <Head title="Productos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Productos</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex justify-end p-4">
                        <Link :href="route('users.create')" class="px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">
                            Crear
                        </Link>
                    </div>
                    <div class="p-6 text-gray-900">
                        <DataTable :value="props.users" tableStyle="min-width: 50rem">
                            <Column field="name" header="Nombre"></Column>
                            <Column field="email" header="Correo"></Column>
                            <Column field="rol" header="Rol">
                                <template #body="{data}">
                                    <strong>{{ rolName(data.rol) }}</strong>
                                </template>
                            </Column>
                            <!-- status -->
                            <Column field="status" header="Estado">
                                <template #body="{data}">
                                    <strong>{{ statusName(data.status) }}</strong>
                                </template>
                            </Column>
                            <Column style="min-width:8rem">
                                <template #body="{data}">
                                    <div class="flex justify-center gap-5">
                                        <Link :href="route('users.edit',data.id)" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55 me-2 mb-2">
                                            <svg class="w-6 h-6text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M12.512 8.72a2.46 2.46 0 0 1 3.479 0 2.461 2.461 0 0 1 0 3.479l-.004.005-1.094 1.08a.998.998 0 0 0-.194-.272l-3-3a1 1 0 0 0-.272-.193l1.085-1.1Zm-2.415 2.445L7.28 14.017a1 1 0 0 0-.289.702v2a1 1 0 0 0 1 1h2a1 1 0 0 0 .703-.288l2.851-2.816a.995.995 0 0 1-.26-.189l-3-3a.998.998 0 0 1-.19-.26Z" clip-rule="evenodd"/>
                                                <path fill-rule="evenodd" d="M7 3a1 1 0 0 1 1 1v1h3V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h1V4a1 1 0 0 1 1-1Zm10.67 8H19v8H5v-8h3.855l.53-.537a1 1 0 0 1 .87-.285c.097.015.233.13.277.087.045-.043-.073-.18-.09-.276a1 1 0 0 1 .274-.873l1.09-1.104a3.46 3.46 0 0 1 4.892 0l.001.002A3.461 3.461 0 0 1 17.67 11Z" clip-rule="evenodd"/>
                                            </svg>
                                        </Link>
                                        <button  @click="handleDelete(data.id)" class="text-white bg-red-500 hover:bg-red-500/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2">
                                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <button v-show="data.status == 0"  @click="handleRestore(data.id)" class="text-white bg-purple-500 hover:bg-purple-500/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2">
                                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3"/>
                                            </svg>

                                        </button>

                                    </div>

                                </template>
                            </Column>
                            <template #footer> Total : {{ props.users ? props.users.length : 0 }} Usuarios. </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modalShow" @close="closeModal" :maxWidth="xl">
            <div class="p-6 mb-8">
                <h2 class="mb-5 text-lg font-medium text-gray-900">
                    Escanear Codigo de Barras/QR
                </h2>

                <p class="error">{{ error }}</p>

                <p
                    class="error"
                    v-if="noFrontCamera"
                >
                    You don't seem to have a front camera on your device
                </p>

                <p
                    class="error"
                    v-if="noRearCamera"
                >
                You don't seem to have a rear camera on your device
                </p>

                <div class="flex flex-col items-center justify-center gap-5">
                    <qrcode-stream

                      :track="trackFunctionSelected.value"
                      @error="onError"
                      @detect="onDetect"
                    >
                    <button @click="switchCamera" class="switchCamera">
                        <img
                        src="/img/camera.svg"
                        alt="switch camera"
                        />
                    </button>
                    </qrcode-stream>
                    <!-- input error -->
                    <InputError :message="formSerial.errors.serial" class="text-2xl font-extrabold" />

                </div>

                <div class="flex justify-end mt-3">
                    <SecondaryButton @click="closeModal()"> Cerrar </SecondaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
