<template>
    <div class="factura">
      <div class="envio">
        <h2>Zona de Envío</h2>
        <p>Nombre: {{ envio.nombre }}</p>
        <p>Tipo: {{ envio.tipo }}</p>
        <p>Ruta: {{ envio.ruta }}</p>
      </div>

      <div class="productos">
        <h2>Productos Vendidos</h2>
        <table>
          <thead>
            <tr>
              <th>Productos</th>
              <th>Cantidad Total Individual</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="producto in productos" :key="producto.id">
              <td>{{ producto.nombre }}</td>
              <td>{{ producto.cantidad }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="subtotal">
        <p>Subtotal: {{ subtotal }}</p>
        <p>Descuento: {{ descuento }}</p>
        <p>Total: {{ total }}</p>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';

  const envio = ref({
    nombre: 'Nombre de Envío',
    tipo: 'Tipo de Envío',
    ruta: 'Ruta de Envío',
  });

  const productos = ref([
    { id: 1, nombre: 'Producto 1', cantidad: 5 },
    { id: 2, nombre: 'Producto 2', cantidad: 3 },
    { id: 3, nombre: 'Producto 3', cantidad: 2 },
  ]);

  const subtotal = ref(100);
  const descuento = ref(10);
  const total = ref(subtotal.value - descuento.value);
  </script>

  <style scoped>
  .factura {
    font-family: Arial, sans-serif;
    background-color: #6A3989;
    color: white;
    padding: 20px;
  }

  .envio {
    background-color: #6A3989;
    padding: 10px;
    margin-bottom: 20px;
  }

  .envio h2 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .envio p {
    margin: 5px 0;
  }

  .productos table {
    width: 100%;
    border-collapse: collapse;
  }

  .productos th,
  .productos td {
    padding: 10px;
    border-bottom: 1px solid white;
  }

  .subtotal {
    text-align: right;
  }

  .subtotal p {
    margin: 5px 0;
  }
  </style>
