<?php

namespace App\Exports;

use App\Models\Plottingan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlottinganExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Get all plottingans with their shift and user data
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Plottingan::with(['shift', 'user.profile'])
            ->join('shifts', 'plottingans.shift_id', '=', 'shifts.id')
            ->orderBy('shifts.date')
            ->orderBy('shifts.shift_no')
            ->select('plottingans.*')
            ->get();
    }

    /**
     * Define the column headings
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Shift',
            'Tanggal',
            'Nama',
            'NIM',
        ];
    }

    /**
     * Map the data for each row
     *
     * @param mixed $plottingan
     * @return array
     */
    public function map($plottingan): array
    {
        // Format date
        $date = $plottingan->shift->date ?? null;
        $formattedDate = $date ? $date->format('d/m/Y') : '-';

        return [
            $plottingan->shift->shift_no ?? '-',
            $formattedDate,
            $plottingan->user->profile->name ?? '-',
            (string) $plottingan->user->nim ?? '-',
        ];
    }

    /**
     * Apply styles to the worksheet
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Make the first row bold (headers)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
