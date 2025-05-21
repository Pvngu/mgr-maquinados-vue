<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin-bottom: 0;
        }
        .header p {
            margin-top: 5px;
            color: #666;
        }
        .order-info {
            margin-bottom: 20px;
        }
        .order-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-info td {
            padding: 5px;
            vertical-align: top;
        }
        .order-items {
            margin-bottom: 20px;
        }
        .order-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-items th, .order-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .order-items th {
            background-color: #f2f2f2;
        }
        .order-total {
            text-align: right;
            margin-top: 20px;
            margin-bottom: 40px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ORDEN DE TRABAJO</h1>
        <p>MGR Maquinados</p>
        <p>Fecha: {{ $date }}</p>
    </div>

    <div class="order-info">
        <table>
            <tr>
                <td width="50%">
                    <strong>Información del Cliente:</strong><br>
                    {{ $order->client->name ?? 'N/A' }}<br>
                    {{ $order->contact_name }}<br>
                    {{ $order->contact_email }}<br>
                    {{ $order->contact_phone }}
                </td>
                <td width="50%">
                    <strong>Información del Pedido:</strong><br>
                    Orden #: {{ $order->id }}<br>
                    Fecha de Pedido: {{ $order->order_date }}<br>
                    Atendido por: {{ $order->user->name ?? 'N/A' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="order-items">
        <h3>Detalles del Pedido</h3>
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="45%">Producto</th>
                    <th width="15%">Cantidad</th>
                    <th width="15%">Precio</th>
                    <th width="20%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Producto no disponible' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="order-total">
        <h3>Total: ${{ number_format($order->total_amount, 2) }}</h3>
    </div>

    <div class="footer">
        <p>Este documento es un comprobante de su orden de trabajo. Gracias por su preferencia.</p>
    </div>
</body>
</html>
