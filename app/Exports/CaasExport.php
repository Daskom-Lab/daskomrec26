<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CaasExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Get all users with their profiles (all records, not paginated)
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with('profile', 'caasStage.stage')
            ->join('caas_stages', 'users.id', '=', 'caas_stages.user_id')
            ->select('users.*')
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
            'nama',
            'nim',
            'jurusan',
            'kelas',
            'jenis kelamin',
        ];
    }

    /**
     * Map the data for each row
     *
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->profile->name ?? 'N/A',
            $user->nim,
            $user->profile->major ?? 'N/A',
            $user->profile->class ?? 'N/A',
            $user->profile->gender ?? 'N/A',
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
