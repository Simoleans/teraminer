<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,useForm,Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2'
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
import { ref,onMounted } from 'vue'
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';


const facingMode = ref('environment');
const noRearCamera = ref(false);
const noFrontCamera = ref(false);

//open close modsal
const modalShow = ref(false)
const openModal = () => {
    modalShow.value = true
}
const closeModal = () => {
    modalShow.value = false
}

const switchCamera = () => {
    facingMode.value = facingMode.value === 'environment' ? 'user' : 'environment';
}

function onDetect(detectedCodes) {
    //result.value = JSON.stringify(detectedCodes.map((code) => code.rawValue))
    formData.serial = detectedCodes[0].rawValue
    //swal message Serial Escaneado y boton aceptar cerrar modal
    Swal.fire({
        title: 'Serial Escaneado',
        text: 'Se ha escaneado el serial',
        icon: 'success',
        confirmButtonText: 'Aceptar',
        //action button close modal
    }).then((result) => {
        if (result.isConfirmed) {
            closeModal()
        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            Swal.fire('Cancelado', 'Se ha cancelado el escaneo', 'error')
        }
    })

    //console.log(detectedCodes[0].rawValue)

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
  serial: props.product.serial
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
            //redirect nack
            this.$inertia.visit(this.$route('products.index'))
        }
    });
}



</script>

<template>
    <Head title="Producto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Producto</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3 sm:flex gap-[2.75rem] ">
                <div class="mb-4">
                    <span>Editar Producto</span><br>
                </div>
                <div class="overflow-hidden bg-white shadow-xl grow sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
                        <form @submit.prevent="handleEditProduct()" method="POST">

                            <div class="mt-4">
                                <InputLabel for="code_number" value="Cédula" />
                                <TextInput id="code_number" class="block w-full mt-1" type="text" name="code_number" v-model="formData.code_number"  required autofocus />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" class="block w-full mt-1" type="text" name="name" v-model="formData.name"  required autofocus />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="unit_price" value="Precio Unitario ($)" />
                                <TextInput id="unit_price" class="block w-full mt-1" type="number" step="0.01" name="unit_price" v-model="formData.unit_price"  required />
                            </div>

                            <!-- serial -->
                            <div class="mt-4">
                                <InputLabel for="serial" value="Serial" />
                                <TextInput id="serial" class="block w-full mt-1" type="text" name="serial" v-model="formData.serial"  required />
                                <InputError :message="formData.errors.serial" />
                                <button @click="openModal" type="button" class="px-4 py-2 mt-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                    Escanear Serial
                                </button>


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
                    <InputError :message="formData.errors.serial" class="text-2xl font-extrabold" />

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
