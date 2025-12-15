<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SeguimientoReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = Seguimiento::with('usuario');

        // Filtrar por fecha
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_registro', $request->fecha);
        }

        // Buscar por nombre o CI del cliente
        if ($request->filled('search')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('nombres', 'like', '%' . $request->search . '%')
                  ->orWhere('apellidos', 'like', '%' . $request->search . '%')
                  ->orWhere('ci', 'like', '%' . $request->search . '%');
            });
        }

        $seguimientos = $query->orderBy('fecha_registro', 'desc')
                               ->paginate(10)
                               ->withQueryString();

        return Inertia::render('Reportes/Seguimientos', [
            'seguimientos' => $seguimientos,
            'filters' => $request->only(['search', 'fecha']),
        ]);
    }

    public function export(Request $request)
    {
        $tipo = $request->get('tipo', 'pdf');
        $query = Seguimiento::with('usuario');

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_registro', $request->fecha);
        }

        if ($request->filled('search')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('nombres', 'like', '%' . $request->search . '%')
                  ->orWhere('apellidos', 'like', '%' . $request->search . '%')
                  ->orWhere('ci', 'like', '%' . $request->search . '%');
            });
        }

        $seguimientos = $query->orderBy('fecha_registro', 'desc')->get();

        if ($tipo === 'pdf') {
            return $this->exportPdf($seguimientos, $request);
        } else {
            return $this->exportExcel($seguimientos, $request);
        }
    }

    private function exportPdf($seguimientos, $request)
    {
        $pdf = Pdf::loadView('reportes.seguimientos-pdf', [
            'seguimientos' => $seguimientos,
            'fecha_filtro' => $request->fecha,
        ]);
        $pdf->setPaper('letter', 'landscape');
        $filename = 'reporte-seguimientos-' . date('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    private function exportExcel($seguimientos, $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Título
        $sheet->setCellValue('A1', 'REPORTE DE SEGUIMIENTOS');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Fecha
        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:H2');

        if ($request->filled('fecha')) {
            $sheet->setCellValue('A3', 'Filtrado por fecha: ' . $request->fecha);
            $sheet->mergeCells('A3:H3');
            $headerRow = 5;
        } else {
            $headerRow = 4;
        }

        // Encabezados
        $headers = ['ID', 'Cliente', 'CI', 'Peso', 'Altura', 'IMC', '% Grasa', 'Observaciones', 'Fecha'];
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
        foreach ($seguimientos as $s) {
            $sheet->setCellValue('A' . $row, $s->id);
            $sheet->setCellValue('B' . $row, $s->usuario->nombres . ' ' . $s->usuario->apellidos);
            $sheet->setCellValue('C' . $row, $s->usuario->ci);
            $sheet->setCellValue('D' . $row, $s->peso);
            $sheet->setCellValue('E' . $row, $s->altura);
            $sheet->setCellValue('F' . $row, $s->imc);
            $sheet->setCellValue('G' . $row, $s->porcentaje_grasa);
            $sheet->setCellValue('H' . $row, $s->observaciones);
            $sheet->setCellValue('I' . $row, $s->fecha_registro);
            $row++;
        }

        // Ajustar anchos
        foreach (range('A','I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Bordes
        $sheet->getStyle('A' . $headerRow . ':I' . ($row-1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $filename = 'reporte-seguimientos-' . date('Y-m-d-His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);
        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
