<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .factura {
      display: flex;
      flex-direction: column;
      align-items: center;
      background-color: #ffffff;
      padding: 20px;
    }

    .encabezado {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      margin-bottom: 20px;
    }

    .logo {
      display: flex;
      align-items: center;
    }

    .logo img {
      width: 100px;
      height: 100px;
      margin-right: 10px;
    }

    .titulo {
      font-size: 24px;
      color: #6A3989;
    }

    .datos-empresa {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .datos-empresa p {
      margin: 5px 0;
    }

    .zona-envio {
      display: flex;
      flex-direction: row;
      align-items: flex-start;
      margin-bottom: 20px;
    }

    .zona-envio h2 {
      color: #6A3989;
      margin-bottom: 10px;
    }

    .tabla {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .tabla thead {
      background-color: #6A3989;
      color: #ffffff;
    }

    .tabla th,
    .tabla td {
      border: 1px solid #000000;
      padding: 10px;
    }

    .subtotal {
      display: flex;
      justify-content: flex-end;
    }

    .subtotal p {
      margin-left: 10px;
    }
  </style>
</head>
<body>
  <div class="factura">
    <div class="encabezado">
      <div class="logo">
        <img src="public/img/logoInvoice.pdf" alt="Logo">
        <h1 class="titulo">TERAMINER FACTURA</h1>
      </div>
      <div class="datos-empresa">
        <p>Dirección: Dirección de la Empresa</p>
        <p>Teléfono: Teléfono de la Empresa</p>
        <p>Correo: Correo de la Empresa</p>
        <p>RIF: RIF de la Empresa</p>
        <p>Website: Website de la Empresa</p>
      </div>
    </div>

    <div class="zona-envio">
      <h2>Zona de Envío</h2>
      <p>Nombre: Nombre del Cliente</p>
      <p>Tipo: Tipo de Envío</p>
      <p>Ruta: Ruta de Envío</p>
      <h2>Zona de Envío</h2>
      <p>Nombre: Nombre del Cliente</p>
      <p>Tipo: Tipo de Envío</p>
      <p>Ruta: Ruta de Envío</p>
    </div>

    <div class="tabla">
      <h2>CLIENTE</h2>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Código</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Correo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Cliente 1</td>
            <td>Código 1</td>
            <td>Teléfono 1</td>
            <td>Dirección 1</td>
            <td>Correo 1</td>
          </tr>
          <tr>
            <td>Cliente 2</td>
            <td>Código 2</td>
            <td>Teléfono 2</td>
            <td>Dirección 2</td>
            <td>Correo 2</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="tabla">
      <h2>VENDEDOR</h2>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Código</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Correo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Vendedor 1</td>
            <td>Código 1</td>
            <td>Teléfono 1</td>
            <td>Dirección 1</td>
            <td>Correo 1</td>
          </tr>
          <tr>
            <td>Vendedor 2</td>
            <td>Código 2</td>
            <td>Teléfono 2</td>
            <td>Dirección 2</td>
            <td>Correo 2</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="tabla">
      <h2>PRODUCTOS</h2>
      <table>
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unit</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Producto 1</td>
            <td>5</td>
            <td>$10</td>
            <td>$50</td>
          </tr>
          <tr>
            <td>Producto 2</td>
            <td>3</td>
            <td>$15</td>
            <td>$45</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="subtotal">
      <p>Subtotal: $95</p>
      <p>Descuento: $5</p>
      <p>Total: $90</p>
    </div>
  </div>
</body>
</html>
