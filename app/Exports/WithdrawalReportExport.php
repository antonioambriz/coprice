<?php

namespace App\Exports;

use App\Models\Withdrawal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class WithdrawalReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithTitle
{
    public function __construct(private Collection $withdrawals) {}

    public function title(): string
    {
        return 'Entradas';
    }

    public function collection(): Collection
    {
        return $this->withdrawals;
    }

    public function headings(): array
    {
        return [
            '#',
            'Folio Interno',
            'Fecha Recepción',
            'Generador',
            'División',
            'Transportista',
            'Residuos',
            'Peso Total',
            'Unidad',
            'Peso Estimado',
            'Manifiesto',
            'Destino Final',
            'Estatus Pago',
            'Registrado por',
        ];
    }

    public function map($w): array
    {
        $items     = $w->items;
        $totalKg   = $items->sum('quantity');
        $unit      = $items->first()?->unit ?? '—';
        $residuos  = $items->map(fn($i) => $i->waste?->description ?? '—')->implode(' | ');

        return [
            $w->id,
            $w->folio_interno,
            $w->reception_date?->format('d/m/Y') ?? '—',
            $w->generator?->company_name ?? '—',
            $w->subGenerator?->name ?? '—',
            $w->transporter?->company_name ?? '—',
            $residuos,
            number_format($totalKg, 2),
            $unit,
            $w->is_estimated_weight ? 'Estimado' : 'Real',
            $w->manifest?->manifest_number ?? 'S/M',
            $w->finalDestination?->name ?? '—',
            $w->payment_status,
            $w->user?->name ?? '—',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();

        // Fila de encabezados
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2B5C3F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Filas de datos — zebra
        for ($row = 2; $row <= $lastRow; $row++) {
            $color = ($row % 2 === 0) ? 'F4F8F5' : 'FFFFFF';
            $sheet->getStyle("A{$row}:N{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        // Borde exterior
        $sheet->getStyle("A1:N{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D0D7D2']],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(20);

        return [];
    }
}
