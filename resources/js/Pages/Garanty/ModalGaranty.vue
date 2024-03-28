<template>
    <Modal :show="modalShow" @close="closeModal" :maxWidth="xl">
        <div class="p-6 mb-8">
            <h2 class="mb-5 text-lg font-medium text-gray-900">
                <span class="font-bold">Seriales de la Garantía</span>
            </h2>

            <DataTable :value="garantyProduct" tableStyle="min-width: 50rem" paginator :rows="5">
                <Column style="min-width:8rem" header="Serial">
                    <template #body="{data}">
                        <div class="flex justify-center">
                            <span  v-if="data.serial != null" class="font-bold uppercase">{{ data.serial }}</span>
                            <button v-else @click="openModalCamera(data.id)"  class="flex justify-center w-full px-4 py-2 font-bold text-white bg-blue-400 rounded hover:bg-blue-500">
                                <i class="pi pi-camera"></i>
                            </button>

                        </div>
                    </template>
                </Column>
                <Column field="code_number" header="Codigo">
                    <template #body="slotProps">
                        <span>{{ slotProps.data.product.code_number }}</span>
                    </template>
                </Column>
                <Column field="name" header="Nombre">
                    <template #body="slotProps">
                        <span class="uppercase">{{ slotProps.data.product.name }}</span>
                    </template>
                </Column>
                <Column field="unit_price" header="Precio Unitario">
                    <template #body="slotProps">
                        {{ formatNumber(slotProps.data.product.unit_price) }}
                    </template>
                </Column>
                <!-- <template #footer> Total : {{ garantyProduct.products ? garantyProduct.products.length : 0 }} Productos. </template> -->
            </DataTable>

            <div class="flex justify-end mt-3">
                <SecondaryButton @click="$emit('close')"> Cerrar </SecondaryButton>
            </div>
        </div>
    </Modal>
    <Modal :show="modalShowCamera" @close="closeModalCamera" :maxWidth="xl">
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
                    <SecondaryButton @click="closeModalCamera"> Cerrar </SecondaryButton>
                </div>
            </div>
        </Modal>
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

<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
import Swal from 'sweetalert2'
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  modalShow: Boolean,
  garantyProduct: Object,
});



const formatNumber = (value) => {
    return new Intl.NumberFormat("es-CL", {
        style: "currency",
        currency: "CLP",
    }).format(value);
};


const closeModal = () => {
  props.modalShow = false;
};


/*** camera ***/

const modalShowCamera = ref(false);

const noFrontCamera = ref(false)

const noRearCamera = ref(false)

const facingMode = ref('environment')

const idGarantyProducts = ref(null)

const formSerial = useForm({
    serial: '',
})

const openModalCamera = (id) => {
    modalShowCamera.value = true
    idGarantyProducts.value = id
}

const closeModalCamera = () => {
    modalShowCamera.value = false
    formSerial.errors.serial = null
}

const swalWithBootstrapButtons = Swal.mixin({
    buttonsStyling: true
})

const emit = defineEmits(['close'])

function onDetect(detectedCodes) {
    //result.value = JSON.stringify(detectedCodes.map((code) => code.rawValue))
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
            formSerial.post(route("garanty.serialUpdate",{ id: idGarantyProducts.value }), {
                onStart: () => console.log("start"),
                onFinish: () => console.log("finish"),
                onError: (error) => console.log('error'),
                onSuccess: (result) => {
                    Swal.fire({
                        title: 'Serial Agregado',
                        text: 'Se ha agregado el serial',
                        icon: result.status,
                        confirmButtonText: 'Aceptar'
                    })

                    closeModalCamera()
                    emit('close')

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

</script>
