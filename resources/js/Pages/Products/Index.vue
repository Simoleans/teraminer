<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link,router  } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
import { ref, computed, onMounted } from 'vue'
const result = ref('')

  function onDetect(detectedCodes) {
    console.log(detectedCodes)
    result.value = JSON.stringify(detectedCodes.map((code) => code.rawValue))
  }


/*** select camera ***/

const selectedDevice = ref(null)
const devices = ref([])

onMounted(async () => {

    console.log('mounted')
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

const barcodeFormats = ref({
  aztec: false,
  code_128: false,
  code_39: false,
  code_93: false,
  codabar: false,
  databar: false,
  databar_expanded: false,
  data_matrix: false,
  dx_film_edge: false,
  ean_13: false,
  ean_8: false,
  itf: false,
  maxi_code: false,
  micro_qr_code: false,
  pdf417: false,
  qr_code: true,
  rm_qr_code: false,
  upc_a: false,
  upc_e: false,
  linear_codes: false,
  matrix_codes: false
})

const selectedBarcodeFormats = computed(() => {
  return Object.keys(barcodeFormats.value).filter((format) => barcodeFormats.value[format])
})

/*** error handling ***/

const error = ref('')

function onError(err) {
  error.value = `[${err.name}]: `

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

</script>

<template>
    <Head title="Productos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Productos</h2>
        </template>

        <select v-model="selectedDevice">
            <option
              v-for="device in devices"
              :key="device.label"
              :value="device"
            >
              {{ device.label }}
            </option>
          </select>

          <p class="error">{{ error }}</p>

    <p class="decode-result">
      Last result: <b>{{ result }}</b>
    </p>

        <div>
        <qrcode-stream
         :constraints="{ deviceId: selectedDevice.deviceId }"
          :track="trackFunctionSelected.value"
          :formats="selectedBarcodeFormats"
          @error="onError"
          @detect="onDetect"
          v-if="selectedDevice !== null"
        />
        <p
          v-else
          class="error"
        >
          No cameras on this device
        </p>
      </div>

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
                            <Column style="min-width:8rem">
                                <template #body="{data}">
                                    <div class="flex justify-between">
                                        <Link :href="route('products.camara')" class="inline-block px-6 py-2 text-xs font-medium leading-tight text-blue-600 uppercase transition duration-150 ease-in-out border-2 border-blue-600 rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0">
                                            <i class="pi pi-pencil" />
                                        </Link>
                                        <button type="button" @click="handleDelete(data.id)" class="inline-block px-6 py-2 text-xs font-medium leading-tight text-red-600 uppercase transition duration-150 ease-in-out border-2 border-red-600 rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0">
                                            <i class="pi pi-trash" />
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
    </AuthenticatedLayout>
</template>
