<?php
// app/Http/Controllers/ServicioReporteController.php

namespace App\Http\Controllers;

use App\Models\PresupuestoServicio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ServicioReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = PresupuestoServicio::with(['servicio', 'reserva.cliente', 'trabajador']);

        // Filtrar por fecha de la reserva
        if ($request->filled('fecha')) {
            $query->whereHas('reserva', function ($q) use ($request) {
                $q->whereDate('fecha', $request->fecha);
            });
        }

        // Buscar por nombre de servicio, cliente o trabajador
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('detalles', 'like', '%' . $request->search . '%')
                    ->orWhereHas('servicio', function ($q2) use ($request) {
                        $q2->where('nombre', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('reserva.cliente', function ($q2) use ($request) {
                        $q2->where('nombres', 'like', '%' . $request->search . '%')
                            ->orWhere('apellidos', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('trabajador', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $servicios = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Reportes/Servicios', [
            'servicios' => $servicios,
            'filters' => $request->only(['search', 'fecha'])
        ]);
    }

    public function export(Request $request)
    {
        $tipo = $request->get('tipo', 'pdf');

        $query = PresupuestoServicio::with(['servicio', 'reserva.cliente', 'trabajador']);

        // Aplicar los mismos filtros
        if ($request->filled('fecha')) {
            $query->whereHas('reserva', function ($q) use ($request) {
                $q->whereDate('fecha', $request->fecha);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('detalles', 'like', '%' . $request->search . '%')
                    ->orWhereHas('servicio', function ($q2) use ($request) {
                        $q2->where('nombre', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('reserva.cliente', function ($q2) use ($request) {
                        $q2->where('nombres', 'like', '%' . $request->search . '%')
                            ->orWhere('apellidos', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('trabajador', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $servicios = $query->orderBy('created_at', 'desc')->get();

        if ($tipo === 'pdf') {
            return $this->exportPdf($servicios, $request);
        } else {
            return $this->exportExcel($servicios, $request);
        }
    }

    private function exportPdf($servicios, $request)
    {
        $pdf = Pdf::loadView('reportes.servicios-pdf', [
            'servicios' => $servicios,
            'fecha_filtro' => $request->fecha
        ]);

        $pdf->setPaper('letter', 'landscape'); // Horizontal para más columnas

        $filename = 'reporte-servicios-' . date('Y-m-d-His') . '.pdf';

        return $pdf->download($filename);
    }

    private function exportExcel($servicios, $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Título del reporte
        $sheet->setCellValue('A1', 'REPORTE DE SERVICIOS');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Fecha del reporte
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:G2');

        if ($request->filled('fecha')) {
            $sheet->setCellValue('A3', 'Filtrado por fecha: ' . $request->fecha);
            $sheet->mergeCells('A3:G3');
            $headerRow = 5;
        } else {
            $headerRow = 4;
        }

        // Encabezados
        $headers = ['ID', 'Servicio', 'Detalles', 'Cliente', 'Trabajador', 'Fecha', 'Precio (Bs)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $sheet->getStyle($col . $headerRow)->getFont()->setBold(true);
            $sheet->getStyle($col . $headerRow)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4F46E5');
            $sheet->getStyle($col . $headerRow)->getFont()->getColor()->setRGB('FFFFFF');
            $col++;
        }

        // Datos
        $row = $headerRow + 1;
        $totalGeneral = 0;

        foreach ($servicios as $servicio) {
            $sheet->setCellValue('A' . $row, $servicio->id);
            $sheet->setCellValue('B' . $row, $servicio->servicio->nombre);
            $sheet->setCellValue('C' . $row, $servicio->detalles);
            $sheet->setCellValue('D' . $row, $servicio->reserva->cliente->nombres . ' ' . $servicio->reserva->cliente->apellidos);
            $sheet->setCellValue('E' . $row, $servicio->trabajador ? $servicio->trabajador->name : 'Sin asignar');
            $sheet->setCellValue('F' . $row, $servicio->reserva->fecha);
            $sheet->setCellValue('G' . $row, $servicio->precio);

            $totalGeneral += $servicio->precio;
            $row++;
        }

        // Total general
        $sheet->setCellValue('F' . $row, 'TOTAL:');
        $sheet->setCellValue('G' . $row, $totalGeneral);
        $sheet->getStyle('F' . $row . ':G' . $row)->getFont()->setBold(true);

        // Ajustar anchos de columna
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        // Bordes
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':G' . ($row))->applyFromArray($styleArray);

        // Guardar archivo
        $writer = new Xlsx($spreadsheet);
        $filename = 'reporte-servicios-' . date('Y-m-d-His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);

        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
