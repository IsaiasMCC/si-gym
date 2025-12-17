<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PagoReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::where('estado', 'pagado')->with(['planPago.subscripcion.usuario', 'planPago.subscripcion.membresia']);

        // Filtrar por fecha de pago
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_pago', $request->fecha);
        }

        // Buscar por usuario o membresía
        if ($request->filled('search')) {
            $query->whereHas('planPago.subscripcion.usuario', function($q) use ($request) {
                $q->where('nombres', 'like', "%{$request->search}%")
                  ->orWhere('apellidos', 'like', "%{$request->search}%");
            })->orWhereHas('planPago.subscripcion.membresia', function($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->search}%");
            });
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->paginate(10)->withQueryString();

        return Inertia::render('Reportes/Pagos', [
            'pagos' => $pagos,
            'filters' => $request->only(['search', 'fecha'])
        ]);
    }

    public function export(Request $request)
    {
        $tipo = $request->get('tipo', 'pdf');

        $query = Pago::with(['planPago.subscripcion.usuario', 'planPago.subscripcion.membresia']);

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_pago', $request->fecha);
        }

        if ($request->filled('search')) {
            $query->whereHas('planPago.subscripcion.usuario', function($q) use ($request) {
                $q->where('nombres', 'like', "%{$request->search}%")
                  ->orWhere('apellidos', 'like', "%{$request->search}%");
            })->orWhereHas('planPago.subscripcion.membresia', function($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->search}%");
            });
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->get();

        if ($tipo === 'pdf') {
            return $this->exportPdf($pagos, $request);
        } else {
            return $this->exportExcel($pagos, $request);
        }
    }

    private function exportPdf($pagos, $request)
    {
        $pdf = Pdf::loadView('reportes.pagos-pdf', [
            'pagos' => $pagos,
            'fecha_filtro' => $request->fecha
        ]);

        $pdf->setPaper('letter', 'landscape');
        $filename = 'reporte-pagos-' . date('Y-m-d-His') . '.pdf';

        return $pdf->download($filename);
    }

    private function exportExcel($pagos, $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'REPORTE DE PAGOS');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:H2');

        if ($request->filled('fecha')) {
            $sheet->setCellValue('A3', 'Filtrado por fecha: ' . $request->fecha);
            $sheet->mergeCells('A3:H3');
            $headerRow = 5;
        } else {
            $headerRow = 4;
        }

        $headers = ['ID', 'Usuario', 'Membresía', 'Tipo Pago', 'Monto', 'Saldo', 'Estado', 'Fecha Pago'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col.$headerRow, $header);
            $sheet->getStyle($col.$headerRow)->getFont()->setBold(true);
            $sheet->getStyle($col.$headerRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
            $sheet->getStyle($col.$headerRow)->getFont()->getColor()->setRGB('FFFFFF');
            $col++;
        }

        $row = $headerRow + 1;
        $total = 0;
        foreach ($pagos as $pago) {
            $sheet->setCellValue('A'.$row, $pago->id);
            $sheet->setCellValue('B'.$row, $pago->planPago->subscripcion->usuario->nombres . ' ' . $pago->planPago->subscripcion->usuario->apellidos);
            $sheet->setCellValue('C'.$row, $pago->planPago->subscripcion->membresia->nombre);
            $sheet->setCellValue('D'.$row, $pago->planPago->tipo_pago);
            $sheet->setCellValue('E'.$row, $pago->monto);
            $sheet->setCellValue('F'.$row, $pago->planPago->saldo);
            $sheet->setCellValue('G'.$row, $pago->estado);
            $sheet->setCellValue('H'.$row, $pago->fecha_pago);

            $total += $pago->monto;
            $row++;
        }

        $sheet->setCellValue('G'.$row, 'TOTAL:');
        $sheet->setCellValue('H'.$row, $total);
        $sheet->getStyle('G'.$row.':H'.$row)->getFont()->setBold(true);

        foreach (range('A','H') as $c) $sheet->getColumnDimension($c)->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'reporte-pagos-' . date('Y-m-d-His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}
