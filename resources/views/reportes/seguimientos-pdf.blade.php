{{-- resources/views/reportes/seguimientos-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Seguimientos</title>
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
        <h1>REPORTE DE SEGUIMIENTOS</h1>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
        @if($fecha_filtro)
            <p><strong>Filtrado por fecha:</strong> {{ $fecha_filtro }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 20%;">Usuario</th>
                <th style="width: 10%;">Peso (kg)</th>
                <th style="width: 10%;">Altura (m)</th>
                <th style="width: 10%;">IMC</th>
                <th style="width: 10%;">% Grasa</th>
                <th style="width: 25%;">Observaciones</th>
                <th style="width: 10%;">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($seguimientos as $seg)
                <tr>
                    <td>{{ $seg->id }}</td>
                    <td>{{ $seg->usuario->nombres }} {{ $seg->usuario->apellidos }}</td>
                    <td>{{ number_format($seg->peso, 2) }}</td>
                    <td>{{ number_format($seg->altura, 2) }}</td>
                    <td>{{ $seg->imc !== null ? number_format($seg->imc, 2) : '-' }}</td>
                    <td>{{ $seg->porcentaje_grasa !== null ? number_format($seg->porcentaje_grasa, 2) : '-' }}</td>
                    <td>{{ $seg->observaciones ?? '-' }}</td>
                    <td>{{ $seg->fecha_registro }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No hay registros</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Gestión de Gym</p>
    </div>
</body>
</html>
