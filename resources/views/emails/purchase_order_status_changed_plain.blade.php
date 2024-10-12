<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de la Orden de Compra Actualizado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #fefefe;
            color: grey;
            text-align: center;
            padding: 20px 0;
        }
        .header img {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            color: grey;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.5;
        }
        .content ul {
            padding-left: 20px;
            list-style-type: none;
        }
        .content ul li {
            margin: 5px 0;
        }
        .footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo de la Empresa">
            <h1>Estado de la Orden de Compra Actualizado</h1>
        </div>
        <div class="content">

            <p>El estado de su orden de compra con el consecutivo <strong>{{ $purchaseOrder->order_consecutive }}</strong> ha sido actualizado a <strong>
            @php
                $statusTranslations = [
                    'pending' => 'Pendiente',
                    'processing' => 'En Proceso',
                    'completed' => 'Completada',
                    'cancelled' => 'Cancelada'
                ];
                echo $statusTranslations[$purchaseOrder->status] ?? 'Estado Desconocido';
            @endphp
            </strong>.</p>
            <p>Detalles de la orden:</p>
            <ul>
                <li>Fecha de creación: {{ $purchaseOrder->order_creation_date }}</li>
                <li>Fecha de entrega requerida: {{ $purchaseOrder->required_delivery_date }}</li>
                <li>Dirección de entrega: {{ $purchaseOrder->delivery_address }}</li>
            </ul>
            <p>Gracias por su preferencia.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Finearom. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
