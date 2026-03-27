<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Stage;
use App\Models\CaasStage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Hash;

class CaasImport implements ToCollection, WithHeadingRow, WithValidation, WithChunkReading, SkipsOnError, SkipsEmptyRows
{
    use SkipsErrors;
    
    private $importedCount = 0;
    private $skippedCount = 0;
    private ?int $administrationStageId = null;
    private array $seenNims = [];

    public function __construct()
    {
        $this->administrationStageId = Stage::query()
            ->where('name', 'Administration')
            ->value('id');
    }

    /**
     * Process rows in chunks and insert related records in bulk.
     *
     * @param Collection<int, array<string, mixed>> $rows
     * @return void
     */
    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $now = now();
        $candidateRows = [];

        foreach ($rows as $row) {
            $nim = trim((string) ($row['nim'] ?? ''));
            if ($nim === '') {
                continue;
            }

            // Keep first occurrence only when same NIM appears multiple times in file.
            if (isset($this->seenNims[$nim])) {
                $this->skippedCount++;
                continue;
            }

            $this->seenNims[$nim] = true;
            $candidateRows[$nim] = [
                'nim' => $nim,
                'nama' => $row['nama'] ?? null,
                'jurusan' => $row['jurusan'] ?? null,
                'kelas' => $row['kelas'] ?? null,
                'jenis_kelamin' => $row['jenis_kelamin'] ?? ($row['jenis kelamin'] ?? null),
            ];
        }

        if (empty($candidateRows)) {
            return;
        }

        $nims = array_keys($candidateRows);
        $existingNims = User::query()
            ->whereIn('nim', $nims)
            ->pluck('nim')
            ->map(fn ($nim) => (string) $nim)
            ->all();

        if (!empty($existingNims)) {
            $existingNimSet = array_flip($existingNims);
            foreach ($existingNims as $existingNim) {
                unset($candidateRows[$existingNim]);
            }
            $this->skippedCount += count($existingNimSet);
        }

        if (empty($candidateRows)) {
            return;
        }

        $usersToInsert = [];
        foreach ($candidateRows as $nim => $row) {
            $usersToInsert[] = [
                'nim' => $nim,
                'password' => Hash::make($nim),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::transaction(function () use ($candidateRows, $usersToInsert, $now) {
            User::query()->insert($usersToInsert);

            $insertedUsers = User::query()
                ->whereIn('nim', array_keys($candidateRows))
                ->get(['id', 'nim'])
                ->keyBy(fn ($user) => (string) $user->nim);

            $profilesToInsert = [];
            $stagesToInsert = [];

            foreach ($candidateRows as $nim => $row) {
                $user = $insertedUsers->get($nim);
                if (!$user) {
                    continue;
                }

                $profilesToInsert[] = [
                    'user_id' => $user->id,
                    'name' => $row['nama'],
                    'major' => $row['jurusan'],
                    'class' => $row['kelas'],
                    'gender' => $row['jenis_kelamin'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if ($this->administrationStageId) {
                    $stagesToInsert[] = [
                        'user_id' => $user->id,
                        'stage_id' => $this->administrationStageId,
                        'status' => 'PROSES',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($profilesToInsert)) {
                DB::table('profiles')->insert($profilesToInsert);
            }

            if (!empty($stagesToInsert)) {
                CaasStage::query()->insert($stagesToInsert);
            }

            $this->importedCount += count($profilesToInsert);
        });
    }

    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Define validation rules for each row
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nim' => 'required', // Accept both string and numeric
            'nama' => 'required|string',
            'jurusan' => 'nullable',
            'kelas' => 'nullable',
            'jenis_kelamin' => 'nullable',
        ];
    }

    /**
     * Custom validation messages
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'nim.required' => 'NIM is required',
            'nama.required' => 'Name is required',
        ];
    }

    /**
     * Get imported count
     *
     * @return int
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Get skipped count
     *
     * @return int
     */
    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
