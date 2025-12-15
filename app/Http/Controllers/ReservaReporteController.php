<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReservaReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = Reserva::with('cliente');

        // Filtrar por fecha si se proporciona
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        // Buscar por nombre de cliente o ID
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('cliente', function($q2) use ($request) {
                      $q2->where('nombres', 'like', '%' . $request->search . '%')
                         ->orWhere('apellidos', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $reservas = $query->orderBy('fecha', 'desc')
                          ->orderBy('hora', 'desc')
                          ->paginate(10)
                          ->withQueryString();

        return Inertia::render('Reportes/Reservas', [
            'reservas' => $reservas,
            'filters' => $request->only(['search', 'fecha'])
        ]);
    }

    public function export(Request $request)
    {
        $tipo = $request->get('tipo', 'pdf');
        
        $query = Reserva::with('cliente');

        // Aplicar los mismos filtros
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('cliente', function($q2) use ($request) {
                      $q2->where('nombres', 'like', '%' . $request->search . '%')
                         ->orWhere('apellidos', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $reservas = $query->orderBy('fecha', 'desc')
                          ->orderBy('hora', 'desc')
                          ->get();

        if ($tipo === 'pdf') {
            return $this->exportPdf($reservas, $request);
        } else {
            return $this->exportExcel($reservas, $request);
        }
    }

    private function exportPdf($reservas, $request)
    {
        $pdf = Pdf::loadView('reportes.reservas-pdf', [
            'reservas' => $reservas,
            'fecha_filtro' => $request->fecha
        ]);

        $pdf->setPaper('letter', 'portrait');
        
        $filename = 'reporte-reservas-' . date('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }

    private function exportExcel($reservas, $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Título del reporte
        $sheet->setCellValue('A1', 'REPORTE DE RESERVAS');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Fecha del reporte
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:F2');
        
        if ($request->filled('fecha')) {
            $sheet->setCellValue('A3', 'Filtrado por fecha: ' . $request->fecha);
            $sheet->mergeCells('A3:F3');
            $headerRow = 5;
        } else {
            $headerRow = 4;
        }

        // Encabezados
        $headers = ['ID', 'Cliente', 'Fecha', 'Hora', 'Total (Bs)', 'Estado'];
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

        foreach ($reservas as $reserva) {
            $sheet->setCellValue('A' . $row, $reserva->id);
            $sheet->setCellValue('B' . $row, $reserva->cliente->nombres . ' ' . $reserva->cliente->apellidos);
            $sheet->setCellValue('C' . $row, $reserva->fecha);
            $sheet->setCellValue('D' . $row, $reserva->hora);
            $sheet->setCellValue('E' . $row, $reserva->total);
            $sheet->setCellValue('F' . $row, $reserva->estado_reserva);
            
            $totalGeneral += $reserva->total;
            $row++;
        }

        // Total general
        $sheet->setCellValue('D' . $row, 'TOTAL:');
        $sheet->setCellValue('E' . $row, $totalGeneral);
        $sheet->getStyle('D' . $row . ':E' . $row)->getFont()->setBold(true);

        // Ajustar anchos de columna
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);

        // Bordes
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':F' . ($row))->applyFromArray($styleArray);

        // Guardar archivo
        $writer = new Xlsx($spreadsheet);
        $filename = 'reporte-reservas-' . date('Y-m-d-His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}