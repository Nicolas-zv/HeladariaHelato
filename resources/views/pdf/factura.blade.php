<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $factura->id }}</title>
    {{-- Estilos para Dompdf (más formales) --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20px;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px;
        }
        .header-left {
            width: 50%;
        }
        .header-right {
            width: 50%;
            text-align: right;
        }
        .header h1 {
            font-size: 18pt;
            color: #333;
            margin: 0;
        }
        .header p {
            margin: 0;
            font-size: 9pt;
        }
        .logo {
            max-width: 80px;
            height: auto;
            margin-bottom: 5px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #555;
            border-bottom: 1px solid #eee;
            margin-top: 15px;
            margin-bottom: 5px;
            padding-bottom: 3px;
        }
        .info-grid {
            margin-bottom: 15px;
            overflow: auto; /* Fix para grid con Dompdf */
        }
        .info-col {
            float: left;
            width: 48%;
            margin-right: 2%;
        }
        .info-col p {
            margin: 2px 0;
            font-size: 9pt;
        }
        
        /* Tabla de Productos */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }
        .items-table th {
            background-color: #f5f5f5;
            color: #333;
            text-transform: uppercase;
        }
        .items-table td.qty, .items-table td.price, .items-table td.total {
            text-align: right;
        }

        /* Totales */
        .totales-container {
            width: 40%;
            float: right;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .total-line {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 10pt;
        }
        .total-line.final {
            font-size: 13pt;
            font-weight: bold;
            color: #1a73e8; /* Azul primario */
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 5px;
        }
        .clear {
            clear: both;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- Encabezado de la Factura --}}
    <div class="header">
        <div class="header-left">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo Empresa" class="logo">
            @endif
            <h1>{{ env('APP_NAME', 'Mi Restaurante') }}</h1>
            {{-- <p>RUC: 1234567-8</p> --}}
            <p>Av Beni entre 2do y 3er anillo</p>
            <p>Teléfono: 78200660</p>
        </div>

        <div class="header-right">
            <h1>FACTURA N° {{ $factura->id }}</h1>
            <p><strong>Fecha de Emisión:</strong> {{ $factura->fecha_hora->format('d/m/Y H:i:s') }}</p>
            <p><strong>Código de Control:</strong> {{ $factura->codigo_control ?? 'N/A' }}</p>
            <p style="margin-top: 10px;"><strong>Nota de Venta Asociada:</strong> NV-{{ $notaVenta->id }}</p>
        </div>
    </div>

    <div class="section-title">Información del Cliente</div>
    <div class="info-grid">
        @php
            $cliente = $notaVenta->cliente->persona ?? null;
        @endphp
        <div class="info-col">
            <p><strong>Nombre:</strong> {{ $cliente->nombre ?? 'Consumidor Final' }}</p>
            <p><strong>Ci:</strong> {{ $cliente->persona->carnet ?? 'N/A' }}</p>
        </div>
        <div class="info-col">
            <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'N/A' }}</p>
        </div>
        <div class="clear"></div>
    </div>
    
    <div class="section-title">Detalles de la Compra</div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 55%;">Descripción</th>
                <th style="width: 15%;" class="qty">Cant.</th>
                <th style="width: 15%;" class="price">P. Unit.</th>
                <th style="width: 15%;" class="total">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->item->nombre ?? 'Producto Eliminado' }}</td>
                <td class="qty">{{ $detalle->cantidad }}</td>
                <td class="price">C$ {{ number_format($detalle->precio_unitario, 2) }}</td>
                <td class="total">C$ {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Bloque de Totales y Resumen --}}
    <div class="totales-container">
        <div class="total-line">
            <span>Subtotal:</span>
            <span>C$ {{ number_format($notaVenta->total, 2) }}</span>
        </div>
        <div class="total-line">
            <span>IVA (0%):</span> {{-- Ajustar según la tasa de tu país --}}
            <span>C$ 0.00</span>
        </div>
        <div class="total-line">
            <span>Descuento:</span>
            <span>C$ 0.00</span>
        </div>
        <div class="total-line final">
            <span>TOTAL FACTURADO:</span>
            <span>C$ {{ number_format($factura->monto, 2) }}</span>
        </div>
        <div class="total-line" style="margin-top: 10px;">
            <span>Método de Pago:</span>
            <span>{{ ucfirst($notaVenta->tipo_pago) }}</span>
        </div>
    </div>

    <div class="clear"></div>

    <div class="footer">
        <p>Factura emitida por {{ $notaVenta->empleado->persona->nombre ?? 'Sistema Automático' }}.</p>
        <p>Documento generado el {{ $fechaImpresion }}.</p>
    </div>
</body>
</html>