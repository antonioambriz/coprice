<?php

namespace App\Exports;

use App\Models\WithdrawalItem;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SamaExport implements WithEvents, ShouldAutoSize
{
    public function __construct(
        private string $from,
        private string $to
    ) {}

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ── DATOS ────────────────────────────────────────────────
                $items = WithdrawalItem::with(['withdrawal.generator', 'withdrawal.manifest', 'withdrawal.finalDestination', 'waste'])
                    ->whereHas('withdrawal', fn ($q) => $q
                        ->whereBetween('reception_date', [$this->from, $this->to])
                    )
                    ->orderBy('created_at')
                    ->get();

                // ── CABECERA ─────────────────────────────────────────────
                // Fila 1: vacía
                // Fila 2: título
                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A2', 'BITÁCORA DE RESIDUOS DE MANEJO ESPECIAL');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Fila 3: periodo
                $sheet->mergeCells('A3:J3');
                $sheet->setCellValue('A3', 'Período: ' . \Carbon\Carbon::parse($this->from)->format('d/m/Y') . ' — ' . \Carbon\Carbon::parse($this->to)->format('d/m/Y'));
                $sheet->getStyle('A3')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Fila 9: texto legal
                $sheet->mergeCells('A9:J9');
                $sheet->setCellValue('A9', 'Con fundamento en los artículos 7º fracciones II, IV VI; 134 fracciones I, II y III; 135 fracción III y 149 de la Ley General del Equilibrio Ecológico y la Protección al Ambiente.');
                $sheet->getStyle('A9')->getAlignment()->setWrapText(true);
                $sheet->getRowDimension(9)->setRowHeight(30);

                // Fila 11
                $sheet->mergeCells('A11:J11');
                $sheet->setCellValue('A11', 'DESCRIPCIÓN MENSUAL DETALLADA DEL SISTEMA DE MANEJO:');
                $sheet->getStyle('A11')->getFont()->setBold(true);

                // Fila 12
                $sheet->mergeCells('A12:J12');
                $sheet->setCellValue('A12', 'Esta tabla deberá ser llenada con información de cada movimiento del manifiesto.');

                // ── ENCABEZADOS DE COLUMNAS (filas 14-21) ────────────────
                // Fila 14
                $sheet->mergeCells('A14:C14');
                $sheet->setCellValue('A14', 'RESIDUOS');
                $sheet->mergeCells('D14:I14');
                $sheet->setCellValue('D14', 'FORMA DE MANEJO');
                $sheet->mergeCells('J14:J16');
                $sheet->setCellValue('J14', 'Num. de manifiesto de entrega-recepción');

                // Fila 15
                $sheet->mergeCells('A15:A16');
                $sheet->setCellValue('A15', 'Nombre de los residuos de manejo especial');
                $sheet->mergeCells('B15:B16');
                $sheet->setCellValue('B15', 'Fecha de generación, acopio y/o recolectado');
                $sheet->mergeCells('C15:C16');
                $sheet->setCellValue('C15', 'Estado físico');
                $sheet->mergeCells('D15:E21');
                $sheet->setCellValue('D15', "Ingreso\n\nCantidad\n(Litros, kilogramos,\ntoneladas, metros cúbicos)");
                $sheet->mergeCells('F15:F16');
                $sheet->setCellValue('F15', 'Tipo de envasado (envase, embalaje, contenedor, etc.)');
                $sheet->mergeCells('G15:H15');
                $sheet->setCellValue('G15', 'Capacidad del recipiente empleado');
                $sheet->mergeCells('I15:I16');
                $sheet->setCellValue('I15', 'Etapa y nombre de la empresa encargada de destino final');

                // Fila 20
                $sheet->setCellValue('G20', 'Cantidad (m3)');
                $sheet->setCellValue('H20', 'Cantidad (kg)');

                // Estilos de encabezados
                $headerRange = 'A14:J21';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 9],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DDEEDD']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // ── DATOS (desde fila 22) ─────────────────────────────────
                $row = 22;
                foreach ($items as $item) {
                    $w          = $item->withdrawal;
                    $fecha      = $w?->reception_date?->format('d/m/Y') ?? '';
                    $manifiesto = $w?->manifest?->manifest_number ?? 'S/M';
                    $destFinal  = $w?->finalDestination?->display_name ?? '';
                    $destino    = trim(($w?->treatment_stage ?? '') . ' ' . $destFinal);
                    if (empty($destino)) $destino = 'COPRICE';

                    // Separar capacidad: si tiene número es m3, si no, N/A
                    $cap    = $item->container_capacity ?? '';
                    $capM3  = preg_match('/[\d.]+/', $cap, $m) ? $m[0] : 'N/A';
                    $capKg  = 'N/A';

                    $sheet->fromArray([
                        $item->waste?->description ?? '—',
                        $fecha,
                        $item->waste?->physical_state ?? '',
                        $item->quantity,
                        $item->unit,
                        $item->container_type ?? '',
                        $capM3,
                        $capKg,
                        $destino,
                        $manifiesto,
                    ], null, "A{$row}");

                    $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    $row++;
                }

                // ── ANCHOS ────────────────────────────────────────────────
                $sheet->getColumnDimension('A')->setWidth(35);
                $sheet->getColumnDimension('B')->setWidth(14);
                $sheet->getColumnDimension('C')->setWidth(12);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(8);
                $sheet->getColumnDimension('F')->setWidth(22);
                $sheet->getColumnDimension('G')->setWidth(14);
                $sheet->getColumnDimension('H')->setWidth(14);
                $sheet->getColumnDimension('I')->setWidth(40);
                $sheet->getColumnDimension('J')->setWidth(18);
            },
        ];
    }
}
