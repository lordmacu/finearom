<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de compra</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', sans-serif;
        }

        .container {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 50px;
        }

        .order-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .order-info div {
            margin-left: 20px;
            text-align: left;
        }

        .order-info div span {
            display: block;
        }

        .section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .section h3 {
            margin: 0;
        }

        .section div {
            margin-top: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 0px;
            padding: 12px;
            text-align: left;
            font-family: 'Helvetica', sans-serif;
        }

        .table th {
            color: #6d6e72;
            border: 0px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .totals {
            margin-top: 20px;
            text-align: right;
        }

        .totals-table {
            width: 50%;
            float: right;
            border-collapse: collapse;
        }

        .totals-table th,
        .totals-table td {
            padding: 8px;
            text-align: right;
            font-family: 'Helvetica', sans-serif;
        }

        .totals-table th {
            text-align: left;
            font-family: 'Helvetica', sans-serif;
        }

        .observations {
            margin-top: 20px;
            margin-bottom: 2rem;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .totals-table {
            width: 35rem;
            border-collapse: collapse;
        }

        .totals-table th,
        .totals-table td {
            padding: 8px;
            text-align: right;
            font-family: 'Helvetica', sans-serif;
        }

        .totals-table th {
            text-align: left;
            font-family: 'Helvetica', sans-serif;
        }

        .shipping-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        .shipping-info table {
            width: 100%;
            table-layout: fixed;
        }

        .shipping-info td {
            padding: 3px;
        }

        .shipping-info td:first-child {
            text-align: left;
            font-weight: bold;
        }

        .shipping-info td:last-child {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="order-info">
            <table style="margin-bottom: 20px">
                <tr>
                    <td style="width: 50%; text-align: left;">
                        <img width="50px" src="{{ public_path('images/logo.png') }}" alt="Logo">
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <p style="font-size: 24px; font-weight: 500; margin: 0;"><strong>Finearom</strong></p>
                    </td>
                </tr>
            </table>

            <table style="color: gray; width: 100%;">
                <tr>
                    <td style="width: 45%">
                        <table>
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 55%">
                        <table style="background-color: #f9f9f9;padding: 20px; width: 100%;border-radius:10px">
                            <tr>
                                <td style="text-align: left;"><strong>Consecutivo:</strong></td>
                                <td style="text-align: right;">{{ $purchaseOrder->order_consecutive }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;"><strong>Fecha de Entrega :</strong></td>
                                <td style="text-align: right;">{{ $purchaseOrder->required_delivery_date }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <table style="width: 100%; margin-bottom: 20px;">
            <tbody>
                <tr>
                    <td style="width: 50%;">
                        <div style="color: #504d5e; border: solid #e1e1e1 1px; margin: 14px; padding: 13px; border-radius: 7px; margin-left: 0px;">
                            <h3 style="margin-top: 0px;">{{ $purchaseOrder->client->client_name }}</h3>
                            <div style="line-height: 20px">{{ $purchaseOrder->contact }}</div>
                        </div>
                    </td>
                    <td style="width: 50%; position: relative;">
                        <div class="shipping-info" style="color: #504d5e; border: solid #e1e1e1 1px; margin: 14px; padding: 13px; border-radius: 7px; position: absolute; top: 0px; width: 92%; height: fit-content; line-height: 21px;">
                            <table>
                                <tr>
                                    <td>Teléfono:</td>
                                    <td>{{ $purchaseOrder->phone }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 50px relative overflow-x-auto">
            <table class="table">
                <thead>
                    <tr style="border-bottom: 2px solid #ebebeb; background-color:#e1e1e1;">
                        <th style="color: #686868; font-family: 'Helvetica';"><strong>PRODUCTO</strong></th>
                        <th style="color: #686868; font-family: 'Helvetica', sans-serif;"><strong>PRECIO</strong></th>
                        <th style="color: #686868; font-family: 'Helvetica', sans-serif;"><strong>CANTIDAD</strong></th>
                        <th style="color: #686868; font-family: 'Helvetica', sans-serif;"><strong>TOTAL</strong></th>
                        <th style="color: #686868; font-family: 'Helvetica', sans-serif;"><strong>SUCURSAL</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ number_format($product->price * $exchange, 2) }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ number_format($product->price * $exchange * $product->pivot->quantity, 2) }}</td>
                        <td>{{ $purchaseOrder->getBranchOfficeName($product) }}</td> <!-- Show the branch office name -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <th style="text-align: right; color: #686868; line-height: 50px; font-family: 'Helvetica', sans-serif;">Total parcial</th>
                    <td style="color: #686868; font-size: 18px; font-family: 'Helvetica', sans-serif;"><strong>{{ number_format($subtotal, 2) }} USD</strong></td>
                </tr>
                <tr>
                    <td style="text-align: right; color: #686868; font-family: 'Helvetica', sans-serif;">Iva:</td>
                    <td style="font-family: 'Helvetica', sans-serif;">{{ number_format($iva, 2) }} USD</td>
                </tr>
                <tr>
                    <td style="text-align: right; color: #686868; font-family: 'Helvetica', sans-serif;">Retefuente y RetelICA:</td>
                    <td style="font-family: 'Helvetica', sans-serif;">{{ number_format($reteica, 2) }} USD</td>
                </tr>
                <tr>
                    <th style="text-align: right; color: #686868; font-family: 'Helvetica', sans-serif;"><strong>Total:</strong></th>
                    <td style="color: #686868; font-size: 18px; font-family: 'Helvetica', sans-serif;"><strong>{{ number_format($total, 2) }} USD</strong></td>
                </tr>
            </table>
        </div>

        @if($purchaseOrder->observations)
        <div class="observations">
            <div style="color: #686868; font-weight: 300; font-family: 'Helvetica', sans-serif;"><strong>Observaciones</strong></div>
            <div style="margin-top: 10px; font-family: 'Helvetica', sans-serif;">{{ $purchaseOrder->observations }}</div>
        </div>
        @endif

    </div>

</body>

</html>
