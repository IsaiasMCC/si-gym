{{-- resources/views/reportes/reservas-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Reservas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            font-weight: bold;
            background-color: #e5e7eb !important;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE RESERVAS</h1>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
        @if($fecha_filtro)
            <p><strong>Filtrado por fecha:</strong> {{ $fecha_filtro }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Total (Bs)</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeneral = 0; @endphp
            @forelse($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->id }}</td>
                    <td>{{ $reserva->cliente->nombres }} {{ $reserva->cliente->apellidos }}</td>
                    <td>{{ $reserva->fecha }}</td>
                    <td>{{ $reserva->hora }}</td>
                    <td>{{ number_format($reserva->total, 2) }}</td>
                    <td>{{ $reserva->estado_reserva }}</td>
                </tr>
                @php $totalGeneral += $reserva->total; @endphp
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No hay registros</td>
                </tr>
            @endforelse
            
            @if($reservas->count() > 0)
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;"><strong>TOTAL GENERAL:</strong></td>
                    <td><strong>{{ number_format($totalGeneral, 2) }}</strong></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Reservas</p>
    </div>
</body>
</html>