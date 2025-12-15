<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Pagos</title>
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
            font-weight: bold;
            font-size: 10px;
            text-align: left;
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
        <h1>REPORTE DE PAGOS</h1>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
        @if ($fecha_filtro)
            <p><strong>Filtrado por fecha:</strong> {{ $fecha_filtro }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Membresía</th>
                <th>Tipo Pago</th>
                <th>Monto (Bs)</th>
                <th>Saldo (Bs)</th>
                <th>Estado</th>
                <th>Fecha Pago</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($pagos as $pago)
                <tr>
                    <td>{{ $pago->id }}</td>
                    <td>{{ $pago->planPago->subscripcion->usuario->nombres }}
                        {{ $pago->planPago->subscripcion->usuario->apellidos }}</td>
                    <td>{{ $pago->planPago->subscripcion->membresia->nombre }}</td>
                    <td>{{ $pago->planPago->tipo_pago }}</td>
                    <td>{{ number_format($pago->monto, 2) }}</td>
                    <td>{{ number_format($pago->planPago->saldo, 2) }}</td>
                    <td>{{ $pago->estado }}</td>
                    <td>{{ $pago->fecha_pago }}</td>
                </tr>
                @php $total += $pago->monto; @endphp
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;">No hay registros</td>
                </tr>
            @endforelse
            @if ($pagos->count() > 0)
                <tr class="total-row">
                    <td colspan="4" style="text-align:right;"><strong>TOTAL GENERAL:</strong></td>
                    <td colspan="4"><strong>{{ number_format($total, 2) }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Gym</p>
    </div>
</body>

</html>
