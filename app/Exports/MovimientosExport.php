<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class MovimientosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $movimientos;

    public function __construct($movimientos)
    {
        $this->movimientos = $movimientos;
    }

    /**
     * Retorna la colección de movimientos.
     */
    public function collection()
    {
        return $this->movimientos;
    }

    /**
     * Define los encabezados de las columnas.
     */
    public function headings(): array
    {
        return [
            'Fecha',
            'Hora',
            'Producto',
            'Tipo Producto',
            'Tipo Movimiento',
            'Cantidad',
            'Unidad',
            'Origen',
            'Destino',
            'Referencia',
            'Usuario',
            'Empleado',
            'Observación'
        ];
    }

    /**
     * Mapea cada fila de datos.
     */
    public function map($movimiento): array
    {
        return [
            $movimiento->fecha_movimiento->format('d/m/Y'),
            $movimiento->fecha_movimiento->format('H:i:s'),
            $movimiento->producto->nombre ?? '-',
            $movimiento->producto->tipo ?? '-',
            ucfirst($movimiento->tipo_movimiento),
            $movimiento->cantidad,
            $movimiento->producto->unidad_medida ?? 'unidades',
            $movimiento->origen ?? '-',
            $movimiento->destino ?? '-',
            $movimiento->referencia ?? '-',
            $movimiento->usuario->nombre ?? '-',
            $movimiento->usuario->personal->nombre_completo ?? '-',
            $movimiento->observacion ?? '-'
        ];
    }

    /**
     * Aplica estilos a la hoja.
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para la fila de encabezados
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1e3a8a'],
            ],
        ]);

        // Aplicar colores según tipo de movimiento
        $row = 2; // Comenzar después de los encabezados
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->tipo_movimiento === 'entrada') {
                $sheet->getStyle("E{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'd1fae5'],
                    ],
                ]);
            } else {
                $sheet->getStyle("E{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'fee2e2'],
                    ],
                ]);
            }
            $row++;
        }

        return [];
    }

    /**
     * Define el título de la hoja.
     */
    public function title(): string
    {
        return 'Movimientos';
    }
}
