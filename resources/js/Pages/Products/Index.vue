<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link,router  } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue';

const result = ref('')

const formSerial = useForm({
    serial: ''
})

  function onDetect(detectedCodes) {
    result.value = JSON.stringify(detectedCodes.map((code) => code.rawValue))
    formSerial.serial = detectedCodes[0].rawValue

    //console.log(detectedCodes[0].rawValue)

    swalWithBootstrapButtons.fire({
        title: '¿Estas seguro que quieres agregar este serial?',
        text: `Serial: ${formSerial.serial}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, estoy seguro.',
        cancelButtonText: 'No, no quiero.',
        reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            formSerial.put(route("products.updateSerial",{ product: idProducts.value }), {
                onStart: () => console.log("start"),
                onFinish: () => console.log("finish"),
                onError: (error) => console.log(error),
                onSuccess: () => {
                    Swal.fire({
                        title: 'Agregado',
                        text: 'Se ha agregado el serial',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    })
                    //redirect nack
                    closeModal()
                }
            });
        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
            'Cancelado',
            'No se ha agregado el serial, vuelve a escanear.',
            'error'
            )
        }
    })
  }


/*** select camera ***/

const selectedDevice = ref(null)
const devices = ref([])

onMounted(async () => {
    devices.value = (await navigator.mediaDevices.enumerateDevices()).filter(
        ({ kind }) => kind === 'videoinput'
    )

    if (devices.value.length > 0) {
        selectedDevice.value = devices.value[0]
    }
})

  const trackFunctionOptions = [
  { text: 'nothing (default)', value: undefined },
  { text: 'outline', value: paintOutline },
  { text: 'centered text', value: paintCenterText },
  { text: 'bounding box', value: paintBoundingBox }
]
const trackFunctionSelected = ref(trackFunctionOptions[1])


/*** error handling ***/

const error = ref('')

function onError(err) {
    error.value = `[${err.name}]: `

    const triedFrontCamera = facingMode.value === 'user';
    const triedRearCamera = facingMode.value === 'environment';

    const cameraMissingError = err.value === 'OverconstrainedError'


    if (triedFrontCamera && !cameraMissingError) {
        noRearCamera.value = true
    }

    if (triedRearCamera && !cameraMissingError) {
        noFrontCamera.value = true
    }

    //console.error(error)

  if (err.name === 'NotAllowedError') {
    error.value += 'you need to grant camera access permission'
  } else if (err.name === 'NotFoundError') {
    error.value += 'no camera on this device'
  } else if (err.name === 'NotSupportedError') {
    error.value += 'secure context required (HTTPS, localhost)'
  } else if (err.name === 'NotReadableError') {
    error.value += 'is the camera already in use?'
  } else if (err.name === 'OverconstrainedError') {
    error.value += 'installed cameras are not suitable'
  } else if (err.name === 'StreamApiNotSupportedError') {
    error.value += 'Stream API is not supported in this browser'
  } else if (err.name === 'InsecureContextError') {
    error.value +=
      'Camera access is only permitted in secure context. Use HTTPS or localhost rather than HTTP.'
  } else {
    error.value += err.message
  }
}

/*** track functons ***/

function paintOutline(detectedCodes, ctx) {
  for (const detectedCode of detectedCodes) {
    const [firstPoint, ...otherPoints] = detectedCode.cornerPoints

    ctx.strokeStyle = 'red'

    ctx.beginPath()
    ctx.moveTo(firstPoint.x, firstPoint.y)
    for (const { x, y } of otherPoints) {
      ctx.lineTo(x, y)
    }
    ctx.lineTo(firstPoint.x, firstPoint.y)
    ctx.closePath()
    ctx.stroke()
  }
}
function paintBoundingBox(detectedCodes, ctx) {
  for (const detectedCode of detectedCodes) {
    const {
      boundingBox: { x, y, width, height }
    } = detectedCode

    ctx.lineWidth = 2
    ctx.strokeStyle = '#007bff'
    ctx.strokeRect(x, y, width, height)
  }
}
function paintCenterText(detectedCodes, ctx) {
  for (const detectedCode of detectedCodes) {
    const { boundingBox, rawValue } = detectedCode

    const centerX = boundingBox.x + boundingBox.width / 2
    const centerY = boundingBox.y + boundingBox.height / 2

    const fontSize = Math.max(12, (50 * boundingBox.width) / ctx.canvas.width)

    ctx.font = `bold ${fontSize}px sans-serif`
    ctx.textAlign = 'center'

    ctx.lineWidth = 3
    ctx.strokeStyle = '#35495e'
    ctx.strokeText(detectedCode.rawValue, centerX, centerY)

    ctx.fillStyle = '#5cb984'
    ctx.fillText(rawValue, centerX, centerY)
  }
}



const props = defineProps({
  products: {
    type: Object,
    required: true
  }
});

const requestDelete = (id) => {
    router.delete(route("products.destroy",id), {
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
        title: '¿Estas seguro que quieres eliminar este producto?',
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
            'No se ha eliminado el producto.',
            'error'
            )
        }
    })
};

const modalShow = ref(false)
const idProducts = ref(null)

const openModalCamera = (id) => {
    modalShow.value = true
    idProducts.value = id
}

const closeModal = () => {
    modalShow.value = false
}

const facingMode = ref('environment');
const noRearCamera = ref(false);
const noFrontCamera = ref(false);

const switchCamera = () => {
    facingMode.value = facingMode.value === 'environment' ? 'user' : 'environment';
}


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
                        <Link :href="route('products.create')" class="px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">
                            Crear
                        </Link>
                    </div>
                    <div class="p-6 text-gray-900">
                        <DataTable :value="props.products" tableStyle="min-width: 50rem">
                            <Column field="code_number" header="Codigo"></Column>
                            <Column field="name" header="Nombre"></Column>
                            <Column field="unit_price" header="Precio Unitario"></Column>
                            <Column field="serial" header="Serial">
                                <template #body="{data}">
                                    <span v-if="data.serial != null">{{ data.serial }}</span>
                                    <button title="Agregar Serial" v-else type="button" @click="openModalCamera(data.id)" class="text-white bg-[#1da1f2] hover:bg-[#1da1f2]/90 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#1da1f2]/55 me-2 mb-2">
                                        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M7.5 4.586A2 2 0 0 1 8.914 4h6.172a2 2 0 0 1 1.414.586L17.914 6H19a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1.086L7.5 4.586ZM10 12a2 2 0 1 1 4 0 2 2 0 0 1-4 0Zm2-4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </template>
                            </Column>
                            <Column style="min-width:8rem">
                                <template #body="{data}">
                                    <div class="flex justify-center gap-5">
                                        <Link :href="route('products.edit',data.id)" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55 me-2 mb-2">
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
                                    </div>

                                </template>
                            </Column>
                            <template #footer> Total : {{ props.products ? props.products.length : 0 }} Productos. </template>
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

<style scoped>

.switchCamera {
  position: absolute;
  left: 10px;
  top: 10px;
  color : white;
}
.switchCamera img {
  width: 50px;
  height: 50px;
}
.error {
  color: red;
  font-weight: bold;
}
</style>
