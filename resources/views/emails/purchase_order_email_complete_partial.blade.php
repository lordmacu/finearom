<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra</title>
</head>
<body>
    <p>Buen día estimado cliente,</p>
    <p>
        Confirmamos que el día <b>{{ $purchaseOrder->dispatch_date }}</b> se realizó el despacho del pedido en referencia bajo la guía <b>No {{ $purchaseOrder->tracking_number }}</b>.
        Número de factura <b>{{ $purchaseOrder->invoice_number }}</b>.
    </p>

    @if(!empty($purchaseOrder->observations_extra))
        <h3>Observaciones:</h3>
        <p>{{ $purchaseOrder->observations_extra }}</p>
    @endif
    
    <p>Agradecemos su atención.</p>
    <p>Cordialmente,</p>
    <p>{{ $purchaseOrder->sender_name }}</p>
</body>
</html>
