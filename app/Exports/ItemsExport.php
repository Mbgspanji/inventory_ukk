<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ItemsExport implements FromView, WithStyles, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.items', [
            'items' => Item::with('category')->get()
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Get the total number of rows and columns
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $range = 'A1:' . $highestColumn . $highestRow;

        return [
            // Style the header row (1)
            1 => [
                'font' => ['bold' => true],
            ],
            // Apply borders to the entire table
            $range => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
