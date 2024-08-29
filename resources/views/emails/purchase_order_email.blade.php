<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #dddddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <p>Buen día a todos,</p>
    <p>Espero que se encuentren muy bien.</p>
    <p>Quisiera solicitar su ayuda con el procesamiento de la siguiente orden de compra:</p>

    <table>
        <thead>
            <tr>
                <th>REFERENCIA</th>
                <th>CÓDIGO</th>
                <th>CANTIDAD</th>
                <th>PRECIO U</th>
                <th>PRECIO TOTAL</th>
                <th>LUGAR DE ENTREGA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->products as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->code }}</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td>{{ number_format(($product->price/ $purchaseOrder->trm), 2) }}</td>
                <td>{{ number_format((($product->price * $product->pivot->quantity) / $purchaseOrder->trm ), 2) }}</td>
                <td>{{ $purchaseOrder->getBranchOfficeName($product) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td><strong>TOTAL</strong></td>
                <td><strong>{{ number_format($purchaseOrder->products->sum(function($product) use ($purchaseOrder) {
                    return ($product->price * $product->pivot->quantity)/ $purchaseOrder->trm;
                }), 2) }} USD</strong></td>
            </tr>
        </tbody>
    </table>

    <p><strong>TRM:</strong> 
        @if($purchaseOrder->trm_updated_at)
            Trm de cliente ${{ number_format($purchaseOrder->trm, 2) }}
        @else
            Trm del día {{ optional($purchaseOrder->created_at)->format('d/m/Y') }} por ${{ number_format($purchaseOrder->trm, 2) }}
        @endif
    </p>
    
    <p><strong>Observaciones:</strong> {{ $purchaseOrder->observations }}</p>
    <p>Condiciones de Compra:</p>
    <ul>
        <li>1. LA MERCANCÍA NO SERÁ RECIBIDA DESPUÉS DE VENCIDA LA FECHA DE ENTREGA PACTADA.</li>
        <li>2. PARA EFECTOS DEL CIERRE CONTABLE LA FECHA LÍMITE PARA RECIBIR FACTURAS ES EL ÚLTIMO DÍA HÁBIL DEL MES, EL VENCIMIENTO PARA PAGO DE LA FACTURA ES...</li>
        <li>3. CUALQUIER INCONVENIENTE POR FAVOR CONSULTAR NUESTRO MANUAL DE PROVEEDORES INGRESANDO A LA PÁGINA...</li>
        <li>4. CON CADA ENTREGA DEBE ADJUNTAR EL CERTIFICADO DE ANÁLISIS, CERTIFICADO DE MICROBIOLOGÍA O CERTIFICADO DE SEGURIDAD SEGÚN EL CASO.</li>
        <li>5. LOS ÍTEMS SOLICITADOS EN ESTA ORDEN DE COMPRA, ESTÁN SUJETOS A REVISIÓN DEFINITIVA PREVIO VISTO BUENO DE NUESTRO DEPARTAMENTO DE CONTROL DE CALIDAD...</li>
        <li>6. REPORTAR DE INMEDIATO CUALQUIER INCONSISTENCIA RELACIONADA A LAS ESPECIFICACIONES DE ESTA ORDEN DE COMPRA.</li>
    </ul>

    <p>Agradezco su atención. Quedo muy atento a sus comentarios.</p>
</body>
</html>
