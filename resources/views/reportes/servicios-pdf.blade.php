{{-- resources/views/reportes/servicios-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Servicios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #4F46E5;
            margin-bottom: 5px;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            font-weight: bold;
            background-color: #e5e7eb !important;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE SERVICIOS</h1>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
        @if($fecha_filtro)
            <p><strong>Filtrado por fecha:</strong> {{ $fecha_filtro }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 18%;">Servicio</th>
                <th style="width: 22%;">Detalles</th>
                <th style="width: 18%;">Cliente</th>
                <th style="width: 15%;">Trabajador</th>
                <th style="width: 10%;">Fecha</th>
                <th style="width: 12%;">Precio (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeneral = 0; @endphp
            @forelse($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->id }}</td>
                    <td>{{ $servicio->servicio->nombre }}</td>
                    <td>{{ $servicio->detalles }}</td>
                    <td>{{ $servicio->reserva->cliente->nombres }} {{ $servicio->reserva->cliente->apellidos }}</td>
                    <td>{{ $servicio->trabajador ? $servicio->trabajador->name : 'Sin asignar' }}</td>
                    <td>{{ $servicio->reserva->fecha }}</td>
                    <td>{{ number_format($servicio->precio, 2) }}</td>
                </tr>
                @php $totalGeneral += $servicio->precio; @endphp
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No hay registros</td>
                </tr>
            @endforelse
            
            @if($servicios->count() > 0)
                <tr class="total-row">
                    <td colspan="6" style="text-align: right;"><strong>TOTAL GENERAL:</strong></td>
                    <td><strong>{{ number_format($totalGeneral, 2) }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Reservas</p>
    </div>
</body>
</html>