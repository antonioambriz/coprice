<?php

namespace App\Exports;

use App\Models\Withdrawal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BitacoraExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * Traemos la colección con sus relaciones para evitar el problema de N+1
    */
    public function collection()
    {
        return Withdrawal::with(['generator', 'transporter'])->get();
    }

    /**
    * Encabezados de la tabla
    */
    public function headings(): array
    {
        return [
            'ID',
            'FOLIO INTERNO',
            'FECHA RECEPCIÓN',
            'GENERADOR',
            'TRANSPORTISTA',
            'MANIFIESTO',
            'ESTATUS PAGO'
        ];
    }

    /**
    * Mapeo de datos (Asegúrate de que los nombres coincidan con tu BD)
    */
    public function map($withdrawal): array
    {
        return [
            $withdrawal->id,
            $withdrawal->folio_interno,
            $withdrawal->reception_date,
            $withdrawal->generator->company_name ?? 'N/A',
            $withdrawal->transporter->company_name ?? 'N/A',
            $withdrawal->manifest?->manifest_number ?? 'S/M',
            $withdrawal->payment_status
        ];
    }

    /**
    * Aplicar estilos a la hoja
    */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila 1 (Encabezados)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A5E28'], // El verde Excel que usamos en el CSS
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
