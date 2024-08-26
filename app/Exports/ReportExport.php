<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromView, WithColumnWidths, WithStyles
{
    use Exportable;

    public function __construct($data)
    {
        $this->data = $data;
    }

    // https://docs.laravel-excel.com/3.1/exports/from-view.html
    public function view(): View
    {
        return view('exports.timesheet', [
            'time_entries' => $this->data,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 45,
            'E' => 45,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Styling an entire column.
            'A' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]],
            'B' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]],
            'C' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]],
            'D' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]],
            'E' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]],
            'F' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]],
            'G' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]],
            'H' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],
            'I' => ['style' => ['alignment' => 'middle'], 'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],
        ];
    }
}
