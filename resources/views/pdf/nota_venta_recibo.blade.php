<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo Nota de Venta #{{ $notaVenta->id }}</title>
    {{-- Estilos simples integrados que Dompdf puede procesar --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            line-height: 1.4;
            color: #333;
        }
        .recibo-container {
            width: 100%;
            max-width: 300px; /* Tamaño típico de recibo */
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
        }
        .header img {
            max-width: 60px;
            height: auto;
            margin-bottom: 5px;
        }
        .info-block {
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #ccc;
        }
        .info-block p {
            margin: 0;
        }
        .linea {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .linea-detalle {
            margin-bottom: 5px;
            border-bottom: 1px dotted #eee;
        }
        .linea-detalle span {
            display: inline-block;
        }
        .items-header {
            font-weight: bold;
            border-top: 1px dashed #ccc;
            border-bottom: 1px dashed #ccc;
            padding: 5px 0;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }
        .items-header .col-item, .items-body .col-item { width: 50%; }
        .items-header .col-qty, .items-body .col-qty { width: 15%; text-align: right; }
        .items-header .col-price, .items-body .col-price { width: 15%; text-align: right; }
        .items-header .col-total, .items-body .col-total { width: 20%; text-align: right; }

        .items-body .linea-producto {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .totales {
            border-top: 1px dashed #ccc;
            padding-top: 5px;
            text-align: right;
        }
        .totales .total-final {
            font-size: 14px;
            font-weight: bold;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 9px;
            border-top: 1px dashed #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="recibo-container">
        <div class="header">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo">
            @endif
            <h2>RECIBO DE VENTA</h2>
            <p>Av Beni entre 2do y 3er anillo</p>
        </div>

        <div class="info-block">
            <p><strong>Recibo N°:</strong> {{ $notaVenta->id }}</p>
            <p><strong>Fecha/Hora:</strong> {{ $notaVenta->fecha_hora->format('d/m/Y H:i') }}</p>
            <p><strong>Mesa:</strong> {{ $notaVenta->mesa->numero ?? 'N/A' }}</p>
            <p><strong>Atendido por:</strong> {{ $notaVenta->empleado->persona->nombre ?? 'N/A' }}</p>
        </div>

        <div class="info-block">
            <p><strong>Cliente:</strong> {{ $notaVenta->cliente->persona->nombre ?? 'Consumidor Final' }}</p>
        </div>
        
        <div class="items-header">
            <span class="col-item">DESCRIPCIÓN</span>
            <span class="col-qty">CANT</span>
            <span class="col-price">P.UNIT</span>
            <span class="col-total">MONTO</span>
        </div>

        <div class="items-body">
            @php
                $detalles = $notaVenta->orden->detalles ?? collect(); // Asegurarse de tener la colección
            @endphp

            @foreach ($detalles as $detalle)
                <div class="linea-producto">
                    <span class="col-item">{{ $detalle->producto->item->nombre ?? 'Producto Eliminado' }}</span>
                    <span class="col-qty">cant. ({{ $detalle->cantidad }})</span>
                    <span class="col-price">precio. {{ number_format($detalle->precio_unitario, 2) }} Bs.</span>
                    <span class="col-total"> =  {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }} Bs.</span>
                </div>
            @endforeach
        </div>
        
        <div class="totales">
            <div class="linea">
                <span>SUBTOTAL:</span>
                <span>{{ number_format($notaVenta->total, 2) }}</span>
            </div>
            <div class="linea">
                <span>DESCUENTO:</span>
                <span>0.00</span>
            </div>
            <div class="linea total-final">
                <span>TOTAL A PAGAR:</span>
                <span>C$ {{ number_format($notaVenta->total, 2) }}</span>
            </div>
            <div class="linea" style="margin-top: 10px;">
                <span>Método de Pago:</span>
                <span>{{ ucfirst($notaVenta->tipo_pago) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>** GRACIAS POR SU VISITA **</p>
            <p>Recibo generado el: {{ $fechaImpresion }}</p>
        </div>
    </div>
</body>
</html>